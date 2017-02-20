<?php
##调试开关；  true为打开 | false或不定义为关闭
define('DEBUG',true);

##环境标示；  develop 开发环境 | test 测试环境 | product 生成环境
define('ENV','develop');

##模块名称； 名称可以自定义   默认为Home | 后台为Admin
define('MODULE','Home');

##相对web服务器根目录的子目录；  例子：web服务器根目录为www  example项目位于 www/SPHP/example
define('SUB_DIR','SPHP/example');

##导入SPHP框架
include '../../SPHP/S.class.php';

##启动项目
S::Main();

