<?php
/**
 * Describe: 用户模型
 * Author: Sky
 * Date: 2017/2/13
 */

namespace App\Model;


use SPHPCore\Lib\Mvc\Model;

class User extends Model
{
     protected static $tableName = 'user';
     protected static $primaryKey = 'id';

    public function getUserDetail(){
         echo 'UserModel里的getUserDetail方法';
         hello();
    }
}