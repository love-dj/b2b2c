<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 积分礼品功能公用
 */
'admin_pointprodp'	 		=> '礼品',
'admin_pointprod_unavailable'	 		=> '系统未开启积分中心，是否自动开启',
'admin_pointprod_parameter_error'		=> '参数错误',
'admin_pointprod_record_error'			=> '记录信息错误',
'admin_pointprod_userrecord_error'		=> '用户信息错误',
'admin_pointprod_goodsrecord_error'		=> '礼品信息错误',
'admin_pointprod_list_title'			=> '礼品列表',
'admin_pointprod_add_title'			=> '新增礼品',
'admin_pointprod_state'				=> '状态',
'admin_pointprod_show_up'			=> '上架',
'admin_pointprod_show_down'			=> '下架',
'admin_pointprod_commend'			=> '推荐',
'admin_pointprod_forbid'				=> '禁售',
'admin_pointprod_goods_name'			=> '礼品名称',
'pointprod_help1'					=> '使用积分兑换功能请先确保系统积分状态处于开启状态（促销 -> 促销设置），礼品会出现在积分中心，会员可凭积分兑换，兑换成功后，由系统平台进行发货。',
'admin_pointprod_goods_points'		=> '兑换积分',
'admin_pointprod_goods_price'		=> '礼品原价',
'admin_pointprod_goods_storage'		=> '库存',
'admin_pointprod_goods_view'			=> '浏览',
'admin_pointprod_salenum'			=> '售出',
'admin_pointprod_yes'				=> '是',
'admin_pointprod_no'					=> '否',
'admin_pointprod_delfail'			=> '删除失败',
'admin_pointorder_list_title'		=> '兑换列表',
/**
 * 添加
 */
'admin_pointprod_baseinfo'		=> '礼品基本信息',
'admin_pointprod_goods_image'	=> '礼品图片',
'admin_pointprod_goods_tag'		=> '礼品标签',
'admin_pointprod_goods_serial'	=> '礼品编号',
'admin_pointprod_requireinfo'	=> '兑换要求',
'admin_pointprod_limittip'		=> '限制每会员兑换数量',
'admin_pointprod_limit_yes'		=> '限制',
'admin_pointprod_limit_no'		=> '不限制',
'admin_pointprod_limitnum'		=> '每会员限兑数量',
'admin_pointprod_freightcharge'	=> '运费承担方式',
'admin_pointprod_freightcharge_saler'	=> '商家',
'admin_pointprod_freightcharge_buyer'	=> '买家',
'admin_pointprod_freightprice'	=> '运费价格',
'admin_pointprod_limittimetip'		=> '限制兑换时间',
'admin_pointprod_limittime_yes'		=> '限制',
'admin_pointprod_limittime_no'		=> '不限制',
'admin_pointprod_starttime'	=> '开始时间',
'admin_pointprod_endtime'	=> '结束时间',
'admin_pointprod_time_day'	=> '日',
'admin_pointprod_time_hour'	=> '时',
'admin_pointprod_stateinfo'	=> '状态设置',
'admin_pointprod_isshow'	=> '是否上架',
'admin_pointprod_iscommend'	=> '是否推荐',
'admin_pointprod_isforbid'	=> '是否禁售',
'admin_pointprod_forbidreason'=> '禁售原因',
'admin_pointprod_seoinfo'	=> 'SEO设置',
'admin_pointprod_seokey'		=> '关键字',
'admin_pointprod_otherinfo'		=> '其他设置',
'admin_pointprod_sort'		=> '礼品排序',
'admin_pointprod_sorttip'		=> '注：数值越小排序越靠前',
'admin_pointprod_seodescription'		=> 'SEO描述',
'admin_pointprod_descriptioninfo'	=> '礼品描述',
'admin_pointprod_uploadimg'	=> '图片上传',
'admin_pointprod_uploadimg_more'	=> '批量上传',
'admin_pointprod_uploadimg_common'	=> '普通上传',
'admin_pointprod_uploadimg_complete'	=> '已传图片',
'admin_pointprod_uploadimg_add'	=> '插入',
'admin_pointprod_uploadimg_addtoeditor'	=> '插入编辑器',
'admin_pointprod_add_goodsname_error'	=> '请添加礼品名称',
'admin_pointprod_add_goodsprice_null_error'	=> '请添加礼品原价',
'admin_pointprod_add_goodsprice_number_error'	=> '礼品原价必须为数字且大于等于0',
'admin_pointprod_add_goodspoint_null_error'	=> '请添加兑换积分',
'admin_pointprod_add_goodspoint_number_error'	=> '兑换积分为整数且大于等于0',
'admin_pointprod_add_goodsserial_null_error'	=> '请添加礼品编号',
'admin_pointprod_add_storage_null_error'	    => '请添加礼品库存',
'admin_pointprod_add_storage_number_error'	=> '礼品库存必须为整数且大于等于0',
'admin_pointprod_add_limitnum_error'			=> '请添加每会员限兑数量',
'admin_pointprod_add_limitnum_digits_error'	=> '会员限兑数量为整数且大于等于0',
'admin_pointprod_add_freightprice_null_error'		=> '请添加运费价格',
'admin_pointprod_add_freightprice_number_error'		=> '运费价格必须为数字且大于等于0',
'admin_pointprod_add_limittime_null_error'		=> '请添加开始时间和结束时间',
'admin_pointprod_add_sort_null_error'		=> '请添加礼品排序',
'admin_pointprod_add_sort_number_error'		=> '礼品排序为整数且大于等于0',
'admin_pointprod_add_upload'		=> '上传',
'admin_pointprod_add_upload_img_error'		=> '图片限于png,gif,jpeg,jpg格式',
'admin_pointprod_add_iframe_uploadfail'		=> '上传失败',
'admin_pointprod_add_success'		=> '礼品添加成功',
/**
 * 更新
 */
'admin_pointprod_edit_success'		=> '礼品更新成功',
'admin_pointprod_edit_addtime'		=> '添加时间',
'admin_pointprod_edit_viewnum'		=> '浏览次数',
'admin_pointprod_edit_salenum'		=> '售出数量',
/**
 * 删除
 */
'admin_pointprod_del_success'		=> '删除成功',
'admin_pointprod_del_fail'			=> '删除失败',
);