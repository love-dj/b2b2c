<?php
/**
 * 买什么首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class indexControl extends MircroShopControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','index');
    }
    public function indexWt(){

        //首页幻灯
        self::get_what_show('index');

        //用户信息
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        if(!empty($member_info)) {
            $member_info = self::get_member_detail_info($member_info);
        }
        $model_what_member_info = Model('what_member_info');
        $what_member_info = $model_what_member_info->getOneById($_SESSION['member_id']);
        if(empty($what_member_info)) {
            $member_info['personal_count'] = 0;
            $member_info['goods_count'] = 0;
        } else {
            $member_info['personal_count'] = $what_member_info['personal_count'];
            $member_info['goods_count'] = $what_member_info['goods_count'];
        }
        Tpl::output('member_info',$member_info);

        //首页购物达人
        $model_member_info = Model('what_member_info');
        $member_array = $model_member_info->getListWithUserInfo(TRUE,null,'personal_count desc','*',3);
        $member_list = array();
        if(!empty($member_array)) {
            foreach ($member_array as $value) {
                $member_info = self::get_member_detail_info($value);
                if(!empty($_SESSION['member_id']) && $value['member_id'] != $_SESSION['member_id']) {
                    $model = Model();
                    $gz_array   = $model->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id'], 'friend_tomid'=>array('in', $value['member_id'])))->select();
                    if(empty($gz_array)) {
                        $member_info['follow_flag'] = TRUE;
                    } else {
                        $member_info['follow_flag'] = FALSE;
                    }
                }
                $member_list[] = $member_info;
            }
        }
        Tpl::output('member_list',$member_list);

        //首页推荐买心得
        $condition_personal = array();
        $condition_personal['what_commend'] = 1;
        $model_what_personal = Model('what_personal');
        $personal_list = $model_what_personal->getListWithUserInfo($condition_personal,null,'','*',8);
        Tpl::output('personal_list',$personal_list);

        //首页推荐说说看
        $model_what_goods = Model('what_goods');
        $model_goods_class = Model('what_goods_class');
        //取分类
        $goods_class_list = $model_goods_class->getList(TRUE,NULL,'class_sort asc');
        $goods_class_root = array();
        $goods_class_menu = array();
        $goods_class_root_children = array();
        $goods_list = array();
        if(!empty($goods_class_list)) {
            foreach($goods_class_list as $val) {
                if($val['class_parent_id'] == 0 && $val['class_commend'] == 1) {
                    $goods_class_root[] = $val;
                } else {
                    $goods_class_menu[$val['class_parent_id']][] = $val;
                    $goods_class_root_children[$val['class_parent_id']] .= $val['class_id'].',';
                }
            }
        }
        //取分类下推荐商品
        foreach ($goods_class_root as $value) {
            $condition_goods = array();
            $condition_goods['what_commend'] = 1;
            $condition_goods['class_id'] = array('in',rtrim($goods_class_root_children[$value['class_id']],','));
            $goods_list[$value['class_id']] = $model_what_goods->getListWithUserInfo($condition_goods,null,'','*',6);
        }
        Tpl::output('goods_class_root',$goods_class_root);
        Tpl::output('goods_class_menu',$goods_class_menu);
        Tpl::output('goods_list',$goods_list);

        //首页推荐店铺
        $condition_store = array();
        $condition_store['what_commend'] = 1;
        $model_what_store = Model('what_store');
        $model_store = Model('store');
        $store_list = $model_what_store->getListWithStoreInfo($condition_personal,null,'like_count desc,click_count desc','*',15);
        Tpl::output('store_list',$store_list);

        Tpl::showpage('index');
    }
}
