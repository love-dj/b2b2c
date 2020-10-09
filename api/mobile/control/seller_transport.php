<?php
/**
 * 商家运费模板
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class seller_transportControl extends mobileSellerControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->transport_listWt();
    }

    /**
     * 返回商家店铺商品分类列表
     */
    public function transport_listWt() {
        $model_transport = Model('transport');
        $transport_list = $model_transport->getTransportList(array('store_id'=>$this->store_info['store_id']));
        output_data(array('transport_list' => $transport_list));
    }
}
