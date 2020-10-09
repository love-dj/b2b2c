<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 设置 语言包
 */
'test_email'           => '测试邮件',
'this_is_to'           => '这是一封来自',
'test_email_set_ok'    => '的测试邮件，证明您所邮件设置正常',
'test_email_send_fail' => '测试邮件发送失败，请重新配置邮件服务器',
'test_email_send_ok'   => '测试邮件发送成功',

'web_set'        => '平台设置',
'web_set_subhead' => '网站全局内容基本选项设置',
'account_syn'     => '账号同步',
'account_syn_subhead'=> '设置使用第三方账号登录本站',
'sys_set'        => '系统设置',
'basic_info'     => '基本信息',
'upload_set'		=> '上传设置',
'upload_set_subhead'	=> '网站全局图片、上传等参数设定',
'default_thumb'	=> '默认图片',
'upload_set_ftp' => '远程图片',
'upload_param'	=> '上传参数',
'point_set'		=> '积分设置',
'user_auth' 		=> '用户权限',
'fx_dump'   	=> '验证码开关',
'open_store_set' => '店铺设置',
'credit'         => '信用评价',
'domain'         => '二级域名',
'qqSettings'   => 'QQ互联',
'qqSettings_notice' => '开启后可使用QQ账号登录商城系统',
'sinaSettings'   => '新浪微博',
'loginSettings'   => '会员登录主题图片',
'seller_loginSettings'   => '商家登录主题图片',
'registerSettings'   => '注册主题图片',
'login_set_help1'   	=> '设置登录页左侧主题图片',
'login_click_open'   => '点击打开',
'ftp_set_help1'   	=> 'FTP设置测试通过后，请更改system/config/confi.ini.php中 $config[\'thumb\'[\'save_type\' = 3',
'ftp_set_help2'   	=> '如果服务器已配置NFS等网络文件系统，建议关闭FTP存储，使用NFS等文件系统实现图片共享。',
'pointssettings'   => '积分规则',

'email_set'		=> '邮件设置',
'email_tpl'		=> '消息模板',
'message_tpl'	=> '站内信模板',
'message_tpl_state'	=> '消息模板状态更改',
'seller_tpl'     => '商家消息模板',
'seller_tpl_edit'     => '编辑商家消息模板',
'member_tpl'     => '用户消息模板',
'member_tpl_edit'=> '编辑用户消息模板',

'time_zone_set'         => '默认时区',
'set_sys_use_time_zone' => '设置系统使用的时区，中国为',
'default_product_pic'   => '默认商品图片',
'default_store_logo'    => '默认店铺标志',
'default_user_pic'      => '默认会员头像',
'flow_static_code'      => '版权底部信息',
'flow_static_code_notice'     => '前台页面底部可以显示版权信息或第三方统计',
'image_dir_type'		=> '图片存放类型',
'image_dir_type_0'	=> '按照文件名存放 (例:/店铺id/图片)',
'image_dir_type_1'	=> '按照年份存放 (例:/店铺id/年/图片)',
'image_dir_type_2'	=> '按照年月存放 (例:/店铺id/年/月/图片)',
'image_dir_type_3'	=> '按照年月日存放 (例:/店铺id/年/月/日/图片)',
'image_width'	=> '宽',
'image_height'	=> '高',
'image_typeerror'	=> '上传图片格式不正确',
'image_thumb_tool'	=> '压缩工具',
'image_thumb_tool_tips'	=> '商城默认使用GD库生成缩略图，GD使用广泛但占用系统资源较多，ImageMagick速度快系统资源占用少，但需要服务器有执行命令行命令的权限。可到 http://www.imagemagick.org 下载安装，如改用ImageMagick，可编辑data/config/config.php文件(用EditPlus): <br/>$config[\'thumb\'[\'cut_type\' = \'im\',<br/>$config[\'thumb\'[\'impath\' = \'ImageMagick下convert工具所在路径\', 如：<br/>$config[\'thumb\'[\'impath\' = \'/usr/local/ImageMagick/bin\',',

