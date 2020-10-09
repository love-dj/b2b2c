<?php
/**
 * news管理
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class news_manageControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('news');
    }

    public function indexWt() {
        $this->news_manageWt();
    }

    /**
     * news设置
     */
    public function news_manageWt() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('news');
Tpl::showpage('news_manage');
    }

    /**
     * news设置保存
     */
    public function news_manage_saveWt() {
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['news_isuse'] = intval($_POST['news_isuse']);
        if(!empty($_FILES['news_logo']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_NEWS);
            $result = $upload->upfile('news_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['news_logo'] = $upload->file_name;
            $old_image = BASE_UPLOAD_PATH.DS.ATTACH_NEWS.DS.C('what_logo');
            if(is_file($old_image)) {
                unlink($old_image);
            }
        }
        $update_array['news_submit_verify_flag'] = intval($_POST['news_submit_verify_flag']);
        $update_array['news_comment_flag'] = intval($_POST['news_comment_flag']);
        $update_array['news_attitude_flag'] = intval($_POST['news_attitude_flag']);
        $update_array['news_seo_title'] = $_POST['news_seo_title'];
        $update_array['news_seo_keywords'] = $_POST['news_seo_keywords'];
        $update_array['news_seo_description'] = $_POST['news_seo_description'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log(Language::get('news_log_manage_save'), 0);
            showMessage(Language::get('wt_common_save_succ'));
        }else {
            $this->log(Language::get('news_log_manage_save'), 0);
            showMessage(Language::get('wt_common_save_fail'));
        }
    }


}
