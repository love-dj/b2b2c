<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 *

 

 */
define('APP_ID','news');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
require __DIR__ . '/../shopwt.php';


define('APP_SITE_URL', NEWS_SITE_URL);
define('TPL_NAME',TPL_NEWS_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templets/'.TPL_NAME);

define('NEWS_STATIC_SITE_URL',NEWS_SITE_URL.'/static');
define('NEWS_TEMPLATES_URL',NEWS_SITE_URL.'/templets/'.TPL_NAME);
define('NEWS_BASE_TPL_PATH',dirname(__FILE__).'/templets/'.TPL_NAME);
define('NEWS_SEO_KEYWORD',$config['seo_keywords']);
define('NEWS_SEO_DESCRIPTION',$config['seo_description']);
//news框架扩展
require(BASE_PATH.'/library/function/function.php');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
