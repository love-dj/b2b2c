<?php
/**
 *
 *
 */
define('APP_ID','delivery');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/shopwt.php')) exit('shopwt.php isn\'t exists!');

define('APP_SITE_URL',DELIVERY_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('DELIVERY_TEMPLATES_URL', DELIVERY_SITE_URL.'/templets/'.TPL_NAME);
define('BASE_DELIVERY_TEMPLATES_URL', dirname(__FILE__).'/templets/'.TPL_NAME);
define('DELIVERY_STATIC_SITE_URL',DELIVERY_SITE_URL.'/static');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
?>
