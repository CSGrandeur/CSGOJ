<?php
namespace app\ojtool\controller;
use app\ojtool\controller\Rankroll as Contestbase;
class Contestlive extends Contestbase
{
    public function InitController() {
        $privateAction = ['ctrl', 'live_command_send_ajax'];
        if(in_array($this->action, $privateAction) && !IsAdmin('contest', input('cid/d')) && !$this->IsContestAdmin('watcher')) {
            $this->error("无访问权限", '/ojtool/contestlive', null, 1);
        }
        $this->contest = null;
        if(input('?cid')) {
            $cid = input('cid/d');
            $this->contest = db('contest')->where('contest_id', $cid)->find();
            if($this->contest == null) {
                $this->error("错误的比赛请求");
            }
        }
    }
    public function index() {
        $this->assign('pagetitle', "Contest List");
        $this->assign('contest_controller', 'contestlive');
        $this->assign('module', 'contest');
        return $this->fetch();
    }
    public function live() {
        $this->assign('title', '直播-' . $this->contest['title']);
        return $this->fetch();
    }
    public function ctrl() {
        $this->assign('title', '控制台-' . $this->contest['title']);
        return $this->fetch();
    }
    public function live_command_get_ajax() {
        $live_command_list = $this->OldLiveCommanClear(true);
        $this->success('msg', null, array_reverse($live_command_list));
    }
    public function live_command_send_ajax() {
        $live_command = input('live_command/s');
        if($live_command === null) {
            $this->error("need parameter live command");
        } else if(strlen($live_command) > 2048) {
            $this->error("live command too long");
        }
        $timestamp = time();
        $live_command_list = $this->OldLiveCommanClear(false);
        $live_command_list[] = [
            'timestamp'     => $timestamp,
            'live_command'  => $live_command
        ];
        $this->SaveLiveCache($live_command_list);
        $this->success('ok');
    }
    protected function OldLiveCommanClear($save=false) {
        $cache_name = 'live_command_' . $this->contest['contest_id'];
        $timestamp = time();
        $live_commond_list = cache($cache_name);
        if($live_commond_list === null) {
            $live_commond_list = [];
        } else if(!is_array($live_commond_list)) {
            $live_commond_list = [];
        }
        $i = 0;
        $j = 0;
        $len = count($live_commond_list);
        for(; $j < $len && $timestamp - $live_commond_list[$j]['timestamp'] > 60; $j ++);
        for(; $j < $len; $i ++, $j ++) {
            $live_commond_list[$i] = $live_commond_list[$j];
        }
        $live_commond_list = array_slice($live_commond_list, 0, $i);
        if($save) {
            $this->SaveLiveCache($live_commond_list);
        }
        return $live_commond_list;
    }
    protected function SaveLiveCache($live_command_list) {
        $cache_name = 'live_command_' . $this->contest['contest_id'];
        cache($cache_name, $live_command_list, 60);
    }
}
