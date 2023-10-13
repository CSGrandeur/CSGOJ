<?php
namespace app\csgoj\controller;
class Clss extends Csgojbase {
    var $clss_query_field;
    var $clss_default_map;
    var $is_teacher;
    var $user_id;
    public function InitController() {
        if(!session('?user_id')) {
            $this->error("请先登录", '/', null, 1);
        }
        $this->clss_query_field = [
            'mail_id clss_id',
            'title',
            'reply year',
            'to_user semester',
            'in_date',
            'content teachers'
        ];
        $this->clss_default_map = [
            'defunct'   => 'C'
        ];
        $this->user_id = session('user_id');
    }
    public function index() {
        $this->assign('pagetitle', '我的班级');
        return $this->fetch();
    }
    public function GetClassRelated() {
        $map = [];
        if(!IsAdmin('super_admin')) {
            $clss_list = db('mail')->where($this->clss_default_map)->where('content', 'like', '%,' . session('user_id') . ',%')->column('mail_id');
            $clss_accessible_tmp = db('privilege')->where(['user_id' => $this->user_id, 'rightstr' => ['like', 'clss_%']])->column('rightstr');
            foreach($clss_accessible_tmp as $val) {
                $clss_list[] = intval(substr($val, 5));
            }
            $map['mail_id'] = ['in', $clss_list];
        }
        return db('mail')->where($this->clss_default_map)->where($map)->field($this->clss_query_field)->select();
    }
    public function clss_list_ajax() {
        return $this->GetClassRelated();
    }


    // **************************************************
    // students
    // **************************************************
    public function IsUserClssTeacher($user_id, $clss_item=null, $clss_id=null) {
        if($clss_item == null) {
            $clss_item = db('mail')->where($this->clss_default_map)->where('mail_id', $clss_id)->field($this->clss_query_field)->find();
        }
        return IsAdmin('super_admin') || strstr($clss_item['teachers'], ',' . $user_id . ',');
    }
    public function IsUserClssStu($user_id, $clss_id) {
        return db('privilege')->where(['user_id' => $user_id, 'rightstr' => 'clss_' . $clss_id]) !== null;
    }
    public function JudgeClss($clss_id=null) {
        if($clss_id == null) {
            $clss_id = input('clss_id/d');
        }
        $clss_item = db('mail')->where($this->clss_default_map)->where('mail_id', $clss_id)->field($this->clss_query_field)->find();
        if(!$clss_item) {
            $this->error("没有这个班级", null, null, 1);
        }
        $isClssTeacher = $this->IsUserClssTeacher($this->user_id, $clss_item);
        $isClssStu = $this->IsUserClssStu($this->user_id, $clss_id);
        if(!IsAdmin('super_admin') && !$isClssStu && !$isClssTeacher) {
            $this->error("非本班", null, null, 1);
        }
        return [
            'clss'          => $clss_item,
            'isClssTeacher' => $isClssTeacher,
            'isClssStu'     => $isClssStu,
        ];
    }
    public function stu() {
        $info = $this->JudgeClss();
        $this->assign('pagetitle', '班级学生');
        $this->assign($info);
        return $this->fetch();
    }
    public function stu_list_ajax() {
        $info = $this->JudgeClss();
        $stu_list = db('users')->join('privilege pr', 'pr.user_id=users.user_id', 'right')->where('pr.rightstr', 'clss_' . $info['clss']['clss_id'])->field([
            'pr.user_id user_id',
            'pr.defunct defunct',
            'users.nick nick',
            'users.school school',
        ])->select();
        return $stu_list;
    }
    public function stu_del_ajax() {
        $info = $this->JudgeClss();
        if(!$info['isClssTeacher']) {
            $this->error("非本班教师");
        }
        db('privilege')->where(['user_id' => input('user_id/s'), 'rightstr' => 'clss_' . $info['clss']['clss_id']])->delete();
        $this->success('删除成功');
    }
    public function stu_add_ajax() {
        $info = $this->JudgeClss();
        if(!$info['isClssTeacher']) {
            $this->error("非本班教师");
        }
        $stu_add_list = input('stu_add_list/a');
        if($stu_add_list == null) {
            $stu_add_list = [];
        }
        if(count($stu_add_list) > 2048) {
            $this->error("学生数量过多");
        }
        $insert_list = [];
        foreach($stu_add_list as $val) {
            $len = strlen($val['user_id']);
            if($len > 32 || $len < 3) {
                $this->error("存在ID长度不正确：" . $val['user_id']);
            }
            if(!preg_match('/^[a-zA-Z0-9_]+$/', $val['user_id'])) {
                $this->error("存在ID格式不正确：" . $val['user_id']);
            }
            $insert_list[] = [
                'user_id'   => $val['user_id'],
                'rightstr'  => 'clss_' . $info['clss']['clss_id'],
                'defunct'   => $val['defunct']
            ];
        }
        db('privilege')->where('rightstr', 'clss_' . $info['clss']['clss_id'])->delete();
        db('privilege')->insertAll($insert_list);
        $this->success('处理完毕');
    }
}
