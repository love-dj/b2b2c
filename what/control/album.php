<?php
/**
 * 默认展示页面
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class albumControl extends MircroShopControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','album');
    }

    //首页
    public function indexWt(){
        Tpl::showpage('album');
    }
}
