<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['system'] = array (
        'name' => '系统',
        'child' => array (
                array(
                        'name' => $lang['wt_config'],
                        'child' => array(
                                'setting' => $lang['wt_web_set'],
                                'navigation' => $lang['wt_navigation'],
                                'upload' => $lang['wt_upload_set'],
                                'show' => $lang['wt_show_manage'],
								'db' => '数据库管理',
                                'area' => '地区管理',
								'link' => '友情连接',
								'task' => '计划任务',
                                'cache' => $lang['wt_admin_clear_cache'],
								
                        )
                ),
                array(
                        'name' => $lang['wt_limit'],
                        'child' => array(
								'admin' => '权限设置',
                                'admin_log' => $lang['wt_admin_log'],
                        )
                ),
				 array(
                        'name' => '接口',
                        'child' => array(
								'message' => '邮件设置',
								'sms' => '短信设置',
                                'account' => $lang['wt_web_account_syn'],
								'taobao_api' => '淘宝接口',
								'qiniuyun_api' => '七牛云',
                        )
                ),
                array(
                        'name' => $lang['wt_article'],
                        'child' => array(
                                'article' => $lang['wt_article_manage'],
                                'article_class' => $lang['wt_article_class'],
                                'document' => $lang['wt_document'],
                        )
                )
        ) 
);
