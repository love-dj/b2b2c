<?php
/**
 * 店铺开店
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class show_joininControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 店铺开店页
     *
     */
    public function indexWt() {
        Language::read("home_login_index");
        $code_info = C('store_joinin_pic');
        $info['pic'] = array();
        if(!empty($code_info)) {
            $info = unserialize($code_info);
        }
		
		$model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_SESSION['member_id']));
		if ($joinin_detail['is_person']) {
			Tpl::output('store_zizhi','is_person');
		}
        Tpl::output('pic_list',$info['pic']);//首页图片
        Tpl::output('show_txt',$info['show_txt']);//贴心提示
        $model_help = Model('help');
        $condition['type_id'] = '1';//入驻指南
        $help_list = $model_help->getHelpList($condition,'',4);//显示4个
        Tpl::output('help_list',$help_list);
        Tpl::output('article_list','');//底部不显示文章分类
        Tpl::output('show_sign','joinin');
        Tpl::output('html_title',C('site_name').' - '.'商家入驻');
        Tpl::setLayout('store_joinin_layout');
        Tpl::showpage('store_joinin');
    }

}
