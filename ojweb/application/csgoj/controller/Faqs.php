<?php
namespace app\csgoj\controller;
use think\Controller;
class Faqs extends Csgojbase
{
    public function index()
    {
        $this->assign(['pagetitle' => 'F.A.Qs']);
        $News = db('news');
        $new = $News->where([
            'news_id' => config('CsgcpcConst.STATIC_PAGE')['oj_faq']
        ])->find();
        $data['new'] = $new;
        $this->assign($data);
        return $this->fetch();
    }
}
