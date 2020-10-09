<?php
/**
 * 微信分享
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class wx_shareControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }
	public function indexWt()
	{
		$str = $_GET['str'];
		$key = $_POST['key'];
        if (empty($key)) {
            $key = $_GET['key'];
        }

		if (!empty($str)) {
			$share = explode('@@@', $str);
			$link = str_replace('&amp;', '&', $share[0]);
			$url = $link;
			
			//分享用户id
			$myurl = '';
			$share_member_id = $this->getMemberIdIfExists();
			if($share_member_id>0){
				$encode_member_id = base64_encode(intval($share_member_id)*1);
				$myurl = "?smid=".$encode_member_id;
				if(strpos($url,'?') !==false){
					$myurl = "&smid=".$encode_member_id;
				}
			}
			$to_url =  $url.$myurl;
			if (!empty($share[4])) {
				$to_url =str_replace('&amp;', '&', $share[4]);
			}
          
            Tpl::output('link', $to_url);
            Tpl::output('title', $share[1]);
			Tpl::output('imgUrl', $share[2]);

			if (empty($share[3])) {
				$share[3] = C('site_name');
			}

			Tpl::output('desc', $share[3]);
			$weixin_appid = C('weixin_mp_appid');

			if (!empty($weixin_appid)) {
				Tpl::output('appid', $weixin_appid);
				
				$jsapiTicket = $this->getJsApiTicket();
				$nonceStr = $this->createNonceStr();
				$timestamp = TIMESTAMP;
				$string = 'jsapi_ticket=' . $jsapiTicket . '&noncestr=' . $nonceStr . '&timestamp=' . $timestamp . '&url=' . $url;
				$signature = sha1($string);
                Tpl::output('nonceStr', $nonceStr);
                Tpl::output('signature', $signature);
				
				$goods_id = intval($_GET['goods_id']);
                Tpl::output('goods_id', $goods_id);
                Tpl::output('key', $key);
                Tpl::showpage('wx_share');
			}
		}
	}

	private function getJsApiTicket(){
		$_token_info = C('weixin_mp_jsapi_array');

		if (!empty($_token_info)) {
			$_info = unserialize($_token_info);

			if (TIMESTAMP < $_info['end_time']) {
				return $_info['jsapi_ticket'];
			}
		}

		$weixin_appid = C('weixin_mp_appid');
		$weixin_appsecret = C('weixin_mp_appsecret');
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $weixin_appid . '&secret=' . $weixin_appsecret;
		$_token_info = $this->httpGet($url);

		if (!empty($_token_info)) {
			$_info = json_decode($_token_info, true);
			$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=' . $_info['access_token'];
			$res = json_decode($this->httpGet($url), true);
			$_info['jsapi_ticket'] = $res['ticket'];
			$model_setting = Model('setting');
			$_info['end_time'] = TIMESTAMP + $_info['expires_in'];
			$update_array = array();
			$update_array['weixin_mp_jsapi_array'] = serialize($_info);

			if (C('weixin_mp_appid') == $weixin_appid) {
				$update_array['weixin_mp_token_array'] = $update_array['weixin_mp_jsapi_array'];
			}

			$model_setting->updateSetting($update_array);
			return $_info['jsapi_ticket'];
		}
	}

	private function createNonceStr($length = 16){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$str = '';

		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		return $str;
	}

	private function httpGet($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}
