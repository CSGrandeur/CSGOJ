<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/3/4
 * Time: 9:39
 */
namespace app\index\controller;
use think\Controller;
class About extends Homebase
{
    public function MakePageTitle()
    {
        $this->assign('pagetitle', $this->pagetitle = $this->OJ_NAME . ' About');
    }
    public function index()
    {
        $nid = $this->staticPage['about_us'];
        $News = db('news');
        $map = [
            'news_id'     => $nid,
        ];
        $news = $News->where($map)->find();
        if(!$news)
            $this->error("This page is not edited yet", null, '', 1);
        $this->assign('news', $news);
        return $this->fetch('public/news_detail');
    }
}
