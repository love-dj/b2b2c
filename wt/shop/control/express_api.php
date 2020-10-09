<?php
/**
 * 快递接口设置 v5.5
 *

 
 


 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class express_apiControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['express_api']   = $_POST['express_api'];
            $update_array['express_kuaidi100_id']   = $_POST['express_kuaidi100_id'];
            $update_array['express_kuaidi100_key']  = $_POST['express_kuaidi100_key'];
            $update_array['express_kdniao_id']   = $_POST['express_kdniao_id'];
            $update_array['express_kdniao_key']  = $_POST['express_kdniao_key'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('快递接口设置');
                showMessage(Language::get('wt_common_save_succ'));
            } else {
                showMessage(Language::get('wt_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
	Tpl::setDirquna('shop');
        Tpl::showpage('express_api.edit');
    }

}
