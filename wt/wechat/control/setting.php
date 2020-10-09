<?php
/**
 * 微信公众号
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class settingControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('wechat');
    }

    public function indexWt() {
        $model_setting = Model('setting');
		$model_wechat = Model('wechat');
		if (chksubmit()){
			$update_array = array();
			$update_array['wechat_isuse'] = intval($_POST['isuse']);
			$result = $model_setting->updateSetting($update_array);
			
			$update_array = array();
			if(empty($_POST['wid'])){
				showMessage('参数错误');
			}
			$update_array['wechat_share_title'] = htmlspecialchars($_POST['sharetitle'], ENT_QUOTES);
			$update_array['wechat_share_desc'] = htmlspecialchars($_POST['sharedesc'], ENT_QUOTES);
			if(!empty($_FILES['_pic']['name'])) {
				$upload = new UploadFile();
				$upload->set('default_dir','wxshare');
				$result = $upload->upfile('_pic');
				if(!$result) {
					showMessage($upload->error);
				}
				$update_array['wechat_share_logo'] = $upload->file_name;
				
			}
			$wechatid = intval($_POST['wid']);
						
			$condition = array('wechat_id'=>$wechatid);
			
			$result = $model_wechat->editInfo('weixin_wechat',$update_array,$condition);
			
			if ($result === true){
				showMessage('提交成功！');
			}else {
				showMessage('提交失败！');
			}
		}else{
			$setting_list = $model_setting->getListSetting();
			
			$api_account = $model_wechat->getInfoOne('weixin_wechat','','wechat_share_title,wechat_share_logo,wechat_share_desc,wechat_id');
			if(empty($api_account)){
				$api_account = array(
					'wechat_share_title'=>'',
					'wechat_share_logo'=>'',
					'wechat_share_desc'=>'',
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
			
			Tpl::output('setting',$setting_list);
			Tpl::setDirquna('wechat');
			Tpl::showpage('wechat_setting');
		}
	}

}
