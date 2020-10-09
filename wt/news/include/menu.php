<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['news'] = array (
        'name' => $lang['wt_news'],
        'child' => array(
                array(
                        'name' => $lang['wt_config'],
                        'child' => array(
                                'news_manage' => $lang['wt_news_manage'],
                                'news_index' => $lang['wt_news_index_manage'],
                                'news_navigation' => $lang['wt_news_navigation_manage'],
                                'news_tag' => $lang['wt_news_tag_manage'],
                                'news_comment' => $lang['wt_news_comment_manage']
                        )
                ),
                array(
                        'name' => '专题',
                        'child' => array(
                                'news_special' => $lang['wt_news_special_manage']
                        )
                ),
                array(
                        'name' => '文章',
                        'child' => array(
                                'news_article_class' => '文章分类',
                                'news_article' => '文章管理'
                        )
                ),
                array(
                        'name' => '图刊',
                        'child' => array(
                                'news_picture_class' => '图刊分类',
                                'news_picture' => '图刊管理'
                        )
                )
));