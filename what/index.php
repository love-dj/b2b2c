<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 *

 
  
 */
define('APP_ID','what');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../shopwt.php';

define('APP_SITE_URL', WHAT_SITE_URL);
define('what_IMG_URL',UPLOAD_SITE_URL.DS.ATTACH_WHAT);
define('TPL_NAME',TPL_WHAT_NAME);
define('WHAT_STATIC_SITE_URL',WHAT_SITE_URL.'/static');
define('WHAT_TEMPLATES_URL',WHAT_SITE_URL.'/templets/'.TPL_NAME);
define('what_BASE_TPL_PATH',dirname(__FILE__).'/templets/'.TPL_NAME);

//define('what_SEO_KEYWORD',$config['seo_keywords']);
//define('what_SEO_DESCRIPTION',$config['seo_description']);

define('what_SEO_KEYWORD',C('seo_keywords'));
define('what_SEO_DESCRIPTION',C('seo_description'));

//买什么框架扩展
require(BASE_PATH.'/library/function/function.php');

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
