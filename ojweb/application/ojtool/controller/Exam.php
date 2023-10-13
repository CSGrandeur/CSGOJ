<?php
namespace app\ojtool\controller;
class Exam extends Ojtoolbase {
    public function index() {
        $this->redirect('time_page_set');
    }
    public function time_page_set() {
        // 监考时显示时间的小工具
        $this->assign("pagetitle", "Time Page Set");
        return $this->fetch();
    }
    public function time_page_show() {
        // 监考时显示时间的小工具
        $this->assign("pagetitle", "Time Page Show");
        return $this->fetch();
    }
}
