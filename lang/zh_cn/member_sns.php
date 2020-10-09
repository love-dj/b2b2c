<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * SNS功能公用
 */
		'sns_smiles'					=> '表情',
		'sns_weiboprivacy_default'	=> '所有人',
		'sns_weiboprivacy_all'		=> '所有人可见',
		'sns_weiboprivacy_friend'	=> '仅好友可见',
		'sns_weiboprivacy_self'		=> '仅自己可见',
		'sns_content_beyond'			=> '不能超过140字',
		'sns_charcount_tip1'			=> '还可以输入',
		'sns_charcount_tip2'			=> '字',
		'sns_charcount_tip3'			=> '已经超出',
		'sns_today'					=> '今天',
		'sns_yesterday'				=> '昨天',
		'sns_beforeyesterday'		=> '前天',
		'sns_member_error'			=> '会员信息错误',
		'sns_goods_error'			=> '商品信息错误',
		'sns_store_error'			=> '店铺信息错误',
		'sns_share_succ'				=> '分享成功',
		'sns_share_fail'				=> '分享失败',
		'sns_collecttip'				=> '人收藏',
		'sns_setting'				=> '设置',
		'sns_setting_succ'			=> '设置成功',
		'sns_setting_fail'			=> '设置失败',
		'sns_goback'					=> '返回',
		'sns_viewdetails'			=> '查看详情',
		'sns_selected'				=> '已选择',
		'sns_trace_deleted'				=> '该动态已经删除',

/**
 * SNS首页
 */
		'sns_sharemood'				=> '分享心情',
		'sns_sharegoods'				=> '分享商品',
		'sns_sharestore'				=> '分享店铺',
		'sns_friendtrace'			=> '好友动态',
		'sns_visit_me'				=> '谁来看过',
		'sns_visit_other'			=> '访问过的人',
		'sns_sharemood_content_null'	=> '请填写心情',
		'sns_visitme_tip_1'	=> '最近没有人访问过您的空间，多关注些',
		'sns_visitme_tip_2'	=> '好友',
		'sns_visitme_tip_3'	=> '互动吧~',

		'sns_visitother_tip_1'	=> '最近您没有浏览过好友的空间，赶快去看看',
		'sns_visitother_tip_2'	=> '好友',
		'sns_visitother_tip_3'	=> '最近搜罗了哪些宝贝',
/**
 * 分享商品
 */
		'sns_sharegoods_notbuygoods'	=> '还没有购买商品~',
		'sns_sharegoods_contenttip'	=> '买到和收藏的好宝贝分享给好友吧!',
		'sns_sharegoods_choose'	=> '选择一件分享的商品~',
		'sns_sharegoods_title'	=> '分享了商品',
		'sns_sharegoods_price'	=> '价&nbsp;&nbsp;格',
		'sns_sharegoods_freight'	=> '运&nbsp;&nbsp;费',
		'sns_sharegoods_collect'	=> '收藏该宝贝',
		'sns_share_purchasedgoods'	=> '分享已买到和收藏的宝贝',
		'sns_sharegoods_tofriend'	=> '分享给好友',
		'sns_sharegoods_goodserror'	=> '商品已下架或者已被删除，无法进行分享',
		'sns_sharegoods_contenttip2'	=> '我蛮喜欢这件商品的哦~',
/**
 * 分享店铺
 */
		'sns_sharestore'				=> '分享店铺',
		'sns_sharestore_choose'		=> '选择一家要分享的店铺~',
		'sns_sharestore_title'		=> '分享了店铺',
		'sns_sharestore_collect'		=> '收藏该店铺',
		'sns_sharestore_storeerror'	=> '店铺关闭，无法进行分享',
		'sns_sharestore_nothavecollect'	=> '您还没有收藏店铺',
		'sns_sharestore_contenttip'	=> '喜爱的店铺与朋友一起分享吧！',
		'sns_sharestore_shopkeeper'	=> '店主',
		'sns_sharestore_location'	=> '所在地',
/**
 * 评论
 */
		'sns_comment'	=> '评论',
		'sns_original_comment'	=> '原文评论',
		'sns_comment_null'	=> '需要评论点内容~',
		'sns_comment_fail'	=> '评论失败，请重试',
		'sns_comment_succ'	=> '评论成功',
		'sns_comment_recorderror'	=> '评论信息错误',
		'sns_comment_contenttip'	=> '我也插句话...',
		'sns_comment_floor'	=> '楼',
		'sns_comment_more'	=> '查看更多评论',
/**
 * 喜欢商品
 */
		'sns_like'	=> '喜欢',
		'sns_likegoods_choose'	=> '选择一件喜欢的商品呗~',
		'sns_likegoods_exist'	=> '您已经喜欢过了',
		'sns_likegoods_title'	=> '我很喜欢这个哦~',
/**
 * 转发
 */
		'sns_forward'	=> '转发',
		'sns_original_forward'	=> '原文转发',
		'sns_forward_fail'	=> '转发失败，请重试',
		'sns_forward_succ'	=> '转发成功',
/**
 * 分享和喜欢商品列表
 */
		'sns_shareofgoods'					=> '分享的宝贝',
		'sns_likeofgoods'					=> '喜欢的宝贝',
		'sns_sharegoods_nothave_self_1'		=> '您还没有分享过宝贝哦~<br/>从',
		'sns_sharegoods_nothave_self_2'		=> '已购买',
		'sns_sharegoods_nothave_self_3'		=> '或',
		'sns_sharegoods_nothave_self_4'		=> '收藏商品',
		'sns_sharegoods_nothave_self_5'		=> '中分享一些宝贝！',
		'sns_sharegoods_nothave'				=> '很遗憾！<br/>主人暂无宝贝分享~',
		'sns_likegoods_nothave_self'			=> '您还没有喜欢的宝贝哦~<br/>赶紧喜欢一些宝贝与好友互动吧。',
		'sns_likegoods_nothave'				=> '很遗憾！<br/>TA暂无喜欢的宝贝~',
/**
 * 分享店铺列表
 */
		'sns_sharestore_sellercredit'		=> '卖家信用',
		'sns_sharestore_allgoods'			=> '所有宝贝',
		'sns_sharestore_collectnum'			=> '收藏人气',
		'sns_sharestore_nohave_self_1'		=> '您还没有分享过店铺！<br/>可以从',
		'sns_sharestore_nohave_self_2'		=> '收藏的店铺',
		'sns_sharestore_nohave_self_3'		=> '中分享哦~',
		'sns_sharestore_nohave_1'			=> '很遗憾！<br/>TA暂无店铺分享~',
/**
 * 动态列表
 */
		'sns_trace_nohave_self'				=> '没有动态提示，赶快多关注几个好友吧。',
		'sns_trace_nohave_self_1'			=> '还没有新鲜事哦~<br/>关注',
		'sns_trace_nohave_self_2'			=> '更多的好友',
		'sns_trace_nohave_self_3'			=> '并互动吧！',
		'sns_trace_nohave'					=> '很遗憾！<br/>TA暂无新鲜事分享~',

		'sns_trace_originaldeleted'		=> '原文已删除',
/**
 * home
 */
		'sns_fansnum' => '粉丝数',
		'sns_visits' => '访问量',
		'sns_tab_goods' => '商品',
		'sns_tab_store' => '店铺',
		'sns_tab_trace' => '动态',
);