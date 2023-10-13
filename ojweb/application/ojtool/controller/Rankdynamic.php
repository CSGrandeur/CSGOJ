<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/3/17
 * Time: 12:58
 */
namespace app\ojtool\controller;
use think\Controller;
class Rankdynamic extends Ojtoolbase {
    function rank() {
        $this->view->engine->layout('rankdynamic/rank_base');
        return $this->fetch();
    }
    function schoolrank() {
        $this->view->engine->layout('rankdynamic/rank_base');
        return $this->fetch();
    }

}