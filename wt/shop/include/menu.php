<?php
/**
 * 菜单
 *

 
 
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

$_menu['shop'] = array(
        'name' => '商城',
        'child' => array(
                array(
                        'name' => '设置',
                        'child' => array(
                                'setting' => '基本设置',
                                'page_config' => '首页管理',
                                'web_channel' => '频道管理',
                                'upload' => '上传设置',
                                'search' => '搜索设置',
                                'seo' => $lang['wt_seo_set'],
                                'payment' => $lang['wt_pay_method'],
                                'message' => $lang['wt_message_set'],
                                'express_api' => '快递接口',
                                'express' => $lang['wt_admin_express_set'],
                                'waybill' => '运单模板'
                        )),
                array(
                        'name' => $lang['wt_operation'],
                        'child' => array(
                                'bill' => $lang['wt_bill_manage'],
                                'vr_bill' => '虚拟订单结算',
                                'contract' => '商家保障服务',
                                'delivery' => '自提点管理',
                                'rechargecard' => '充值卡管理',
                                'mall_consult' => '客服咨询管理',
                        )),
                array(
                        'name' => $lang['wt_store'],
                        'child' => array(
                                'ownshop' => '自营店铺',
                                'store' => $lang['wt_store_manage'],
                                'store_class' => $lang['wt_store_class'],
                                'store_grade' => $lang['wt_store_grade'],
                                'sns_strace' => $lang['wt_s_snstrace'],
                                'domain' => $lang['wt_domain_manage'],
                                'help_store' => '店铺帮助',
                                'store_joinin' => '入驻首页'
                        )),
                array(
						'name' => '商品',
                        'child'=>array(
                                'goods' => $lang['wt_goods_manage'],
                                'goods_class' => $lang['wt_class_manage'],
                                'type' => $lang['wt_type_manage'],
                                'spec' => $lang['wt_spec_manage'],
                                'goods_album' => $lang['wt_album_manage'],
                                'goods_video_album' => '视频管理',
                                'brand' => $lang['wt_brand_manage'],
                                'goods_recommend' => '商品推荐',
				'lib_goods' => '商品库管理',
                        )),
                array(
                        'name' => $lang['wt_trade'],
                        'child' => array(
                                'order' => $lang['wt_order_manage'],
                                'refund' => '实物退款处理',
                                'return' => '实物退货处理',
	                            'order_vr' => '虚拟订单管理',
                                'vr_refund' => '虚拟订单退款',
                                'inform' => $lang['wt_inform_config'],
                                'complain' => $lang['wt_complain_config'],
                                'consulting' => $lang['wt_consult_manage'],
                                'evaluate' => $lang['wt_goods_evaluate']
                        )),
                array(
                        'name' => $lang['wt_stat'],
                        'child' => array(
                                'stat_general' => $lang['wt_statgeneral'],
                                'stat_trade' => $lang['wt_stattrade'],
                                'stat_store' => $lang['wt_statstore'],
                                'stat_goods' => $lang['wt_statgoods'],
                                'stat_industry' => $lang['wt_statindustry'],
                                'stat_aftersale' => $lang['wt_stataftersale'],
                                'stat_marketing' => $lang['wt_statmarketing'],
                                'stat_member' => $lang['wt_statmember'],
                        )),
                array(
                        'name' => '促销',
                        'child' => array(
                                'operation' => '促销设置',
                                'sale_xianshi' => $lang['wt_sale_xianshi'],
                                'robbuy' => $lang['wt_robbuy_manage'],
                                'vr_robbuy' => '虚拟抢购设置',
                                'activity' => $lang['wt_activity_manage'],
                                'coupon' => '平台优惠券',
                                'sale_cou' => '加价购活动',
                                'sale_pingou' => '拼团活动',
                                'sale_bundling' => $lang['wt_sale_bundling'],
                                'sale_combo' => '推荐组合',
                                'sale_booth' => '推荐展位',
                                'sale_mansong' => $lang['wt_sale_mansong'],
                                'voucher' => $lang['wt_voucher_price_manage'],
                                'sale_book' => '定金预售',
                                'sale_fcode' => 'Ｆ码商品',
                                'sale_sole' => '移动端专享',
                                'pointprod'=>$lang['wt_pointprod'],
                        )),
                array(
                        'name' => $lang['wt_member'],
                        'child' => array(
                                'member' => $lang['wt_member_manage'],
                                'sns_malbum' => $lang['wt_member_album_manage'],
                                'sns_sharesetting' => $lang['wt_binding_manage'],
                                'sns_member' => $lang['wt_member_tag'],
                                'snstrace' => $lang['wt_snstrace'],
                                'points' => $lang['wt_member_pointsmanage'],
                                'member_exp' => '经验值管理',
                                'chat_log' => 'IM消息记录',
                                'predeposit' => $lang['wt_member_predepositmanage']
                        ))
));
