<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
'what_not_install' => '您没有安装买什么模块',

'what_member' => '用户',
'what_channel' => '频道',
'what_commend' => '推荐',
'what_text_id' => '编号',

'what_class_name' => '分类名称',
'what_parent_class' => '上级分类',
'what_class_image' => '分类图片',
'what_class_keyword' => '分类关键字',

'what_goods_class_binding' => '绑定分类',
'what_goods_class_binding_select' => '选择分类',
'what_goods_class_binded' => '已绑定分类',
'goods_relation_save_success' => '绑定分类保存成功',
'goods_relation_save_fail' => '绑定分类保存失败',
'what_goods_class_default' => '设为默认',

//分类表单
'class_parent_id_error' => '分类上级编号错误',
'class_name_error' => '分类名称名称不能为空且必须小于10个字符',
'class_name_required' => '分类名称不能为空',
'class_name_maxlength' => '分类名称最多个{0}字符',
'class_keyword_maxlength' => '分类关键字最多个{0}字符',
'class_keyword_explain' => '分类关键字用英文逗号分隔，如果需要高亮显示在关键字前加"*"，例："裤子,*鞋子"',
'class_sort_explain' => '数字范围为0~255，数字越小越靠前',
'class_sort_error' => '分类排序必须为0~255之间的数字',
'class_sort_required' => '排序不能为空',
'class_sort_digits' => '排序必须为数字',
'class_sort_max' => '排序最大为{0}',
'class_sort_min' => '排序最小为{0}',
'class_add_success' => '分类保存成功',
'class_add_fail' => '分类保存失败',
'class_drop_success' => '分类删除成功',
'class_drop_fail' => '分类删除失败',
'what_sort_error' => '排序必须为0~255之间的数字',

//买什么管理
'what_isuse' => '买什么开关',
'what_isuse_explain' => '关闭后买什么前台将无法访问',
'what_url' => '买什么地址',
'what_url_explain' => '如果买什么配置了二级域名，在此填写后商城中的买什么链接使用二级域名，如果留空使用默认地址',
'what_style' => '买什么主题',
'what_style_explain' => '设置买什么主题，默认为default',
'what_header_image' => '买什么头部图片',
'what_personal_limit' => '买心得数量限制',
'what_personal_limit_explain' => '会员发布买心得的数量限制，0为不限制',
'what_seo_keywords' => '买什么SEO关键字',
'what_seo_description' => '买什么SEO描述',

//说说看
'what_goods_name' => '商品名称',
'what_goods_image' => '商品图片',
'what_commend_time' => '推荐时间',
'what_commend_message' => '推荐说明',
'what_goods_tip1' => '通过修改排序数字可以控制前台说说看的显示顺序，数字越小越靠前',
'what_goods_tip2' => '点亮推荐列的符号，该商品将推荐到买什么首页',
'what_goods_class_tip1' => '通过修改排序数字可以控制前台说说看分类的显示顺序，数字越小越靠前',
'what_goods_class_tip2' => '点亮推荐列的符号，该分类将推荐到买什么首页',
'what_goods_class_tip3' => '点击行首的"+"号，可以展开下级分类',
'what_goods_class_tip4' => '点击二级分类后的"绑定分类"按钮可以绑定买什么和商城系统的分类，绑定后推荐的说说看商品将自动匹配分类',
'what_goods_class_tip5' => '点击二级分类后的"设为默认"按钮可以设定买什么的默认分类，说说看发布的商城中未绑定分类都将使用默认分类',
'what_goods_class_binding_tip1' => '选择下方的商城分类后单击完成绑定，绑定后推荐的说说看商品将自动匹配分类',
'what_goods_class_binding_tip2' => '鼠标移到已绑定的分类上，点击右上角的"x"可以删除绑定',

'what_personal_tip1' => '通过修改排序数字可以控制前台说说看的显示顺序，数字越小越靠前',
'what_personal_tip2' => '点亮推荐列的符号，该商品将推荐到买什么首页',

//店铺
'what_store_add_confirm' => '确认添加该店铺到逛店铺?',
'what_store_goods_count' => '商品数',
'what_store_credit' => '商家信用',
'what_store_praise_rate' => '好评率',
'what_store_add' => '已添加',
'what_store_tip1' => '通过修改排序数字可以控制前台逛店铺的显示顺序，数字越小越靠前',
'what_store_tip2' => '点亮推荐列的符号，该店铺将推荐到买什么首页，首页最多显示15个推荐店铺',
'what_store_add_tip1' => '点击"添加"按钮将商城店铺添加到买什么的逛店铺',

//评论
'what_comment_id' => '评论编号',
'what_comment_object_id' => '对象编号',
'what_comment_message' => '评论内容',
'what_comment_tip1' => '点击"删除"按钮将删除对应的评论',

//广告
'what_show_type' => '广告类型',
'what_show_name' => '广告名称',
'what_show_image' => '广告图片',
'what_show_url' => '广告链接',
'what_show_type_index' => '首页幻灯',
'what_show_type_store_list' => '店铺列表页幻灯',
'what_show_image_error' => '广告图片不能为空',
'what_show_tip1' => '通过修改排序数字可以控制前台广告的显示顺序，数字越小越靠前',
'what_show_type_explain' => '选择对应的广告位置',
'what_show_image_explain' => '首页广告图片推荐尺寸700px*280px，店铺列表页广告图片推荐尺寸1000px*250px',
'what_show_url_explain' => '广告对应的链接地址',
);

