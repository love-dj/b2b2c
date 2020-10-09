<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['bbs'] = array (
        'name' => $lang['wt_bbs'],
        'child' => array (
                array (
                        'name' => $lang['wt_config'],
                        'child' => array(
                                'bbs_setting' => $lang['wt_bbs_setting'],
                                'bbs_show' => '首页幻灯'
                        )
                ),
                array (
                        'name' => '成员',
                        'child' => array(
                                'bbs_member' => $lang['wt_bbs_membermanage'],
                                'bbs_memberlevel' => '成员头衔'
                        )
                ),
                array (
                        'name' => '社区',
                        'child' => array(
                                'bbs_manage' => $lang['wt_bbs_manage'],
                                'bbs_class' => $lang['wt_bbs_classmanage'],
                                'bbs_theme' => $lang['wt_bbs_thememanage'],
                                'bbs_inform' => '举报管理'
                        )
                )
        ) 
);