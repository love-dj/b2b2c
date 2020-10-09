<?php
/**
 * 商家商品分类
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class seller_goods_classControl extends mobileSellerControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->class_listWt();
    }

    /**
     * 返回商家商品分类列表
     */
    public function class_listWt() {
        $gc_id = intval($_POST['gc_id']);
        $deep = intval($_POST['deep']);
        if($gc_id < 0) {
            $gc_id = 0;
        } 
		if($deep ==2) {
            $deep = 3;
        }
		if($deep==1&& $gc_id >0) {
            $deep = 2;
        } 
        if($deep < 1) {
            $deep = 1;
        }

        $model_goods_class = Model('goods_class');
        $seller_goods_class = $model_goods_class->getGoodsClass(
            $this->seller_info['store_id'],
            $gc_id,
            $deep,
            $this->seller_info['seller_group_id'],
            $this->seller_group_info['gc_limits'],
            $this->store_info['is_own_shop'] && $this->store_info['bind_all_gc']
        );
		/**
         * 转码
         */
        if (strtoupper ( CHARSET ) == 'GBK') {
            $seller_goods_class = Language::getUTF8 ( $seller_goods_class );
        }
        output_data(array('class_list' => $seller_goods_class));
    }

    /**
     * 返回分类规格属性
     */
    public function type_infoWt() {
        $gc_id = intval($_POST['gc_id']);

        $model_goods_class = Model('goods_class');
        $goods_class = $model_goods_class->getGoodsClassLineForTag($gc_id);
        $type_id = intval($goods_class['type_id']);

        $spec_list = array();
        $attr_list = array();

        if($type_id > 0) {
            list($spec_json, $spec_list, $attr_list, $brand_list) = Model('type')->getAttr($goods_class['type_id'], $this->store_info['store_id'], $gc_id);

            $temp = array();
            foreach ($spec_list as $key => $value) {
                $value['sp_id']= $key;
                $temp[] = $value;
            }
            $spec_list = $temp;

            $temp = array();
            foreach ($attr_list as $key => $value) {
                $value['attr_id']= $key;
                $temp[] = $value;
            }
            $attr_list = $temp;
        }

        output_data(array('type_id' => $type_id, 'spec_list' => $spec_list, 'attr_list' => $attr_list));
    }
}
