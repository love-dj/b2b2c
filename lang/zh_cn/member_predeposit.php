<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
		/**
		 * 预存款功能公用
		 */
		'predeposit_no_record'	 				=> '没有符合条件的记录',
		'predeposit_unavailable'	 			=> '系统未开启预存款功能',
		'predeposit_parameter_error'			=> '参数错误',
		'predeposit_record_error'				=> '记录信息错误',
		'predeposit_userrecord_error'			=> '会员信息错误',
		'predeposit_payment'					=> '支付方式',
		'predeposit_addtime'					=> '创建时间',
		'predeposit_apptime'					=> '申请时间',
		'predeposit_checktime'					=> '审核时间',
		'predeposit_paytime'					=> '付款时间',
		'predeposit_addtime_to'					=> '至',
		'predeposit_trade_no'					=> '交易号',
		'predeposit_adminremark'				=> '管理员备注',
		'predeposit_recordstate'				=> '记录状态',
		'predeposit_paystate'					=> '状态',
		'predeposit_backlist'					=> '返回列表',
		'predeposit_pricetype'					=> '预存款类型',
		'predeposit_pricetype_available'		=> '可用金额',
		'predeposit_pricetype_freeze'			=> '冻结金额',
		'predeposit_price'						=> '金额',
		'predeposit_payment_error'				=> '支付方式错误',
		/**
		 * 充值功能公用
		 */
		'predeposit_rechargesn'					=> '充值单号',
		'predeposit_rechargewaitpaying'			=> '未支付',
		'predeposit_rechargepaysuccess'			=> '已支付',
		'predeposit_rechargestate_auditing'		=> '审核中',
		'predeposit_rechargestate_completed'	=> '已完成',
		'predeposit_rechargestate_closed'		=> '已关闭',
		'predeposit_recharge_price'				=> '充值金额',
		'predeposit_recharge_huikuanname'		=> '汇款人姓名',
		'predeposit_recharge_huikuanbank'		=> '汇款银行',
		'predeposit_recharge_huikuandate'		=> '汇款日期',
		'predeposit_recharge_memberremark'		=> '会员备注',
		'predeposit_recharge_success'			=> '充值成功',
		'predeposit_recharge_fail'				=> '充值失败',
		'predeposit_recharge_pay'				=> '支&nbsp;付',
		'predeposit_recharge_view'				=> '查看详单',
		'predeposit_recharge_paydesc'			=> '预存款充值订单',
		'predeposit_recharge_pay_offline'		=> '待确认',
		/**
		 * 充值添加
		 */
		'predeposit_recharge_add_pricenull_error'	=> '请添加充值金额',
		'predeposit_recharge_add_pricemin_error'	=> '充值金额为大于或者等于0.01的数字',
		/**
		 * 充值信息删除
		 */
		'predeposit_recharge_del_success'		=> '充值信息删除成功',
		'predeposit_recharge_del_fail'			=> '充值信息删除失败',
		/**
		 * 提现功能公用
		 */
		'predeposit_cashsn'						=> '申请单号',
		'predeposit_cashmanage'					=> '提现管理',
		'predeposit_cashwaitpaying'				=> '等待支付',
		'predeposit_cashpaysuccess'				=> '支付成功',
		'predeposit_cashstate_auditing'			=> '审核中',
		'predeposit_cashstate_completed'		=> '已完成',
		'predeposit_cashstate_closed'			=> '已关闭',
		'predeposit_cash_price'					=> '提现金额',
		'predeposit_cash_shoukuanname'			=> '开户人姓名',
		'predeposit_cash_shoukuanbank'			=> '收款银行',
		'predeposit_cash_shoukuanaccount'		=> '收款账号',
		'predeposit_cash_shoukuanname_tip'		=> '强烈建议优先填写国有4大银行(中国银行、中国建设银行、中国工商银行和中国农业银行)<br/>请填写详细的开户银行分行名称，虚拟账户如支付宝、财付通填写“支付宝”、“财付通”即可',
		'predeposit_cash_shoukuanaccount_tip'	=> '银行账号或虚拟账号(支付宝、财付通等账号)',
		'predeposit_cash_shoukuanauser_tip'		=> '收款账号的开户人姓名',
		'predeposit_cash_shortprice_error'		=> '预存款金额不足',
		'predeposit_cash_price_tip'				=> '当前可用金额',
		'predeposit_cash_availablereducedesc'	=>  '会员申请提现减少预存款金额',
		'predeposit_cash_freezeadddesc'			=>  '会员申请提现增加冻结预存款金额',
		'predeposit_cash_availableadddesc'		=>  '会员删除提现增加预存款金额',
		'predeposit_cash_freezereducedesc'		=>  '会员删除提现减少冻结预存款金额',

		/**
		 * 提现添加
		 */
		'predeposit_cash_add_shoukuannamenull_error'	=> '请填写收款人姓名',
		'predeposit_cash_add_shoukuanbanknull_error'	=> '请填写收款银行',
		'predeposit_cash_add_pricemin_error'			=> '提现金额为大于或者等于0.01的数字',
		'predeposit_cash_add_enough_error'				=> '账户余额不足',
		'predeposit_cash_add_pricenull_error'			=> '请填写提现金额',
		'predeposit_cash_add_shoukuanaccountnull_error'	=> '请填写收款账号',
		'predeposit_cash_add_success'					=> '您的提现申请已成功提交，请等待系统处理',
		'predeposit_cash_add_fail'						=> '提现信息添加失败',
		/**
		 * 提现信息删除
		 */
		'predeposit_cash_del_success'		=> '提现信息删除成功',
		'predeposit_cash_del_fail'			=> '提现信息删除失败',
		/**
		 * 支付接口
		 */
		'predeposit_payment_pay_fail'		=> '充值失败',
		'predeposit_payment_pay_success'	=> '充值成功，正在前往我的订单',
		'predepositrechargedesc'			=>  '充值',
		/**
		 * 出入明细 
		 */
		'predeposit_log_stage' 				=> '类型',
		'predeposit_log_stage_recharge'		=> '充值',
		'predeposit_log_stage_cash'			=> '提现',
		'predeposit_log_stage_order'		=> '消费',
		'predeposit_log_stage_artificial'	=> '手动修改',
		'predeposit_log_stage_system'		=> '系统',
		'predeposit_log_stage_income'		=> '收入',
		'predeposit_log_desc'				=> '变更说明',
);