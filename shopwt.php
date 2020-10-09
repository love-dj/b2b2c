<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 */

error_reporting(E_ALL & ~E_NOTICE);
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));
define('BASE_API_PATH',BASE_ROOT_PATH.'/api');
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/system');
define('BASE_VENDOR_PATH',BASE_ROOT_PATH.'/vendor');
define("BASE_UPLOAD_PATH", BASE_ROOT_PATH . "/system/upfiles");
define("BASE_STATIC_PATH", BASE_ROOT_PATH . "/system/static");

/**
 * 安装判断
 */
if (!is_file(BASE_ROOT_PATH."/install/lock") && is_file(BASE_ROOT_PATH."/install/index.php")){
    if (ProjectName != 'shop'){
        @header("location: ../install/index.php");
    }else{
        @header("location: install/index.php");
    }
    exit;
}

/**
 * 初始化
 */

define('DS','/');
define('ShopWT',true);
define('StartTime',microtime(true));
define('TIMESTAMP',time());
define('DIR_SHOP','/');
define('DIR_MBMBER','member');
define('DIR_NEWS','news');
define('DIR_BBS','bbs');
define('DIR_WHAT','what');
define('DIR_ADMIN','admin');
define('DIR_API','api');
define('DIR_MOBILE','api/mobile');
define('DIR_WAP','mobile');
define('DIR_STATIC','system/static');
define('DIR_UPLOAD','system/upfiles');

define('ATTACH_PATH','shop');
define('ATTACH_COMMON','shop/common');
define('ATTACH_AVATAR','shop/avatar');
define('ATTACH_EDITOR','shop/editor');
define('ATTACH_MEMBERTAG','shop/membertag');
define('ATTACH_STORE','shop/store');
define('ATTACH_GOODS','shop/store/goods');
define('ATTACH_STORE_DECORATION','shop/store/decoration');
define('ATTACH_LOGIN','shop/login');
define('ATTACH_REGISTER','shop/register');
define('ATTACH_SELLER','shop/seller');
define('ATTACH_ARTICLE','shop/article');
define('ATTACH_BRAND','shop/brand');
define('ATTACH_GOODS_CLASS','shop/goods_class');
define('ATTACH_SHOW','shop/shopwt');
define('ATTACH_ACTIVITY','shop/activity');
define('ATTACH_WATERMARK','shop/watermark');
define('ATTACH_POINTPROD','shop/pointprod');
define('ATTACH_ROBBUY','shop/robbuy');
define('ATTACH_SLIDE','shop/store/slide');
define('ATTACH_VOUCHER','shop/voucher');
define('ATTACH_REDPACKET','shop/coupon');
define('ATTACH_STORE_JOININ','shop/store_joinin');
define('ATTACH_REC_POSITION','shop/rec_position');
define('ATTACH_CONTRACTICON','shop/contracticon');
define('ATTACH_CONTRACTPAY','shop/contractpay');
define('ATTACH_WAYBILL','shop/waybill');
define('ATTACH_MOBILE','mobile');
define('ATTACH_BBS','bbs');
define('ATTACH_NEWS','news');
define('ATTACH_LIVE','live');
define('ATTACH_MALBUM','shop/member');
define('ATTACH_WHAT','what');
define('ATTACH_DELIVERY','since');
define('ATTACH_CHAIN', 'store');
define('ATTACH_FENXIAO','fenxiao');
define('ATTACH_MSELLERR','mseller');
define('ATTACH_ADMIN_AVATAR','admin/avatar');
define('TPL_SHOP_NAME','shopwt');
define('TPL_BBS_NAME', 'shopwt');
define('TPL_WHAT_NAME', 'shopwt');
define('TPL_NEWS_NAME', 'shopwt');
define('TPL_ADMIN_NAME', 'shopwt');
define('TPL_DELIVERY_NAME', 'shopwt');
define('TPL_CHAIN_NAME', 'shopwt');
define('TPL_MEMBER_NAME', 'shopwt');
define('TPL_FENXIAO_NAME','shopwt');
define('TPL_MSELLER_NAME','shopwt');
define('ADMIN_MODULES_SYSTEM', 'system');
define('ADMIN_MODULES_SHOP', 'shop');
define('ADMIN_MODULES_NEWS', 'news');
define('ADMIN_MODULES_BBS', 'bbs');
define('ADMIN_MODULES_WHAT', 'what');
define('ADMIN_MODULES_FLEA', 'flea');
define('ADMIN_MODULES_MOBILE', 'mobile');
define('ADMIN_MODULES_FENXIAO', 'fenxiao');
define('ADMIN_MODULES_BONUSRULES', 'bonusrules');
//define('ADMIN_MODULES_IMCHAT', 'imchat');
define('ADMIN_MODULES_WECHAT', 'wechat');
/*
 * 商家入驻状态定义
 */
