<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 * 购买行为
 *

 
 

 */
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
require __DIR__ . '/../shopwt.php';

session_save_path(BASE_DATA_PATH.DS.'session');
require_once(BASE_DATA_PATH.DS.'config/config.php');
$site_url = $config['base_site_url'];
$version = $config['version'];
$setup_date = $config['setup_date'];
$gip = $config['gip'];
$dbtype = $config['dbdriver'];
$dbcharset = $config['db']['1']['dbcharset'];
$dbserver = $config['db']['1']['dbhost'];
$dbserver_port = $config['db']['1']['dbport'];
$dbname = $config['db']['1']['dbname'];
$db_pre = $config['tablepre'];
$dbuser = $config['db']['1']['dbuser'];
$dbpasswd = $config['db']['1']['dbpwd'];
$lang_type = $config['lang_type'];
$cookie_pre = $config['cookie_pre'];
unset($config);

if($_GET['w'] == 'show'){
    define('ATTACH_SHOW','shop/shopwt');
    $show_classfile = BASE_PATH.DS.'control/show.php';
    if(is_file($show_classfile)){
        include_once ($show_classfile);
        $show = new showControl();
        $show->showWt();
    }else{
        echo "Show System Inner Error!";
    }
}elseif ($_GET['w'] == 'get_session'){
    $key = $_GET['key'];
    $val = '';
    if (!empty($_SESSION[$key])) $val = $_SESSION[$key];
    echo $val;
    exit;
}elseif ($_GET['w'] == 'sharebind'){
    if($_GET['type'] == 'sinaweibo'){
        include BASE_API_PATH.DS.'sns/sinaweibo/index.php';
    }
}
