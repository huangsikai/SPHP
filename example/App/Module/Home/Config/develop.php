<?php
return array(
    SPHP_TPL_SUFFIX => '.phtml',
    SPHP_CHARSET => 'utf-8',
    SPHP_DB => array(
        array(
            SPHP_DB_ID => 1,
            SPHP_DB_HOST => 'localhost',
            SPHP_DB_USER => 'root',
            SPHP_DB_PORT => '3306',
            SPHP_DB_TYPE => 'mysql',
            SPHP_DB_PWD => '',
            SPHP_DB_DATABASE => 'test',
            SPHP_DB_PREFIX => 't_',
            SPHP_DB_CHARSET => 'utf8',
            SPHP_DB_MODE => ['write'],
            SPHP_DB_PRI => 4,
        ),
        array(
            SPHP_DB_ID => 2,
            SPHP_DB_HOST => 'localhost',
            SPHP_DB_USER => 'root',
            SPHP_DB_PORT => '3306',
            SPHP_DB_TYPE => 'mysql',
            SPHP_DB_PWD => '',
            SPHP_DB_DATABASE => 'test',
            SPHP_DB_PREFIX => 't_',
            SPHP_DB_CHARSET => 'utf8',
            SPHP_DB_MODE => ['read',],
            SPHP_DB_PRI => 10,
        ),
    ),
);