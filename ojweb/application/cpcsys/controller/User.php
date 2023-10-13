<?php
namespace app\cpcsys\controller;
use app\csgoj\controller\User as Userbase;
class User extends Userbase
{
	// cpcsys 模式下无常规用户，且禁止访问 csgoj
	// 只有管理员可访问以及有修改信息需求，所以该模块代码理论上无法触及，无需访问
	public function modify()
	{
		if(!IsAdmin('administrator'))
		{
			$this->error('You cannot modify user without admin privilege', null, '', 1);
		}
		$userinfo = $this->modify_func();
		return $this->fetch();
	}
	public function modify_ajax()
	{
		if(!IsAdmin('administrator'))
		{
			$this->error('You cannot modify user without admin privilege', null, '', 1);
		}
		$this->modify_ajax_func(input('post.'));
	}
	public function register()
	{
		$this->error('Registration is prohibited.');
		return $this->fetch();
	}
	public function register_ajax()
	{
		$this->error('Registration is prohibited.');
	}
}
