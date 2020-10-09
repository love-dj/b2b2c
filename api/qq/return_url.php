<?php
header("Content-type: text/html; charset=UTF-8");
define('ShopWT',true);

$config_file = '../../system/config/config.php';
$config = require($config_file);
$code = $_GET['code'];
$state  = $_GET['state'];
if(!empty($code) && !empty($state)) {
    $qq_url = $config['member_site_url'].'/index.php?w=connect_qq&t=index&code=';
    if($state == 'api'){
        $qq_url = $config['mobile_site_url'].'/index.php?w=connect&t=get_qq_info&client=wap&code=';
    }
    @header("location: ".$qq_url.$code);
} else {
    exit('参数错误');
}