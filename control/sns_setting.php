<?php
/**
 * 图片空间操作
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class sns_settingControl extends BaseSNSControl {
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('sns_setting');
    }
    public function change_skinWt(){
        Tpl::showpage('sns_changeskin', 'null_layout');
    }
    public function skin_saveWt(){
        $insert = array();
        $insert['member_id']    = $_SESSION['member_id'];
        $insert['setting_skin'] = $_GET['skin'];

        Model()->table('sns_setting')->insert($insert,true);
    }
}
