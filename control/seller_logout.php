<?php
/**
 * 店铺卖家注销
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class seller_logoutControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
    }

    public function indexWt() {
        $this->logoutWt();
    }

    public function logoutWt() {
        $this->recordSellerLog('注销成功');
        // 清除店铺消息数量缓存
        setWtCookie('storemsgnewnum'.$_SESSION['seller_id'],0,-3600);
        session_destroy();
        redirect('index.php?w=seller_login');
    }

}
