<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['wechat'] = array (
        'name' => '微信',
        'child'=>array(
                array(
                        'name'=>'设置',
                        'child' => array(
                                'setting' => '基本设置',
                                'api' => '接口配置',
								'wechat_msg' => '微信通知',
                                'material' => '素材管理',
						        'subcribe' => '首次关注设置',
                                'menu' => '自定义菜单',
                                'keyword' => '关键词管理',
                                'url' => 'URL管理'
                        )
                )
        )
);