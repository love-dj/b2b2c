<?php
/**
 * 淘宝接口
 *
 *
 *
 *

 
 
 */


//require_once('../../function.php');
defined('ShopWT') or exit('Access Denied By ShopWT');
class qiniuyun_apiControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexWt() {
        $this->qiniuyun_api_settingWt();
    }

    public function qiniuyun_api_settingWt() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        if ($setting_list['qiniuyun_api_isuse'] == ''){
            $setting_list['qiniuyun_api_isuse'] = 0;
        }
        Tpl::output('setting',$setting_list);
				
		Tpl::setDirquna('system');
        Tpl::showpage('qiniuyun_api');
    }

    public function qiniuyun_api_saveWt() {
        $model_setting = Model('setting');

        $update_array['qiniuyun_api_isuse'] = intval($_POST['qiniuyun_api_isuse']);
        $update_array['qiniuyun_app_key'] = $_POST['qiniuyun_app_key'];
        $update_array['qiniuyun_secret_key'] = $_POST['qiniuyun_secret_key'];
        $update_array['bucket_name'] = $_POST['bucket_name'];
        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log('七牛云接口保存', 1);
            showMessage(Language::get('wt_common_save_succ'));
        }else {
            $this->log('七牛云接口保存', 0);
            showMessage(Language::get('wt_common_save_fail'));
        }
    }
}