'allowed_visitors_consult'           => '允许游客咨询',
'allowed_visitors_consult_notice'    => '允许游客在商品的详细展示页面，对当前商品进行咨询',
'sale_allow' => '商品促销',
'sale_notice' => '启用商品促销功能后，商家可以通过限时打折、满即送、优惠套装、推荐展位、加价购、预售商品、F码商品活动，对店铺商品进行促销',
'open_points_isuse' => '积分中心',
'open_points_isuse_notice' => '积分中心启用后，网站将增加积分中心频道',
'open_pointprod_isuse' => '积分兑换',
'open_pointprod_isuse_notice' => '积分兑换、积分功能以及积分中心启用后，平台发布礼品，会员的积分在达到要求时可以在积分中心中兑换礼品',
'points_isuse_notice' => '积分系统启用后，可设置会员的注册、登录、购买商品送一定的积分',
'voucher_allow' => '代金券',
'voucher_allow_notice' => '代金券功能、积分功能、积分中心启用后，商家可以申请代金券活动；会员积分达到要求时可以在积分中心兑换代金券；<br>拥有代金券的会员可在代金券所属店铺内购买商品时，选择使用而得到优惠',
'robbuy_allow' => '抢购',
'robbuy_isuse_notice'    => '抢购功能启用后，商家通过活动发布抢购商品，进行促销',
'complain_time_limit' => '投诉时效',
'complain_time_limit_desc' => '单位为天，订单完成后开始计算，多少天内可以发起投诉，根据具体情况商家和买家都可发起投诉',
'update_cycle_hour'                  => '更新周期(小时)',
'web_name'                           => '网站名称',
'web_name_notice'					=> '网站名称，将显示在前台顶部欢迎信息等位置',
'site_description'                   => '网站描述',
'site_description_notice'			=> '网站描述，出现在前台页面头部的 Meta 标签中，用于记录该页面的概要与描述',
'site_keyword'                       => '网站关键字',
'site_keyword_notice'                => '网站关键字，出现在前台页面头部的 Meta 标签中，用于记录该页面的关键字，多个关键字间请用半角逗号 "," 隔开',
'site_logo'                          => '网站Logo',
'member_logo'                        => '会员中心Logo',
'member_logo_notice'                 => '默认为网站Logo，在会员中心头部显示，建议使用180px * 50px',
'icp_number'                         => 'ICP证书号',
'icp_number_notice'                  => '前台页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入你的授权码，它将显示在前台页面底部，如果没有请留空',
'site_phone'                         => '平台客服联系电话',
'site_phone_notice'                  => '商家中心右下侧显示，方便商家遇到问题时咨询，多个请用半角逗号 "," 隔开',
'site_bank_account'                  => '平台汇款账号',
'site_bank_account_notice'           => '用半角逗号","分隔项目，用半角冒号":"分隔标题和内容，例："银行:中国银行,币种:人民币,账号:xxxxxxxxxxx,姓名:ShopWT,开户行:中国银行北京分行"',
'site_email'                         => '电子邮件',
'site_email_notice'                  => '商家中心右下侧显示，方便商家遇到问题时咨询',
    'site_imchat'                    => 'IM聊天',
    'site_imchat_notice'              =>'关闭IM聊天时，客服端不显示',
    'short_video'                    => '短视频',
    'short_video_notice'              =>'关闭短视频时，客服端不显示',
    'site_area'                         => '自动定位',
'site_area_notice'                  => '网站顶部配送至地区开关，不开启状态：全国，商品页显示所有商品; 开启状态：定位显示用户所在地区，商品列表自动对应显示商品所在地，商品页会自动对应配送地区;如果用户选择后，会使用Cooike方式记录用户所在地区。',
'site_state'                         => '站点状态',
'site_state_notice'                  => '可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台',
'closed_reason'                      => '关闭原因',
'closed_reason_notice'               => '当网站处于关闭状态时，关闭原因将显示在前台',
'hot_search'                         => '热门搜索',
'field_notice'                       => '热门搜索，将显示在前台搜索框下面，前台点击时直接作为关键词进行搜索，多个关键词间请用半角逗号 "," 隔开',
'email_type_open'                    => '邮件功能开启',
'email_type'                         => '邮件发送方式',
'use_other_smtp_service'             => '采用其他的SMTP服务',
'use_server_mail_service'            => '采用服务器内置的Mail服务',
'if_choose_server_mail_no_input_follow' => '如果您选择服务器内置方式则无须填写以下选项',
'smtp_server'             => 'SMTP 服务器',
'set_smtp_server_address' => '设置 SMTP 服务器的地址，如 smtp.163.com; 不建议使用QQ个人邮箱，有所限制',
'smtp_port'               => 'SMTP 端口',
'set_smtp_port'           => '设置 SMTP 服务器的端口，默认为 25',
'sender_mail_address'     => '发信人邮件地址',
'if_smtp_authentication'  => '使用SMTP协议发送的邮件地址，如 shopwt@163.com',
'smtp_user_name'          => 'SMTP 身份验证用户名',
'smtp_user_name_tip'      => '如果使用163或126邮箱，填写完整，如 shopwt@163.com',
'smtp_user_pwd'           => 'SMTP 身份验证密码',
'smtp_user_pwd_tip'       => '邮件的密码，如果使用163或126邮箱，填写授权码',
'test_mail_address'       => '测试接收的邮件地址',
'test'                    => '测试',
'open_checkcode'          => '使用验证码',
'front_login'             => '前台登录',
'front_goodsqa'           => '商品咨询',
'front_regist'            => '前台注册',
'allow_open_store'        => '开店申请',
'setting_store_creditrule'        => '店铺信用',
'setting_store_creditrule_grade'        => '等级',
'setting_store_creditrule_gradenum'        => '信用介于',


