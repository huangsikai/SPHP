<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

return array(

//    SPHP_ROUTE_MATCH => array(
//        array(array('page'=>'p(\d+)','cat'=>'c(\d+)'),array(SPHP_ROUTE_CONTROLLER=>'index',SPHP_ROUTE_ACTION=>'index')),
//        array(array('cat'=>'c(\d+)','page'=>'p(\d+)'),array(SPHP_ROUTE_CONTROLLER=>'index',SPHP_ROUTE_ACTION=>'index')),
//        array(array('page'=>'p(\d+)'),array(SPHP_ROUTE_CONTROLLER=>'index',SPHP_ROUTE_ACTION=>'index')),
//        array(array('cat'=>'c(\d+)'),array(SPHP_ROUTE_CONTROLLER=>'index',SPHP_ROUTE_ACTION=>'index')),
//        array(array('product_id'=>'(\d+)'),array(SPHP_ROUTE_CONTROLLER=>'product',SPHP_ROUTE_ACTION=>'detail')),
//
//        array(array(SPHP_ROUTE_CONTROLLER=>'(\w+)',SPHP_ROUTE_ACTION=>'(\w+)',SPHP_ROUTE_TARGET=>'(\w+)')),
//        array(array(SPHP_ROUTE_CONTROLLER=>'(\w+)',SPHP_ROUTE_ACTION=>'(\w+)')),
//        array(array('unknown'=>'(.*)'),array(SPHP_ROUTE_CONTROLLER=>'index',SPHP_ROUTE_ACTION=>'index')),   //默认
//    ),
    SPHP_ROUTE_BASIC => array(
        array('controller'=>array('a','index'),'action'=>array('b','index')),
    ),

);