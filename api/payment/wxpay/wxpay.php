<?php 
/**
 * 微信扫码支付
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class wxpay{

    /**
     * 存放支付订单信息
     * @var array
     */
    private $_order_info = array();

    /**
     * 支付信息初始化
     * @param array $payment_info
     * @param array $order_info
     */
    public function __construct($payment_info = array(), $order_info = array()) {
        define('WXN_APPID', $payment_info['payment_config']['appid']);
        define('WXN_MCHID', $payment_info['payment_config']['mchid']);
        define('WXN_KEY', $payment_info['payment_config']['key']);
        $this->_order_info = $order_info;
    }

    /**
     * 组装包含支付信息的url(模式1)
     */
    public function get_payurls() {
        require_once BASE_PATH.'/api/payment/wxpay/lib/WxPay.Api.php';
        require_once BASE_PATH.'/api/payment/wxpay/WxPay.NativePay.php';
        require_once BASE_PATH.'/api/payment/wxpay/log.php';
        $logHandler= new CLogFileHandler(BASE_DATA_PATH.'/log/wxpay/'.date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);
        $notify = new NativePay();
        return $notify->GetPrePayUrl($this->_order_info['pay_sn']);
    }

    /**
     * 组装包含支付信息的url(模式2)
     */
    public function get_payurl() {
        require_once BASE_PATH.'/api/payment/wxpay/lib/WxPay.Api.php';
        require_once BASE_PATH.'/api/payment/wxpay/WxPay.NativePay.php';
        require_once BASE_PATH.'/api/payment/wxpay/log.php';

        $logHandler= new CLogFileHandler(BASE_DATA_PATH.'/log/wxpay/'.date('Y-m-d').'.log');
        $log = Logs::Init($logHandler, 15);

        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($this->_order_info['pay_sn'].'订单');
		$order_type='';
		if($this->_order_info['order_type'] == 'order_vr'){
			$order_type='v'; 
		}elseif($this->_order_info['order_type'] == 'pd_order'){
			$order_type='pd'; 
		}else{
			$order_type='r'; 
		}
		$input->SetAttach($order_type);
		if($order_type=='v'){
			$input->SetOut_trade_no($this->_order_info['order_sn'].''.mt_rand(1000,9999));
		}elseif($order_type=='pd'){
			$input->SetOut_trade_no($this->_order_info['pdr_sn'].''.mt_rand(1000,9999));
		}else{
			$input->SetOut_trade_no($this->_order_info['pay_sn'].''.mt_rand(1000,9999));          
		}
        $input->SetTotal_fee($this->_order_info['api_pay_amount']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 3600));
        $input->SetGoods_tag('');
        $input->SetNotify_url(BASE_SITE_URL.'/api/payment/wxpay/notify_url.php');
        $input->SetTrade_type("NATIVE");
        //$input->SetOpenid($openId);
        $input->SetProduct_id($this->_order_info['pay_sn']);
        $result = WxPayApi::unifiedOrder($input);
        //header("Content-type:text/html;charset=utf-8");
        //print_R($result);exit;
        Logs::DEBUG("unifiedorder:" . json_encode($result));
        return $result["code_url"];
    }
}
