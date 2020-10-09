<?php
/**
 * 微信支付接口类
 * JSAPI 适用于微信内置浏览器访问WAP时支付
 *
 *


 


 */
defined('ShopWT') or exit('Access Denied By ShopWT');

/**
 * @todo
 */
class mini_wxpay
{
    const DEBUG = 0;

    protected $config;

    public function __construct()
    {
        $this->config = (object) array(
            'appId' => '',
            'appSecret' => '',
            'partnerId' => '',
            'apiKey' => '',

            'notifyUrl' => MOBILE_SITE_URL . '/api/payment/mini_wxpay/notify_url.php',
            'finishedUrl' => WXMINI_SITE_URL . '/html/member/payment_result.html?_=2&attach=_attach_',
            'undoneUrl' => WXMINI_SITE_URL . '/html/member/payment_result_failed.html?_=2&attach=_attach_',

            'orderSn' => date('YmdHis'),
            'orderInfo' => 'Test wxpay js api',
            'orderFee' => 1,
            'orderAttach' => '_',
        );
    }

    public function setConfig($name, $value)
    {
        $this->config->$name = $value;
    }

    public function setConfigs(array $params)
    {
        foreach ($params as $name => $value) {
            $this->config->$name = $value;
        }
    }

    public function notify()
    {
        try {
            $data = $this->onNotify();
            $resultXml = $this->arrayToXml(array(
                'return_code' => 'SUCCESS',
            ));

            if (self::DEBUG) {
                file_put_contents(__DIR__ . '/log.txt', var_export($data, true), FILE_APPEND | LOCK_EX);
            }

        } catch (Exception $ex) {

            $data = null;
            $resultXml = $this->arrayToXml(array(
                'return_code' => 'FAIL',
                'return_msg' => $ex->getMessage(),
            ));

            if (self::DEBUG) {
                file_put_contents(__DIR__ . '/log_err.txt', $ex . PHP_EOL, FILE_APPEND | LOCK_EX);
            }

        }

        return array(
            $data,
            $resultXml,
        );
    }

    public function onNotify()
    {
        $d = $this->xmlToArray(file_get_contents('php://input'));

        if (empty($d)) {
            throw new Exception(__METHOD__);
        }

        if ($d['return_code'] != 'SUCCESS') {
            throw new Exception($d['return_msg']);
        }

        if ($d['result_code'] != 'SUCCESS') {
            throw new Exception("[{$d['err_code']}]{$d['err_code_des']}");
        }

        if (!$this->verify($d)) {
            throw new Exception("Invalid signature");
        }

        return $d;
    }

    public function verify(array $d)
    {
        if (empty($d['sign'])) {
            return false;
        }

        $sign = $d['sign'];
        unset($d['sign']);

        return $sign == $this->sign($d);
    }

    protected $control;

    public function paymentHtml($control = null)
    {
        $this->control = $control;
        $prepayId = $this->getPrepayId();
       
        $params = array();
        $params['appId'] = $this->config->appId;
        $params['timeStamp'] = '' . time();
        $params['nonceStr'] = md5(uniqid(mt_rand(), true));
        $params['package'] = 'prepay_id=' . $prepayId;
        $params['signType'] = 'MD5';

        $sign = $this->sign($params);
        $params['paySign'] = $sign;
		$params['prepay_id'] = $prepayId;
        // @todo timestamp
        $jsonParams = json_encode($params);
		return $jsonParams;
			//$urlData='?timestamp='.$params['timeStamp'].'&nonceStr='.$params['nonceStr'].'&prepay_id='.$prepayId.'&signType='.$params['signType'].'&paySign='.$params['paySign'].'&orderId='.$this->config->orderSn; 		
 
    }

	public function _record($message,$level='ERR') {
        $now = @date('Y-m-d H:i:s',time());
        
            
                $log_file = BASE_DATA_PATH.'/log/'.date('Ymd',TIMESTAMP).'.log';
                $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
                $url .= " ( app={$_GET['act']}&cb={$_GET['op']} ) ";
                $content = "[{$now}] {$url}\r\n{$level}: {$message}\r\n";
                file_put_contents($log_file,$content, FILE_APPEND);
               
    }
    protected function getOpenId()
    {
        if ($c = $this->control) {
            $openId = $c->getOpenId();
            if ($openId) {
                return $openId;
            }

            // through multiple requests
            $openId = $this->getOpenIdThroughMultipleRequests();
            $c->setOpenId($openId);
            return $openId;
        }
        return $this->getOpenIdThroughMultipleRequests();
    }

    public function getPrepayId()
    {
        // ...
        $openId = $this->getOpenId();

        $data = array();
        $data['appid'] = $this->config->appId;
        $data['mch_id'] = $this->config->partnerId;
        $data['nonce_str'] = md5(uniqid(mt_rand(), true));
        $data['body'] = $this->config->orderInfo;
        $data['attach'] = $this->config->orderAttach;
        $data['out_trade_no'] = $this->config->orderSn;
        $data['total_fee'] = $this->config->orderFee;
        $data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['notify_url'] = $this->config->notifyUrl;
        $data['trade_type'] = 'JSAPI';
        $data['openid'] = $openId;

        $sign = $this->sign($data);
        $data['sign'] = $sign;

        $result = $this->postXml('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);

        if ($result['return_code'] != 'SUCCESS') {
            throw new Exception($result['return_msg']);
        }

        if ($result['result_code'] != 'SUCCESS') {
            throw new Exception("[{$result['err_code']}]{$result['err_code_des']}");
        }

        return $result['prepay_id'];
    }

    public function getOpenIdThroughMultipleRequests()
    {
		$rurl = sprintf(
			'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
            $this->config->appId,
            $this->config->appSecret,
            $this->config->js_code
        );
        //$d = json_decode(file_get_contents($rurl), true);
		
		$res = $this->wxcurl_get($rurl);
		$d = json_decode($res, true);
		
        if (empty($d)) {
            throw new Exception(__METHOD__);
        }

        if (isset($d['openid'])) {
            return $d['openid'];
        }

        throw new Exception(var_export($d, true));
    }

	public function wxcurl_get($url){
		$chs = curl_init();
        curl_setopt($chs, CURLOPT_TIMEOUT, 30);
        curl_setopt($chs, CURLOPT_URL, $url);
        curl_setopt($chs, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($chs, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($chs, CURLOPT_HEADER, FALSE);
        curl_setopt($chs, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($chs);
		if (!$response) {
            throw new Exception('CURL Error: ' . curl_errno($chs));
        }
        curl_close($chs);
		return $response;
	}

    public function sign(array $data)
    {
        ksort($data);

        $a = array();
        foreach ($data as $k => $v) {
            if ((string) $v === '') {
                continue;
            }
            $a[] = "{$k}={$v}";
        }

        $a = implode('&', $a);
        $a .= '&key=' . $this->config->apiKey;

        return strtoupper(md5($a));
    }

    public function postXml($url, array $data)
    {
        // pack xml
        $xml = $this->arrayToXml($data);

        // curl post
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec($ch);
        if (!$response) {
            throw new Exception('CURL Error: ' . curl_errno($ch));
        }
        curl_close($ch);

        // unpack xml
        return $this->xmlToArray($response);
    }

    public function arrayToXml(array $data)
    {
        $xml = "<xml>";
        foreach ($data as $k => $v) {
            if (is_numeric($v)) {
                $xml .= "<{$k}>{$v}</{$k}>";
            } else {
                $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function xmlToArray($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

}
