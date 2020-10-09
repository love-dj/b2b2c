<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 积分礼品兑换功能公用
 */
		'pointcart_unavailable'	 		=> '系统未开启积分或者积分兑换功能',
		'pointcart_parameter_error'		=> '参数错误',
		'pointcart_record_error'			=> '记录信息错误',
		'pointcart_unlogin_error'		=> '请您先进行登录',
		'pointcart_goodsname'			=> '礼品名称',
		'pointcart_exchangepoint'		=> '兑换积分',
		'pointcart_exchangenum'			=> '兑换数量',
		'pointcart_pointoneall'			=> '积分小计',
		'pointcart_ensure_order'			=> '确认兑换清单',
		'pointcart_ensure_info'			=> '确认收货人资料',
		'pointcart_exchange_finish'		=> '兑换完成',
		'pointcart_cart_allpoints'		=> '所需总积分',
		'pointcart_shipfee'				=> '运费',
		'pointcart_step2_prder_trans_to'	=> '寄送至',
		'pointcart_step2_prder_trans_receive'	=> '收货人',
		'pointcart_buyer_error'			=> '买家信息错误',
/**
 * 购物车
 */
		'pointcart_cart_addcart_fail'		=> '兑换失败',
		'pointcart_cart_addcart_willbe'		=> '积分礼品兑换活动即将开始',
		'pointcart_cart_addcart_end'			=> '积分礼品兑换活动已经结束',
		'pointcart_cart_addcart_pointshort'	=> '积分不足,请选择其他礼品',
		'pointcart_cart_addcart_prodexists'	=> '您已经兑换过该礼品',
		'pointcart_cart_modcart_fail'		=> '修改失败',
		'pointcart_cart_chooseprod_title'	=> '已选择的兑换礼品',
		'pointcart_cart_handle'				=> '操作',
		'pointcart_cart_reduse'				=> '减少',
		'pointcart_cart_increase'			=> '增加',
		'pointcart_cart_del'					=> '删除',
		'pointcart_cart_submit'				=> '确认兑换',
		'pointcart_cart_continue'			=> '继续兑换',
		'pointcart_cart_null'				=> '您还没有选择兑换礼品',
		'pointcart_cart_exchangenow'			=> '马上去兑换',
		'pointcart_cart_exchanged_list'		=> '查看已兑换信息',
/**
 * step1
 */
		'pointcart_step1_receiver_address'	=> '收货人地址',
		'pointcart_step1_manage_receiver_address'	=> '管理收货人地址',
		'pointcart_step1_addnewaddress_submit'	=> '保存收货人地址',
		'pointcart_step1_receiver_name'		=> '收货人姓名',
		'pointcart_step1_phone'				=> '电话',
		'pointcart_step1_new_address'		=> '使用新的收货地址',
		'pointcart_step1_input_true_name'	=> '请填写真实姓名',
		'pointcart_step1_area'				=> '所在地区',
		'pointcart_step1_edit'				=> '编辑',
		'pointcart_step1_please_choose'		=> '请选择',
		'pointcart_step1_choose_area'		=> '请选择所在地区',
		'pointcart_step1_whole_address'		=> '详细地址',
		'pointcart_step1_true_address'		=> '请填写真实地址，不需要重复填写所在地区',
		'pointcart_step1_zipcode'			=> '邮政编码',
		'pointcart_step1_zipcode_tip'		=> '请填写邮政编码',
		'pointcart_step1_zipcode_error'		=> '邮政编码由6位数字构成',
		'pointcart_step1_phone_num'			=> '电话号码',
		'pointcart_step1_telphone'			=> '电话号码和手机号码请至少填写一个',
		'pointcart_step1_mobile_num'			=> '手机号码',
		'pointcart_step1_auto_save'			=> '自动保存收货地址',
		'pointcart_step1_auto_saved'			=> '选择后该地址将会保存到您的收货地址列表',
		'pointcart_step1_message'			=> '备注留言',
		'pointcart_step1_message_showice'		=> '选填，可以写下您对礼品的需求',
		'pointcart_step1_submit'				=> '兑换完成',
		'pointcart_step1_chooseprod'			=> '已选礼品',
		'pointcart_step1_order_wrong1'		=> '很抱歉，您填写的订单信息中有',
		'pointcart_step1_order_wrong2'		=> '个错误(如红色斜体部分所示)，请检查并修正后再提交！',
		'pointcart_step1_input_address'		=> '请如实填写收货人详细地址',
		'pointcart_step1_input_receiver'		=> '请如实填写您的收货人姓名',
		'pointcart_step1_phone_format'		=> '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位',
		'pointcart_step1_wrong_mobile'		=> '手机号码只能是数字,并且不能少于6位',

		'pointcart_step1_goods_content'					=> '兑换商品信息',
		'pointcart_step1_goods_point'				=> '兑换所需积分',
		'pointcart_step1_goods_num'					=> '兑换数量',
		'pointcart_step1_return_list'				=> '返回兑换列表',
/**
 * step2
 */
		'pointcart_step2_fail'				=> '兑换操作失败',
		'pointcart_step2_exchange_success'	=> '兑换成功！',
		'pointcart_step2_order_created'		=> '您的兑换订单已成功生成',
		'pointcart_step2_order_sn'			=> '兑换单号',
		'pointcart_step2_order_allpoints'	=> '兑换积分',
		'pointcart_step2_view_order'			=> '查看详单',
		'pointcart_step2_choose_method_to_pay'=> '选择支付方式',
		'pointcart_step2_paymessage'			=> '支付留言',
		'pointcart_step2_choose_pay_method'	=> '请选择支付方式',
		'pointcart_step2_ensure_pay'			=> '确认支付',
		'pointcart_step2_orderinfo_title'	=> '兑换订单信息如下',
		'pointcart_step2_pay_online'			=> '线上支付',
		'pointcart_step2_pay_offline'		=> '线下支付',

		'pointcart_step2_paymentnull'		=> '抱歉，暂时没有符合条件的支付方式，请联系客服进行后续购买流程',


		'pointcart_step2_paymessage_nullerror'	=> '请填写汇款信息',
		'pay_bank_user'			=> '汇款人姓名',
		'pay_bank_bank'			=> '汇入银行',
		'pay_bank_account'		=> '汇款入账号',
		'pay_bank_num'			=> '汇款金额',
		'pay_bank_date'			=> '汇款日期',
		'pay_bank_extend'		=> '其它',
		'pay_bank_order'			=> '汇款单号',

/**
 * step3
 */
		'pointcart_step3_paymenterror'	=> '支付方式错误',
		'pointcart_step3_choosepayment'	=> '请选择支付方式',
		'pointcart_step3_paysuccess'		=> '兑换信息支付成功',
		'pointcart_step3_payfail'		=> '兑换信息支付失败',
		'pointcart_step3_predepositreduce_logdesc'=> '积分兑换减少预存款可用金额',
);