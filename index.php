<?php
/**
 * 商城板块初始化文件
 *
 *
 *

 
 
 */

define('APP_ID','shop');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/shopwt.php';


define('APP_SITE_URL',BASE_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('SHOP_TEMPLATES_URL',BASE_SITE_URL.'/templets/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templets/'.TPL_NAME);
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
