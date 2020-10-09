<?php
/**
 * 分销-分销设置
 *
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class manageControl extends SystemControl{

    function __construct()
    {
        parent::__construct();
    }

    public function indexWt() {
        $this->manageWt();
    }

    /**
     * 分销设置
     */
    public function manageWt() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
		Tpl::setDirquna('fenxiao');
        Tpl::showpage('fx_manage');
    }

    /**
     * 保存分销设置
     */
    public function manage_saveWt(){
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['fenxiao_isuse'] = intval($_POST['fenxiao_isuse']);
        $update_array['fenxiao_check'] = intval($_POST['fenxiao_check']);
        $old_image = '';
        if(!empty($_FILES['fenxiao_logo']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_FENXIAO);
            $result = $upload->upfile('fenxiao_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['fenxiao_logo'] = $upload->file_name;
            $old_image = BASE_UPLOAD_PATH.DS.ATTACH_FENXIAO.DS.C('fenxiao_logo');
        }
        $update_array['fenxiao_bill_limit'] = intval($_POST['fenxiao_bill_limit']);

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            if(!empty($old_image) && is_file($old_image)) {
                unlink($old_image);
            }
            showMessage(Language::get('wt_common_save_succ'));
        }else {
            showMessage(Language::get('wt_common_save_fail'));
        }
    }
}