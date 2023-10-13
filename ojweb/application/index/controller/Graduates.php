<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/3/4
 * Time: 9:39
 */
namespace app\index\controller;
use think\Controller;
class Graduates extends Homebase
{
    public function MakePageTitle()
    {
        $this->assign('pagetitle', $this->pagetitle = $this->OJ_NAME . ' Graduates');
    }
    public function index()
    {
        return $this->fetch('public/news_detail_list');
    }
    public function category_news_list_ajax()
    {
        $offset        = intval(input('offset'));
        $limit        = intval(input('limit'));
        $sort        = trim(input('sort', ''));
        $sort = validate_item_range($sort, ['title']);
        $order        = input('order');
        $search        = trim(input('search/s'));

        $ordertype = [];
        if(strlen($sort) > 0)
        {
            $ordertype = [
                $sort => $order
            ];
        }
        $map = [
            'category' => strtolower($this->category),
            'defunct'  => '0'
        ];
        if(strlen($search) > 0)
            $map['user_id|title|modify_user_id|tag'] = ['like', "%$search%"];
        $News = db('news');
        $newsList = $News
            ->where($map)
            ->limit($offset, $limit)
            ->order($ordertype)
            ->select();
        foreach($newsList as &$news)
        {
            $news['title'] = "<a href='/index/".$this->category."/detail?nid=" . $news['news_id'] . "' title='" . $news['title'] . "' class='article-title-in-table'>" . $news['title'] . "</a>";
            $news['user_id'] = "<a href='/csgoj/user/userinfo?user_id=" . $news['user_id'] . "' target='_blank'>" . $news['user_id'] . "</a>";
            $news['modify_user_id'] = "<a href='/csgoj/user/userinfo?user_id=" . $news['modify_user_id'] . "' target='_blank'>" . $news['modify_user_id'] . "</a>";
            $news['tags'] = "<span title='" . $news['tags'] . "' class='tags-in-table'>" . ($news['tags'] == null ? '' : $news['tags']) . "</span>";
        }
        $ret['total'] = $News->where($map)->count();
        $ret['rows'] = $newsList;
        return $ret;
    }
}
