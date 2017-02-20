<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace App\Module\Admin\Controller;


use App\Model\User;
use SPHPCore\Lib\Mvc\Controller;

class Index extends Controller
{
    public function index(){
        $this->response->text("Admin模块Index控制器");
    }

}