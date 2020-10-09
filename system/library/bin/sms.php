<?php
/**
 * 手机短信类
 *
 *
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class Sms {
    /**
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    public function send($mobile,$content,$data) {
       $wt_sms_type=C('wt_sms_type');
		if($wt_sms_type==1)
		{
			return $this->mysend_smsbao($mobile,$content);
		
		}elseif($wt_sms_type==2)
		{
			return $this->mysend_yunpian($mobile,$content);
		}elseif($wt_sms_type==3) 
		{
			return $this->_sendAliDy($mobile,$data);
		}elseif($wt_sms_type==4) 
		{
			return $this->_mysend_yunsms($mobile,$content);
		}
    }
	private function _sendAliDy($mobile, $datas)
	{	
		if(empty($datas)) return false;
		$tempId=$datas['apicodeid'];
		unset($datas['apicodeid']);
		unset($datas['site_name']);
		$tempId = $tempId == 'default'?'SMS_134150158':$tempId;//当模板不存在时，请填写自己默认的ID
		if(empty($tempId)||$tempId==''){
			$tempId= 'SMS_130235154';
		}
		set_time_limit(0);
		define('PLUGIN_ROOT', BASE_API_PATH . DS .'sms' . DS .'alidy/');
		require_once PLUGIN_ROOT . "SignatureHelper.php";
		date_default_timezone_set('Asia/Shanghai'); 
		
		$return = sendAliDySms($mobile,$datas,$tempId);
		
		return $return;
	}
	
/*
	您于{$send_time}绑定手机号，验证码是：{$verify_code}。【{$site_name}】
	0  提交成功
	30：密码错误
	40：账号不存在
	41：余额不足
	42：帐号过期
	43：IP地址限制
	50：内容含有敏感词
	51：手机号码不正确
	http://api.smsbao.com/sms?u=USERNAME&p=PASSWORD&m=PHONE&c=CONTENT
	*/
    private function mysend_smsbao($mobile,$content){
     
	   $user_id = urlencode(C('wt_sms_zh')); // 这里填写用户名
 	   $pass = urlencode(C('wt_sms_pw')); // 这里填登陆密码
 	   if(!$mobile || !$content || !$user_id || !$pass) return false;
	   if(is_array($mobile)) $mobile = implode(",",$mobile);
       $mobile=urlencode($mobile);
       //$content=$content."【我的网站】";
       $content=urlencode($content);
	   $pass =md5($pass);//MD5加密
	   $url="http://api.smsbao.com/sms?u=".$user_id."&p=".$pass."&m=".$mobile."&c=".$content."";
 	   $res = file_get_contents($url);
 	   //return $res;
 	   $ok=$res=="0";
 	   if($ok)
 	   {
 	     return true;
 	   }
 	   return false;

    }
	 /**
	 * http://www.yunpian.com/
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function mysend_yunpian($mobile,$content) {
		$yunpian='yunpian';
		$plugin = str_replace('\\', '', str_replace('/', '', str_replace('.', '',$yunpian)));
        if (!empty($plugin)) {
            define('PLUGIN_ROOT', BASE_API_PATH . DS .'sms');
            require_once(PLUGIN_ROOT . DS . $plugin . DS . 'Send.php');
            return send_sms($content, $mobile);
        }
		else
		{
			return false;
		}
    }

		 /**
	 * http.yunsms.cn
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function _mysend_yunsms($mobile,$content) {
		$yunsms='yunsms';
		$plugin = str_replace('\\', '', str_replace('/', '', str_replace('.', '',$yunsms)));
        if (!empty($plugin)) {
            define('PLUGIN_ROOT', BASE_API_PATH . DS .'sms');
            require_once(PLUGIN_ROOT . DS . $plugin . DS . 'sendSMS.php');
            return sendSMS($mobile,$content);
        }
		else
		{
			return false;
		}
    }
	
}

