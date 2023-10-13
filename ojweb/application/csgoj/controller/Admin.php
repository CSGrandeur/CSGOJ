<?php
namespace app\csgoj\controller;
use think\Db;
use think\Validate;
use app\csgoj\controller\Contest as Contestbase;
require_once(__DIR__ . "../../../traits.php");
use app\ContestAdminTrait;
class Admin extends Contestbase
{
    use ContestAdminTrait;
}