<?php
/**
 * Created by Vscode.
 * User: CSGrandeur
 * Date: 2022/2/11
 * Time: 21:40
 */
namespace app\admin\controller;
use think\Validate;
class Usermanager extends Adminbase
{

	//***************************************************************//
	// User Manager
	//***************************************************************//
	public function _initialize()
	{
		$this->OJMode();
		$this->AdminInit();
		if(!IsAdmin('password_setter'))
			$this->error("You have no privilege to reset password");
	}
	public function index()
	{
		return $this->fetch();
	}
    // **************************************************
    // User List
	public function password_reset_ajax()
	{
		$user_id = trim(input('passwordreset_user_id'));//用带前缀的input name是避免与登录框name相同，导致前端自动填表功能干扰填写
		if(!isset($user_id) || strlen($user_id) == 0)
			$this->error('Pleas input User ID');
		$password = trim(input('passwordreset_password'));
		if(!isset($password) || strlen($password) < 6 || strlen($password) > 255)
			$this->error('Length of password should between 6 and 255');
		$Users = db('users');
		$userinfo = $Users->where('user_id', $user_id)->find();
		if(!$userinfo)
			$this->error('No such user');
		if($userinfo['user_id'] == session('user_id'))
			$this->error('You can only reset the password of yourself in the user modify page.');

		$userprivilege = db('privilege')->where(['user_id' => $user_id, 'rightstr' => ['in', array_keys($this->ojAdminList)]])->select();

		// administrator可以改其他管理员密码，super_admin可以改administrator密码
		if(count($userprivilege))
		{
			foreach($userprivilege as $privilege)
			{
				if(
					//password_setter不可改任何有权限的帐号密码
					!IsAdmin('administrator') ||
					//非super_admin不可改administrator密码
					($privilege['rightstr'] == 'administrator' && !IsAdmin('super_admin')) ||
					//谁都不可以在这里改super_admin密码
					$privilege['rightstr'] == 'super_admin'
				)
				{
					$this->error('You cannot reset the password of an administrator.');
					return;
				}
			}
		}
		$Users->where('user_id', $user_id)->update(['password'=>MkPasswd($password)]);
		$this->success('Reset password of "'.$user_id.'" successfully.');
	}

