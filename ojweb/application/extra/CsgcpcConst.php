<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/3/3
 * Time: 20:58
 */
return [
    'STATIC_PAGE'    => [
        // 固定页面的news_id，小于1000
        'about_us'    => 10,        //关于我们
        'oj_faq'    => 20,        //F.A.Qs
        'cr_faq'    => 21,        //报名系统F.A.Qs
        'carousel'    => 30,        //首页三张滚动大图
    ],
    'HOME_CATEGORY'     => [
       'news'           => '团队新闻',
       'notification'   => '通知公告',
       'answer'         => '解题报告',
       'cpcinfo'        => '竞赛周边',
    //    'achievement'    => '历年成绩',
    //    'graduates'      => '毕业队员',    //最好按年发，以“届”为标准
    ],
    'CATEGORY_SHOW_INDEX' => [
        'news',
        'notification',
        'answer',
        'cpcinfo'
    ],
    'MAX_TAG'        => 5,    //最多几个tag
    'TAG_LENGTH'    => 32,    //每个tag最长是多长

    'CAROUSEL'    => [
        'carouselItem'    => ['href', 'src', 'header', 'content'],
        'srcDefault'    => [
            '/static/image/carousel_default/carousel0.png',
            '/static/image/carousel_default/carousel1.png',
            '/static/image/carousel_default/carousel2.png',
        ]
    ],
];