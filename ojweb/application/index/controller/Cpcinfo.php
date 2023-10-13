<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/3/4
 * Time: 9:39
 */
namespace app\index\controller;
use think\Controller;
class Cpcinfo extends Homebase
{
    public function MakePageTitle()
    {
        $this->assign('pagetitle', $this->pagetitle = $this->OJ_NAME . ' Articles');
    }
}
