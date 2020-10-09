<?php
defined('ShopWT') or exit('Access Denied By ShopWT');

 
class paypal{

    private $payment;
    /**
     * 订单信息
     *
     * @var array
     */
    private $order;

    public function __construct($payment_info = array(),$order_info = array()){
        if(!empty($payment_info) and !empty($order_info)){
            $this->payment	= $payment_info;
            $this->order	= $order_info;
        }
    }

    public function submit(){
        $data_order_id      = $this->order['pay_sn'];//外部交易编号
        $data_amount        = $this->order['api_pay_amount'];//订单总价
        $data_return_url    = MOBILE_SITE_URL."/api/payment/paypal/return_url.php";//返回URL
        $data_pay_account   = $this->payment['paypal_account'];
        $currency_code      = $this->payment['paypal_currency'];
        $data_notify_url    = MOBILE_SITE_URL."/api/payment/paypal/notify_url.php";
        $cancel_return      = WAP_SITE_URL."/member/order_list.html";
        $invoice  = $data_order_id;

        if(empty($this->payment['sandbox']))
        {
            $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        else
        {
            $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        }

        echo '<form  id=pf action="'.$paypal_url.'" method="post" >' .   // 不能省略
            "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
            "<input type='hidden' name='business' value='$data_pay_account'>" .                 // 贝宝帐号
            "<input type='hidden' name='item_name' value=". $this->order['pay_sn'].">" .                 // payment for
            "<input type='hidden' name='amount' value='$data_amount'>" .                        // 订单金额
            "<input type='hidden' name='currency_code' value='$currency_code'>" .            // 货币
            "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
            "<input type='hidden' name='invoice' value='$invoice'>" .                      // 订单号
            "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
            "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
            "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
            "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
            "<input type='hidden' name='rm' value='2'>" .
            "<input type='hidden' name='cancel_return' value='$cancel_return'>" .             // 按钮
            "</form> <script>document.getElementById('pf').submit();</script>";


    }


    public function return_verify() {

        /*var_dump($_POST);
        echo '<br><br>';
        var_dump($this->order);
        echo '<br><br>';
        var_dump($this->payment);
        exit;*/
		return true;
        if($_POST['payer_status'] == 'verified')
        {
            // check the payment_status is Completed
            if ($_POST['payment_status'] != 'Completed' && $_POST['payment_status'] != 'Pending')
            {
                return false;
            }

            // check that receiver_email is your Primary PayPal email
            if ($_POST['receiver_email'] != $this->payment['paypal_account'])
            {
                return false;
            }

            if ($this->order['api_pay_amount'] != $_POST['mc_gross'])
            {
                return false;
            }
            if ($this->payment['paypal_currency'] != $_POST['mc_currency'])
            {
                return false;
            }
            return true;
        }
        else
        {
            // log for manual investigation
            return false;
        }
    }



    /**
     * 通知地址验证
     *
     * @return bool
     */
    public function notify_verify() {
        if($_POST['payer_status'] == 'verified')
        {
            // check the payment_status is Completed
            if ($_POST['payment_status'] != 'Completed' && $_POST['payment_status'] != 'Pending')
            {
                return false;
            }

            // check that receiver_email is your Primary PayPal email
            if ($_POST['receiver_email'] != $this->payment['payment_config']['paypal_account'])
            {
                return false;
            }

            if ($this->order['api_pay_amount'] != $_POST['mc_gross'])
            {
                return false;
            }
            if ($this->payment['payment_config']['paypal_currency'] != $_POST['mc_currency'])
            {
                return false;
            }
            return true;
        }
        else
        {
            // log for manual investigation
            return false;
        }

    }
    /**
     *
     * 取得订单支付状态，成功或失败
     * @param array $param
     * @return array
     */
    public function getPayResult($param){
        return  true;
    }

}



?>