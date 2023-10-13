<?php
namespace app\cpcsys\controller;
use think\Controller;
class Index extends Cpcsysbase
{
    public function index()
    {
    	$this->redirect('/' . $this->request->module() . '/contest');
	}
}
