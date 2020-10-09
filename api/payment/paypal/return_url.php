<?php
/**
 * paypal返回地址
 *
 *
 * 
 */
$_GET['w']	= 'payment';
$_GET['t']		= 'return';
$_GET['payment_code'] = 'paypal';
$_GET['extra_common_param'] = 'real_order';
$_GET['out_trade_no'] = $_POST['invoice'];
$_GET['trade_no'] = $_POST['txn_id'];
require_once(dirname(__FILE__).'/../../../index.php');