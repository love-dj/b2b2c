<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['flea'] = array (
        'name' => $lang['wt_flea'],
        'child'=>array(
                array(
                        'name'=>'设置',
                        'child' => array(
                                'flea' => '闲置管理',
                                'flea_show' => '闲置幻灯',
                                'flea_class_index' => '首页分类',
						        'flea_index' => 'SEO设置',
                                'flea_class' => '分类管理',
                                'flea_region' => '地区管理'
                        )
                )
        )
);