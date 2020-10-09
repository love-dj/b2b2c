<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
'wt_wechat_setting'	=> '模块设置',
'wechat_not_install' => '您没有安装微商城模块',

'wechat_isuse' => '微信模块开关',
'wechat_isuse_explain' => '关闭后系统将不再启用微信相关功能',

//微信接口配置
'wt_wechat_api' => '接口配置',
'wechat_api_url' => '接口URL',
'wechat_token' => 'Token',
'wechat_type' => '公众号类型',
'wechat_appid' => 'AppID',
'wechat_appsecret' => 'AppSecret',
'wechat_name' => '公众号名称',
'wechat_email' => '公众号邮箱',
'wechat_preid' => '公众号原始ID',
'wechat_account' => '微信号',
'wechat_encodingtype' => '消息加解密方式',
'wechat_encoding' => 'EncodingAESKey',
'wechat_type_name' => array(
	'0'=>'订阅号未认证',
	'1'=>'订阅号已认证',
	'2'=>'服务号未认证',
	'3'=>'服务号已认证'
),

'wechat_encodingtype_name' => array(
	'0'=>'明文模式',
	'1'=>'兼容模式',
	'2'=>'安全模式'
),

//素材管理
'material_manage' => '素材管理',
'material_all' => '全部',
'material_single' => '单图文',
'material_multi' => '多图文',
'material_add' => '新增',
'material_edit' => '编辑素材图文',
'material_item_max' => '你最多只可以加入8条图文消息！',
'material_item_title' => '标题',
'material_item_image' => '缩略图',
'material_item_add' => '增加一条',
'material_item_openimage' => '封面图',
'material_item_link' => '链接，必带上http://或https://',
'material_item_image_size' => '建议尺寸',
'material_not_null' => '提交内容不能为空',
'material_delete_tips' => '删除后不可恢复，继续吗？',
'material_type' => array(
	1 => '单图文',
	2 => '多图文'
),

//公共语言
'reply_type' => '回复类型',
'reply_type_name' => array('文字消息','图文消息','链接网址','我的二维码'),
'reply_content' => '回复内容',
'reply_material' => '回复图文',
'reply_link' => '链接网址',
'open_btn' => '开启',
'close_btn' => '关闭',
'material_select_btn' => '选择图文',
'wechat_keywords' => '关键词',
'wechat_select_all' => '全部',
'reply_pattern_type' => '匹配模式',
'reply_pattern_type_name' => array('精确匹配','模糊匹配'),
'not_info_id' => '请选择信息',
'info_not_exist' => '信息不存在',
'not_info_keywords' => '请填写关键词',
'info_keywords_exits' => '关键词已存在',
'wechat_patternmethod_notice' => array('（用户输入的文字和此关键词一样才会触发,一般用于一个关键词.）','（只要用户输入的文字包含此关键词就触）'),


//首次关注设置
'wt_wechat_attention' => '首次关注设置',
'attention_each_keyword' => '任意关键词',
'attention_each_keyword_tips' => '开启后，当输入的关键字无相关的匹配内容时，则使用本设置回复',
'attention_user_notice' => '成为会员提醒',
'attention_user_notice_tips' => '开启后，用户关注公众收到的消息中会包含会员信息，例如：您好**，您已成为第***位会员。此设置仅对“文字消息”有效',

//关键词管理
'wt_wechat_keywords' => '关键词设置',
'wechat_keywords_notice' => '多个关键词,请用 "|" 隔开',

//URL管理
'wechat_url_manage' => '自定义URL管理',
'wechat_url_select_type' => array(
	'name'=>'URL名称',
	'link'=>'URL地址'
),
'not_info_url_name' => '请填写URL名字',
'not_info_url_link' => '请填写URL链接，必带上http://或https://',
'wechat_url_name' => 'URL名称',
'wechat_url_link' => 'URL链接',

//自定义菜单
'wechat_menu_manage' => '自定义菜单管理',
'wechat_menu_title' => '菜单标题',
'wechat_is_useful' => '是否生效',
'wechat_updatetime' => '更新时间',
'wechat_not_title' => '请填写标题',
'wechat_not_menu' => '请设置菜单',

'wechat_edit_menu' => '菜单设置',
'wechat_menu_name' => '菜单',
'wechat_child_name' => '子菜单',

'not_appid' => '还没有配置AppID和AppSecret，请到【接口配置】中进行配置',
'not_menu_data' => '暂无菜单数据，请先设置菜单',
'get_access_token_fail' => '获取access_token失败，请检查配置信息',
'menu_publish_success' => '菜单已同步到微信',
'menu_publish_fail' => '菜单发布失败',
'menu_publish_to_weixin' => '同步到微信',
'menu_delete_success' => '微信菜单成功删除',
'menu_delete_fail' => '微信菜单删除失败'
);