<?php
/**
 * Describe: 控制器例子
 * Author: Sky
 * Date: 2017/2/5
 */

namespace App\Module\Home\Controller;


use App\Model\User;
use SPHPCore\Lib\Mvc\Controller;

class Index extends Controller
{

    public function index(){

        $user = User::tb()->find();print_r($user);exit;
    }
}