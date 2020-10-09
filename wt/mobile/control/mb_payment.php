<?php
/**
 * 手机支付方式
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class mb_paymentControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexWt() {
        $this->payment_listWt();
    }

    public function payment_listWt() {
        $model_mb_payment = Model('mb_payment');
        $mb_payment_list = $model_mb_payment->getMbPaymentList();
        Tpl::output('mb_payment_list', $mb_payment_list);
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_payment.list');
    }

    /**
     * 编辑
     */
    public function payment_editWt() {
        $payment_id = intval($_GET["payment_id"]);

        $model_mb_payment = Model('mb_payment');

        $mb_payment_info = $model_mb_payment->getMbPaymentInfo(array('payment_id' => $payment_id));
        Tpl::output('payment', $mb_payment_info);
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_payment.edit');
    }

    /**
     * 编辑保存
     */
    public function payment_saveWt() {
        $payment_id = intval($_POST["payment_id"]);

        $data = array();
        $data['payment_state'] = intval($_POST["payment_state"]);

        switch ($_POST['payment_code']) {
            case 'alipay':
                $payment_config = array(
                    'alipay_account' => $_POST['alipay_account'],
                    'alipay_key' => $_POST['alipay_key'],
                    'alipay_partner' => $_POST['alipay_partner']
                );
                break;
            case 'wxpay':
                $payment_config = array(
                    'wxpay_appid'       => $_POST['wxpay_appid'],
                    'wxpay_appsecret'   => $_POST['wxpay_appsecret'],
                    'wxpay_appkey'      => $_POST['wxpay_appkey'],
                    'wxpay_partnerid'   => $_POST['wxpay_partnerid'],
                    'wxpay_partnerkey'  => $_POST['wxpay_partnerkey']
                );
                break;
            case 'wxpay_jsapi':
                $payment_config = array(
                    'appId'     => $_POST['appId'],
                    'appSecret' => $_POST['appSecret'],
                    'partnerId' => $_POST['partnerId'],
					'apiKey' => $_POST['apiKey'],
                );
                break;
            case 'wxpay_h5':
                $payment_config = array(
                    'appId'     => $_POST['appId'],
                    'appSecret' => $_POST['appSecret'],
                    'partnerId' => $_POST['partnerId'],
					'apiKey' => $_POST['apiKey'],
                );
                break;
            case 'mini_wxpay':
                $payment_config = array(
                    'appId'     => $_POST['appId'],
                    'appSecret' => $_POST['appSecret'],
                    'partnerId' => $_POST['partnerId'],
		    'apiKey' => $_POST['apiKey'],
                );
                break;
            case 'alipay_native':
                $payment_config = array(
                'alipay_account' => $_POST['alipay_account'],
                'alipay_key' => $_POST['alipay_key'],
                'alipay_partner' => $_POST['alipay_partner']
                );
                break;
		case 'paypal':
                $payment_config = array(
                    'paypal_account' => $_POST['paypal_account'],
                    'paypal_currency' => $_POST['paypal_currency'],
                    'sandbox' => $_POST['sandbox']
                );
                break;
            default:
                showMessage(L('param_error'), '');
        }
        $data['payment_config'] = $payment_config;

        $model_mb_payment = Model('mb_payment');

        $result = $model_mb_payment->editMbPayment($data, array('payment_id' => $payment_id));
        if($result) {
            showMessage(Language::get('wt_common_save_succ'), urlAdminMobile('mb_payment', 'payment_list'));
        } else {
            showMessage(Language::get('wt_common_save_fail'), urlAdminMobile('mb_payment', 'payment_list'));
        }
    }
}
