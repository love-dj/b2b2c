<?php
/**
 * news首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class indexControl extends NEWSHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','index');
    }
    public function indexWt(){
        Tpl::showpage('index');
    }
}
