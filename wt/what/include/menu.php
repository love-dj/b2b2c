<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['what'] = array (
        'name' => '买什么',
        'child' => array(
                array(
                        'name' => $lang['wt_config'], 
                        'child' => array(
                                'manage' => $lang['wt_what_manage'],
                                'show' => $lang['wt_what_show_manage'],
                                'comment' => $lang['wt_what_comment_manage']
                        )
                ),
                array(
                        'name' => '说说看', 
                        'child' => array(
                                'goods' => $lang['wt_what_goods_manage'],
                                'goods_class' => $lang['wt_what_goods_class']
                        )
                ),
                array(
                        'name' => '买心得', 
                        'child' => array(
                                'personal' => $lang['wt_what_personal_manage'],
                                'personal_class' => $lang['wt_what_personal_class']
                        )
                        
                ),
                array(
                        'name' => '逛店铺',
                        'child' => array(
                                'store' => $lang['wt_what_store_manage']
                        )
                )
        )
);