<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(

'limit_admin'			=> '管理员',
'limit_gadmin'			=> '权限组',
'admin_add_limit_admin'			=> '添加管理员',
'admin_add_limit_gadmin'			=> '添加权限组',
/**
 * 管理员列表
 */
'admin_index_not_allow_del'	=> '该账号为系统管理员,不得删除',
'admin_index_login_null'		=> '此管理员未登录过',
'admin_index_username'		=> '登录名',
'admin_index_password'		=> '密码',
'admin_rpassword'			=> '确认密码',
'admin_index_truename'		=> '真实姓名',
'admin_index_email'			=> '电子邮件',
'admin_index_im'				=> '即时通讯',
'admin_index_last_login'		=> '上次登录',
'admin_index_login_times'	=> '登录次数',
'admin_index_sys_admin'		=> '系统管理员',
'admin_index_del_admin'		=> '删除',
'admin_index_sys_admin_no'	=> '超级管理员不可编辑',
/**
 * 管理员添加
 */
'admin_add_admin_not_exists'		=> '该名称已存在',
'admin_add_username_tip'			=> '3-15位字符，可由中文、英文、数字及“_”、“-”组成。',
'admin_add_password_tip'			=> '6-20位字符，可由英文、数字及标点符号组成。',
'admin_add_rpassword_tip'		=> '请再次输入您的密码。',
'admin_add_gid_tip'				=> '请选择一个权限组，如果还未设置，请先建立权限组后再添加管理员。',
'admin_add_username_null'		=> '登录名不能为空',
'admin_add_username_max'			=> '登录名长度为3-20',
'admin_add_password_null'		=> '密码不能为空',
'admin_add_gid_null'				=> '请选择一个权限组',
'admin_add_password_type'		=> '密码为英文或数字',
'admin_add_password_max'			=> '密码长度为6-20',
'admin_add_username_not_exists'	=> '该名称不存在，请换一个',
/**
 * 管理权限设置
 */
'admin_set_admin_not_exists'		=> '此管理员不存在',
'admin_set_back_to_admin_list'	=> '返回管理员列表',
'admin_set_back_to_member_list'	=> '返回会员列表',
'admin_set_limt'					=> '设置权限',
'admin_set_system_login'			=> '后台登录',
'admin_set_website_manage'		=> '网站管理',
'admin_set_clear_cache'			=> '清空缓存',
'admin_set_operation'			=> '运营管理',
'admin_set_operation_ztc_class'	=> '直通车管理',
'admin_set_operation_gold_buy'	=> '金币购买管理',
'admin_set_operation_pointprod'	=> '积分兑换管理',
/**
 * 管理员修改
 */
'admin_edit_success'				=> '更新成功',
'admin_edit_fail'				=> '更新失败',
'admin_edit_repeat_error'		=> '两次输入的密码不一致，请重新输入',
'admin_edit_admin_error'			=> '管理员信息错误',
'admin_edit_admin_pw'			=> '密码',
'admin_edit_admin_pw2'			=> '确认密码',
'admin_edit_pwd_tip1'			=> '不修改留空即可',


'gadmin_name'				=> '权限组',
'gadmin_del_confirm'				=> '删除该组同时会清除该组内成员的所有权限，确认删除吗?',
);
