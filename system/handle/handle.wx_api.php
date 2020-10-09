<?php
/**
 * 微信通知接口
 *

 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class wx_apiHandle{
	public function getAccessToken($update = 0)
	{
		if (C('weixin_mp_isuse') == 1) {
			$_token_info = C('weixin_mp_token_array');
			if (!empty($_token_info) && $update == 0) {
				$_info = unserialize($_token_info);

				if (TIMESTAMP < $_info['end_time']) {
					return $_info['access_token'];
				}
			}

			$weixin_appid = C('weixin_mp_appid');
			$weixin_appsecret = C('weixin_mp_appsecret');
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $weixin_appid . '&secret=' . $weixin_appsecret;
			$_token_info = $this->getUrlContents($url);

			if (!empty($_token_info)) {
				$_info = json_decode($_token_info, true);
				$model_setting = Model('setting');
				$_info['end_time'] = TIMESTAMP + $_info['expires_in'];
				$update_array = array();

				if (C('wap_weixin_appid') == $weixin_appid) {
					$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=' . $_info['access_token'];
					$res = json_decode($this->getUrlContents($url), true);
					$_info['jsapi_ticket'] = $res['ticket'];
					$update_array['weixin_mp_jsapi_array'] = serialize($_info);
				}

				$update_array['weixin_mp_token_array'] = serialize($_info);
				$model_setting->updateSetting($update_array);
				return $_info['access_token'];
			}
		}

		return false;
	}

	public function addTemplate($code, $id)
	{
		$result = false;
		$url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $token;
		$get_info = $this->getUrlContents($url, array('template_id_short' => $id));
		$_info = json_decode($get_info, true);

		if (!empty($_info['template_id'])) {
			$result = $this->updateTemplate($code, $_info['template_id']);
		}

		return $result;
	}

	public function updateTemplate($code, $id)
	{
		Model('wx_log')->editWxTpl(array('msg_code' => $code), array('mp_msg_id' => $id));

		if ($code == 'consult_mall_reply') {
			$result = Model('member_msg_tpl')->editMemberMsgTpl(array('mmt_code' => 'consult_goods_reply'), array('mp_msg_id' => $id));
		}elseif ($code == 'predeposit_change') {
			$result = Model('member_msg_tpl')->editMemberMsgTpl(array('mmt_code' => 'recharge_card_balance_change'), array('mp_msg_id' => $id));
		}else{

			$result = Model('member_msg_tpl')->editMemberMsgTpl(array('mmt_code' => $code), array('mp_msg_id' => $id));
		}
		return $result;
	}

	public function getQrcode($token, $member_id)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token;
		$post_data = array();
		$post_data['expire_seconds'] = 600;
		$post_data['action_name'] = 'QR_SCENE';
		$post_data['action_info'] = array(
	'scene' => array('scene_id' => '99' . $member_id)
	);
		$get_info = $this->getUrlContents($url, $post_data);
		$_info = json_decode($get_info, true);
		return $_info;
	}

	public function getUserInfo($token, $openid)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $openid . '&lang=zh_CN';
		$get_info = $this->getUrlContents($url);
		$_info = json_decode($get_info, true);
		return $_info;
	}

	public function getIndustry($token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=' . $token;
		$get_info = $this->getUrlContents($url);
		$_info = json_decode($get_info, true);
		return $_info;
	}

	public function getAllTemplate($token)
	{
		$list = array();
		$url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $token;
		$get_info = $this->getUrlContents($url);
		$_info = json_decode($get_info, true);

		if (!empty($_info)) {
			$list = $_info['template_list'];
		}

		return $list;
	}

	public function sendTemplate($token, $msg)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token;
		$post_data = array();
		$post_data['touser'] = $msg['to_id'];
		$post_data['template_id'] = $msg['subject'];
		$post_data['data'] = unserialize($msg['log_msg']);
		$post_data['url'] = $post_data['data']['url'];

		if (empty($post_data['url'])) {
			$post_data['url'] = WAP_SITE_URL;
		}

		$get_info = $this->getUrlContents($url, $post_data);
		$_info = json_decode($get_info, true);
		return $_info['msgid'];
	}

	public function getTemplateData($code, $info)
	{
		$data = array();
		$data['first'] = array('value' => '您好', 'color' => '#173177');
		$data['remark'] = array('value' => '祝您购物愉快！', 'color' => '#173177');

		switch ($code) {
		case 'arrival_notice':
			$data['first'] = array('value' => '您好，您预定商品现已到货', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['goods_name'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['goods_sale_price'], 'color' => '#173177');
			$data['keyword3'] = array('value' => $info['an_addtime'], 'color' => '#173177');
			break;

		case 'consult_goods_reply':
			$data['first'] = array('value' => '您好，您咨询商品"' . $info['goods_name'] . '"的问题已回复', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['consult_content'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['consult_reply'], 'color' => '#173177');
			break;

		case 'consult_mall_reply':
			$data['first'] = array('value' => '您好，您咨询平台客服的问题已回复', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['mc_content'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['mc_reply'], 'color' => '#173177');
			break;

		case 'order_book_end_pay':
			$data['first'] = array('value' => '您好，请完成余款支付', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['goods_name'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['goods_num'], 'color' => '#173177');
			$data['keyword3'] = array('value' => '可发货', 'color' => '#173177');
			$data['keyword4'] = array('value' => $info['book_amount'], 'color' => '#173177');
			break;

		case 'order_deliver_success':
			$data['first'] = array('value' => '您好，购买的商品现已发货', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['order_sn'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['e_name'], 'color' => '#173177');
			$data['keyword3'] = array('value' => $info['shipping_code'], 'color' => '#173177');
			break;

		case 'order_payment_success':
			$data['first'] = array('value' => '您好，订单支付成功', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['order_amount'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['order_sn'], 'color' => '#173177');
			break;

		case 'predeposit_change':
			$data['first'] = array('value' => '您好，会员账号余额有变动', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['desc'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['amount'], 'color' => '#173177');
			$data['keyword3'] = array('value' => $info['time'], 'color' => '#173177');
			break;

		case 'recharge_card_balance_change':
			$data['first'] = array('value' => '您好，会员账号余额有变动', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['description'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['amount'], 'color' => '#173177');
			$data['keyword3'] = array('value' => $info['time'], 'color' => '#173177');
			break;

		case 'refund_return_notice':
			$data['first'] = array('value' => '您好，退款申请有新的处理进度', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['order_sn'], 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['msg'], 'color' => '#173177');
			$data['keyword3'] = array('value' => $info['goods_name'], 'color' => '#173177');
			$data['keyword4'] = array('value' => $info['refund_amount'], 'color' => '#173177');
			break;

		case 'trad_change':
			$data['first'] = array('value' => '您好，分销佣金有变动', 'color' => '#173177');
			$data['keyword1'] = array('value' => $info['amount'] . '(' . $info['desc'] . ')', 'color' => '#173177');
			$data['keyword2'] = array('value' => $info['time'], 'color' => '#173177');
			break;

		default:
			break;
		}

		return $data;
	}

	public function getUrlContents($url, $post_data = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);

		if (!empty($post_data)) {
			curl_setopt($ch, CURLOPT_POST, true);

			if (is_array($post_data)) {
				$post_data = json_encode($post_data);
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
