<?php
/**
 * 淘宝接口
 *
 *
 *
 *

 
 
 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class taobao_apiControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexWt() {
        $this->taobao_api_settingWt();
    }

    public function taobao_api_settingWt() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
				
		Tpl::setDirquna('system');
        Tpl::showpage('taobao_api');
    }

    public function taobao_api_saveWt() {
        $model_setting = Model('setting');

        $update_array['taobao_api_isuse'] = intval($_POST['taobao_api_isuse']);
        $update_array['taobao_app_key'] = $_POST['taobao_app_key'];
        $update_array['taobao_secret_key'] = $_POST['taobao_secret_key'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log('淘宝接口保存', 1);
            showMessage(Language::get('wt_common_save_succ'));
        }else {
            $this->log('淘宝接口保存', 0);
            showMessage(Language::get('wt_common_save_fail'));
        }
    }
}
