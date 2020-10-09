<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class apiControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
		//$lang = core\Language::getLangContent();
		$model_wechat = Model('wechat');
		if (chksubmit()){
			if(empty($_POST['wid'])){
				error($lang['wt_common_save_fail']);
			}
			$wechatid = intval($_POST['wid']);
			$update_array = array(
				'wechat_type'=>intval($_POST['type']),
				'wechat_appid'=>trim($_POST['appid']),
				'wechat_appsecret'=>trim($_POST['appsecret']),
				'wechat_name'=>trim($_POST['name']),
				'wechat_email'=>trim($_POST['email']),
				'wechat_preid'=>trim($_POST['preid']),
				'wechat_account'=>trim($_POST['account']),
				'wechat_encodingtype'=>intval($_POST['encodingtype']),
				'wechat_encoding'=>trim($_POST['encoding']),
			);
			
			$condition = array('wechat_id'=>$wechatid);
			
			$result = $model_wechat->editInfo('weixin_wechat',$update_array,$condition);
			
			if ($result === true){
				$model_setting = Model('setting');
				//微信通知同步key =====s=======
				$update_array = array();
				//$update_array['weixin_mp_isuse'] = $_POST['weixin_mp_isuse'];
				$update_array['weixin_mp_appid'] = trim($_POST['appid']);
				$update_array['weixin_mp_appsecret'] = trim($_POST['appsecret']);
				$update_array['weixin_mp_token'] = trim($_POST['wechat_token']);
				$update_array['weixin_mp_token_array'] = '';
				$result = $model_setting->updateSetting($update_array);
				//========e============
				showMessage('提交成功！');
			}else {
				showMessage('提交失败！');
			}
		}else{			
			$api_account = $model_wechat->getInfoOne('weixin_wechat','');
			if(empty($api_account)){
				
				$api_account = array(
					'admin_id'=>'',
					'wechat_token'=>strtolower(random(10)),
					'wechat_sn'=>strtolower(random(10)),
					'wechat_type'=>3,
					'wechat_appid'=>'',
					'wechat_appsecret'=>'',
					'wechat_name'=>'',
					'wechat_email'=>'',
					'wechat_preid'=>'',
					'wechat_account'=>'',
					'wechat_encodingtype'=>0,
					'wechat_encoding'=>''					
				);
				$wechat_id = $model_wechat->addInfo('weixin_wechat',$api_account);
				$api_account['wechat_id'] = $wechat_id;
			}
			Tpl::output('api_account',$api_account);
			
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_api');
		}
	}

}
