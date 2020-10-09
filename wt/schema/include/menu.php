<?php
/**
 * 菜单
 *
 */
defined('ShopWT') or exit('Access Denied By ShopWT');
$_menu['schema'] = array (
        'name' => '奖金制度',
        'child' => array (
                array(
                        'name' => '三级分销',
                        'child' => array(
                                'schema_config' => '分销设置',
                                'schema_level' => '分销商等级',
                                'schema_manage' => '分销商管理',
                                'schema_order' => '分销订单管理',
                        )
                        
                ),
                 array(
                        'name' => $lang['nc_schema_team'],
                        'child' => array(
                            'schema_team_config' => $lang['nc_schema_team_config'],
                            'schema_team_level' => $lang['nc_schema_team_level'],
                            'schema_team_manage' => $lang['nc_schema_team_manage'],
                            'schema_team_order' => $lang['nc_schema_team_order'],
                        )

                    ),
                array(
                        'name' => $lang['nc_region_agent'],  //区域代理
                        'child' => array(
                            'region_config' => $lang['nc_region_fenhong_config'],   //区域分红设置
                            'region_management'     => $lang['nc_region_management'],        //区域代理管理
                            'region_apply'          => $lang['nc_region_apply'],              //区域代理申请
                            'region_fenhong_record' => $lang['nc_region_fenhong_record']  // 区域分红记录
                        )

                ),
                array(
                    'name' => $lang['nc_shareholders'],  //股东分红
                    'child' => array(
                        'shareholders_base_config' => $lang['nc_shareholders_base_config'],     //基础设置
                        'shareholders_dividend'     => $lang['nc_shareholders_dividend']        //股东分红

                    )

                ),
                array(
                    'name' => $lang['nc_full_amount_give'],  //满额返现
                    'child' => array(
                        'full_amount_give_config'    => $lang['nc_full_amount_give_config'],     //满额返现设置
                        'full_amount_give_queue'     => $lang['nc_full_amount_give_queue'],       //满额返现队列
                        'full_amount_give_queuefreeze'=> $lang['nc_full_amount_give_queuefreeze'],  //满额返现冻结队列
                        'full_amount_give_record'     => $lang['nc_full_amount_give_record']       //满额返现记录

                    )
                ),
                array(
                    'name' => $lang['nc_single_consume_give'],  // 消费赠送
                    'child' => array(
                        'single_consume_give_config' => $lang['nc_single_consume_give_config'],      //消费赠送设置
                        'single_consume_give_queue'     => $lang['nc_single_consume_give_queue'],     //消费赠送队列
                        'single_consume_give_record'     => $lang['nc_single_consume_give_record'],     //消费赠送记录

                    )

                ),

        )
        
);