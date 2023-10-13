<?php
namespace app\ojtool\controller;
use think\Db;
use think\Validate;
use app\ojtool\controller\Contest as Contestbase;
require_once(__DIR__ . "../../../traits.php");
use app\ContestAdminTrait as AT;
class Admin extends Contestbase
{
    use AT;
    
}