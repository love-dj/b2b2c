<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
		/**
		 * 共有语言
		 */
		'store_storeinfo_error'			=> '店铺信息错误',
		/**
		 * 开店
		 */
		'store_create_right_closed'		=> '暂时关闭了申请店铺的权限',
		'store_create_created'			=> '您已经创建了店铺，不能重复创建',
		'store_create_grade_not_exists'	=> '错误的操作，该等级不存在',
		'store_create_choose_store_class'	=> '选择店铺类型',
		'store_create_input_owner_info'	=> '填写店主和店铺信息',
		'store_create_finish'			=> '完成',
		'store_create_goods_num'		=> '商品数',
		'store_create_upload_space'		=> '上传空间',
		'store_create_template_num'		=> '模板数',
		'store_create_charge_standard'	=> '收费标准',
		'store_create_need_audit'		=> '需要审核',
		'store_create_yes'				=> '是',
		'store_create_no'				=> '否',
		'store_create_additional_function'	=> '附加功能',
		'store_create_create_now'		=> '立即开店',
		'store_create_back' 			=> '返&nbsp;&nbsp;&nbsp;&nbsp;回',
		'store_create_input_store_name'	=> '请输入店铺名称',
		'store_create_store_name_exists'=> '该店铺名称已经存在，请您更换一个店面名称',
		'store_create_input_phone'		=> '请输入联系电话',
		'store_create_input_store_card'	=> '身份证格式不正确',
		'store_create_input_zip_code_is_number'	=> '邮编必须为数字',
		'store_create_input_zip_code'	=> '邮编格式不正确',
		'store_create_phone_rule'		=> '电话号码不能少于6位',
		'store_create_choose_store_class'	=> '请选择店铺分类',
		'store_create_choose_area'		=> '请选择地区',
		'store_create_upload_type'		=> '请上传格式为 jpg,jpeg,png,gif 的文件',
		'store_create_read_agreement'	=> '请阅读并同意开店协议',
		'store_create_card_no'			=> '身份证号',
		'store_create_input_true_card'	=> '请填写真实准确的身份证号',
		'store_create_store_name'		=> '店铺名称',
		'store_create_name_rule'		=> '请控制在20个字符以内',
		'store_create_store_class'		=> '店铺分类',
		'store_create_please_choose'	=> '请选择',
		'store_create_location'			=> '所在地区',
		'store_create_address'			=> '详细地址',
		'store_create_zipcode'			=> '邮政编码',
		'store_create_phone'			=> '联系电话',
		'store_create_input_phone'		=> '请输入联系电话',
		'store_create_upload_paper'		=> '上传身份证',
		'store_create_true_name_intro'	=> '什么是实名认证',
		'store_create_upload_rule'		=> '支持格式jpg,jpeg,png,gif，请保证图片清晰且文件大小不超过400KB',
		'store_create_upload_licence'	=> '上传执照',
		'store_create_true_store_intro'	=> '什么是实体店铺认证',
		'store_create_read_agreement1'	=> '我已认真阅读并同意',
		'store_create_store_agreement'	=> '开店协议',
		'store_create_read_agreement2'	=> '中的所有条款',
		'store_create_store_editor_multimedia'	=> '编辑器多媒体功能',
		'store_create_store_robbuy'		=> '抢购活动',
		'store_create_store_null'	    => '无',
		/**
		 * 保存店铺
		 */
		'store_save_owner_null'			=> '店主名称不能为空',
		'store_save_store_name_null'	=> '店铺名称不能为空',
		'store_save_store_class_null'	=> '店铺分类不能为空',
		'store_save_area_null'			=> '地区不能为空',
		'store_save_create_success'		=> '恭喜您，您的店铺创建成功。',
		'store_save_create_fail'		=> '创建店铺失败',
		'store_save_defaultalbumclass_name'	=> '默认相册',
		/**
		 * 卖家商品分类
		 */
		'store_goods_class_csc_null'	=> '导入的csv文件不能为空',
		'store_goods_class_new_class'	=> '新增分类',
		'store_goods_class_import'		=> '导入',
		'store_goods_class_export'		=> '导出',
		'store_goods_class_ensure_del'	=> '您确实要删除该分类吗',
		'store_goods_class_name'		=> '分类名称',
		'store_goods_class_sort'		=> '排序',
		'store_goods_class_add_sub'		=> '新增下级',
		'store_goods_class_no_record'	=> '没有商品分类',
		'store_goods_class_name_null'	=> '分类名称不能为空',
		'store_goods_class_input_int'	=> '需要输入数字',
		'store_goods_class_edit_class'	=> '编辑分类',
		'store_goods_class_add_class'	=> '添加分类',
		'store_goods_class_sup_class'	=> '上级分类',
		'store_goods_class_display_state'	=> '显示状态',
		'store_goods_class_submit'		=> '提交',
		'store_goods_class_problem'		=> '导出您店铺分类的数据',
		'store_goods_class_choose_file'	=> '请选择文件',
		'store_goods_class_choose_code'	=> '请选择文件编码',
		'store_goods_class_trans_tip'	=> '如果文件较大，建议您先把文件转换为 utf-8 编码，这样可以避免转换编码时耗费时间',
		'store_goods_class_file_format'	=> '文件格式',
		'store_goods_class_csv_file'	=> 'csv文件',
		'store_goods_class_csv_download'	=> 'CSV样例下载',
		'store_goods_class_download'	=> '点击下载',
		'store_goods_class_wrong'		=> '错误的操作，无此分类',
		'store_goods_class_modify_fail'	=> '店铺分类修改失败',
		'store_goods_class_add_fail'	=> '店铺分类添加失败',
		'store_goods_class_no_csv'		=> '请选择csv文件',
		/**
		 * 订单
		 */

		'store_order_order_sn'			=> '订单编号',
		'store_order_order_sn_search'	=> '输入您要查询的订单编号',
		'store_order_comp_exp'			=> '快递公司',
		'store_order_goods_detail'		=> '商品',
		'store_order_goods_single_price'	=> '单价（元）',
		'store_order_sell_back'			=> '售后',
		'store_order_order_stateop'		=> '状态与操作',
		'store_order_order_confirm'		=> '订单确认',
		'store_order_confirm_order'		=> '确认订单',
		'store_order_shipping_order'	=> '确认货到付款订单',
		'store_order_add_time'		=> '下单时间',
		'store_order_buyer'			=> '买家',
		'store_order_search'		=> '搜索',
		'store_order_cancel_order'	=> '取消订单',
		'store_order_show_deliver'	=> '查看物流',
		'store_order_buyer_info'	=> '联系信息',
		'store_order_receiver'		=> '姓名',
		'store_order_phone'			=> '电话',
		'store_order_mobile'		=> '手机',
		'store_order_email'			=> '电子邮件',
		'store_order_area'			=> '城市',
		'store_order_address'		=> '收货地址',
		'store_order_zip_code'		=> '邮政编码',
		'store_order_pay_method'	=> '支付方式',
		'store_order_sum'			=> '订单金额',
		'store_order_state'			=> '订单状态',
		'store_order_group'			=> '抢购',
		'store_order_evaluated'		=> '已评价',
		'store_order_received_price'	=> '收到货款',
		'store_order_modify_price'	=> '修改价格',
		'store_order_modify_price_gpriceerror'	=> '商品总价不能为空且必须为数字',
		'store_order_send'			=> '设置发货',
		'store_order_refund'		=> '退款',
		'store_buyer_confirm'		=> '退款待确认',
		'store_order_return'		=> '退货',
		'store_order_modify_no'		=> '修改单号',
		'store_order_view_order'	=> '订单详情',
		'store_order_complain'		=> '投诉',
		'store_order_no_result'		=> '暂时没有符合条件的订单',
		'store_order_ensure_cancel'	=> '您确实要取消该订单吗？',
		'store_order_cancel_reason'	=> '取消缘由',
		'store_order_lose_goods'	=> '无法备齐货物',
		'store_order_invalid_order'	=> '不是有效的订单',
		'store_order_buy_apply'		=> '买家主动要求',
		'store_order_other_reason'	=> '其他原因',
		'store_order_buyer_with'		=> '买&nbsp;家',
		'store_order_sn'				=> '订单号',
		'store_order_modify_rule'		=> '输入要修改的金额，只能为数字',
		'store_order_ensure_receive_fee'=> '您确定已经收到货款了吗',
		'store_order_handle_desc'		=> '操作备注',
		'store_order_shipping_no_null'	=> '物流单号不能为空',
		'store_order_input_shipping_no'	=> '请输入您的物流单号',
		'store_order_shipping_no'		=> '物流单号',
		'store_order_want_evaluate'		=> '我要评价',
		'store_show_order_detail'		=> '订单详情',
		'store_show_order_info'			=> '订单信息',
		'store_show_order_seller_info'	=> '卖家信息',
		'store_show_order_store_name'	=> '店铺名',
		'store_show_order_wangwang'		=> '旺旺',
		'store_show_order_goods_name'	=> '商品',
		'store_show_order_amount'		=> '数量',
		'store_show_order_price'			=> '单价(元)',
		'store_show_order_tp_fee'		=> '运费',
		'store_show_order_pay_message'	=> '支付信息',
		'store_show_order_pay_time'		=> '付款时间',
		'store_show_order_send_time'		=> '发货时间',
		'store_show_order_finish_time'	=> '完成时间',
		'store_show_order_shipping_info'	=> '物流信息',
		'store_show_order_receiver'		=> '收&nbsp;&nbsp;货&nbsp;&nbsp;人',
		'store_show_order_receiver_address'	=> '收货地址',
		'store_show_order_mobile'			=> '手机号码',
		'store_show_order_buyer_message'		=> '买家留言',
		'store_show_order_handle_history'	=> '操作历史',
		'store_show_system'					=> '系统',
		'store_show_order_at'				=> '于',
		'store_show_order_cur_state'		=> '订单当前状态',
		'store_show_order_next_state'		=> '下一状态',
		'store_show_order_reason'			=> '原因',
		'store_show_order_printorder'		=> '打印发货单',
		'store_show_order_shipping_han'		=> '含',
		'store_order_tip1'		=> '平台收款，确认收款由系统自动或管理员手动完成，卖家不能进行收款操作，管理员可以取消未付款的线下支付订单',
		'store_order_cancel_success'	=> '成功取消了订单',
		'store_order_edit_ship_success'	=> '成功修改了运费',
		'store_order_none_exist'	=> '该订单不存在',
		'store_order_edit_amount_fail'	    => '修改价格失败',
		'store_order_edit_amount_success'	=> '修改价格成功',
		/**
		 * 支付
		 */
		'store_payment_name'				=> '名称',
		'store_payment_intro'			=> '插件说明',
		'store_payment_enable'			=> '启用',
		'store_payment_yes'				=> '是',
		'store_payment_no'				=> '否',
		'store_payment_config'			=> '配置插件',
		'store_payment_ensure_uninstall'	=> '您确实要卸载该插件吗',
		'store_payment_uninstall'		=> '卸载',
		'store_payment_install'			=> '安装',
		'store_payment_not_exists'		=> '系统不存在该支付接口',
		'store_payment_add'				=> '配置支付方式',
		'store_payment_info'				=> '提示信息',
		'store_payment_display'			=> '用户支付时的提示信息',
		'store_payment_uninstall_fail'	=> '卸载失败',
		'store_payment_edit_not_null'	=> '不能为空',
		/**
		 * 广告管理
		 */
		'store_show_buy'				=> '购买广告',
		/**
		 * 导航
		 */
		'store_navigation_name_null'		=> '导航名称不能为空',
		'store_navigation_name_max'		=> '导航名称最多10个字',
		'store_navigation_del_fail'		=> '删除导航失败',
		'store_navigation_new'			=> '新增导航',
		'store_navigation_edit'			=> '编辑导航',
		'store_navigation_name'			=> '导航名称',
		'store_navigation_display'		=> '是否显示',
		'store_navigation_content'		=> '内容',
		'store_navigation_no_result'	=> '没有符合条件的导航',
		'store_navigation_url'		    => '导航URL地址',
		'store_navigation_url_tip'		=> '请填写包含http://的完整URL地址,如果填写此项则点击该导航会跳转到该链接',
		'store_navigation_new_open'		=> '新窗口打开',
		'store_navigation_new_open_yes'	=> '是',
		'store_navigation_new_open_no'	=> '否',

		/**
		 * 合作伙伴
		 */
		'store_partner_title_null'	=> '标题不能为空',
		'store_partner_wrong_href'	=> '链接格式不正确',
		'store_partner_add_fail'	=> '新增合作伙伴失败',
		'store_partner_del_fail'	=> '删除合作伙伴失败',
		'store_partner_add'			=> '新增合作伙伴',
		'store_partner_edit'		=> '编辑内容',
		'store_partner_title'		=> '标题',
		'store_partner_href'		=> '链接',
		'store_partner_href_tip'	=> '数字需大于零，越小越靠前',
		'store_partner_sign'		=> '标识',
		'store_partner_pic_upload'	=> '图片上传',
		'store_partner_href_null'	=> '链接不能为空',
		'store_partner_no_result'	=> '没有符合条件的合作伙伴',
		'store_partner_des_one'		=> '填写链接地址，您可以在',
		'store_partner_des_two'		=> '中复制链接。',
		/**
		 * 店铺设置
		 */
		'store_setting_name_null'			=> '店铺名称不能为空',
		'store_setting_wrong_uri'			=> '二级域名长度不符合要求',
		'store_setting_exists_uri'			=> '该二级域名已存在,请更换其它域名',
		'store_setting_invalid_uri'			=> '该二级域名为系统禁止域名,请更换其它域名',
		'store_setting_lack_uri'			=> '该二级域名不符合域名命名规范,请不要使用特殊字符',
		'store_create_store_name_hint'		=> '店铺名称请控制长度不超过20字',
		'store_create_store_zy_hint'		=> '关键字最多可输入50字，请用","进行分隔，例如”男装,女装,童装”',

		'store_setting_change_label'		=> '店铺logo',
		'store_setting_label_tip'			=> '此处理店铺页logo；<br/><span style="color:orange;">建议使用宽200像素-高60像素内的GIF或PNG透明图片；点击下方"提交"按钮后生效。</span>',
		'store_setting_change_avatar'		=> '店铺头像',
		'store_setting_sign_tip'				=> '此处为店铺方形头像；<br/><span style="color:orange;">建议使用宽100像素*高100像素内的方型图片；点击下方"提交"按钮后生效。</span>',
		'store_setting_change_banner'		=> '店铺条幅',
		'store_setting_banner_tip'			=> '此处为店铺页banner导航；<br/><span style="color:orange;">建议使用宽1200像素*高130像素的图片；点击下方"提交"按钮后生效。</span>',
		'store_setting_uri'					=> '二级域名',
		'store_setting_uri_tip'				=> '可留空，域名长度应为',
		'store_setting_domain_times'			=> '已修改次数为',
		'store_setting_domain_times_max'		=> '最多可修改次数为',
		'store_setting_domain_notice'		=> '注意！设置后将不能修改',
		'store_setting_domain_tip'			=> '不可修改',
		'store_setting_domain_valid'			=> '字母、数字、下划线、中划线为有效字符',
		'store_setting_domain_rangelength'   => '二级域名长度为 {0} 到 {1} 个字符之间',
		'store_setting_my_homepage'			=> '我的店铺首页',
		'store_setting_grade'				=> '店铺等级',
		'store_setting_upgrade'				=> '马上升级店铺等级',
		'store_setting_location_tip'			=> '不必重复填写所在地区',
		'store_setting_contact'				=> '联系',
		'store_setting_wangwang'				=> '阿里旺旺',
		'store_setting_intro'				=> '店铺简介',
		'store_setting_customer_service'		=> '在线客服',
		'store_setting_username'				=> '用户名',
		'store_setting_password'				=> '密码',
		'store_setting_checking'				=> '审核中...',
		'store_setting_apply'				=> '申请开通',
		'store_setting_applying'				=> '申请中...',
		'store_setting_apply_success'		=> '在线客服申请成功,请等待管理员审核开通',
		'store_setting_apply_error'			=> '网络忙,在线客服申请失败,请稍后再试',
		'store_setting_seo_keywords'			=> 'SEO关键字',
		'store_setting_store_zy'				=> '主营商品',
		'store_setting_seo_description'		=> 'SEO店铺描述',
		'store_setting_seo_keywords_help'	=> '用于店铺搜索引擎的优化，关键字之间请用英文逗号分隔',
		'store_setting_seo_description_help'	=> '用于店铺搜索引擎的优化，建议120字以内',
		'store_settine_browse'				=> '浏览...',
		'store_setting_store_url'			=> '当前店铺首页连接：',
		/**
		 * 升级店铺
		 */
		'store_upgrade_submit'		=> '店铺等级申请已提交给管理员，请您等待审核',
		'store_upgrade_submit_fail'	=> '店铺等级提交失败，请重新操作',
		'store_upgrade_cur_grade'	=> '店铺当前等级',
		//'store_upgrade_tip'			=> '如果店铺等级需要审核，升级后在待审核这段期间，店铺部分功能不能正常使用，您确定要升级吗?',
		'store_upgrade_tip'			=> '您确定要升级吗?',
		'store_upgrade_now'			=> '马上申请',
		'store_upgrade_store_error'			=> '店铺信息错误',
		'store_upgrade_gradesort_error'		=> '等级错误,升级级别应高于当前级别',
		'store_upgrade_exist_error'			=> '店铺等级升级申请已经提交，正在审核中，请耐心等待',
		'store_upgrade_exist_tip_1'			=> '店铺等级升级为',
		'store_upgrade_exist_tip_2'			=> '的申请，正在审核中...',
		/**
		 * 主题
		 */
		'store_theme_load_preview_fail'	=> '加载预览失败',
		'store_theme_effect_preview'		=> '效果预览',
		'store_theme_loading1'			=> '加载中',
		'store_theme_use'				=> '使用',
		'store_theme_loading2'			=> '载入中',
		'store_theme_congfig_success'	=> '设置成功',
		'store_theme_error'				=> '错误',
		'store_theme_homepage'			=> '店铺首页',
		'store_theme_tpl_name'			=> '店铺模版名称',
		'store_theme_style_name'			=> '店铺风格名称',
		'store_theme_valid'				=> '可用主题',
		'store_theme_tpl_name1'			=> '模版名称',
		'store_theme_style_name1'		=> '风格名称',
		'store_theme_preview'			=> '预览',
		'store_theme_congfig_fail'		=> '设置失败',
		/**
		 * 活动
		 */
		'store_activity_year'		=> '年',
		'store_activity_month'		=> '月',
		'store_activity_day'			=> '日',
		'store_activity_theme'		=> '活动主题',
		'store_activity_intro'		=> '活动说明',
		'store_activity_start_time'	=> '开始时间',
		'store_activity_end_time'	=> '结束时间',
		'store_activity_long_time'	=> '长期活动',
		'store_activity_type'		=> '活动类型',
		'store_activity_goods'		=> '商品',
		'store_activity_group'		=> '抢购',
		'store_activity_join'		=> '参与活动',
		'store_activity_no_record'	=> '没有符合条件的活动',
		'store_activity_goods_name'	=> '商品名称',
		'store_activity_goods_class'	=> '商品类别',
		'store_activity_goods_brand'	=> '商品品牌',
		'store_activity_pass'		=> '已通过',
		'store_activity_audit'		=> '审核中',
		'store_activity_refuse'		=> '未通过',
		'store_activity_join_tip'	=> '您尚未参与本活动,可以在本页下方进行选择',
		'store_activity_group_name'	=> '抢购名称',
		'store_activity_group_intro'	=> '抢购介绍',
		'store_activity_class'		=> '类别',
		'store_activity_choose'		=> '请选择',
		'store_activity_brand'		=> '品牌',
		'store_activity_name'		=> '名称',
		'store_activity_search'		=> '查找',
		'store_activity_goods_applied'	=> '您的商品已经全部申请完毕',
		'store_activity_none_goods'		=> '您尚未发布任何商品',
		'store_activity_group_applied'	=> '您的抢购已经全部申请完毕',
		'store_activity_none_group'		=> '您尚未发布任何抢购',
		'store_activity_join_now'		=> '选择完毕,参与活动',
		'store_activity_choose_goods'	=> '请手动选择内容后再保存',
		'store_activity_not_exists'		=> '该活动并不存在',
		'store_activity_unknown_type'	=> '该活动类型不明',
		'store_activity_id_is'			=> '编号为',
		'store_activity_goods_not_exists'	=> '的商品并不存在',
		'store_activity_group_not_exists'	=> '的抢购并不存在',
		'store_activity_submitted'			=> '参与申请已提交',
		'store_activity_info_title'			=> '活动信息',
		'store_activity_goods_tip'			=> '活动商品如下',
		'store_activity_confirmstatus'		=> '审核状态',
		'store_activity_choosegoods'		=> '选择商品',
		/**
		 * ajax修改商品分类
		 */
		'store_goods_class_ajax_update_fail'	=> '更新数据库失败',
		/**
		 * 水印管理
		 */
		'store_watermark_pic'		=> '水印图片：',
		'store_watermark_del'		=> '删除',
		'store_watermark_del_pic'		=> '删除水印',
		'store_watermark_choose_pic'		=> '请选择水印图片',
		'store_watermark_pic_quality'		=> '图片质量：',
		'store_watermark_pic_pos'		=> '图片位置：',
		'store_watermark_choose_pos'		=> '选择水印图片放置位置',
		'store_watermark_pic_pos1'		=> '左上',
		'store_watermark_pic_pos2'		=> '正上',
		'store_watermark_pic_pos3'		=> '右上',
		'store_watermark_pic_pos4'		=> '左中',
		'store_watermark_pic_pos5'		=> '中间',
		'store_watermark_pic_pos6'		=> '右中',
		'store_watermark_pic_pos7'		=> '左下',
		'store_watermark_pic_pos8'		=> '中下',
		'store_watermark_pic_pos9'		=> '右下',
		'store_watermark_transition'		=> '融合度：',
		'store_watermark_transition_notice'		=> '水印图片与原图片的融合度',
		'store_watermark_text'		=> '水印文字：',
		'store_watermark_text_notice'		=> '水印文字',
		'store_watermark_text_size'		=> '文字大小：',
		'store_watermark_text_size_notice'		=> '设置水印文字大小',
		'store_watermark_text_angle'		=> '文字角度：',
		'store_watermark_text_angle_notice'		=> '水印文字角度,尽量不要更改',
		'store_watermark_text_pos'		=> '文字位置：',
		'store_watermark_text_pos_notice'		=> '选择水印文字放置位置',
		'store_watermark_text_pos1'		=> '左上',
		'store_watermark_text_pos2'		=> '正上',
		'store_watermark_text_pos3'		=> '右上',
		'store_watermark_text_pos4'		=> '左中',
		'store_watermark_text_pos5'		=> '中间',
		'store_watermark_text_pos6'		=> '右中',
		'store_watermark_text_pos7'		=> '左下',
		'store_watermark_text_pos8'		=> '中下',
		'store_watermark_text_pos9'		=> '右下',
		'store_watermark_text_font'		=> '文字字体：',
		'store_watermark_text_font_notice'		=> '水印文字的字体',
		'store_watermark_text_color'		=> '文字颜色：',
		'store_watermark_text_color_notice'		=> '水印字体的颜色值',
		'store_watermark_is_open'		=> '是否开启：',
		'store_watermark_is_open_notice'		=> '是否开启水印',
		'store_watermark_is_open1'		=> '开启',
		'store_watermark_is_open0'		=> '关闭',
		'store_watermark_submit'		=> '提交',
		'store_watermark_del_pic_confirm'		=> '确定删除水印图片?',
		'store_watermark_pic_quality_null'		=> '水印图片质量不能为空',
		'store_watermark_pic_quality_number'		=> '水印图片质量必须为数字',
		'store_watermark_pic_quality_min'		=> '水印图片质量在 0-100 之间',
		'store_watermark_pic_quality_max'		=> '水印图片质量在 0-100 之间',
		'store_watermark_transition_null'		=> '水印图片融合度不能为空',
		'store_watermark_transition_number'		=> '水印图片融合度必须为数字',
		'store_watermark_transition_min'		=> '水印图片融合度在 0-100 之间',
		'store_watermark_transition_max'		=> '水印图片融合度在 0-100 之间',
		'store_watermark_text_size_null'		=> '水印文字大小不能为空',
		'store_watermark_text_size_number'		=> '水印文字大小必须为数字',
		'store_watermark_text_color_null'		=> '水印字体颜色不能为空',
		'store_watermark_text_color_max'		=> '字体颜色值格式不正确',
		'store_watermark_congfig_success'		=> '设置成功',
		'store_watermark_congfig_fail'		=> '设置失败',
		'store_watermark_congfig_notice'		=> '如果开启水印,必须设置水印图片或者水印文字',
		'store_watermark_browse'				=> '浏览...',
		/**
		 * 优惠券管理
		 */
		'store_coupon_name'		=> '优惠券名称',
		'store_coupon_period'	=> '有效期：',
		'store_coupon_add'		=> '新增优惠券',
		'store_coupon_pic'		=> '优惠券图片',
		'store_coupon_price'		=> '优惠金额',
		'store_coupon_lifetime'	=> '使用期限',
		'store_coupon_state'		=> '上架',
		'store_coupon_no_result'		=> '没有符合条件的记录',
		'store_coupon_null_class'		=> '总后台管理员新增优惠券分类后方可添加优惠券',
		'store_coupon_name_null'		=> '优惠券名称不能为空',
		'store_coupon_price_error'		=> '优惠金额错误',
		'store_coupon_price_min'		=> '最小金额为1',
		'store_coupon_start_time_null'		=> '优惠券开始日期不能为空',
		'store_coupon_end_time_null'		=> '优惠券结束日期不能为空',
		'store_coupon_update_success'		=> '更新优惠券成功',
		'store_coupon_update_fail'		=> '更新优惠券失败',
		'store_coupon_add_success'		=> '增加优惠券成功',
		'store_coupon_add_fail'		=> '增加优惠券失败',
		'store_coupon_del_success'		=> '删除成功',
		'store_coupon_del_fail'		=> '删除失败',
		'store_coupon_time_error'		=> '有效期条件错误',
		'store_coupon_edit'		=> '修改优惠券',
		'store_coupon_class'		=> '优惠券分类',
		'store_coupon_to'		=> '至',
		'store_coupon_notice'		=> '使用条件',
		'store_coupon_coupon_pic_notice'		=> '填写链接地址，建议图片的比例为：300×90',
		'store_coupon_coupon_pic_notice_one' => '可以在',
		'store_coupon_coupon_pic_notice_two' => '中，复制图片链接。',
		'store_coupon_pic_null'		=> '请上传优惠券图片',
		'store_coupon_pic_format_error' => '格式错误，必须填写链接地址',
		'store_coupon_allow'		=> '审核状态',
		'store_coupon_allow_state'		=> '待审核',
		'store_coupon_allow_yes'		=> '已通过',
		'store_coupon_allow_no'		=> '未通过',
		'store_coupon_allow_remark'		=> '审核备注',
		'store_coupon_allow_notice'		=> '注意：提交后需要重新审核',
		/**
		 * 优惠券打印
		 */
		'store_coupon_print'		=> '优惠券打印',
		'store_coupon_choose_print'		=> '你选择打印',
		'store_coupon_print_notice'		=> '张优惠券，预计将打印在1张A4纸上。',
		'store_coupon_print_coupon'		=> '打印优惠券',
		'store_coupon_id_error'		=> '优惠券ID错误',
		'store_coupon_num_error'		=> '打印数量错误',
		'store_coupon_error'		=> '该优惠券不存在',

		/**
		 * 幻灯片
		 */
		'store_slide_upload_fail'		=> '上传失败',
		'store_slide_image_upload'		=> '图片上传',
		'store_slide_description_one'	=> '最多可上传5张幻灯片图片。',
		'store_slide_description_two'	=> '支持jpg、jpeg、gif、png格式上传，建议图片宽度940px、高度在300px到440px之间、大小%.2fM以内的图片。提交2~5张图片可以进行幻灯片播放，一张图片没有幻灯片播放效果。',
		'store_slide_description_three'	=> '操作完成以后，按“提交”按钮，可以在当前页面进行幻灯片展示。',
		'store_slide_description_fore'	=> '跳转链接必须带有<b style="color:red;">“http://”</b>',
		'store_slide_submit'				=> '提交',
		'store_slide_image_url'			=> '跳转URL...',

		/**
		 * 店铺印章
		 */
		'store_printsetup_stampimg'			=> '印章图片',
		'store_printsetup_tip2'			=> '印章图片将出现在打印订单的右下角位置，请选择120x120px大小<br/>透明GIF/PNG格式图片上传作为您店铺的电子印章使用。',
		'store_printsetup_tip1'			=> '打印备注信息将出现在打印订单的下方位置，用于注明店铺简介或发货、<br/>退换货相关规则等；<span class="orange">内容不要超过100字。</span>',
		'store_printsetup_desc_error'	=> '备注信息长度为1到100个字符之间',
		'store_printsetup_desc'	=> '备注信息',

		'pay_bank_user'			=> '汇款人姓名',
		'pay_bank_bank'			=> '汇入银行',
		'pay_bank_account'		=> '汇款入账号',
		'pay_bank_num'			=> '汇款金额',
		'pay_bank_date'			=> '汇款日期',
		'pay_bank_extend'		=> '其它',
		'pay_bank_order'			=> '汇款单号',

		/**
		 * 客服中心
		 */
		'store_callcenter_notes'		=> '客服信息需要填写完整，不完整信息将不会被保存。',
		'store_callcenter_presales_service'	=> '售前客服',
		'store_callcenter_aftersales_service'=> '售后客服',
		'store_callcenter_service_name'		=> '客服名称',
		'store_callcenter_service_tool'		=> '客服工具',
		'store_callcenter_service_number'	=> '客服账号',
		'store_callcenter_presales'			=> '售前',
		'store_callcenter_aftersales'		=> '售后',
		'store_callcenter_name_title'		=> '使用默认值或修改您自己的客服名称',
		'store_callcenter_tool_title'		=> '请选择即时通讯工具类型',
		'store_callcenter_number_title'		=> '根据您所选择的即时通讯工具类型输入正确的用户账号',
		'store_callcenter_please_choose'		=> '-请选择-',
		'store_callcenter_wangwang'			=> '旺旺',
		'store_callcenter_add_service'		=> '添加客服',
		'store_callcenter_working_time'		=> '工作时间',
		'store_callcenter_working_time_title'=> '例：（工作时间 AM 10:00 - PM 18:00）',

		'wt_cut'				=> '裁剪',
);