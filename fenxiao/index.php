<?php
/**
 * 分销板块初始化文件
 *
 *
 *

 

 */
define('APP_ID','fenxiao');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
require __DIR__ . '/../shopwt.php';

define('APP_SITE_URL', FENXIAO_SITE_URL);
define('TPL_NAME',TPL_FENXIAO_NAME);
define('FENXIAO_STATIC_SITE_URL',FENXIAO_SITE_URL.DS.'static');
define('FENXIAO_TEMPLATES_URL',FENXIAO_SITE_URL.'/templets/'.TPL_NAME);
define('SHOP_TEMPLATES_URL',BASE_SITE_URL.'/templets/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templets/'.TPL_NAME);

require(BASE_PATH.'/control/control.php');
Base::run();
