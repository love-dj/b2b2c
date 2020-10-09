<?php
/**
 * 买什么api
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class apiControl extends MircroShopControl{

    private $data_type = 'html';

    public function __construct() {
        parent::__construct();
        if(!empty($_GET['data_type']) && $_GET['data_type'] === 'json') {
            $this->data_type = 'json';
        }
    }

    /**
     * 获取买什么名称
     */
    public function get_what_nameWt() {
        $result = '';
        $what_name = Language::get('wt_what');
        if($this->data_type === 'json') {
            $result = json_encode($what_name);
        } else {
            $result = $what_name;
        }

        $this->return_result($result);
    }

    /**
     * 推荐买心得
     */
    public function get_personal_commendWt(){
        $result = '';
        $data_count = intval($_GET['data_count']);
        if($data_count <= 0) {
            $data_count = 8;
        }
        $condition_personal = array();
        $condition_personal['what_commend'] = 1;
        $model_what_personal = Model('what_personal');
        $personal_list = $model_what_personal->getListWithUserInfo($condition_personal, null, '', '*', $data_count);
        if($this->data_type === 'json') {
            $result = json_encode($personal_list);
        } else {
            Tpl::output('personal_list',$personal_list);
            ob_start();
            Tpl::showpage('api_personal_list', 'null_layout');
            $result = ob_get_clean();
        }

        $this->return_result($result);
    }

    /**
     * 买心得分类
     */
    public function get_personal_classWt(){
        $result = '';
        $model_class = Model('what_personal_class');
        $class_list = $model_class->getList(TRUE, NULL, 'class_sort asc');
        if($this->data_type === 'json') {
            $result = json_encode($class_list);
        } else {
            Tpl::output('class_list',$class_list);
            ob_start();
            Tpl::showpage('api_personal_class', 'null_layout');
            $result = ob_get_clean();
        }

        $this->return_result($result);
    }

    /**
     * 推荐店铺
     */
    public function get_store_commendWt(){
        $result = '';
        $data_count = intval($_GET['data_count']);
        if($data_count <= 0) {
            $data_count = 10;
        }
        $condition_store = array();
        $condition_store['what_commend'] = 1;
        $model_what_store = Model('what_store');
        $model_store = Model('store');
        $store_list = $model_what_store->getListWithStoreInfo($condition_personal, null, 'like_count desc,click_count desc', '*', $data_count);
        if($this->data_type === 'json') {
            $result = json_encode($store_list);
        } else {
            Tpl::output('store_list',$store_list);
            ob_start();
            Tpl::showpage('api_store_list', 'null_layout');
            $result = ob_get_clean();
        }

        $this->return_result($result);
    }

    private function return_result($result) {
        $result = str_replace("\n", "", $result);
        $result = str_replace("\r", "", $result);
        echo empty($_GET['callback']) ? $result : $_GET['callback']."('".$result."')";
    }
}
