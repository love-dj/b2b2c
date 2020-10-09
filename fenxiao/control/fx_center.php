<?php
/**
 * 分销员会员页面
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_centerControl extends MemberfenxiaoControl{
    function __construct()
    {
        parent::__construct();
    }

    public function homeWt(){
        Tpl::showpage('home');
    }
}