<?php
/**
 * news专题
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class specialControl extends NEWSHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexWt() {
        $this->special_listWt();
    }

    /**
     * 专题列表
     */
    public function special_listWt() {
        $conition = array();
        $conition['special_state'] = 2;
        $model_special = Model('news_special');
        $special_list = $model_special->getNEWSList($conition, 10, 'special_id desc');
        Tpl::output('show_page', $model_special->showpage(2));
        Tpl::output('special_list', $special_list);
        Tpl::showpage('special_list');
    }

    /**
     * 专题详细页
     */
    public function special_detailWt() {
        $special_file = getNEWSSpecialHtml($_GET['special_id']);
        if($special_file) {
            Tpl::output('special_file', $special_file);
            Tpl::output('index_sign', 'special');
            Tpl::showpage('special_detail');
        } else {
            showMessage('专题不存在', '', '', 'error');
        }
    }
}
