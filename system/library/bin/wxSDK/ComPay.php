<?php

class ComPay {
	//证书
	private $apiclient_cert = '';
	private $apiclient_key = '';
	//pay的秘钥值
	private $apikey = '';
	//错误信息
	private $error = '';

	private $mchid = '';//商户号
	private $mchappid = '';//公众号
	private $openid = '';//接收者openid
	private $amount = 100;//金额
	private $partnertradeno = '';//订单号
	private $spbillcreateip = '';//触发ip
	private $checkname = 'NO_CHECK';//校验要求

	private $sendname = '';
	private $wishing = '';
	private $actname = '';
	private $remark = '';

	private $totalnum =1;
	private $amttype ='ALL_RAND';

	//裂变红包
	private $api_group = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
    //普通红包
	private $api_single = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
    //企业支付
	private $api_compay = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
	//约包查询
	private $api_redbag_select = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";
	//企业支付查询
	private $api_compay_select = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";
	
	
	/**
	*公用-支付用商户号
	*@var string
	*/
	public function setMchid($mchid){
		$this->mchid = $mchid;
	}
	/**
	*公用-pay的秘钥值
	*@var string
	*/
	public function setApiKey($apikey){
		$this->apikey = $apikey;
	}


	/**
	*企业支付用微信公众号
	*@var string
	*/
	public function setMchAppid($mchappid){
		$this->mchappid = $mchappid;
	}
	/**
	*企业支付接收用户openid
	*@var string
	*/
	public function setOpenid($openid){
		$this->openid = $openid;
	}

	/**
	*企业支付金额
	*@var integer
	*/
	public function setAmount($amount){
		$this->amount = $amount;
	}
	/**
	*企业支付描述
	*@var string
	*/
	public function setDesc($desc){
		$this->remark = $desc;
	}
	
	/**
	*企业支付订单号
	*@var string
	*/
	public function setPartnerTradeNo($partnertradeno){
		$this->partnertradeno = $partnertradeno;
	}
	/**
	*企业支付触发ip
	*@var string
	*/
	public function setSpbillCreateIp($spbillcreateip){
		$this->spbillcreateip = $spbillcreateip;
	}
	/**
	*企业支付校验规则
	*@var string
	*/
	public function setCheckName($checkname){
		$this->checkname = $checkname;
	}

	/**
	*红包支付公众号
	*@var string
	*/
	public function setWxappid($wxappid){
		$this->mchappid = $wxappid;
	}
	/**
	*红包支付订单号
	*@var string
	*/
	public function setMchBillNo($mchbillno){
		$this->partnertradeno = $mchbillno;
	}
	/**
	*红包支付触发ip
	*@var string
	*/
	public function setClientIp($clientip){
		$this->spbillcreateip = $clientip;
	}
	/**
	*红包接收者/裂一变红包的种子接收者
	*@var string
	*/
	public function setReOpenid($reopenid){
		$this->openid = $reopenid;
	}
	/**
	*红包支付金额
	*@var integer
	*/
	public function setTotalAmount($totalamount){
		$this->amount = $totalamount;
	}
	/**
	*红包支付公众号
	*@var string
	*/
	public function setSendName($sendname){
		$this->sendname = $sendname;
	}
	/**
	*红包支祝福语
	*@var string
	*/
	public function setWishing($wishing){
		$this->wishing = $wishing;
	}
	/**
	*红包支付活动名称
	*@var string
	*/
	public function setActName($actname){
		$this->actname = $actname;
	}
	/**
	*红包支付备注信息
	*@var string
	*/
	public function setRemark($remark){
		$this->remark = $remark;
	}
	/**
	*红包支付个数-裂变专用
	*@var string
	*/
	public function setTotalNum($totalnum){
		$this->totalnum = $totalnum;
	}

	public function setAppId($appid){
		$this->mchappid = $appid;
	}
	
	public function certfile($certfile){
		$this->apiclient_cert = $certfile;
	}
	
	public function keyfile($keyfile){
		$this->apiclient_key = $keyfile;
	}
	/**
	*错误反馈
	*@return string
	*/
	public function error(){
		return $this->error;
	}

	/**
	*普通红包支付
	*@return boolean
	*/
	public function RedBag(){
		if(!$this->inited()) return;
		$obj = array();
		$obj['wxappid'] = $this->mchappid;
		$obj['mch_id'] = $this->mchid;
		$obj['mch_billno'] = $this->partnertradeno;
		$obj['client_ip'] = $this->spbillcreateip;
		$obj['re_openid'] = $this->openid;
		$obj['total_amount'] = $this->amount;
		$obj['total_num'] = 1;
		$obj['send_name'] = $this->sendname;
		$obj['wishing'] = $this->wishing;
		$obj['act_name'] = $this->actname;
		$obj['remark'] = $this->remark;
		$url = $this->api_single;
		return $this->Pay($url,$obj);
	}



