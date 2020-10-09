<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 兑换订单功能公用
 */
'admin_pointorder_unavailable'	 		=> '系统未开启积分或者积分兑换功能',
'admin_pointorder_parameter_error'		=> '参数错误',
'admin_pointorderd_record_error'			=> '记录信息错误',
'admin_pointorder_userrecord_error'		=> '用户信息错误',
'admin_pointorder_goodsrecord_error'		=> '礼品信息错误',
'admin_pointprod_list_title'				=> '礼品列表',
'admin_pointprod_add_title'				=> '新增礼品',
'admin_pointorder_list_title'			=> '兑换列表',
'admin_pointorder_ordersn'				=> '兑换单号',
'admin_pointorder_membername'			=> '会员名称',
'admin_pointorder_payment'				=> '支付方式',
'admin_pointorder_orderstate'			=> '状态',
'admin_pointorder_exchangepoints'		=> '兑换积分',
'admin_pointorder_shippingfee'			=> '运费',
'admin_pointorder_addtime'				=> '兑换时间',
'admin_pointorder_shipping_code'			=> '物流单号',
'admin_pointorder_shipping_time'			=> '发货时间',
'admin_pointorder_gobacklist'			=> '返回列表',
/**
 * 兑换信息状态
 */
'admin_pointorder_state_submit'			=> '已提交',
'admin_pointorder_state_waitpay'			=> '待付款',
'admin_pointorder_state_canceled'		=> '已取消',
//'admin_pointorder_state_waitfinish'	=> '待完成',
'admin_pointorder_state_paid'			=> '已付款',
'admin_pointorder_state_confirmpay'		=> '待确认',
'admin_pointorder_state_confirmpaid'		=> '确认收款',
'admin_pointorder_state_waitship'		=> '待发货',
'admin_pointorder_state_shipped'			=> '已发货',
'admin_pointorder_state_waitreceiving'	=> '待收货',
'admin_pointorder_state_finished'		=> '已完成',
'admin_pointorder_state_unknown'			=> '未知',
/**
 * 兑换信息列表
 */
'admin_pointorder_changefee'					=> '调整运费',
'admin_pointorder_changefee_success'			=> '调整运费成功',
'admin_pointorder_changefee_freightpricenull'=> '请添加运费',
'admin_pointorder_changefee_freightprice_error'=> '运费价格必须为数字且大于等于0',
'admin_pointorder_cancel_tip1'				=> '取消兑换礼品信息',
'admin_pointorder_cancel_tip2'				=> '增加积分',
'admin_pointorder_cancel_title'				=> '取消兑换',
'admin_pointorder_cancel_confirmtip'			=> '确认取消兑换信息?',
'admin_pointorder_cancel_success'			=> '取消成功',
'admin_pointorder_cancel_fail'				=> '取消失败',
'admin_pointorder_confirmpaid'				=> '确认收款',
'admin_pointorder_confirmpaid_confirmtip'	=> '是否确认兑换信息款项已经收到?',
'admin_pointorder_confirmpaid_success'		=> '确认成功',
'admin_pointorder_confirmpaid_fail'			=> '确认失败',
'admin_pointorder_ship_title'				=> '发货',
'admin_pointorder_ship_modtip'				=> '修改物流',
'admin_pointorder_ship_code_nullerror'		=> '请添加物流单号',
'admin_pointorder_ship_success'				=> '信息操作成功',
'admin_pointorder_ship_fail'					=> '信息操作失败',
'admin_pointorder_shipping_timetip'			=> '注：默认为当前时间',
'admin_pointorder_shipping_description'		=> '描述',
/**
 * 兑换信息删除
 */
'admin_pointorder_del_success'		=> '删除成功',
'admin_pointorder_del_fail'			=> '删除失败',
/**
 * 兑换信息详细
 */
'admin_pointorder_info_title'			=> '兑换信息详细',
'admin_pointorder_info_ordersimple'		=> '兑换信息',
'admin_pointorder_info_orderdetail'		=> '兑换详情',
'admin_pointorder_info_memberinfo'		=> '会员信息',
'admin_pointorder_info_memberemail'		=> '会员Email',
'admin_pointorder_info_ordermessage'		=> '留言',
'admin_pointorder_info_paymentinfo'		=> '支付信息',
'admin_pointorder_info_paymenttime'		=> '支付时间',
'admin_pointorder_info_paymentmessage'	=> '支付留言',
'admin_pointorder_info_shipinfo'			=> '收货人及发货信息',
'admin_pointorder_info_shipinfo_truename'=> '收货人',
'admin_pointorder_info_shipinfo_areainfo'=> '所在地区',
'admin_pointorder_info_shipinfo_zipcode'=> '邮政编码',
'admin_pointorder_info_shipinfo_telphone'=> '电话号码',
'admin_pointorder_info_shipinfo_mobphone'=> '手机号码',
'admin_pointorder_info_shipinfo_address'=> '详细地址',
'admin_pointorder_info_shipinfo_description'	=> '发货描述',
'admin_pointorder_info_prodinfo'				=> '礼品信息',
'admin_pointorder_info_prodinfo_exnum'		=> '兑换数量',

'pay_bank_user'			=> '汇款人姓名',
'pay_bank_bank'			=> '汇入银行',
'pay_bank_account'		=> '汇款入账号',
'pay_bank_num'			=> '汇款金额',
'pay_bank_date'			=> '汇款日期',
'pay_bank_extend'		=> '其它',
'pay_bank_order'			=> '汇款单号',
'pay_bank_bank_tips'		=> '（需要填写详细的支行名称，如中国银行北京分行朝阳路支行）',
);