'default_img_wrong'       => '图片限于png,gif,jpeg,jpg格式',

'upload_image_filesize'	=> '图片文件大小',
'image_allow_ext'	=> '图片扩展名',
'image_allow_ext_notice'	=> '图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 "," 隔开。',
'image_allow_ext_not_null'	=> '图片扩展名不能为空',
'upload_image_file_size'	=> '大小',
'upload_image_filesize_is_number'    => '图片文件大小仅能为数字',
'image_max_size_tips' => '当前服务器环境，最大允许上传'.ini_get('upload_max_filesize').'B 的文件，您的设置请勿超过该值。',
'upload_image_size_c_num' => '图片像素最多四位数',
'image_max_size_only_num' => '图片文件大小仅能为数字',
'image_max_size_c_num' => '图片文件大小最多四位数',

'qq_isuse'   			=> '是否启用QQ互联功能',
'qq_isuse_open'    	 	=> '开启',
'qq_isuse_close'    	 	=> '关闭',
'qq_apply_link'    	 	=> '立即在线申请',
'qq_appcode'    	 		=> '域名验证信息代码',
'qq_appid'    	 		=> '应用标识',
'qq_appkey'    	 		=> '应用密钥',
'qq_appid_error'    	 	=> '请添加应用标识',
'qq_appkey_error'    	=> '请添加应用密钥',

'sina_isuse'   			=> '新浪微博登录功能',
'sina_isuse_open'    	=> '开启',
'sina_isuse_close'    	=> '关闭',
'sina_apply_link'    	=> '立即在线申请',
'sina_appcode'    	 		=> '域名验证信息代码',
'sina_wb_akey'    	 	=> '应用标识',
'sina_wb_skey'    	 	=> '应用密钥',
'sina_wb_akey_error'    	=> '请添加应用标识',
'sina_wb_skey_error'    	=> '请添加应用密钥',
'sina_function_fail_tip' => '该功能需要在  php.ini 中 开启 php_curl 扩展，才能使用。',

'user_info_del'           => '会员信息清除',
'click_clear'             => '点击清除',
'user_info_clear'         => '会员信息清除，其拥有的店铺和商品也会被清除，您确定要清除吗?',
'first_integration'       => '<span>如果是第一次整合Ucenter，</span><span style=" color: #F00;">需要</span><span style=" color: #F00;">清除商城会员</span><span>信息，清除前建议您备份数据</span>',
'click_bak'               => '点击备份',
'ucenter_integration'     => '是否启用UC互联登陆系统',
'ucenter_type'            => '请选择整合的社区系统',
'ucenter_uc_discuz'       => 'Ucenter',
'ucenter_application_id'  => '应用ID',
'ucenter_help_url'		 => '点击查看会员整合教程',
'ucenter_address'         => '访问地址',
'ucenter_key'             => '通讯密钥',
'ucenter_ip'              => 'IP地址',
'ucenter_mysql_server'    => '数据库地址',
'ucenter_mysql_username'  => '数据库用户名',
'ucenter_mysql_passwd'    => '数据库密码',
'ucenter_mysql_name'      => '数据库名',
'ucenter_mysql_pre'       => '表前缀',

'ucenter_application_id_tips' 	=> '商城系统在若整合Ucenter中的应用ID',
'ucenter_address_tips' 			=> '若整合Ucenter，需要填写Ucenter的访问地址',
'ucenter_ip_tips' 				=> '需要整合应用的IP地址',
'ucenter_mysql_server_tips' 		=> '需要整合应用的数据库地址',
'ucenter_mysql_username_tips' 	=> '需要整合应用的数据库访问账号',
'ucenter_mysql_passwd_tips' 		=> '需要整合应用的数据库访问密码',
'ucenter_mysql_name_tips' 		=> '需要整合应用的数据库名称',
'ucenter_mysql_pre_tips' 		=> '需要整合应用的表前缀',

