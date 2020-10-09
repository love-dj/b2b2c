<?php
/**
 * 买什么
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class manageControl extends SystemControl{

    const what_CLASS_LIST = 'index.php?w=goods_class&t=goodsclass_list';
    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    public function __construct(){
        parent::__construct();
        Language::read('store');
        Language::read('what');
    }

    public function indexWt() {
       $this->manageWt();
    }

    /**
     * 买什么管理
     */
    public function manageWt() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('what');
Tpl::showpage('what_manage');
    }

    /**
     * 买什么管理保存
     */
    public function manage_saveWt() {
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['what_isuse'] = intval($_POST['what_isuse']);
        $update_array['what_style'] = trim($_POST['what_style']);
        $update_array['what_personal_limit'] = intval($_POST['what_personal_limit']);
        $old_image = array();
        if(!empty($_FILES['what_logo']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_WHAT);
            $result = $upload->upfile('what_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['what_logo'] = $upload->file_name;
            $old_image[] = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.C('what_logo');
        }
        if(!empty($_FILES['what_header_pic']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_WHAT);
            $result = $upload->upfile('what_header_pic');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['what_header_pic'] = $upload->file_name;
            $old_image[] = BASE_UPLOAD_PATH.DS.ATTACH_WHAT.DS.C('what_header_pic');
        }
        $update_array['what_seo_keywords'] = $_POST['what_seo_keywords'];
        $update_array['what_seo_description'] = $_POST['what_seo_description'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            if(!empty($old_image)) {
                foreach ($old_image as $value) {
                    if(is_file($value)) {
                        unlink($value);
                    }
                }
            }
            showMessage(Language::get('wt_common_save_succ'));
        }else {
            showMessage(Language::get('wt_common_save_fail'));
        }
    }
}