    public function user_list_ajax()
    {
        $offset        = intval(input('offset'));
        $limit        = intval(input('limit'));
        $search        = trim(input('search/s'));

        $map = [];
        if(strlen($search) > 0)
            $map['user_id|nick'] =  ['like', "%$search%"];

        $ret = [];
        $Users = db('users');
        $userlist = $Users
            ->field('user_id, nick, school, email, solved, submit, reg_time')
            ->where($map)
            ->order('reg_time', 'desc')
            ->limit("$offset, $limit")
            ->select();
        $retList = [];
        $i = $offset + 1;
        foreach($userlist as $user)
        {
            $row = [
                'rank'      => $i,
                'user_id'   => $user['user_id'],
                'nick'      => htmlspecialchars($user['nick']),
                'school'    => htmlspecialchars($user['school']),
                'email'     => htmlspecialchars($user['email']),
                'reg_time'  => $user['reg_time'],
                'solved'  => $user['solved'],
                'submit'    => $user['submit'],
                'ratio'     => $user['submit'] == 0 ? '-' : (strval(sprintf("%.3f", floatval($user['solved']) / floatval($user['submit']) * 100)) . "%"),
            ];
            $retList[] = $row;
            $i ++;
        }
        $ret['total'] = $Users->where($map)->count();
        $ret['order'] = 'desc';
        $ret['rows'] = $retList;
        return $ret;
    }
	// **************************************************
	// User Del
	public function user_del_ajax() {
		if(!IsAdmin()) {
			$this->error("No permission");
		}
		$user_id = input('user_id/s');
		if(db('solution')->where('user_id', $user_id)->find()) {
			$this->error("User " . $user_id . "<br/>already submitted some problem.<br/>Could not be deleted.");
		}
		if(db('privilege')->where('user_id', $user_id)->find()) {
			$this->error("User " . $user_id . " has some privileges.<br/>Could not be deleted.");
		}
		db('users')->where('user_id', $user_id)->delete();
		$this->success("User " . $user_id . " deleted");
	}
    // **************************************************
    // User Add
	public function usergen() {
		if(!IsAdmin()) {
			$this->error("No permission");
		}
		return $this->fetch();
	}
	public function usergen_ajax() {
		if(!IsAdmin("super_admin") && $this->OJ_STATUS!='exp') {
			$this->error("User generation is not allowd in this OJ mode.");
		}
		if(!IsAdmin()) {
			$this->error("No permission");
		}
		
        $userDescription = trim(input('user_description/s'));
        if(strlen($userDescription) == 0)
            $userList = [];
        else
            $userList = explode("\n", $userDescription);
        $userListNum = count($userList);					//userDescription的行数，与userNumber取较大者
        if($userListNum == 0) {
            $this->error('At least fill in one form of User description or User number.');
		}
		else if($userListNum > 100) {
            $this->error('No more than 100 user allowed to generate at once.');
		}
        $userToInsert = [];
        $userToShow = [];
        $fieldList = ['user_id', 'nick', 'school', 'email', 'password'];
        $fieldNum = count($fieldList);
        $validate = new Validate(config('CpcSysConfig.userinfo_rule'), config('CpcSysConfig.userinfo_msg'));
        $validateNotList = '';
        $solutionUserQuery = db('solution')->group('user_id')->field('user_id')->select();
        $solutionUsers = [];
        foreach($solutionUserQuery as $val) {
            $solutionUsers[strtolower($val['user_id'])] = true;
		}
        $privilegeUserQuery = db('privilege')->group('user_id')->field('user_id')->select();
        $privilegeUsers = [];
        foreach($privilegeUserQuery as $val) {
            $privilegeUsers[strtolower($val['user_id'])] = true;
		}

		$userIdsInsert = [];
		$userNotUpdateInfo = "";
        $idxNotupdate = 0;
		foreach($userList as $userStr)
        {
            $user = preg_split("/[#\\t]/", $userStr);
            $userinfo = [];
            for($j = 0; $j < $fieldNum; $j ++)
            {
                if(!array_key_exists($j, $user))
                    $user[$j] = '';
                $field = trim($user[$j]);
                switch($fieldList[$j])
                {
                    case 'user_id':
                        if($field == '' || strlen($field) < 3) {
                        	continue 2;
						}
                        $userinfo['user_id'] = $field;
                        break;
                    case 'password':
                        if($field == '' || strlen($field) < 6) {
                            $field = RandPass();
						}
                        $userinfo['password'] = $field;
                        break;
                    default:
                        $userinfo[$fieldList[$j]] = $field;
                }
            }
            $userinfo['reg_time'] = date('Y-m-d H:i:s');
            if(!$validate->check($userinfo)) {
                $validateNotList .= "<br/>" . $userinfo['user_id'] . ': ' . $validate->getError();
            }
            if(strlen($validateNotList) == 0 && 
				!array_key_exists(strtolower($userinfo['user_id']), $solutionUsers) &&
				!array_key_exists(strtolower($userinfo['user_id']), $privilegeUsers)
			)
            {
                $userToShow[] = $userinfo;
                $userinfo['password'] = MkPasswd($userinfo['password']);
                $userToInsert[] = $userinfo;
				$userIdsInsert[] = $userinfo['user_id'];
            }
			else {
                $idxNotupdate ++;
				$userNotUpdateInfo .= "<br/>" . $idxNotupdate . ". " . $userinfo['user_id'];
			}
        }
        if(strlen($validateNotList) > 0)
        {
            $addInfo = '<br/>Some user information is not valid. Please check.' . $validateNotList;
            $this->error('User generation failed.' . $addInfo);
        }
		if(strlen($userNotUpdateInfo) > 0) {
			$userNotUpdateInfo = "<br/>User with submission or privilege not updated:" . $userNotUpdateInfo;
		}
        $Users = db('users');
        if(!$Users->insertAll($userToInsert, 'REPLACE')) {
            $retInfo = 'No user generated. Users already exist or data input is invalid.';
            if(strlen($userNotUpdateInfo) > 0) {
                $retInfo .= $userNotUpdateInfo;
            }
            $this->error($retInfo);
        }
        $this->success('User successfully generated/updated. See the table below.' . $userNotUpdateInfo, null, ['rows' => $userToShow, 'type' => 'usergen']);
	}
}