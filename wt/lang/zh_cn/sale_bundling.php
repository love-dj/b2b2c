<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(

// index
'sale_unavailable' => '商品促销功能尚未开启',
'bundling_quota'				=> '套餐列表',
'bundling_list'				=> '活动',
'bundling_setting'			=> '设置',
'bundling_gold_price'		=> '优惠套装价格',
'bundling_sum'				=> '优惠套装发布数量',
'bundling_goods_sum'			=> '每个组合加入商品数量',
'bundling_price_prompt'		=> '购买单位为月(30天)，购买后商家可以在所购买周期内发布优惠套装活动。',
'bundling_sum_prompt'		=> '允许店铺发布优惠套装的最大数量，0为没有限制。',
'bundling_goods_sum_prompt'	=> '每个组合能加入商品的最大数量，不大于5的数字。',
'bundling_price_error'		=> '不能为空，且不小于1的整数',
'bundling_sum_error'			=> '不能为空，且不小于0的整数',
'bundling_goods_sum_error'	=> '不能为空，且为1到5之间的整数，包括1和5',
'bundling_update_succ'		=> '更新成功',
'bundling_update_fail'		=> '更新失败',

'bundling_state_all'			=> '全部状态',
'bundling_state_1'			=> '开启',
'bundling_state_0'			=> '关闭',


// 活动列表
'bundling_quota_list_prompts'=> '商家购买优惠套装活动的列表。',
'bundling_quota_store_name'	=> '店铺名称',

// 活动编辑
'bundling_quota_endtime_required'	=> '请添加结束时间',
'bundling_quota_endtime_dateValidate'=> '结束时间需要大于开始时间',
'bundling_quota_store_name'			=> '店铺名称',
'bundling_quota_quantity'			=> '购买数量',
'bundling_quota_starttime'			=> '开始时间',
'bundling_quota_endtime'				=> '结束时间',
'bundling_quota_endtime_tips'		=> '如果状态选择开启，请设置结束时间大于当前时候，否则不能开启。',
'bundling_quota_state_tips'			=> '设置状态为开启时，结束时间必须大于当前时间，否则状态无法开启。',
'bundling_quota_prompts'				=> '查看每个店铺的优惠套装活动信息，您可以取消某个活动。',

// 套餐列表
'bundling_name'						=> '活动名称',
'bundling_price'						=> '活动销售价格',
'bundling_goods_count'				=> '商品数量',
);
	