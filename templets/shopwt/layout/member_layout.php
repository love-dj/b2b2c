<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/common_layout');?>
<style type="text/css">
.wt-nav { display:none;}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ToolTip.js"></script>
<script>
//sidebar-menu
$(document).ready(function() {
    $.each($(".side-menu > a"), function() {
        $(this).click(function() {
            var ulNode = $(this).next("ul");
            if (ulNode.css('display') == 'block') {
            	$.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),1);
            } else {
            	$.cookie(COOKIE_PRE+'Mmenu_'+$(this).attr('key'),null);
            }
			ulNode.slideToggle();
				if ($(this).hasClass('shrink')) {
					$(this).removeClass('shrink');
				} else {
					$(this).addClass('shrink');
				}
        });
    });
	$.each($(".side-menu-quick > a"), function() {
        $(this).click(function() {
            var ulNode = $(this).next("ul");
			ulNode.slideToggle();
				if ($(this).hasClass('shrink')) {
					$(this).removeClass('shrink');
				} else {
					$(this).addClass('shrink');
				}
        });
    });
});

</script>
<div class="wtm-header-nav all-nav-box">
     <div class="wrapper">
     <div class="category-nav">
      <?php require template('layout/home_goods_class');?>
     </div>
      <ul class="nav-menu">
        <li class="ml10"><a href="<?php echo urlShop('member', 'home');?>" class="current">我的商城</a></li>
        <li class="set"><a href="<?php echo urlMember('member', 'home');?>">用户设置<i></i></a>
          <div class="sub-menu">
            <dl>
              <dt><a href="<?php echo urlMember('member_security', 'index');?>" style="color: #3AAC8A;">安全设置</a></dt>
              <dd><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_pwd'));?>">修改登录密码</a></dd>
              <dd><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_mobile'));?>">手机绑定</a></dd>
              <dd><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_email'));?>">邮件绑定</a></dd>
              <dd><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd'));?>">支付密码</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlMember('member_information', 'member')?>" style="color: #EA746B">个人资料</a></dt>
              <dd><a href="<?php echo urlMember('member_address', 'address');?>">收货地址</a></dd>
              <dd><a href="<?php echo urlMember('member_information', 'avatar')?>">修改头像</a></dd>
              <dd><a href="<?php echo urlMember('member_message', 'setting');?>">消息接受设置</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlMember('predeposit', 'pd_log_list')?>" style="color: #FF7F00">账户财产</a></dt>
              <dd><a href="<?php echo urlMember('predeposit', 'recharge_add');?>">余额充值</a></dd>
              <dd><a href="<?php echo urlMember('member_voucher', 'voucher_binding')?>">领取代金券</a></dd>
              <dd><a href="<?php echo urlMember('member_coupon', 'rp_binding');?>">领取优惠券</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlMember('member_bind', 'qqbind')?>" style="color: #398EE8">账号绑定</a></dt>
              <dd><a href="<?php echo urlMember('member_bind', 'qqbind');?>">QQ绑定</a></dd>
              <dd><a href="<?php echo urlMember('member_bind', 'sinabind')?>">微博绑定</a></dd>
              <dd><a href="<?php echo urlMember('member_bind', 'weixinbind');?>">微信绑定</a></dd>
              <dd><a href="<?php echo urlMember('member_sharemanage', 'index');?>">分享绑定</a></dd>
            </dl>
          </div>
        </li>
        <li><a href="<?php echo urlShop('member_snshome', 'index')?>">个人主页<i></i></a>
          <div class="sub-menu">
            <dl>
              <dd><a href="<?php echo urlShop('member_snshome', 'index');?>">新鲜事</a></dd>
              <dd><a href="<?php echo urlShop('sns_album', 'index');?>">个人相册</a></dd>
              <dd><a href="<?php echo urlShop('member_snshome', 'shareglist');?>">分享商品</a></dd>
              <dd><a href="<?php echo urlShop('member_snshome', 'storelist');?>">分享店铺</a></dd>
            </dl>
          </div>
        </li>
        <li><a href="javascript:;">其他应用<i></i></a>
          <div class="sub-menu">
            <dl>
              <dd><a href="<?php echo urlnews('member_article', 'article_list');?>">我的投稿</a></dd>
              <dd><a href="<?php echo urlbbs('p_center', 'index');?>">我的社区</a></dd>
              <dd><a href="<?php echo urlwhat('home', 'index', array('member_id' => $_SESSION['member_id']));?>">我的买什么</a></dd>
            </dl>
          </div>
        </li>
		<?php if(C('fenxiao_isuse') == 1){?>
		<li class="fenxiao"><a href="<?php echo FENXIAO_SITE_URL;?>">分销中心<i></i></a>
            <div class="sub-menu">
			<dl>
				  <dd><a href="<?php echo urlFenxiao('search', 'index');?>">分销商品</a></dd>
				  <dd><a href="<?php echo urlFenxiao('search', 'store');?>">分销店铺</a></dd>
				  <?php if(C('fenxiao_isuse') && in_array($output['member_info']['fx_state'], array(0,1))){?>
				<dd><a href="<?php echo urlFenxiao('fx_join', 'index');?>">成为分销员</a></dd>
				<?php }?>
			</dl>
			<?php if(C('fenxiao_isuse') && in_array($output['member_info']['fx_state'], array(2,4,5)) && $output['member_info']['fx_show'] == 1){?>
            <dl>
              <dt><a href="<?php echo urlFenxiao('fx_goods', 'goods_list');?>" style="color: #398EE8;">分销管理</a></dt>
              <dd><a href="<?php echo urlFenxiao('fx_goods', 'goods_list');?>">我的分销</a></dd>
              <dd><a href="<?php echo urlFenxiao('fx_order', 'order_list');?>">我的订单</a></dd>
              <dd><a href="<?php echo urlFenxiao('fx_bill', 'bill_list');?>">分销结算</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlFenxiao('access_infomation', 'member')?>" style="color: #3AAC8A">结算中心</a></dt>
              <dd><a href="<?php echo urlFenxiao('access_infomation', 'member');?>">账户设置</a></dd>
              <dd><a href="<?php echo urlFenxiao('commission', 'commission_info')?>">账户余额</a></dd>
              <dd><a href="<?php echo urlFenxiao('cash','cash_list');?>">提现记录</a></dd>
            </dl>
          <?php }?>
          </div>
        </li>
		<?php }?>
      </ul>
      <div class="notice">
        <ul class="line">
          <?php if (is_array($output['system_notice']) && !empty($output['system_notice'])) { ?>
          <?php foreach ($output['system_notice'] as $v) { ?>
          <li><a <?php if($v['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($v['article_url']!='')echo $v['article_url'];else echo urlShop('article', 'show', array('article_id'=>$v['article_id']));?>"><?php echo $v['article_title']?>
            <time>(<?php echo date('Y-m-d',$v['article_time']);?>)</time>
            </a> </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <script>
$(function() {
    var _wrap = $('ul.line');
    var _interval = 2000;
    var _moving;
    _wrap.hover(function() {
        clearInterval(_moving);
    },
    function() {
        _moving = setInterval(function() {
            var _field = _wrap.find('li:first');
            var _h = _field.height();
            _field.animate({
                marginTop: -_h + 'px'
            },
            600,
            function() {
                _field.css('marginTop', 0).appendTo(_wrap);
            })
        },
        _interval)
    }).trigger('mouseleave');
});
</script> 
    </div>
</div>
<div class="wtm-container">
  <div class="left-box">
    <ul id="sidebarMenu" class="wtm-sidebar">
      <?php if (!empty($output['menu_list'])) {?>
      <?php foreach ($output['menu_list'] as $key => $value) {?>
      <li class="side-menu"><a href="javascript:void(0)" key="<?php echo $key;?>" <?php if (cookie('Mmenu_'.$key) == 1) echo 'class="shrink"';?>>
        <h3><?php echo $value['name'];?></h3>
        </a>
        <?php if (!empty($value['child'])) {?>
        <ul <?php if (cookie('Mmenu_'.$key) == 1) echo 'style="display:none"';?>>
          <?php foreach ($value['child'] as $key => $val) {?>
          <li <?php if ($key == $output['w']) {?>class="selected"<?php }?>><a href="<?php echo $val['url'];?>"><?php echo $val['name'];?></a></li>
          <?php }?>
        </ul>
        <?php }?>
      </li>
      <?php }?>
      <?php }?>
    </ul>
  </div>
  <div class="right-box">
     <div class="wtm-header">
    <div class="wtm-header-top">
      <div class="wtm-member-info">
        <div class="avatar"><a href="<?php echo urlMember('member_information', 'avatar');?>" title="修改头像"><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>">
          <div class="frame"></div>
          </a>
          <?php if (intval($output['message_num']) > 0){ ?>
          <a href="<?php echo MEMBER_SITE_URL?>/index.php?w=member_message&t=message" class="new-message" title="新消息"><?php echo intval($output['message_num']); ?></a>
          <?php }?>
        </div>
        <dl>
          <dt><a href="<?php echo urlMember('member_information', 'member');?>" title="修改资料"><?php echo $output['member_info']['member_name'];?></a></dt>
          <dd>会员等级：
            <?php if ($output['member_info']['level_name']){ ?>
            <div class="wt-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?>会员</div>
            <?php } ?>
          </dd>
          <dd>账户安全：
            <div class="SAM"><a href="<?php echo urlMember('member_security','index');?>" title="安全设置">
              <?php if ($output['member_info']['security_level'] <= 1) { ?>
              <div id="low" class="SAM-info"><span><em></em></span><strong>低</strong></div>
              <?php } elseif ($output['member_info']['security_level'] == 2) {?>
              <div id="normal" class="SAM-info"><span><em></em></span><strong>中</strong></div>
              <?php }else {?>
              <div id="high" class="SAM-info"><span><em></em></span><strong>高</strong></div>
              <?php } ?>
              </a> </div>
          </dd>
          <dd>用户财产：
            <div class="user-account">
              <ul>
                <li id="pre-deposit"><a href="<?php echo urlMember('predeposit', 'pd_log_list');?>" title="我的余额：￥<?php echo $output['member_info']['available_predeposit'];?>"> <span class="icon"></span> </a> </li>
                <li id="points"><a href="<?php echo urlMember('member_points', 'index');?>" title="我的积分：<?php echo $output['member_info']['member_points'];?>分"> <span class="icon"></span></a> </li>
                <li id="voucher"><a href="<?php echo urlMember('member_voucher', 'index');?>" title="我的代金券：<?php echo $output['member_info']['voucher_count'];?>张"> <span class="icon"></span></a> </li>
                <li id="envelope"><a href="<?php echo urlMember('member_coupon', 'index');?>" title="我的优惠券：<?php echo $output['member_info']['coupon_count'];?>张"> <span class="icon"></span></a></li>
              </ul>
            </div>
          </dd>
        </dl>
      </div>
      <div class="wtm-trade-menu">
        <div class="line-bg"></div>
        <dl class="trade-step-01">
          <dt>关注中</dt>
          <dd></dd>
        </dl>
        <ul class="trade-function-01">
          <li><a href="<?php echo urlShop('member_favorite_goods', 'index');?>"><span class="tf01"></span>
            <h5>商品</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_favorite_store', 'index');?>"><span class="tf02"></span>
            <h5>店铺</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_goodsbrowse', 'list');?>"><span class="tf03"></span>
            <h5>足迹</h5>
            </a> </li>
        </ul>
        <dl class="trade-step-02">
          <dt>交易进行</dt>
          <dd></dd>
        </dl>
        <ul class="trade-function-02">
          <li><a href="<?php echo urlShop('member_order', 'index', array('state_type' => 'state_new'));?>">
            <?php if ($output['order_tip']['order_nopay_count'] > 0) {?>
            <sup><?php echo $output['order_tip']['order_nopay_count']?></sup>
            <?php }?>
            <span class="tf04"></span>
            <h5>待付款</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_order', 'index', array('state_type' => 'state_send'));?>">
            <?php if ($output['order_tip']['order_noreceipt_count'] > 0) {?>
            <sup><?php echo $output['order_tip']['order_noreceipt_count']?></sup>
            <?php }?>
            <span class="tf05"></span>
            <h5>待收货</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_order', 'index', array('state_type' => 'state_notakes'));?>">
            <?php if ($output['order_tip']['order_notakes_count'] > 0) {?>
            <sup><?php echo $output['order_tip']['order_notakes_count']?></sup>
            <?php }?>
            <span class="tf06"></span>
            <h5>待自提</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_order', 'index', array('state_type' => 'state_noeval'));?>">
            <?php if ($output['order_tip']['order_noeval_count'] > 0) {?>
            <sup><?php echo $output['order_tip']['order_noeval_count']?></sup>
            <?php }?>
            <span class="tf07"></span>
            <h5>待评价</h5>
            </a> </li>
        </ul>
        <dl class="trade-step-03">
          <dt>售后服务</dt>
          <dd></dd>
        </dl>
        <ul class="trade-function-03">
          <li><a href="<?php echo urlShop('member_refund', 'index');?>"><span class="tf08"></span>
            <h5>退款</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_return', 'index');?>"><span class="tf09"></span>
            <h5>退货</h5>
            </a> </li>
          <li><a href="<?php echo urlShop('member_complain', 'index',array('select_complain_state' => '1'));?>"><span class="tf10"></span>
            <h5>投诉</h5>
            </a> </li>
        </ul>
      </div>
    </div>
    
  </div>
    <?php require_once($tpl_file);?>
  </div>
  <div class="clear"></div>
</div>
<?php require_once template('footer');?>
</body></html>