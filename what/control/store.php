<?php
/**
 * 买什么店铺街
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class storeControl extends MircroShopControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','store');
    }

    public function indexWt(){
        $this->store_listWt();
    }

    /**
     * 店铺列表
     */
    public function store_listWt() {
        $model_store = Model('store');
        $model_what_store = Model('what_store');
        $condition = array();
        $store_list = $model_what_store->getListWithStoreInfo($condition,30,'what_sort asc');
        Tpl::output('list',$store_list);
        Tpl::output('show_page',$model_store->showpage(2));
        //广告位
        self::get_what_show('store_list');
        Tpl::output('html_title',Language::get('wt_what_store').'-'.Language::get('wt_what').'-'.C('site_name'));
        Tpl::showpage('store');
    }

    /**
     * 店铺详细页
     */
    public function detailWt() {
        $store_id = intval($_GET['store_id']);
        if($store_id <= 0) {
            header('location: '.WHAT_SITE_URL);die;
        }
        $model_store = Model('store');
        $model_goods = Model('goods');
        $model_what_store = Model('what_store');

        $store_info = $model_what_store->getOneWithStoreInfo(array('what_store_id'=>$store_id));
        if(empty($store_info)) {
            header('location: '.WHAT_SITE_URL);
        }

        //点击数加1
        $update = array();
        $update['click_count'] = array('exp','click_count+1');
        $model_what_store->modify($update,array('what_store_id'=>$store_id));

        Tpl::output('detail',$store_info);

        $condition = array();
        $condition['store_id'] = $store_info['shop_store_id'];
        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, 'goods_id,store_id,goods_name,goods_image,goods_price,goods_salenum', 'goods_id asc', 39);
        Tpl::output('comment_type','store');
        Tpl::output('comment_id',$store_id);
        Tpl::output('list',$goods_list);
        Tpl::output('show_page',$model_goods->showpage());
        //获得分享app列表
        self::get_share_app_list();
        Tpl::output('html_title',$store_info['store_name'].'-'.Language::get('wt_what_store').'-'.Language::get('wt_what').'-'.C('site_name'));
        Tpl::showpage('store_detail');
    }

}
