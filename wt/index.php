<?php
/**
 * 商城板块初始化文件
 *
 *
 *

 
 
 */

define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/shopwt.php')) exit('shopwt.php isn\'t exists!');

define('APP_SITE_URL', ADMIN_SITE_URL);
define('TPL_NAME',TPL_ADMIN_NAME);
define('ADMIN_TEMPLATES_URL',ADMIN_SITE_URL.'/templets/'.TPL_NAME);
define('ADMIN_STATIC_URL',ADMIN_SITE_URL.'/static');
define('SHOP_TEMPLATES_URL',BASE_SITE_URL.'/templets/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templets/'.TPL_NAME);
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