//新申请
define('STORE_JOIN_STATE_NEW', 10);
//完成付款
define('STORE_JOIN_STATE_PAY', 11);
//初审成功
define('STORE_JOIN_STATE_VERIFY_SUCCESS', 20);
//初审失败
define('STORE_JOIN_STATE_VERIFY_FAIL', 30);
//付款审核失败
define('STORE_JOIN_STATE_PAY_FAIL', 31);
//开店成功
define('STORE_JOIN_STATE_FINAL', 40);
//默认颜色规格id(前台显示图片的规格)
define('DEFAULT_SPEC_COLOR_ID', 1);
//会员登录注册发送短信间隔（单位为秒）
define('DEFAULT_CONNECT_SMS_TIME', 60);
//会员登录注册时每个手机号发送短信个数
define('DEFAULT_CONNECT_SMS_PHONE', 5);
//会员登录注册时每个IP发送短信个数
define('DEFAULT_CONNECT_SMS_IP', 20);

//商品图片
define('GOODS_IMAGES_WIDTH', '60,240,360,1280');
define('GOODS_IMAGES_HEIGHT', '60,240,360,12800');
define('GOODS_IMAGES_EXT', '_60,_240,_360,_1280');

/**
 *  订单状态
 */
//已取消
define('ORDER_STATE_CANCEL', 0);
//已产生但未支付
define('ORDER_STATE_NEW', 10);
//已支付
define('ORDER_STATE_PAY', 20);
//已发货
define('ORDER_STATE_SEND', 30);
//已收货，交易成功
define('ORDER_STATE_SUCCESS', 40);
//订单超过N小时未支付自动取消
define('ORDER_AUTO_CANCEL_TIME', 1);
//订单超过N天未收货自动收货
define('ORDER_AUTO_RECEIVE_DAY', 10);

//拼团订单有效成团最长时间(天)，不超过平团活动时间
define('PINGOU_ORDER_SUCCESS_MAX_TIME', 7);


//预订尾款支付期限(小时)
define('BOOK_AUTO_END_TIME', 72);

//门店支付订单支付提货期限(天)
define('CHAIN_ORDER_PAYPUT_DAY', 7);
/**
 * 订单删除状态
 */
//默认未删除
define('ORDER_DEL_STATE_DEFAULT', 0);
//已删除
define('ORDER_DEL_STATE_DELETE', 1);
//彻底删除
define('ORDER_DEL_STATE_DROP', 2);

/**
 * 文章显示位置状态,1默认网站前台,2买家,3卖家,4全站
 */
define('ARTICLE_POSIT_SHOP', 1);
define('ARTICLE_POSIT_BUYER', 2);
define('ARTICLE_POSIT_SELLER', 3);
define('ARTICLE_POSIT_ALL', 4);

//兑换码过期后可退款时间，15天
define('CODE_INVALID_REFUND', 15);

//用户退出分销后N天关闭其分销中心
define('MEMBER_FENXIAO_CLOSE',30);

//商品退出分销后N天清理出商品分销表
define('GOODS_FENXIAO_DEL',180);

if (!@include(BASE_DATA_PATH.'/config/config.php')) exit('config.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.php')){
	include(BASE_PATH.'/config/config.php');
}
global $config;

