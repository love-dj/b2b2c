<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 共有语言
 */

/**
 * index
 */
'store_goods_index_store_close'	 		=> '您的店铺已关闭',
'store_goods_index_taobao_import'		=> '淘宝助理导入',
'store_goods_index_new_goods'			=> '新增商品',
'store_goods_index_add_goods'			=> '发布新商品',
'store_goods_index_add_time'				=> '发布时间',
'store_goods_index_store_goods_class'	=> '本店分类',
'store_goods_index_state'	 			=> '状态',
'store_goods_index_show'	 				=> '上架',
'store_goods_index_unshow'	 			=> '下架',
'store_goods_index_recommend'	 		=> '推荐',
'store_goods_index_lock'	 				=> '禁售',
'store_goods_index_unlock'	 			=> '否',
'store_goods_index_close_reason'			=> '违规禁售原因',
'store_goods_close_reason_des'			=> '分类或规格信息不符',
'store_goods_index_sort'					=> '排序',
'store_goods_index_goods_name'	 		=> '商品名称',
'store_goods_index_goods_name_help'	 	=> '商品标题名称长度至少3个字符，最长50个汉字',
'store_goods_index_goods_class'	 		=> '商品分类',
'store_goods_index_brand'	 			=> '品牌',
'store_goods_index_price'	 			=> '价格',
'store_goods_index_stock'				=> '库存',
'store_goods_index_goods_limit'			=> '您已经达到了添加商品的上限',
'store_goods_index_goods_limit1'			=> '个，如果您想继续增加商品，请到“店铺设置”升级店铺等级',
'store_goods_index_pic_limit'			=> '您已经达到了图片空间的上限',
'store_goods_index_pic_limit1'			=> 'M，如果您想继续增加商品，请到“店铺设置”升级店铺等级',
'store_goods_index_time_limit'			=> '您已经达到店铺使用期限，如果您想继续增加商品，请到“店铺设置”升级店铺等级',
'store_goods_index_no_pay_type'			=> '平台还未设置支付方式，请及时与平台联系',
'store_goods_index_color'				=> '颜色',
'store_goods_index_size'					=> '尺码',
'store_goods_index_left'					=> '排序向前',
'store_goods_index_right'				=> '排序向后',
'store_goods_index_face'					=> '设为封面',
'store_goods_index_insert_editor'		=> '插入编辑器',
'store_goods_index_goods_class_null'		=> '商品分类不能为空',
'store_goods_index_goods_class_error'	=> '选择商品分类（必须选到最后一级）',
'store_goods_index_goods_name_null'		=> '商品名称不能为空',
'store_goods_index_store_price_null'		=> '商品价格不能为空',
'store_goods_index_store_price_error'	=> '商品价格只能是数字',
'store_goods_index_store_price_interval'	=> '商品价格必须是0.01~9999999之间的数字',
'store_goods_index_goods_stock_null'		=> '商品库存不能为空',
'store_goods_index_goods_stock_error'	=> '库存只能填写数字',
'store_goods_index_edit_goods_spec'		=> '编辑商品规格',
'store_goods_index_goods_spec_tip'		=> '您最多可以添加两种规格（如：颜色和尺码）规格名称可以自定义<br/>两种规格必须填写完整',
'store_goods_index_no'					=> '货号',
'store_goods_index_new_goods_spec'		=> '添加新的规格属性',
'store_goods_index_save_spec'			=> '保存规格',
'store_goods_index_new_class'			=> '新增分类',
'store_goods_index_belong_multiple_store_class'	=> '商品可以从属于店铺的多个分类之下，店铺分类可以由 "商家中心 -> 店铺 -> 店铺分类" 中自定义',
'store_goods_index_goods_base_info'		=> '商品基本信息',
'store_goods_index_goods_detail_info'	=> '商品详情描述',
'store_goods_index_goods_transport'		=> '商品物流信息',
'store_goods_index_goods_szd'			=> '所在地',
'store_goods_index_use_tpl'				=> '选择售卖区域',
'store_goods_index_select_tpl'			=> '选择售卖区域',
'store_goods_index_goods_other_info'		=> '其他信息',
'store_goods_index_upload_goods_pic'		=> '上传商品图片',
'store_goods_index_remote_url'			=> '远程地址',
'store_goods_index_remote_tip'			=> '支持JPEG和静态的GIF格式图片，不支持GIF动画图片，上传图片大小不能超过2M.浏览文件时可以按住ctrl或shift键多选',
'store_goods_index_goods_brand'			=> '商品品牌',
'store_goods_index_multiple_tag'			=> '多个Tag标签请用半角逗号 "," 隔开',
'store_goods_index_store_price'			=> '商品价格',
'store_goods_index_store_price_help'		=> '价格必须是0.01~9999999之间的数字',
'store_goods_index_goods_stock'			=> '商品库存',
'store_goods_index_goods_stock_checking'	=> '商铺库存数量必须为0~999999999之间的整数',
'store_goods_index_goods_stock_help'		=> '商铺库存数量必须为0~999999999之间的整数<br/>若启用了库存配置，则系统自动计算商品的总数，此处无需卖家填写',
'store_goods_index_goods_pyprice_null'	=> '缺少平邮价格',
'store_goods_index_goods_kdprice_null'	=> '缺少快递价格',
'store_goods_index_goods_emsprice_error'	=> 'EMS价格格式错误',
'store_goods_index_goods_select_tpl'		=> '请选择要使用的运费模板',
'store_goods_index_goods_weight_tag'     => '单位：千克(Kg)',
'store_goods_index_goods_transfee_charge'=> '运费',
'store_goods_index_goods_transfee_charge_seller'=> '卖家承担运费',
'store_goods_index_goods_transfee_charge_buyer'=> '买家承担运费',
'store_goods_index_goods_no'				=> '商家货号',
'store_goods_index_goods_no_help'		=> '商家货号是指商家管理商品的编号，买家不可见<br/>最多可输入20个字符，支持输入中文、字母、数字、_、/、-和小数点',
'srore_goods_index_goods_stock_set'		=> '库存配置',
'store_goods_index_goods_spec'			=> '商品规格',
'store_goods_index_open_spec'			=> '开启规格',
'store_goods_index_spec_tip'				=> '您最多可以添加两种商品规格（如：颜色，尺码）如商品没有规格则不用添加',
'store_goods_index_edit_spec'			=> '编辑规格',
'store_goods_index_close_spec'			=> '关闭规格',
'store_goods_index_goods_attr'			=> '商品属性',
'store_goods_index_goods_show'			=> '商品发布',
'store_goods_index_immediately_sales'	=> '立即发布',
'store_goods_index_in_warehouse'			=> '放入仓库',
'store_goods_index_goods_recommend'		=> '商品推荐',
'store_goods_index_recommend_tip'		=> '被推荐的商品会显示在店铺首页',
'store_goods_index_goods_desc'			=> '商品描述',
'store_goods_index_upload_pic'			=> '上传图片',
'store_goods_index_spec'					=> '规格',
'store_goods_index_edit_goods'			=> '编辑商品',
'store_goods_index_add_sclasserror'		=> '该分类已经选择,请选择其他分类',
'store_goods_index_goods_add_success'	=> '商品添加成功',
'store_goods_index_goods_add_fail'		=> '商品添加失败',
'store_goods_index_goods_edit_success'	=> '商品编辑成功',
'store_goods_index_goods_edit_fail'		=> '商品编辑失败',
'store_goods_index_goods_del_success'	=> '商品删除成功',
'store_goods_index_goods_del_fail'		=> '商品删除失败',
'store_goods_index_goods_unshow_success'	=> '商品下架成功',
'store_goods_index_goods_unshow_fail'	=> '商品下架失败',
'store_goods_index_goods_show_success'	=> '商品上架成功',
'store_goods_index_goods_show_fail'		=> '商品上架失败',
'store_goods_index_goods_seo_keywords'		    => 'SEO关键字<br/>(keywords)',
'store_goods_index_goods_seo_description'		=> 'SEO描述<br/>(description)',
'store_goods_index_goods_seo_keywords_help'		=> 'SEO关键字 (keywords) 出现在商品详细页面头部的 Meta 标签中，<br/>用于记录本页面商品的关键字，多个关键字间请用半角逗号 "," 隔开',
'store_goods_index_goods_seo_description_help'   => 'SEO描述 (description) 出现在商品详细页面头部的 Meta 标签中，<br/>用于记录本页面商品内容的概要与描述，建议120字以内',
'store_goods_index_goods_del_confirm'			=> '您确实要删除该图片吗?',
'store_goods_index_goods_not_add'				=> '不能再添加图片',
'store_goods_index_goods_the_same'				=> '不能再重复图片',
'store_goods_index_default_album'				=> '默认相册',
'store_goods_index_flow_chart_step1'				=> '选择商品所在分类',
'store_goods_index_flow_chart_step2'				=> '填写商品详细信息',
'store_goods_index_flow_chart_step3'				=> '商品发布成功',
'store_goods_index_again_choose_category1'       => '您选择的分类不存在，或没有选择到最后一级，请重新选择分类。',
'store_goods_index_again_choose_category2'       => '您的店铺没有绑定该分类，请重新选择分类。',
'store_goods_add_next'							=> '下一步',
'store_goods_step2_image'						=> '图片（无图片可不填）',
'store_goods_step2_start_time'					=> '发布时间',
'store_goods_step2_hour'							=> '时',
'store_goods_step2_minute'						=> '分',
'store_goods_step2_goods_form'					=> '商品类型',
'store_goods_step2_brand_new'					=> '全新',
'store_goods_step2_second_hand'					=> '二手',
'store_goods_step2_exist_image'					=> '已有图片',
'store_goods_step2_exist_image_null'				=> '当前无图片',
'store_goods_step2_spec_img_help'				=> '支持jpg、jpeg、gif、png格式图片。<br />建议上传尺寸310x310、大小%.2fM内的图片。<br />商品详细页选中颜色图片后，颜色图片将会在商品展示图区域展示。',
'store_goods_step2_description_one'				=> '上传商品默认主图，如多规格值时将默认使用该图或分规格上传各规格主图；支持jpg、gif、png格式上传或从图片空间中选择，建议使用<font color="red">尺寸800x800像素以上、大小不超过1M的正方形图片</font>，上传后的图片将会自动保存在图片空间的默认分类中。',

