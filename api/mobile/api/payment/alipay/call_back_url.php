<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 */

$_GET['w'] = 'payment';
$_GET['t']	= 'return';
$_GET['payment_code']	= 'alipay';
require_once(dirname(__FILE__).'/../../../index.php');
?>
