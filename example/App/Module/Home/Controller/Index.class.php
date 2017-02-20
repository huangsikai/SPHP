<?php
/**
 * Describe: 控制器例子
 * Author: Sky
 * Date: 2017/2/5
 */

namespace App\Module\Home\Controller;


use SPHPCore\Lib\Mvc\Controller;

class Index extends Controller
{
    public function index(){
        $this->assign('test','欢迎使用Simple PHP 框架');
        $this->response->view();
    }

}