<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(

/**
 * 页面需要的语言
 */
'inform_page_title' => '举报商品',
'inform_manage_title' => '举报管理',
'inform_manage_subhead' => '商城设置举报类型及处理被举报商品',
'inform' 			=> '举报',

'inform_state_all' => '所有举报',
'inform_state_handled' => '已处理',
'inform_state_unhandle' => '未处理',
'inform_goods_name' => '商品名称',
'inform_member_name' => '举报人',
'inform_subject' => '举报主题',
'inform_type' => '举报类型',
'inform_type_desc' => '举报类型描述',
'inform_pic' => '图片',
'inform_pic_view' => '查看图片',
'inform_pic_none' => '暂无图片',
'inform_datetime' => '举报时间',
'inform_state' => '状态',
'inform_content' => '举报内容',
'inform_handle_message' => '处理信息',
'inform_handle_type' => '处理结果',
'inform_handle_type_unuse' => '无效举报',
'inform_handle_type_venom' => '恶意举报',
'inform_handle_type_valid' => '有效举报',
'inform_handle_type_unuse_message' => '无效举报--商品会正常销售',
'inform_handle_type_venom_message' => '恶意举报--该用户的所有未处理举报将被取消，用户将被禁止举报',
'inform_handle_type_valid_message' => '有效举报--商品将被违规下架',
'inform_subject_add' => '添加主题',
'inform_type_add' => '添加类型',

'inform_text_none' => '无',
'inform_text_handle' => '处理',
'inform_text_select' => '请选择...',

/**
 * 提示信息
 */
'inform_content_null' => '举报内容不能为空且不能大于100个字符',
'inform_subject_add_null' => '举报主题不能为空且不能大于100个字符',
'inform_handle_message_null' => '处理信息不能为空且不能大于100个字符',
'inform_type_null' => '举报类型不能为空且不能大于50个字符',
'inform_type_desc_null' => '举报类型描述不能为空且不能大于100个字符',
'inform_handle_confirm' => '确认处理该举报?',
'inform_type_delete_confirm' => '确认删除举报分类，该分类下的主题也将被删除?',
'confirm_delete' => '确认删除?',
'inform_pic_error' => '图片只能是jpg格式',
'inform_handling' => '该商品已经被举报请等待处理',
'inform_type_error' => '举报类型不存在请联系平台管理员添加类型',
'inform_subject_null' => '举报主题不存在请联系平台管理员',
'inform_success' => '举报成功请等待处理',
'inform_fail' => '举报失败请联系管理员',
'goods_null' => '商品不存在',
'deny_inform' => '您已经被禁止举报商品，如有疑问请联系平台管理员', 
'inform_help1' => '举报类型和举报主题由管理员在后台设置，在商品信息页会员可根据举报主题举报违规商品，点击详细，查看举报内容',
'inform_help3' => '查看已处理举报内容',
'inform_help4' => '可在同一举报类型下添加多个举报主题',
'inform_help5' => '会员可根据举报主题，举报违规商品',
);
