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
//        echo $this->request->getCurrentActionName();
//        $this->assign('name',array('a','b','c'=>array(123)));
//        $this->sss='hello';
//        $this->setLayout('layout');
        $this->response->view();
//        $a->setLayout('a');
//        $a->setLayout('a');
    }
}