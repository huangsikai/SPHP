<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace App\Module\Home\Controller;


use App\Model\User;
use SPHPCore\Lib\Mvc\Controller;

class Index extends Controller
{
    public function index(){
        $this->forward('Index','detail');
    }

    public function detail(){
        $this->response->view();
    }
}