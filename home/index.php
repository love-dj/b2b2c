<?php
/**
 *
 *

 
 
 */
define('APP_ID','member');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../shopwt.php';

define('APP_SITE_URL', MEMBER_SITE_URL);
define('TPL_NAME',TPL_MEMBER_NAME);
define('SHOP_TEMPLATES_URL',BASE_SITE_URL.'/templets/'.TPL_NAME);
define('MEMBER_TEMPLATES_URL', MEMBER_SITE_URL.'/templets/'.TPL_MEMBER_NAME);
define('BASE_MEMBER_TEMPLATES_URL', dirname(__FILE__).'/templets/'.TPL_MEMBER_NAME);
define('MEMBER_STATIC_SITE_URL',MEMBER_SITE_URL.'/static');
define('LOGIN_STATIC_SITE_URL',LOGIN_SITE_URL.'/static');
define('LOGIN_TEMPLATES_URL', LOGIN_SITE_URL.'/templets/'.TPL_MEMBER_NAME);

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
