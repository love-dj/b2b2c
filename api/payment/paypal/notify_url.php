<?php
/**
 * paypal通知地址
 *
 * 
 * 
 */

$_GET['w']	= 'payment';
$_GET['t']		= 'notify';
$_GET['payment_code'] = 'paypal';
$_POST['extra_common_param'] = 'real_order';
$_POST['out_trade_no'] = $_POST['invoice'];
$_POST['trade_no'] = $_POST['txn_id'];
require_once(dirname(__FILE__).'/../../../index.php');