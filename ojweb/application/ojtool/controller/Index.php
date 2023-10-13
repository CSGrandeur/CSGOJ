<?php
namespace app\ojtool\controller;
use think\Controller;
class Index extends Ojtoolbase
{
    public function index() {
        return $this->fetch();
    }
}
