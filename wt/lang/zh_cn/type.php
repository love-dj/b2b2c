<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * index
 */
'type_index_related_fail'			=> '部分信息添加失败请重新编辑该类型。',
'type_index_continue_to_dd'			=> '继续添加类型',
'type_index_return_type_list'		=> '返回类型列表',
'type_index_del_succ'				=> '类型删除成功。',
'type_index_del_fail'				=> '类型删除失败。',
'type_index_del_related_attr_fail'	=> '删除关联属性失败。',
'type_index_del_related_brand_fail'	=> '删除关联品牌失败。',
'type_index_del_related_type_fail'	=> '删除关联规格失败。',
'type_index_type_name'				=> '类型',
'type_index_no_checked'				=> '请选择要操作的数据项。',
'type_index_prompts_one'				=> '当管理员添加商品分类时需选择类型。前台分类下商品列表页通过类型生成商品检索，方便用户搜索需要的商品。',
/**
 * 新增属性
 */
'type_add_related_brand'				=> '选择关联品牌',
'type_add_related_spec'				=> '选择关联规格',
'type_add_remove'					=> '移除',
'type_add_name_no_null'				=> '请填写类型名称',
'type_add_name_max'					=> '类型名称长度应在1-20个字符之间',
'type_add_sort_no_null'				=> '请填写类型排序',
'type_add_sort_no_digits'			=> '请填写整数',
'type_add_sort_desc'					=> '请填写自然数。类型列表将会根据排序进行由小到大排列显示。',
'type_add_spec_name'					=> '规格名称',
'type_add_spec_value'				=> '规格值',
'type_add_spec_null_one'				=> '还没有规格，赶快去',
'type_add_spec_null_two'				=> '添加规格吧！',
'type_add_brand_null_one'			=> '还没有品牌，赶快去',
'type_add_brand_null_two'			=> '添加品牌吧！',
'type_add_attr_add'					=> '添加属性',
'type_add_attr_add_one'				=> '添加一个属性',
'type_add_attr_add_one_value'		=> '添加一个属性值',
'type_add_attr_name'					=> '输入属性名称',
'type_add_attr_value'				=> '输入属性可选值',
'type_add_prompts_one'				=> '关联规格不是必选项，它会影响商品发布时的规格及价格的录入。不选为没有规格。',
'type_add_prompts_two'				=> '关联品牌不是必选项，它会影响商品发布时的品牌选择。',
'type_add_prompts_three'				=> '属性值可以添加多个，每个属性值之间需要使用逗号隔开。',
'type_add_prompts_four'				=> '选中属性的“显示”选项，该属性将会在商品列表页显示。',
'type_add_spec_must_choose'			=> '请至少选择一种规格',
'type_common_checked_hide'			=> '隐藏未选项',
'type_common_checked_show'			=> '全部显示',
'type_common_belong_class'			=> '快捷定位',
'type_common_belong_class_tips'		=> '选择分类，可关联到任意级分类。（只在后台快捷定位中起作用）',
/**
 * 编辑属性
 */
'type_edit_type_value_null'			=> '还没有添加类型值信息。',
'type_edit_type_value_del_fail'		=> '类型值信息删除失败。',
'type_edit_type_attr_edit'			=> '编辑属性',
'type_edit_type_attr_is_show'		=> '是否显示',
'type_edit_type_attr_name_no_null'	=> '属性值名称不能为空',
'type_edit_type_attr_name_max'		=> '属性值名称不能超过10个字符',
'type_edit_type_attr_sort_no_null'	=> '排序不能为空',
'type_edit_type_attr_sort_no_digits'	=> '排序值只能为数字',
'type_edit_type_attr_edit_succ'		=> '属性编辑成功',
'type_edit_type_attr_edit_fail'		=> '属性编辑失败',
'type_attr_edit_name_desc'			=> '请填写常用的商品属性的名称；例如：材质；价格区间等',
'type_attr_edit_sort_desc'			=> ' 请填写自然数。属性列表将会根据排序进行由小到大排列显示。',
);