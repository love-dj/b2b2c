<?php
/**
 * 社区板块初始化文件
 *
 * 社区板块初始化文件，引用框架初始化文件
 *
 *

 

 */
define('APP_ID','bbs');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../shopwt.php';

define('APP_SITE_URL', BBS_SITE_URL);
define('TPL_NAME', TPL_BBS_NAME);
define('BBS_TEMPLATES_URL', BBS_SITE_URL.'/templets/'.TPL_NAME);
define('BBS_STATIC_SITE_URL',BBS_SITE_URL.'/static');
require(BASE_PATH.'/library/function/function.php');

require(BASE_PATH.'/control/control.php');
Base::run();
