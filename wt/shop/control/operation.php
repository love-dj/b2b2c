<?php
/**
 * 网站设置
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class operationControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexWt() {
        $this->settingWt();
    }

    /**
     * 基本设置
     */
    public function settingWt(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(

            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['sale_allow'] = $_POST['sale_allow'];
                $update_array['robbuy_allow'] = $_POST['robbuy_allow'];
                $update_array['pointscenter_isuse'] = $_POST['pointscenter_isuse'];
                $update_array['voucher_allow'] = $_POST['voucher_allow'];
                $update_array['pointprod_isuse'] = $_POST['pointprod_isuse'];
                $update_array['coupon_allow'] = $_POST['coupon_allow'];
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    $this->log(L('wt_edit,wt_operation,wt_operation_set'),1);
                    showMessage(L('wt_common_save_succ'));
                }else {
                    showMessage(L('wt_common_save_fail'));
                }
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
		Tpl::setDirquna('shop');
        Tpl::showpage('operation.setting');
    }
}
