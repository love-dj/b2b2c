<?php
/**
 * 买什么买心得
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class personalControl extends MircroShopControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','personal');
    }

    public function indexWt(){
        $this->listWt();
    }

    public function listWt() {
        $model_class = Model('what_personal_class');
        $class_list = $model_class->getList(TRUE,NULL,'class_sort asc');
        Tpl::output('class_list',$class_list);

        $condition = array();
        if(isset($_GET['keyword'])) {
            $condition['commend_message'] = array('like','%'.$_GET['keyword'].'%');
        }
        if(isset($_GET['class_id'])&&!empty($_GET['class_id'])) {
            $condition['class_id'] = $_GET['class_id'];
        }

        $order = 'what_sort asc,commend_time desc';
        if($_GET['order'] == 'hot') {
            $order = 'what_sort asc,click_count desc';
        }
        self::get_personal_list($condition,$order);
        Tpl::output('html_title',Language::get('wt_what_personal').'-'.Language::get('wt_what').'-'.C('site_name'));
        Tpl::showpage('personal_list');
    }

    public function detailWt() {

        $personal_id = intval($_GET['personal_id']);
        if($personal_id <= 0) {
            header('location: '.WHAT_SITE_URL);die;
        }
        $model_personal = Model('what_personal');
        $condition = array();
        $condition['personal_id'] = $personal_id;
        $detail = $model_personal->getOneWithUserInfo($condition);
        if(empty($detail)) {
            header('location: '.WHAT_SITE_URL);die;
        }

        //点击数加1
        $update = array();
        $update['click_count'] = array('exp','click_count+1');
        $model_personal->modify($update,$condition);
        Tpl::output('detail',$detail);

        //侧栏
        self::get_sidebar_list($detail['commend_member_id']);

        //获得分享app列表
        self::get_share_app_list();
        Tpl::output('comment_id',$detail['personal_id']);
        Tpl::output('comment_type','personal');
        Tpl::output('html_title',$detail['commend_message'].'-'.Language::get('wt_what_personal').'-'.Language::get('wt_what').'-'.C('site_name'));
        Tpl::showpage('personal_detail');

    }

}