'points_isuse'   		=> '积分',
'points_isuse_open'    	=> '开启',
'points_isuse_close'    	=> '关闭',
'points_ruletip'    		=> '积分规则如下',
'points_item'    	 	=> '项目',
'points_number'    	 	=> '赠送积分',
'points_number_reg'    	=> '会员注册',
'points_number_login'    => '会员每天登录',
'points_number_comments'    => '订单商品评论',
'points_number_order'    => '购物并付款',
'points_number_orderrate'    => '消费额与赠送积分比例',
'points_number_orderrate_tip'    => '例:设置为10，表明消费10单位货币赠送1积分',
'points_number_ordermax'    => '每订单最多赠送积分',
'points_number_ordermax_tip'    => '例:设置为100，表明每订单赠送积分最多为100积分',
'points_update_success'    => '更新成功',
'points_update_fail'    	=> '更新失败',

'open_yes'    	=> '是',
'open_no'    	=> '否',

'font_set' => '水印字体',
'font_help1' => '如果图片管理中水印使用汉字则要下载并安装相应字体库。',
'font_help2' => '使用方法：将您下载到的字体库上传到网站根目录下\system\static\font这个文件夹内，同时需要修改此文件夹下的font.info.php文件。例如：您下载了一个“宋体”字库simsun.ttf，将其放置于前面所述文件夹内，打开font.info.php文件在其中的$fontInfo = array(\'arial\'=>\'Arial\')数组后面添加宋体字库信息,“=>”符号左边是文件名，右边是您想在网站上显示的文字信息，添加后的样子是array(\'arial\'=>\'Arial\',\'simsun\'=>\'宋体\')',
'font_info' => '已经安装字体如下',

'share_allow' 	=> '是否开启站外分享功能',
'share_notice' 	=> '开启站外分享功能并设置站外分享绑定的相应接口后，SNS分享店铺和商品信息功能中将可以使用站外分享信息功能',


'seo_set_index' 		=> '首页',
'seo_set_group' 		=> '抢购',
'seo_set_brand' 		=> '品牌',
'seo_set_point' 		=> '积分中心',
'seo_set_article' 	=> '文章',
'seo_set_shop' 		=> '店铺',
'seo_set_product' 	=> '商品',
'seo_set_category' 	=> '商品分类',
'seo_set_prompt' 	=> '插入的变量必需包括花括号“{}”，当应用范围不支持该变量时，该变量将不会在前台显示(变量后边的分隔符也不会显示)，留空为系统默认设置，SEO自定义支持手写。以下是可用SEO变量:',
'seo_set_tips1' 	=> '站点名称 {sitename}，（应用范围：全站）',
'seo_set_tips2' 	=> '名称 {name}，（应用范围：抢购名称、商品名称、品牌名称、文章标题、分类名称）',
'seo_set_tips3' 	=> '文章分类名称 {article_class}，（应用范围：文章分类页）',
'seo_set_tips4' 	=> '店铺名称 {shopname}，（应用范围：店铺页）',
'seo_set_tips5' 	=> '关键词 {key}，（应用范围：商品关键词、文章关键词、店铺关键词）',
'seo_set_tips6' 	=> '简单描述 {description}，（应用范围：商品描述、文章摘要、店铺关键词）',
'seo_set_tips7' 	=> '<a>提交保存后，需要到 工具 -> 更新缓存 清理SEO，新的SEO设置才会生效</a>',
'seo_set_group_content' 		=> '抢购内容',
'seo_set_brand_list' 		=> '某一品牌商品列表',
'seo_set_point_content' 		=> '积分中心商品内容',
'seo_set_atricle_list' 		=> '文章分类列表',
'seo_set_atricle_content' 	=> '文章内容',
'seo_set_insert_tips' 		=> '可用的代码，点击插入',

'wt_set'        => '基本设置',
'wt_set_subhead' => '基本选项设置',
'wt_phone'        => '平台客服电话',
'wt_qq'        => ' 平台客服QQ',
'wt_time'        => '运营时间',
'wt_mail'        => '平台客服邮箱',
'wt_phone_notice' => '显示在网站底部的客服电话',
'wt_qq_notice' => '显示在网站侧右边栏的客服QQ',
'wt_time_notice' => '显示在网站底部的运营时间',
'wt_mail_notice' => '显示在网站底部的邮箱',

'lc_set'        => '首页楼层菜单设置',

'webchat_set'        => '微信公众号',
'webchat_set_subhead' => '在这里你可以设置默认微信公众号',
'webchat_appid' => '公众号APPID',
'webchat_appsecret' => '公众号appsecret',
'wt_webchat_appid_notice' => '请填写微信公众号APPID',
'wt_webchat_appsecret_notice' => '请填写微信公众号appsecret',
);