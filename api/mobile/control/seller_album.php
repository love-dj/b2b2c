<?php
/**
 * 商家注销
 *


 


 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class seller_albumControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    public function image_uploadWt() {
        $handle_goods = Handle('goods');

        $result =  $handle_goods->uploadGoodsImage(
            $_POST['name'],
            $this->seller_info['store_id'],
            $this->store_grade['sg_album_limit']
        );

        if(!$result['state']) {
            output_error($result['msg']);
        }
		output_data($result['data']);
        //output_data(array('image_name' => $result['data']['name']));
    }

}
