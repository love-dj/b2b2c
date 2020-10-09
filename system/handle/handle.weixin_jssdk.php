<?php
/**
 * 微信jssdk
 *

 *

 
 
 */
class weixin_jssdkHandle{
	private $usersid;
	private $access_token;
	private $curl_timeout;
	private $noncestr;
	private $timestamp;

	function __construct($usersid=''){
		$this->curl_timeout = 30;
		$this->noncestr = 'zhuaihushare';
		$this->timestamp = time();
		$this->access_token = '';
	}
	
	private function curl_get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;
	}
	
	private function jssdk_get_ticket(){		
		$item = Model('weixin_wechat')->field('wechat_access_token,wechat_expires_in,wechat_type,wechat_appid,wechat_appsecret,wechat_jssdk_ticket,wechat_jssdk_expires_in,wechat_id')->order('wechat_jssdk_expires_in desc')->find();
		if(empty($item)){
			return array();
		}
		
		$diff = intval($item['wechat_jssdk_expires_in']) - 300;
		if($item['wechat_jssdk_ticket'] && $diff>=time()){
			return array('appid'=>$item['wechat_appid'],'ticket'=>$item['wechat_jssdk_ticket']);
		}
		
		$token = 0;
		$diff_1 = intval($item['wechat_expires_in']) - 300;
		if($item['wechat_access_token'] && $diff_1 >= time()){
			$weixin_ip = $this->curl_get('https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $item['wechat_access_token']);
			if(!empty($weixin_ip['errcode'])){
				if($weixin_ip['errcode'] == '40001' && strpos($weixin_ip['errmsg'], 'access_token is invalid or not latest')>-1){
					$token = 0;
				}
			}else{
				$this->access_token = $item['wechat_access_token'];
			}
		}
		
		if($token == 0){
			$this->access_token = Handle('weixin_token')->get_accesstoken_fromwx($item['wechat_appid'],$item['wechat_appsecret'],$item['wechat_type'],$item['wechat_id']);
		}
		
			
		if($this->access_token){
			$data = $this->curl_get('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->access_token . '&type=jsapi');
			if(!empty($data['ticket'])){
				$ticket = $data['ticket'];
				$Data = array(
					'wechat_jssdk_ticket'=>$data['ticket'],
					'wechat_jssdk_expires_in'=>time() + $data['expires_in']
				);
				Model('weixin_wechat')->where(array('wechat_id'=>$item['wechat_id']))->update($Data);
				return array('appid'=>$item['wechat_appid'],'ticket'=>$data['ticket']);
			}else{
				return array();
			}
		}else{
			return array();
		}
	}
	
	public function jssdk_get_signature($url){//获取自定义分享配置信息
		$ticket = $this->jssdk_get_ticket();
		if(empty($ticket)){
			return array();
		}else{			
			$tmpArr = array(
				'jsapi_ticket'=>$ticket['ticket'],
				'noncestr'=>$this->noncestr,
				'timestamp'=>$this->timestamp,
				'url'=>$url
			);
			
			ksort($tmpArr);
			$html = '';
			foreach($tmpArr as $k => $v){
				$html = $html . '&' . $k . '=' . $v;
			}
			$s = substr($html, 1);
			$tmpArr['signature'] = sha1($s);
			$tmpArr['appId'] = $ticket['appid'];
			return $tmpArr;
		}
	}
}
?>