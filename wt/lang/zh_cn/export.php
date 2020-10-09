<?php
defined('ShopWT') or exit('Access Denied By ShopWT');
$lang	= array(
/**
 * 导出语言包，只有在执行导出行为时，才会调用
 */

//品牌
'exp_brandid'		=> '品牌ID',
'exp_brand'			=> '品牌',
'exp_brand_cate'		=> '类别',
'exp_brand_img'		=> '标识图',

//商品
'exp_product'		=> '商品',
'exp_pr_cate'		=> '分类',
'exp_pr_brand'		=> '品牌',
'exp_pr_price'		=> '价格',
'exp_pr_serial'		=> '货号',
'exp_pr_state'		=> '状态',
'exp_pr_type'		=> '类型',
'exp_pr_addtime'		=> '发布时间',
'exp_pr_store'		=> '店铺',
'exp_pr_storeid'		=> '店铺ID',
'exp_pr_wgxj'		=> '违规下架',
'exp_pr_sj'			=> '上架',
'exp_pr_xj'			=> '下架',
'exp_pr_new'			=> '全新',
'exp_pr_old'			=> '二手',

//类型
'exp_type_name'		=> '类型',

//规格
'exp_spec'			=> '规格',
'exp_sp_content'		=> '规格内容',

//店铺
'exp_store'			=> '店铺',
'exp_st_name'		=> '店主账号',
'exp_st_sarea'		=> '所在地',
'exp_st_grade'		=> '等级',
'exp_st_adtime'		=> '创店时间',
'exp_st_yxq'			=> '有效期',
'exp_st_state'		=> '状态',
'exp_st_xarea'		=> '详细地址',
'exp_st_post'		=> '邮编',
'exp_st_tel'			=> '联系电话',
'exp_st_kq'			=> '开启',
'exp_st_shz'			=> '审核中',
'exp_st_close'		=> '关闭',

//会员
'exp_member'			=> '会员',
'exp_mb_name'		=> '真实姓名',
'exp_mb_jf'			=> '积分',
'exp_mb_yck'			=> '预存款',
'exp_mb_jbs'			=> '金币数',
'exp_mb_sex'			=> '性别',
'exp_mb_ww'			=> '旺旺',
'exp_mb_dcs'			=> '登录次数',
'exp_mb_rtime'		=> '注册时间',
'exp_mb_ltime'		=> '上次登录',
'exp_mb_storeid'		=> '店铺ID',
'exp_mb_nan'			=> '男',
'exp_mb_nv'			=> '女',

//积分明细
'exp_pi_member'		=> '会员',
'exp_pi_system'		=> '管理员',
'exp_pi_point'		=> '积分值',
'exp_pi_time'		=> '发生时间',
'exp_pi_jd'			=> '操作阶段',
'exp_pi_ms'			=> '描述',
'exp_pi_jfmx'		=> '积分明细',

//预存款充值
'exp_yc_no'			=> '充值编号',
'exp_yc_member'		=> '会员名',
'exp_yc_money'		=> '充值金额',
'exp_yc_pay'			=> '付款方式',
'exp_yc_ctime'		=> '创建时间',
'exp_yc_ptime'		=> '付款时间',
'exp_yc_paystate'	=> '支付状态',
'exp_yc_memberid'	=> '会员ID',
'exp_yc_yckcz'		=> '预存款充值',

//预存款提现
'exp_tx_no'			=> '提现编号',
'exp_tx_member'		=> '会员名',
'exp_tx_money'		=> '提现金额',
'exp_tx_type'		=> '提现方式',
'exp_tx_ctime'		=> '申请时间',
'exp_tx_state'		=> '提现状态',
'exp_tx_memberid'	=> '会员ID',
'exp_tx_title'		=> '预存款提现',

//预存款明细
'exp_mx_member'		=> '会员',
'exp_mx_ctime'		=> '变更时间',
'exp_mx_money'		=> '金额',
'exp_mx_av_money'	=> '可用金额',
'exp_mx_freeze_money'=> '冻结金额',
'exp_mx_type'		=> '金额类型',
'exp_mx_system'		=> '管理员',
'exp_mx_stype'		=> '事件类型',
'exp_mx_mshu'		=> '描述',
'exp_mx_rz'			=> '预存款变更日志',

//订单
'exp_od_no'			=> '订单号',
'exp_od_store'		=> '店铺',
'exp_od_buyer'		=> '买家',
'exp_od_xtimd'		=> '下单时间',
'exp_od_count'		=> '订单总额',
'exp_od_yfei'		=> '运费',
'exp_od_paytype'		=> '支付方式',
'exp_od_state'		=> '订单状态',
'exp_od_storeid'		=> '店铺ID',
'exp_od_selerid'		=> '商家ID',
'exp_od_buyerid'		=> '买家ID',
'exp_od_bemail'		=> '买家Email',
'exp_od_sta_qx'		=> '已取消',
'exp_od_sta_dfk'		=> '待付款',
'exp_od_sta_dqr'		=> '已付款、待确认',
'exp_od_sta_yfk'		=> '已付款',
'exp_od_sta_yfh'		=> '已发货',
'exp_od_sta_yjs'		=> '已结算',
'exp_od_sta_dsh'		=> '待审核',
'exp_od_sta_yqr'		=> '已确认',
'exp_od_order'		=> '订单',

//金币购买记录
'exp_jbg_member'		=> '会员名',
'exp_jbg_store'		=> '店铺',
'exp_jbg_jbs'		=> '购买金币数',
'exp_jbg_money'		=> '所需金额',
'exp_jbg_gtime'		=> '购买时间',
'exp_jbg_paytype'	=> '支付方式',
'exp_jbg_paystate'	=> '支付状态',
'exp_jbg_storeid'	=> '店铺ID',
'exp_jbg_memberid'	=> '会员ID',
'exp_jbg_wpay'		=> '未支付',
'exp_jbg_ypay'		=> '已支付',
'exp_jbg_jbgm'		=> '金币购买',

//金币日志
'exp_jb_member'		=> '会员',
'exp_jb_store'		=> '店铺',
'exp_jb_jbs'			=> '金币数',
'exp_jb_type'		=> '变更类型',
'exp_jb_btime'		=> '变更时间',
'exp_jb_mshu'		=> '描述',
'exp_jb_storeid'		=> '店铺ID',
'exp_jb_memberid'	=> '会员ID',
'exp_jb_add'			=> '增加',
'exp_jb_del'			=> '减少',
'exp_jb_log'			=> '金币日志',
);