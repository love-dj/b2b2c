<?php
/**
 * 微信通知
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class wechat_msgControl extends SystemControl{
	private $links = array(
		array('url' => 'w=wechat_msg&t=index', 'text' => '基本设置'),
		array('url' => 'w=wechat_msg&t=msg_list', 'text' => '模板列表')
		);

	public function __construct(){
		parent::__construct();
	}

	public function indexWt(){
		$model_setting = Model('setting');

		if (chksubmit()) {
			$update_array = array();
			$update_array['weixin_mp_isuse'] = $_POST['weixin_mp_isuse'];
			$update_array['weixin_mp_appid'] = $_POST['weixin_mp_appid'];
			$update_array['weixin_mp_appsecret'] = $_POST['weixin_mp_appsecret'];
			$update_array['weixin_mp_token'] = $_POST['weixin_mp_token'];
			$update_array['weixin_mp_token_array'] = '';
			$result = $model_setting->updateSetting($update_array);

			if ($result) {
				$this->log('微信通知接口设置');
				showMessage(Language::get('wt_common_save_succ'));
			}
			else {
				showMessage(Language::get('wt_common_save_fail'));
			}
		}

		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting', $list_setting);
		Tpl::output('top_link', $this->sublink($this->links, 'index'));
		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_msg_edit');
	}

	public function msg_listWt(){
		$handle_wx_api = Handle('wx_api');
		$model_wx_log = Model('wx_log');
		$list = $model_wx_log->getWxTpl();
		Tpl::output('list', $list);
		$access_token = $this->get_token();

		if ($access_token) {
			Tpl::output('access_token', $access_token);
			$wx_industry = $handle_wx_api->getIndustry($access_token);
			Tpl::output('wx_industry', $wx_industry);
			$tpl_list = $handle_wx_api->getAllTemplate($access_token);
			$template_list = array();
			if (!empty($tpl_list) && is_array($tpl_list)) {
				foreach ($tpl_list as $k => $v) {
					$title = $v['title'];
					$template_list[$title] = $v['template_id'];
				}
			}

			Tpl::output('template_list', $template_list);
		}
		Tpl::output('top_link', $this->sublink($this->links, 'msg_list'));

		Tpl::setDirquna('wechat');
		Tpl::showpage('wechat_msg_list');
	}

	public function wx_tplWt(){
		$handle_wx_api = Handle('wx_api');
		$access_token = $this->get_token();
		$state = 0;
		$id = $_GET['id'];
		$code = $_GET['code'];
		$to = $_GET['to'];

		if ($to == 1) {
			$state = $handle_wx_api->addTemplate($code, $id);
		}

		if ($to == 2) {
			$state = $handle_wx_api->updateTemplate($code, $id);
		}

		if ($state) {
			exit(json_encode(array('state' => true, 'msg' => '操作成功')));
		}
		else {
			exit(json_encode(array('state' => false, 'msg' => '操作失败')));
		}
	}

	public function get_token()
	{
		$handle_wx_api = Handle('wx_api');
		$access_token = $handle_wx_api->getAccessToken();

		if (empty($access_token)) {
			$access_token = $handle_wx_api->getAccessToken(1);
		}

		return $access_token;
	}
}

?>
