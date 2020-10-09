<?php
/**
 * 短信接口
 *
 *

 
 
 *
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class smsControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('setting,sms');
	}
	    public function indexWt() {
        $this->smsWt();
    }
    
	/**
	 * 短信平台设置 
	 */
	public function smsWt(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['wt_sms_type'] 	= $_POST['wt_sms_type'];
			$update_array['wt_sms_tgs'] 	= $_POST['wt_sms_tgs'];
			$update_array['wt_sms_zh'] 	= $_POST['wt_sms_zh'];
			$update_array['wt_sms_pw'] 	= $_POST['wt_sms_pw'];
			$update_array['wt_sms_key'] 	= $_POST['wt_sms_key'];
			$update_array['wt_sms_signature'] 		= $_POST['wt_sms_signature'];
			$update_array['wt_sms_bz'] 	= $_POST['wt_sms_bz'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('wt_edit,sms_set'),1);
				showMessage(L('wt_common_save_succ'));
			}else {
				$this->log(L('wt_edit,sms_set'),0);
				showMessage(L('wt_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		
		Tpl::setDirquna('system');
        Tpl::showpage('wt.sms');
	}
}