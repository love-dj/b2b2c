<?php
/**
 * 微信token
 *

 *

 
 
 */
class weixin_tokenHandle{
	private $usersid;
	private $access_token;
	private $curl_timeout;

	function __construct($usersid=''){
		$this->curl_timeout = 30;
		$this->access_token = '';
	}
	
	public function curl_get($url){
       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
		curl_close($ch);
		$encoding = mb_detect_encoding($res, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		$res = mb_convert_encoding($res, 'utf-8', $encoding);
		$data = json_decode($res,true);
		return $data;
	}
	
	public function curl_post($url,$postdata){
       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata,JSON_UNESCAPED_UNICODE));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;
	}
	
	public function get_access_token($item=array(),$first=false){
		$token = 0;
		if(empty($item)){
			$item = Model('weixin_wechat')->field('wechat_access_token,wechat_expires_in,wechat_type,wechat_appid,wechat_appsecret,wechat_id')->order('wechat_expires_in desc')->find();
		}
		
		if(empty($item)){
			return '';
		}
		
		if(!$first){
			$diff = intval($item['wechat_expires_in']) - 300;
			if($item['wechat_access_token'] && $diff >= time()){
				$this->access_token = $item['wechat_access_token'];
				return $this->access_token;
			}
		}
		
		if($token == 0){
			$this->access_token = $this->get_accesstoken_fromwx($item['wechat_appid'],$item['wechat_appsecret'],$item['wechat_type'],$item['wechat_id']);
			return $this->access_token;
		}
    }
	
	public function get_accesstoken_fromwx($appid,$secret,$type,$wid){
		if($secret && $appid && in_array($type, array('1','2','3'))){
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .$appid . '&secret=' . $secret;
			$data = $this->curl_get($url);
			if(empty($data['errcode'])){
				$Data = array(
					'wechat_access_token'=>$data['access_token'],
					'wechat_expires_in'=>time() + intval($data['expires_in'])
				);
				Model('weixin_wechat')->where(array('wechat_id'=>$wid))->update($Data);
				return $data['access_token'];
			}else{
				Log::record('====同步微信获得toeken失败====:'.json_encode($data));
				return '';
			}
		}else{
			return '';
		}
	}
}
?>