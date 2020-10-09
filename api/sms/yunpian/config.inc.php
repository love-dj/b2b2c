<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
/*
 * 配置文件
 */
$options = array();
$options['apikey'] = C('wt_sms_key'); //apikey
$options['signature'] =  C('wt_sms_signature'); //签名
return $options;
?>