'store_goods_album_climit'						=> '您上传图片数达到上限，请升级您的店铺或跟管理员联系',
/**
 * 商品发布第一步
 */
'store_goods_step1_search_category'				=> '分类搜索：',
'store_goods_step1_search_input_text'			=> '请输入商品名称或分类属性名称',
'store_goods_step1_search'						=> '搜索',
'store_goods_step1_return_choose_category'		=> '返回商品分类选择',
'store_goods_step1_search_null'					=> '没有找到相关的商品分类。',
'store_goods_step1_searching'					=> '搜索中...',
'store_goods_step1_loading'						=> '加载中...',
'store_goods_step1_choose_common_category'		=> '您常用的商品分类：',
'store_goods_step1_please_select'				=> '请选择',
'store_goods_step1_no_common_category'			=> '您还没有添加过常用的分类',
'store_goods_step1_please_choose_category'		=> '请选择商品类别',
'store_goods_step1_current_choose_category'		=> '您当前选择的商品类别是',
'store_goods_step1_add_common_category'			=> '[添加到常用分类]',
'store_goods_step1_max_20'						=> '只能添加20个常用分类，请清理不常用或重复的分类。',
'store_goods_step1_ajax_add_class'				=> '添加常用分类成功',

/**
 * 商品发布第三步
 */
