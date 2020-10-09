<?php
/**
 * 菜单
 * *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['fenxiao'] = array(
    'name' => '分销',
    'child' => array(
        array(
            'name' => $lang['wt_config'],
            'child' => array(
                'manage' => '基本设置',
                'page_config' => '首页管理'
            )
        ),
        array(
            'name' => '分销',
            'child' => array(
                'fx_order' => '订单管理',
                'fx_goods' => '商品管理',
                'fx_bill' => '结算管理',
                'fx_cash' => '提现管理',
                'fx_member' => '分销员管理'
            )
        ),
    )
);