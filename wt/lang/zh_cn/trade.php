<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 交易管理 语言包
 */

//订单管理
'order_manage'              => '商品订单',
'order_manage_subhead'      => '商城实物商品交易订单查询及管理',
'order_vr_manage'           => '虚拟订单',
'order_vr_manage_subhead'   => '商城虚拟商品交易订单查询及管理',
'order_help1'			   => '点击查看操作将显示订单（包括订单物品）的详细信息',
'order_help2'			   => '点击取消操作可以取消订单（在线支付但未付款的订单和货到付款但未发货的订单）',
'order_help3'			   => '如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击收到货款操作(仅限于下单后7日内可更改收款状态)，并填写相关信息后更改订单支付状态',
'manage'                    => '管理',
'store_name'                => '店铺',
'buyer_name'                => '买家',
'payment'                   => '支付方式',
'order_number'              => '订单号',
'order_state'               => '订单状态',
'order_state_new'           => '待付款',
'order_state_pay'           => '待发货',
'order_state_send'          => '待收货',
'order_state_success'       => '已完成',
'order_state_cancel'        => '已取消',
'type'					   => '类型',
'pended_payment'            => '已提交，待确认',//增加
'order_time_from'           => '下单时间',
'order_price_from'          => '订单金额',
'cancel_search'             => '撤销检索',
'order_time'                => '下单时间',
'order_total_price'         => '订单总额',
'order_total_transport'     => '运费',
'miss_order_number'         => '缺少订单编号',

'order_state_paid' => '已付款',
'order_admin_operator' => '系统管理员',
'order_state_null' => '无',
'order_handle_history'	=> '操作历史',
'order_admin_cancel' => '未付款，系统管理员取消订单。',
'order_admin_pay' => '系统管理员确认收款完成。',
'order_confirm_cancel'	=> '您确实要取消该订单吗？',
'order_confirm_received'	=> '您确定已经收到货款了吗？',
'order_change_cancel'	=> '取消',
'order_change_received'	=> '收到货款',
'order_log_cancel'	=> '取消订单',

//订单详情
'order_detail'              => '订单详情',
'offer'                     => '优惠了',
'order_info'                => '订单信息',
'seller_name'               => '商家',
'pay_message'               => '支付留言',
'payment_time'              => '支付时间',
'ship_time'                 => '发货时间',
'complate_time'             => '完成时间',
'buyer_message'             => '买家附言',
'consignee_ship_order_info' => '收货人及发货信息',
'consignee_name'            => '收货人姓名',
'region'                    => '所在地区',
'zip'                       => '邮政编码',
'tel_phone'                 => '电话号码',
'mob_phone'                 => '手机号码',
'address'                   => '详细地址',
'ship_method'               => '配送方式',
'ship_code'                 => '发货单号',
'product_info'              => '商品信息',
'product_type'              => '促销',
'product_price'             => '单价',
'product_num'               => '数量',
'product_shipping_mfee'     => '免运费',
'wt_sale'				=> '促销活动',
'wt_robbuy_flag'			=> '团',
'wt_robbuy'				=> '抢购活动',
'wt_robbuy_view'			=> '查看',
'wt_mansong_flag'			=> '满',
'wt_mansong'					=> '满即送',
'wt_xianshi_flag'			=> '折',
'wt_xianshi'					=> '限时折扣',
'wt_bundling_flag'			=> '组',
'wt_bundling'				=> '优惠套装',


'pay_bank_user'			=> '汇款人姓名',
'pay_bank_bank'			=> '汇入银行',
'pay_bank_account'		=> '汇款入账号',
'pay_bank_num'			=> '汇款金额',
'pay_bank_date'			=> '汇款日期',
'pay_bank_extend'		=> '其它',
'pay_bank_order'			=> '汇款单号',

'order_refund'			=> '退款',
'order_return'			=> '退货',

'order_show_system'				=> '系统',
'order_show_at'				=> '于',
'order_show_cur_state'			=> '订单当前状态',
'order_show_next_state'		=> '下一状态',
'order_show_reason'			=> '原因',
);