'store_goods_step3_goods_release_success'		=> '恭喜您，商品发布成功！',
'store_goods_step3_viewed_product'				=> '去店铺查看商品详情',
'store_goods_step3_edit_product'					=> '重新编辑刚发布的商品',
'store_goods_step3_more_actions'					=> '您还可以:',
'store_goods_step3_continue'						=> '继续',
'store_goods_step3_release_new_goods'			=> '发布新商品',
'store_goods_step3_access'						=> '进入',
'store_goods_step3_manage'						=> '管理',
'store_goods_step3_choose_product_add'			=> '选择商品添加申请',
'store_goods_step3_participation'				=> '参与商城的',
'store_goods_step3_special_activities'			=> '专题活动',

/**
 * 品牌
 */
'store_goods_brand_apply'				=> '品牌申请',
'store_goods_brand_name'					=> '品牌名称',
'store_goods_brand_my_applied'			=> '我申请的',
'store_goods_brand_icon'					=> '品牌图标',
'store_goods_brand_belong_class'			=> '所属类别',
'store_goods_brand_no_record'			=> '没有符合条件的品牌',
'store_goods_brand_input_name'			=> '请输入品牌名称',
'store_goods_brand_name_error'			=> '品牌名称不能超过100个字符',
'store_goods_brand_icon_null'			=> '请上传品牌图标',
'store_goods_brand_edit'					=> '编辑品牌',
'store_goods_brand_class'				=> '品牌类别',
'store_goods_brand_pic_upload'			=> '图片上传',
'store_goods_brand_upload_tip'			=> '建议上传大小为88x44的品牌图片。<br />申请品牌的目的是方便买家通过品牌索引页查找商品，申请时请填写品牌所属的类别，方便站长归类。在站长审核前，您可以编辑或撤销申请。',
'store_goods_brand_name_null'			=> '品牌名称不能为空',
'store_goods_brand_apply_success'		=> '保存成功，请等待系统审核',
'store_goods_brand_choose_del_brand'		=> '请选择要删除的内容!',
'store_goods_brand_browse'				=> '浏览...',
/**
 * 图片上传
 */
