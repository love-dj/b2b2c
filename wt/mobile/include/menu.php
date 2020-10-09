<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['mobile'] = array (
        'name'=>$lang['wt_mobile'],
        'child'=>array(
                array(
                        'name'=>'设置',
                        'child' => array(
						        'mb_setting' => '手机端设置',
								'mb_logo' => '手机Logo',
                                'mb_special' => '首页管理',
								'mb_redpacket'=>'红包管理',
                                'mb_category' => $lang['wt_mobile_catepic'],
                                'mb_feedback' => $lang['wt_mobile_feedback'],
                                'mb_payment' => '手机支付',
                                'mb_app' => 'APP二维码',
                                'mb_wx' => '微信二维码',
                                'mb_connect' => '第三方登录',
                        )
                )
        )
);