<?php defined('ShopWT') or exit('Access Denied By ShopWT'); return array (
  'system' => 
  array (
    'name' => '系统',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'setting' => '平台设置',
          'navigation' => '菜单导航',
          'upload' => '上传设置',
          'show' => '广告管理',
          'db' => '数据库管理',
          'area' => '地区管理',
          'link' => '友情连接',
          'task' => '计划任务',
          'cache' => '更新缓存',
        ),
      ),
      1 => 
      array (
        'name' => '权限',
        'child' => 
        array (
          'admin' => '权限设置',
          'admin_log' => '操作日志',
        ),
      ),
      2 => 
      array (
        'name' => '接口',
        'child' => 
        array (
          'message' => '邮件设置',
          'sms' => '短信设置',
          'account' => '第三方登录',
          'taobao_api' => '淘宝接口',
            'qiniuyun_api' => '七牛云',
        ),
      ),
      3 => 
      array (
        'name' => '文章',
        'child' => 
        array (
          'article' => '文章管理',
          'article_class' => '文章分类',
          'document' => '商城协议',
        ),
      ),
    ),
  ),
  'shop' => 
  array (
    'name' => '商城',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'setting' => '基本设置',
          'page_config' => '首页管理',
          'web_channel' => '频道管理',
          'upload' => '上传设置',
          'search' => '搜索设置',
          'seo' => 'SEO设置',
          'payment' => '支付方式',
          'message' => '消息模板',
          'express_api' => '快递接口',
          'express' => '快递公司',
          'waybill' => '运单模板',
        ),
      ),
      1 => 
      array (
        'name' => '运营',
        'child' => 
        array (
          'bill' => '实物订单结算',
          'vr_bill' => '虚拟订单结算',
          'contract' => '商家保障服务',
          'delivery' => '自提点管理',
          'rechargecard' => '充值卡管理',
          'mall_consult' => '客服咨询管理',
        ),
      ),
      2 => 
      array (
        'name' => '店铺',
        'child' => 
        array (
          'ownshop' => '自营店铺',
          'store' => '店铺列表',
          'store_class' => '店铺分类',
          'store_grade' => '店铺等级',
          'sns_strace' => '店铺动态',
          'domain' => '二级域名',
          'help_store' => '店铺帮助',
          'store_joinin' => '入驻首页',
        ),
      ),
      3 => 
      array (
        'name' => '商品',
        'child' => 
        array (
          'goods' => '商品管理',
          'goods_class' => '分类管理',
          'type' => '类型管理',
          'spec' => '规格管理',
          'goods_album' => '图片管理',
          'goods_video_album' => '视频管理',
          'brand' => '品牌管理',
          'goods_recommend' => '商品推荐',
          'lib_goods' => '商品库管理',
        ),
      ),
      4 => 
      array (
        'name' => '订单',
        'child' => 
        array (
          'order' => '商品订单管理',
          'refund' => '实物退款处理',
          'return' => '实物退货处理',
          'order_vr' => '虚拟订单管理',
          'vr_refund' => '虚拟订单退款',
          'inform' => '商品举报管理',
          'complain' => '商品投诉管理',
          'consulting' => '商品咨询管理',
          'evaluate' => '商品评价管理',
        ),
      ),
      5 => 
      array (
        'name' => '统计',
        'child' => 
        array (
          'stat_general' => '概述统计',
          'stat_trade' => '销量统计',
          'stat_store' => '店铺统计',
          'stat_goods' => '商品统计',
          'stat_industry' => '行业分析',
          'stat_aftersale' => '售后统计',
          'stat_marketing' => '营销统计',
          'stat_member' => '会员统计',
        ),
      ),
      6 => 
      array (
        'name' => '促销',
        'child' => 
        array (
          'operation' => '促销设置',
          'sale_xianshi' => '限时折扣',
          'robbuy' => '抢购管理',
          'vr_robbuy' => '虚拟抢购设置',
          'activity' => '平台活动',
          'coupon' => '平台优惠券',
          'sale_cou' => '加价购活动',
          'sale_pingou' => '拼团活动',
          'sale_bundling' => '店铺优惠套装',
          'sale_combo' => '推荐组合',
          'sale_booth' => '推荐展位',
          'sale_mansong' => '店铺满即送',
          'voucher' => '店铺代金券',
          'sale_book' => '定金预售',
          'sale_fcode' => 'Ｆ码商品',
          'sale_sole' => '移动端专享',
          'pointprod' => '积分兑换',
        ),
      ),
      7 => 
      array (
        'name' => '会员',
        'child' => 
        array (
          'member' => '会员管理',
          'sns_malbum' => '相册管理',
          'sns_sharesetting' => '分享设置',
          'sns_member' => '标签管理',
          'snstrace' => '动态管理',
          'points' => '积分管理',
          'member_exp' => '经验值管理',
          'chat_log' => 'IM消息记录',
          'predeposit' => '预存款管理',
        ),
      ),
    ),
  ),
  'news' => 
  array (
    'name' => '资讯',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'news_manage' => '资讯设置',
          'news_index' => '首页管理',
          'news_navigation' => '导航管理',
          'news_tag' => '标签管理',
          'news_comment' => '评论管理',
        ),
      ),
      1 => 
      array (
        'name' => '专题',
        'child' => 
        array (
          'news_special' => '专题管理',
        ),
      ),
      2 => 
      array (
        'name' => '文章',
        'child' => 
        array (
          'news_article_class' => '文章分类',
          'news_article' => '文章管理',
        ),
      ),
      3 => 
      array (
        'name' => '图刊',
        'child' => 
        array (
          'news_picture_class' => '图刊分类',
          'news_picture' => '图刊管理',
        ),
      ),
    ),
  ),
  'bbs' => 
  array (
    'name' => '社区',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'bbs_setting' => '社区设置',
          'bbs_show' => '首页幻灯',
        ),
      ),
      1 => 
      array (
        'name' => '成员',
        'child' => 
        array (
          'bbs_member' => '成员管理',
          'bbs_memberlevel' => '成员头衔',
        ),
      ),
      2 => 
      array (
        'name' => '社区',
        'child' => 
        array (
          'bbs_manage' => '社区管理',
          'bbs_class' => '分类管理',
          'bbs_theme' => '话题管理',
          'bbs_inform' => '举报管理',
        ),
      ),
    ),
  ),
  'what' => 
  array (
    'name' => '买什么',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'manage' => '基本设置',
          'show' => '广告管理',
          'comment' => '评论管理',
        ),
      ),
      1 => 
      array (
        'name' => '说说看',
        'child' => 
        array (
          'goods' => '说说看管理',
          'goods_class' => '说说看分类',
        ),
      ),
      2 => 
      array (
        'name' => '买心得',
        'child' => 
        array (
          'personal' => '买心得管理',
          'personal_class' => '买心得分类',
        ),
      ),
      3 => 
      array (
        'name' => '逛店铺',
        'child' => 
        array (
          'store' => '逛店铺管理',
        ),
      ),
    ),
  ),
  'flea' => 
  array (
    'name' => '闲置',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'flea' => '闲置管理',
          'flea_show' => '闲置幻灯',
          'flea_class_index' => '首页分类',
          'flea_index' => 'SEO设置',
          'flea_class' => '分类管理',
          'flea_region' => '地区管理',
        ),
      ),
    ),
  ),
  'mobile' => 
  array (
    'name' => '手机端',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'mb_setting' => '手机端设置',
          'mb_logo' => '手机Logo',
          'mb_special' => '首页管理',
          'mb_redpacket' => '红包管理',
          'mb_category' => '分类图标',
          'mb_feedback' => '用户反馈',
          'mb_payment' => '手机支付',
          'mb_app' => 'APP二维码',
          'mb_wx' => '微信二维码',
          'mb_connect' => '第三方登录',
        ),
      ),
    ),
  ),
  'wechat' => 
  array (
    'name' => '微信',
    'child' => 
    array (
      0 => 
      array (
        'name' => '设置',
        'child' => 
        array (
          'setting' => '基本设置',
          'api' => '接口配置',
          'wechat_msg' => '微信通知',
          'material' => '素材管理',
          'subcribe' => '首次关注设置',
          'menu' => '自定义菜单',
          'keyword' => '关键词管理',
          'url' => 'URL管理',
        ),
      ),
    ),
  ),
  'bonusrules' => 
  array (
    'name' => '奖金制度',
    'child' => 
    array (
      0 => 
      array (
        'name' => '三级分销',
        'child' => 
        array (
          'distribution_setting' => '分销设置',
          'distribution_level' => '分销等级',
          'distribution_order' => '分销订单管理',
        ),
      ),
      1 => 
      array (
        'name' => '团队无限级',
        'child' => 
        array (
          'team_setting' => '基础设置',
          'team_user' => '团队管理',
          'team_level' => '团队等级管理',
          'team_order' => '团队提成明细',
        ),
      ),
      2 => 
      array (
        'name' => '区域代理',
        'child' => 
        array (
          'agent_setting' => '分红设置',
          'agent_user' => '区域代理管理',
          'agent_apply' => '区域代理申请',
          'agent_order' => '区域分红记录',
        ),
      ),
      3 => 
      array (
        'name' => '股东分红',
        'child' => 
        array (
          'shareholder_setting' => '分红设置',
          'shareholders' => '分红管理',
          'shareholder_logs' => '股东分红记录',
        ),
      ),
      4 => 
      array (
        'name' => '消费返现',
        'child' => 
        array (
          'buy_return_setting' => '消费返现设置',
          'buy_return_queue' => '消费返现队列',
          'buy_return_logs' => '消费返现记录',
        ),
      ),
      5 => 
      array (
        'name' => '满额返现',
        'child' => 
        array (
          'full_return_setting' => '满额返现设置',
          'full_return_queue' => '满额返现队列',
          'full_return_logs' => '满额返现记录',
        ),
      ),
    ),
  ),
);