'store_goods_upload_pic_limit'			=> '您已经达到了图片空间的上限',
'store_goods_upload_pic_limit1'			=> 'M，如果您想继续增加商品，请到“店铺设置”升级店铺等级',
'store_goods_upload_fail'				=> '上传失败',
'store_goods_upload_upload'				=> '上传',
'store_goods_upload_normal'				=> '普通上传',
'store_goods_upload_del_fail'			=> '删除图片失败',
'store_goods_img_upload'					=> '图片上传',
/**
 * 相册
 */
'store_goods_album_goods_pic'			=> '商品图片',
'store_goods_album_select_from_album'	=> '从用户相册选择',
'store_goods_album_users'				=> '用户相册',
'store_goods_album_all_photo'			=> '全部图片',
'store_goods_album_insert_users_photo'	=> '插入相册图片',
/**
 * ajax
 */
'store_goods_ajax_find_none_spec'		=> '未找到商品规格',
'store_goods_ajax_update_fail'			=> '更新数据库失败',
/**
 * 淘宝导入
 */
'store_goods_import_choose_file'		=> '请选择要上传csv的文件',
'store_goods_import_unknown_file'	=> '文件来源不明',
'store_goods_import_wrong_type'		=> '文件类型必须为csv,您所上传的文件类型为:',
'store_goods_import_size_limit'		=> '文件大小必须为'.ini_get('upload_max_filesize').'以内',
'store_goods_import_wrong_class'		=> '请选择商品分类（必须选到最后一级）',
'store_goods_import_wrong_class1'	=> '该商品分类不可用，请重新选择商品分类（必须选到最后一级）',
'store_goods_import_wrong_class2'	=> '必须选到最后一级',
'store_goods_import_wrong_column'	=> '文件内字段与系统要求的字段不符,请详细阅读导入说明',
'store_goods_import_choose'			=> '请选择...',
'store_goods_import_step1'			=> '第一步：导入CSV文件',
'store_goods_import_choose_csv'		=> '请选择文件：',
'store_goods_import_title_csv'		=> '导入程序默认从第二行执行导入，请保留CSV文件第一行的标题行，在'.ini_get('upload_max_filesize').'以内',
'store_goods_import_goods_class'		=> '商品分类：',
'store_goods_import_store_goods_class'	=> '本店分类：',
'store_goods_import_new_class'			=> '新增分类',
'store_goods_import_belong_multiple_store_class'	=> '可以从属于多个本店分类',
'store_goods_import_unicode'			=> '字符编码：',
'store_goods_import_file_type'		=> '文件格式：',
'store_goods_import_file_csv'		=> 'csv文件',
'store_goods_import_desc'			=> '导入说明：',
'store_goods_import_csv_desc'		=> '1.如果修改CSV文件请务必使用微软excel软件，且必须保证第一行表头名称含有如下项目: 
宝贝名称、宝贝类目、新旧程度、宝贝价格、宝贝数量、有效期、运费承担、平邮、EMS、快递、橱窗推荐、宝贝描述、新图片。<br/>
2.如果因为淘宝助理版本差异表头名称有出入，请先修改成上述的名称方可导入，不区分全新、二手、闲置等新旧程度，导入后商品类型都是全新。<br/>
3.如果CSV文件超过'.ini_get('upload_max_filesize').'请通过excel软件编辑拆成多个文件进行导入。<br/>
4.每个商品最多支持导入5张图片。',
'store_goods_import_submit'			=> '导入',
'store_goods_import_step2'			=> '第二步：上传商品图片',
'store_goods_import_tbi_desc'		=> '请上传与csv文件同级的images目录(或与csv文件同名的目录)内的tbi文件',
'store_goods_import_upload_complete' => '上传完毕',
'store_goods_import_doing' 			=> '正在导入...',
'store_goods_import_step3'			=> '第三步：整理数据',
'store_goods_import_remind'			=> '前两步完成后才可进行数据整理，确认整理数据吗',
'store_goods_import_remind2'			=> '（如果图片分多次上传，请在所有图片上传完成后整理）',
'store_goods_import_pack'			=> '整理数据',
'store_goods_pack_wrong1'			=> '请先导入CSV文件',
'store_goods_pack_wrong2'			=> '请导入正确的CSV文件',
'store_goods_pack_success'			=> '数据整理成功',
'store_goods_import_end'				=> '，最后',
'store_goods_import_products_no_import'	=> '件商品没有导入',
'store_goods_import_area'			=> '所在地：',

/*淘宝文件导入*/
'store_goods_import_upload_album' => '导入相册选择',

/**
 * ajax修改商品标题
 */
'store_goods_title_change_tip'		=> '单击修改商品标题名称，长度<br/>至少3个字符，最长50个汉字',

/**
 * ajax修改商品库存
 */
'store_goods_stock_change_stock'		=> '修改库存',
'store_goods_stock_change_tip'		=> '单击修改库存',
'store_goods_stock_stock_sum'		=> '库存总数',
'store_goods_stock_change_more_stock'=> '修改更多的库存信息',
'store_goods_stock_input_error'		=> '请填写不小于零的数字!',

/**
 * ajax修改商品库存
 */
'store_goods_price_change_price'		=> '修改价格',
'store_goods_price_change_tip'		=> '单击修改价格',
'store_goods_price_change_more_price'=> '修改更多价格信息',
'store_goods_price_input_error'		=> '请填写正确的价格！',

/**
 * ajax修改商品推荐
 */
'store_goods_commend_change_tip'		=> '选择是否作为店铺推荐商品',

);
