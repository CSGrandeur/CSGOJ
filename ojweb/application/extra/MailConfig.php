<?php
/**
 * Created by PhpStorm.
 * User: CSGrandeur
 * Date: 2017/4/1
 * Time: 16:53
 */
use think\Env;
return [
    'smtp'          => 'smtp.163.com',
    'account'       => Env::get('OJ_PASSBACK_MAIL', ''),
    'password'      => Env::get('OJ_PASSBACK_MAIL_PASS', ''),
    'secure'        => 'ssl',
    'port'          => 994,
    'from'          => Env::get('OJ_PASSBACK_MAIL', ''),
    'from_name'     => Env::get('OJ_NAME', 'csg') . ' Password Retrieve',
    'passback_url'  => Env::get('OJ_BASE_URL', 'http://127.0.0.1') . '/csgoj/user/passback_retrieve',
];