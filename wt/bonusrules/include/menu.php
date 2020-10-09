<?php
/**
 * 菜单
 * 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['bonusrules'] = array (
        'name' => '奖金制度',
        'child' => array(
                array(
                        'name' => '三级分销',
                        'child' => array(
                                'distribution_setting' => '分销设置',
                                'distribution_level' => '分销等级',
                                'distribution_order' => '分销订单管理'
                        )),
                array(
                        'name' => '团队无限级',
                        'child' => array(
                                'team_setting' => '基础设置',
                                'team_user' => '团队管理',
                                'team_level' => '团队等级管理',
                                'team_order' => '团队提成明细'
                        )
                ),
                array(
                        'name' => '区域代理',
                        'child' => array(
                                'agent_setting' => '分红设置',
                                'agent_user' => '区域代理管理',
                                'agent_apply' => '区域代理申请',
                                'agent_order' => '区域分红记录'
						)
				),
                array(
                        'name' => '股东分红',
                        'child' => array(
                                'shareholder_setting' => '分红设置',
                                'shareholders' => '分红管理',
                                'shareholder_logs' => '股东分红记录'
                        )
				),
                array(
                        'name' => '消费返现',
                        'child' => array(
							'buy_return_setting' => '消费返现设置',
							'buy_return_queue' => '消费返现队列',
							'buy_return_logs' => '消费返现记录'
                        )
                ),
                array(
                        'name' => '满额返现',
                        'child' => array(
							'full_return_setting' => '满额返现设置',
							'full_return_queue' => '满额返现队列',
							'full_return_logs' => '满额返现记录'
                        )
                )
));