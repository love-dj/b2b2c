﻿<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
/**
 * 登录-注册
 */
 $lang	= array(
		'login_register_input_username'			=> '用户名不能为空',
		'login_register_username_range'			=> '用户名必须在6-20个字符之间',
		'login_register_username_lettersonly'	=> '可包含“_”、“-”，不能是纯数字',
		'login_register_username_exists'		=> '该用户名已经存在',
		'login_register_input_password'			=> '密码不能为空',
		'login_register_password_range'			=> '密码长度应在6-20个字符之间',
		'login_register_input_password_again'	=> '您必须再次确认您的密码',
		'login_register_password_not_same'		=> '两次输入的密码不一致',
		'login_register_input_email'			=> '电子邮箱不能为空',
		'login_register_invalid_email'			=> '这不是一个有效的电子邮箱',
		'login_register_email_exists'			=> '该电子邮箱已经存在',
		'login_register_input_text_in_image'	=> '请输入验证码',
		'login_register_code_wrong'				=> '验证码不正确',
		'login_register_must_agree'				=> '请勾选服务协议',
		'login_register_join_us'				=> '用户注册',
		'login_register_input_info'				=> '填写用户注册信息',
		'login_register_username'				=> '用户名',
		'login_register_username_to_login'		=> '支持4-20个中文、英文、数字及“-”符号',
		'login_register_pwd'					=> '设置密码',
		'login_register_password_to_login'		=> '您的登录密码',
		'login_register_password_to_login'		=> '建议至少使用两种字符组合，6-20个字符',
		'login_register_ensure_password'		=> '确认密码',
		'login_register_input_password_again'	=> '请再次输入密码',
		'login_register_email'					=> '邮箱',
		'login_register_input_valid_email'		=> '建议使用常用邮箱',
		'login_register_code'					=> '验证码',
		'login_register_click_to_change_code'	=> '换一张',
		'login_register_input_code'				=> '输入验证码',
		'login_register_agreed'					=> '阅读并同意',
		'login_register_agreement'				=> '《服务协议》',
		'login_register_regist_now'				=> '立即注册',
		'login_register_enter_now'				=> '确认提交',
		'login_register_connect_now'			=> '绑定账号',
		'login_register_after_regist'			=> '注册之后您可以',
		'login_register_buy_info'				=> '购买商品支付订单',
		'login_register_collect_info'			=> '收藏商品关注店铺',
		'login_register_honest_info'			=> '安全交易诚信无忧',
		'login_register_talk_info'				=> '会员等级享受特权',
		'login_register_openstore_info'			=> '积分获取优惠购物',
		'login_register_sns_info'				=> '评价晒单站外分享',


		'login_register_already_have_account'	=> '如果您是本站用户',
		'login_register_login_now_1'			=> '我已经注册，现在就',
		'login_register_login_now_2'			=> '登录',
		'login_register_login_now_3'			=> '或是',
		'login_register_login_forget'			=> '找回密码？',
		/**
		 * 登录-用户保存
		 */
		'login_usersave_login_usersave_username_isnull'	=> '用户名不能为空',
		'login_usersave_password_isnull'		=> '密码不能为空',
		'login_usersave_password_not_the_same'	=> '密码与确认密码不相同，请从重新输入',
		'login_usersave_wrong_format_email'		=> '电子邮件格式不正确，请重新填写',
		'login_usersave_code_isnull'			=> '验证码不能为空',
		'login_usersave_wrong_code'				=> '验证码错误',
		'login_usersave_you_must_agree'			=> '您必须同意服务条款才能注册',
		'login_usersave_your_username_exists'	=> '您填写的用户名称已经存在，请您选择其他用户名填写',
		'login_usersave_your_email_exists'		=> '您填写的邮箱已经存在，请您选择其他邮箱填写',
		'login_usersave_regist_success'			=> '注册成功',
		'login_usersave_regist_success_ajax' 	=> '欢迎来到site_name建议您尽快完善资料，祝您购物愉快！',
		'login_usersave_regist_fail'			=> '注册失败',
		/**
		 * 密码找回
		 */
		'login_index_find_password'				=> '忘记密码',
		'login_password_you_account'			=> '登录账号',
		'login_password_you_email'				=> '电子邮箱',
		'login_password_change_code'			=> '看不清，换一张',
		'login_password_submit'					=> '提交找回',
		'login_password_input_email'			=> '电子邮箱不能为空',
		'login_password_wrong_email'			=> '电子邮箱填写错误',
		/**
		 * 找回处理
		 */
		'login_password_enter_find'				=> '即将进入找回密码页面……',
		'login_password_input_username'			=> '请输入登录名称',
		'login_password_username_not_exists'	=> '登录名称不存在',
		'login_password_input_email'			=> '请输入邮箱地址',
		'login_password_email_not_exists'		=> '邮箱地址错误',
		'login_password_email_fail'				=> '邮件发送超时，请重新申请',
		'login_password_email_success'			=> '邮件已经发出，请查收',
);
?>