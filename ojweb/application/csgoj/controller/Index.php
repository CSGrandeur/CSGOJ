<?php
namespace app\csgoj\controller;
use think\Controller;
class Index extends Csgojbase
{
    public function index()
    {
        $this->redirect('/index');
    }
}
