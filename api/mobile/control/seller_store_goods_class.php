<?php
/**
 * 商家店铺商品分类
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class seller_store_goods_classControl extends mobileSellerControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->class_listWt();
    }

    /**
     * 返回商家店铺商品分类列表
     */
    public function class_listWt() {
        $store_goods_class = Model('store_goods_class')->getStoreGoodsClassPlainList($this->store_info['store_id']);
        output_data(array('class_list' => $store_goods_class));
    }
}
