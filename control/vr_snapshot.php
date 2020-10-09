<?php
/**
 * 买家 交易快照
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class vr_snapshotControl extends BaseHomeControl {

    public function __construct() {
        parent::__construct();
        Tpl::setLayout('home_layout');
    }

    public function indexWt() {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage('参数错误', '', 'html', 'error');
        }
        $model_order = Model('order_vr');
        $order_goods_content = $model_order->getOrderInfo(array('order_id' => $order_id));
        if (empty($order_goods_content)) {
            showMessage('参数错误，或者不是本人购买的商品', '', 'html', 'error');
        }
        $spec_array = array();
        if ($order_goods_content['goods_spec'] != '') {
            $spec = explode('，', $order_goods_content['goods_spec']);
            foreach ($spec as $key=>$val) {
                $param = explode('：', $val);
                $spec_array[$param[0]] = $param[1];
            }
        }
        $order_goods_content['goods_spec'] = $spec_array;

        //查询消费者保障服务
        if (C('contract_allow') == 1 && !empty($order_goods_content['goods_contractid'])) {
            $contract_item = Model('contract')->getContractItemByCache();

            $goods_contractid_arr = explode(',',$order_goods_content['goods_contractid']);
            foreach ((array)$goods_contractid_arr as $gcti_v) {
                $order_goods_content['contractlist'][] = $contract_item[$gcti_v];
            }
        }

        $sp_hot_info = Model('order_vr_snapshot')->getSnapshotInfoByOrderid($order_id,$order_goods_content['goods_id']);
        $sp_hot_info['goods_attr'] = unserialize($sp_hot_info['goods_attr']);
        Tpl::output('goods', array_merge($order_goods_content,$sp_hot_info));


        $store_info = Model('store')->getStoreInfo(array('store_id' => $order_goods_content['store_id']));
        if (!empty($store_info) && $store_info['is_own_shop'] == 0) {
            Tpl::output('store_info', $store_info);
        }
        
        Tpl::showpage('vr_snapshot');
    }
}
