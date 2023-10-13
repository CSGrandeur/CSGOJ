<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Index extends Adminbase
{
	public function index()
	{
		$module = $this->request->module();
		foreach($this->ojItemPri as $item => $pri)
		{
			if(IsAdmin($this->ojPreAdmin[$pri]))
			{
				$this->redirect('/'.$module.'/' . $item);
			}
		}
		if(IsAdmin('administrator'))
			$this->redirect('/'.$module.'/news');
		$this->error('You are not an administrator', '/', '', 2);
	}

	//***************************************************************//
	//Helpful functions
	//***************************************************************//
//	public function UpdateSqlForMarkdown()
//	{
//		if(!IsAdmin('super_admin'))
//			$this->error('Powerless');
//		db()->execute("CREATE TABLE `news_md` IF NOT EXISTS(`news_id` int(11) NOT NULL,`content` text NOT NULL,PRIMARY KEY (`news_id`)) DEFAULT CHARSET=utf8");
//		db()->execute("CREATE TABLE `problem_md` IF NOT EXISTS (`problem_id` int(11) NOT NULL,`description` text,`input` text,`output` text,`hint` text,`source` varchar(100) DEFAULT NULL,PRIMARY KEY (`problem_id`)) DEFAULT CHARSET=utf8");
//		db()->execute("CREATE TABLE `contest_md` IF NOT EXISTS (`contest_id` int(11) NOT NULL,`description` text,PRIMARY KEY (`contest_id`)) DEFAULT CHARSET=utf8");
//
//		return $this->display('<h1>OK</h1>');
//	}
//	public function UpdateSqlDefunct()
//	{
//		if(!IsAdmin('super_admin'))
//			$this->error('Powerless');
//		db('problem')->where('defunct', 'Y')->update(['defunct' => '1']);
//		db('problem')->where('defunct', 'N')->update(['defunct' => '0']);
//		db('contest')->where('defunct', 'Y')->update(['defunct' => '1']);
//		db('contest')->where('defunct', 'N')->update(['defunct' => '0']);
//		db('news')->where('defunct', 'Y')->update(['defunct' => '1']);
//		db('news')->where('defunct', 'N')->update(['defunct' => '0']);
//		echo "success";
//	}
//	public function UpdateUserRegTime()
//	{
//		if(!IsAdmin('super_admin'))
//			$this->error('Powerless');
//		db('users')->whereNull('reg_time')->setField('reg_time', date('Y-m-d H:i:s'));
//		return $this->display("OK");
//	}
//	public function UpdateAttachPath()
//	{
//		if(!IsAdmin('super_admin'))
//			$this->error('Powerless');
//		$content = ['problem', 'news', 'contest'];
//		$ojPath = config('OjPath');
//		foreach($content as $c)
//		{
//			$field = db()->execute("Describe " . $c . " attach");
//			if($field == 0)
//				db()->execute("ALTER TABLE `" . $c . "` ADD `attach` varchar(32) DEFAULT '' ");
//			$path = $ojPath['PUBLIC'] . $ojPath[$c . '_ATTACH'];
//			$S = db($c);
//			$clist = $S->field([$c . '_id', 'attach'])->select();
//			foreach($clist as $cinfo)
//			{
//				$oldAttach = $cinfo['attach'];
//				$cinfo['attach'] = $this->AttachFolderCalculation(session('user_id'));
//				$S->where($c . '_id', $cinfo[$c . '_id'])->update($cinfo);
//
//				if(is_dir($path . '/' . $cinfo[$c . '_id']))
//					rename($path . '/' . $cinfo[$c . '_id'], $path . '/' . $cinfo['attach']);
//				else if(strlen($oldAttach) > 0  && is_dir($path . '/' . $oldAttach))
//					rename($path . '/' . $oldAttach, $path . '/' . $cinfo['attach']);
////				dump($cinfo);
//			}
//		}
//		return $this->display('OK');
//	}
    public function ReInitSpj()
    {
        if(!IsAdmin('super_admin'))
            $this->error('Powerless');
        $this->DoReInitSpj('/home/judge/data');
        exit();
    }
    public function DoReInitSpj($path)
    {
        if(!IsAdmin('super_admin'))
            $this->error('Powerless');

        if (is_dir($path) && ($handle = opendir($path)))
        {
            while (($file = readdir($handle)) !== false)
            {
                if ($file!="." && $file!="..")
                {
                    if(is_dir($path . '/' . $file)) {
                        $this->DoReInitSpj($path . '/' . $file);
                    }
                    else if($file == 'spj')
                    {
                        dump($path);
                        exec("chmod +x " . $path . '/' . $file);
                    }
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        else
        {
            return false;
        }
        return true;
    }
}
