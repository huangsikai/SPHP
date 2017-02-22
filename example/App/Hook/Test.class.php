<?php
namespace App\Hook;


class Test {
    public function testhook($str){
        echo 'Text钩子方法：参数'.$str;
    }

    public static function staticMethod(){
        echo '类钩子方法';
    }
}