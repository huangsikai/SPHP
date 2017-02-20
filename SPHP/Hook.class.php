<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/16
 */

namespace SPHPCore;



abstract class Hook
{
    public $on = false;
    abstract public function controller($controllerName,$actionName);
    abstract public function model($modelName,$data);
    abstract public function layout($layoutName);
    abstract public function template($viewName);
    abstract public function exception($message,$trace,$traceStr);
    abstract public function error($type,$message,$file,$line);
    abstract public function shutdown($time);
}