	/**
	*裂变红包支付
	*@return boolean
	*/
	public function RedBagGroup(){
		if(!$this->inited()) return;
		$obj = array();
		$obj['wxappid'] = $this->mchappid;
		$obj['mch_id'] = $this->mchid;
		$obj['mch_billno'] = $this->partnertradeno;
		$obj['re_openid'] = $this->openid;
		$obj['total_amount'] = $this->amount;
		$obj['total_num'] = $this->totalnum;
		$obj['amt_type'] = $this->amttype;
		$obj['send_name'] = $this->sendname;
		$obj['wishing'] = $this->wishing;
		$obj['act_name'] = $this->actname;
		$obj['remark'] = $this->remark;
		$url = $this->api_single;
		return $this->Pay($url,$obj);
	}
	/**
	*企业支付
	*@return boolean
	*/
	public function ComPay(){
		if(!$this->inited()) return;
		$obj = array();
		$obj['openid'] = $this->openid;
		$obj['amount'] = $this->amount;
		$obj['desc'] = $this->remark;
		$obj['mch_appid'] = $this->mchappid;
		$obj['mchid'] = $this->mchid;
		$obj['partner_trade_no'] = $this->partnertradeno;
		$obj['spbill_create_ip'] = $this->spbillcreateip;
		$obj['check_name'] = $this->checkname;
		$url = $this->api_compay;
		return $this->Pay($url,$obj);
	}
	/**
	*红包查询
	*@return array
	*/
	public function BagSelect(){
		$this->license();
		$obj = array();
		$obj['appid'] = $this->mchappid;
		$obj['mch_id'] = $this->mchid;
		$obj['mch_billno'] = $this->partnertradeno;
		$obj['bill_type'] = 'MCHT';
		$url = $this->api_redbag_select;
		return $this->Pay($url,$obj);
	}
	/**
	*企业支付查询
	*@return array
	*/
	public function ComPaySelect(){
		$this->license();
		$obj = array();
		$obj['appid'] = $this->mchappid;
		$obj['mch_id'] = $this->mchid;
		$obj['partner_trade_no'] = $this->partnertradeno;
		$url = $this->api_compay_select;
		return $this->Pay($url,$obj);
	}

	/**
	*支付前准备
	*@return boolean
	*/
	private function inited(){
		$inited = true;
		
		if(!$this->partnertradeno){
			$this->partnertradeno = $this->GenBillNo();
		}
		if(!$this->spbillcreateip)
			$this->spbillcreateip = $_SERVER['REMOTE_ADDR'];
		$this->license();
		return $inited;
	}
	/**
	*证书初始化
	*放在同目录 cacert/文件夹下
	*/
	private function license(){
		if(!$this->apiclient_cert) 
			$this->apiclient_cert = dirname(__FILE__)."/cacert/apiclient_cert.pem";
		if(!$this->apiclient_key) 
			$this->apiclient_key = dirname(__FILE__)."/cacert/apiclient_key.pem";
	}

	/**
	*生在订单号
	*@return boolean
	*/
	private function GenBillNo(){
		$rnd_num = array('0','1','2','3','4','5','6','7','8','9');
		$rndstr = "";
		while(strlen($rndstr)<10){
			$rndstr .= $rnd_num[array_rand($rnd_num)];    
		}

		return $this->mchid.date("Ymd").$rndstr;
	}

	/**
	*完成支付操作
	*@url string
	*@obj array
	*@return boolean
	*/
	private function Pay($url,$obj){	
		$obj['nonce_str'] = $this->create_noncestr();
		$sign = $this->getSign($obj);
		$obj['sign'] = $sign;
		$postXml = $this->arrayToXml($obj);
		$responseXml = $this->CurlPostSsl($url,$postXml);
		return $responseXml;
	}
	/**
	*创建随机字串
	*@return string
	*/
	private function create_noncestr($length = 32){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$str = '';
		for ($i = 0; $i <$length; $i++){
			$str .= substr($chars,mt_rand(0,strlen($chars)-1),1);
		}
		return $str;
	}
	/**
	*创建签名
	*@return string
	*/
	private function getSign($arr){
		ksort($arr); //按照键名排序
		$sign_raw = '';
		foreach($arr as $k => $v){
			$sign_raw .= $k.'='.$v.'&';
		}
		$sign_raw .= 'key='.$this->apikey;

		return strtoupper(md5($sign_raw));
	}

	/**
     * WXHongBao::genXMLParam()
     * 生成post的参数xml数据包
     * @return $xml
     */
	private function arrayToXml($arr){
		$xml ="<xml>";
		foreach ($arr as $key => $val) {
			if (is_numeric($val)) {
				$xml .= "<".$key.">".$val."</".$key.">";
			}else{
				$xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml .= "</xml>";
		return $xml;		
	}

	/**
     * curl提交
     * @return $boolean
     */
	private function CurlPostSsl($url,$xml,$second = 10){
		$ch = curl_init();   	
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);    	
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,$this->apiclient_cert);    	
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,$this->apiclient_key);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		curl_close($ch);		
		return $data;
	}
}

