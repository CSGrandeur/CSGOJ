<?php
namespace app\ojtool\controller;
class Seatdraw extends Ojtoolbase {
    public function index() {
        $this->assign("pagetitle", "机位抽签");
        return $this->fetch();
    }
}