define('DEFAULT_PLATFORM_STORE_ID', $config['default_store_id']);
define('URL_MODEL',$config['url_model']);
define('SUBDOMAIN_SUFFIX', $config['subdomain_suffix']);
define('BASE_SITE_URL', $config['base_site_url']);
define('FENXIAO_SITE_URL', $config['fenxiao_site_url']);
define('FENXIAO_MODULES_URL', $config['fenxiao_modules_url']);
define('NEWS_SITE_URL', $config['news_site_url']);
define('NEWS_MODULES_URL', $config['news_modules_url']);
define('BBS_SITE_URL', $config['bbs_site_url']);
define('BBS_MODULES_URL', $config['bbs_modules_url']);
define('WHAT_SITE_URL', $config['what_site_url']);
define('WHAT_MODULES_URL', $config['what_modules_url']);
define('ADMIN_SITE_URL', $config['admin_site_url']);
define('ADMIN_MODULES_URL', $config['admin_modules_url']);
define('MOBILE_SITE_URL', $config['mobile_site_url']);
define('MOBILE_MODULES_URL', $config['mobile_modules_url']);
define('WAP_SITE_URL', $config['wap_site_url']);
define('MSELLER_SITE_URL', $config['mseller_site_url']);
define('WXMINI_SITE_URL', $config['wxmini_site_url']);
define('UPLOAD_SITE_URL',$config['upload_site_url']);
define('STATIC_SITE_URL',$config['static_site_url']);
define('DELIVERY_SITE_URL',$config['delivery_site_url']);
define('LOGIN_SITE_URL',$config['member_site_url']);
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/system');
define('BASE_UPLOAD_PATH',BASE_DATA_PATH.'/upfiles');
define('BASE_STATIC_PATH',BASE_DATA_PATH.'/static');
define('STATIC_SITE_URL_HTTPS',$config['static_site_url']);
define('CHAIN_SITE_URL', $config['chain_site_url']);
define('MEMBER_SITE_URL', $config['member_site_url']);
define('LOGIN_STATIC_SITE_URL',MEMBER_SITE_URL.'/static');
define('UPLOAD_SITE_URL_HTTPS', $config['upload_site_url']);
define('CHAT_SITE_URL', $config['chat_site_url']);
define('NODE_SITE_URL', $config['node_site_url']);
define('CHARSET',$config['db'][1]['dbcharset']);
define('DBDRIVER',$config['dbdriver']);
define('SESSION_EXPIRE',$config['session_expire']);
define('LANG_TYPE',$config['lang_type']);
define('COOKIE_PRE',$config['cookie_pre']);
define('DBPRE',$config['tablepre']);
define('DBNAME',$config['db'][1]['dbname']);
$_GET['w'] = is_string($_GET['w']) ? strtolower($_GET['w']) : (is_string($_POST['w']) ? strtolower($_POST['w']) : null);
$_GET['t'] = is_string($_GET['t']) ? strtolower($_GET['t']) : (is_string($_POST['t']) ? strtolower($_POST['t']) : null);

if (empty($_GET['w'])){
    require_once(BASE_DATA_PATH.'/library/function/route.php');
    new Route($config);
}
$_GET['w'] = preg_match('/^[\w]+$/i',$_GET['w']) ? $_GET['w'] : 'index';
$_GET['t'] = preg_match('/^[\w]+$/i',$_GET['t']) ? $_GET['t'] : 'index';

$ignore = array('article_content','pgoods_body','doc_content','content','sn_content','g_body','store_description','p_content','robbuy_intro','remind_content','note_content','show_pic_url','show_word_url','show_slide_url','appcode','mail_content', 'message_content','member_gradedesc');
if (!class_exists('Security')) require(BASE_DATA_PATH.'/library/bin/security.php');
$_GET = !empty($_GET) ? Security::getAddslashesForInput($_GET,$ignore) : array();
$_POST = !empty($_POST) ? Security::getAddslashesForInput($_POST,$ignore) : array();
$_REQUEST = !empty($_REQUEST) ? Security::getAddslashesForInput($_REQUEST,$ignore) : array();
$_SERVER = !empty($_SERVER) ? Security::getAddSlashes($_SERVER) : array();

if ($config['gzip'] == 1 && function_exists('ob_gzhandler') && $_GET['inajax'] != 1){
	ob_start('ob_gzhandler');
}else {
	ob_start();
}

require_once(BASE_DATA_PATH.'/library/bin/queue.php');
require_once(BASE_DATA_PATH.'/library/function/core.php');
require_once(BASE_DATA_PATH.'/library/function/base.php');
require_once(BASE_DATA_PATH.'/library/function/goods.php');

if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Base', 'autoload'));
} else {
	function __autoload($class) {
		return Base::autoload($class);
	}
}
