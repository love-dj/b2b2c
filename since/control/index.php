<?php
/**
 * 物流自提服务站首页
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class indexControl extends BaseDeliveryControl{
    public function __construct(){
        parent::__construct();
        @header('location: index.php?w=login');die;
    }
}
