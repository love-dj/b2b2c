<?php
/**
 *
 *

 
  
 */
define('APP_ID','chain');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../shopwt.php';

define('APP_SITE_URL', CHAIN_SITE_URL);
define('CHAIN_TEMPLATES_URL', CHAIN_SITE_URL.'/templets/'.TPL_CHAIN_NAME);
define('BASE_CHAIN_TEMPLATES_URL', dirname(__FILE__).'/templets/'.TPL_CHAIN_NAME);
define('CHAIN_STATIC_SITE_URL',CHAIN_SITE_URL.'/static');
define('TPL_NAME', TPL_CHAIN_NAME);
define('SHOP_TEMPLATES_URL',BASE_SITE_URL.'/templets/'.TPL_NAME);
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
