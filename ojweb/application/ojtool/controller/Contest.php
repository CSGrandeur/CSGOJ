<?php
namespace app\ojtool\controller;
use app\cpcsys\controller\Contest as Contestbase;
class Contest extends Contestbase
{
    public function jointrank() {
        $this->assign("pagetitle", "Joint Rank");
        return $this->fetch();
    }
}
