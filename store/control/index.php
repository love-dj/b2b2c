<?php
/**
 * 物流自提服务站首页
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class indexControl extends BaseChainCenterControl{
    public function __construct(){
        parent::__construct();
        redirect('index.php?w=goods');
    }

}
