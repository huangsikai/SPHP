<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace App\Model;


use SPHPCore\Lib\Mvc\Model;

class User extends Model
{
     protected static $tableName = 'user';
//     protected static $primaryKey = 'parent_id';

     public function beforeInsert(&$data)
     {

     }

     public function afterInsert($data,$lastId){

         echo 'last:'.$lastId;
     }


     public function beforeUpdate(&$data){
        // print_r($this->id);
     }
     public function afterUpdate($data){

     }

}