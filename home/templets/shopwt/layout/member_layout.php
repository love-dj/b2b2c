<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/common_layout');?>
<style type="text/css">
.wt-nav { display:none;}
</style>
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
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
$(function() {
	//展开关闭常用菜单设置
	$('.set-btn').bind("click",
	function() {
		$(".set-container-arrow").show("fast");
		$(".set-container").show("fast");
	});
	$('[wttype="closeCommonOperations"]').bind("click",
	function() {
		$(".set-container-arrow").hide("fast");
		$(".set-container").hide("fast");
	});

    $('dl[wttype="checkcCommonOperations"]').find('input').click(function(){
        var _this = $(this);
        var _dd = _this.parents('dd:first');
        var _type = _this.is(':checked') ? 'add' : 'del';
        var _value = _this.attr('name');
        var _operations = $('[wttype="commonOperations"]');

        // 最多添加5个
        if (_operations.find('li').length >= 5 && _type == 'add') {
            showError('最多只能添加5个常用选项。');
            return false;
        }
        $.getJSON('<?php echo urlShop('member', 'common_operations')?>', {type : _type, value : _value}, function(data){
            if (data) {
                if (_type == 'add') {
                    _dd.addClass('checked');
                    if (_operations.find('li').length == 0) {
                        _operations.fadeIn('slow');
                    }
                    _operations.find('ul').append('<li style="display : none;" wttype="' + _value + '"><a href="' + _this.attr('data-value') + '">' + _this.attr('data-name') + '</a></li>');
                    _operations.find('li[style]').fadeIn('slow');
                } else {
                    _dd.removeClass('checked');
                    _operations.find('li[wttype="' + _value + '"]').fadeOut('slow', function(){
                        $(this).remove();
                        if (_operations.find('li').length == 0) {
                            _operations.fadeOut('slow');
                        }
                    });
                }
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
        <li class="ml10"><a href="<?php echo urlShop('member', 'home');?>">我的商城<i></i></a>
          <div class="sub-menu">
            <dl>
              <dt><a href="<?php echo urlShop('member_order', 'index');?>" style="color: #398EE8;">交易管理</a></dt>
              <dd><a href="<?php echo urlShop('member_order', 'index');?>">实物交易订单</a></dd>
              <dd><a href="<?php echo urlShop('member_order_vr', 'index');?>">虚拟兑码订单</a></dd>
              <dd><a href="<?php echo urlShop('member_evaluate', 'list');?>">评价/晒单</a></dd>
              <dd><a href="<?php echo urlShop('member_appoint', 'list');?>">预约/到货通知</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlShop('member_favorite_goods', 'index')?>" style="color: #3AAC8A">收藏关注</a></dt>
              <dd><a href="<?php echo urlShop('member_favorite_goods', 'index');?>">收藏的商品</a></dd>
              <dd><a href="<?php echo urlShop('member_favorite_store', 'index')?>">收藏的店铺</a></dd>
              <dd><a href="<?php echo urlShop('member_goodsbrowse', 'list');?>">浏览足迹</a></dd>
            </dl>
            <dl>
              <dt><a href="<?php echo urlShop('member_refund', 'index')?>" style="color: #B68571">服务售后</a></dt>
              <dd><a href="<?php echo urlShop('member_refund', 'index');?>">退款/退货</a></dd>
              <dd><a href="<?php echo urlShop('member_complain', 'index')?>">交易投诉</a></dd>
              <dd><a href="<?php echo urlShop('member_consult', 'my_consult');?>">商品咨询</a></dd>
              <dd><a href="<?php echo urlShop('member_mallconsult', 'index');?>">平台客服</a></dd>
            </dl>
          </div>
        </li>
        <li><a href="<?php echo urlMember('member', 'home');?>" class="current">用户设置</a> </li>
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
          <a href="<?php echo MEMBER_SITE_URL?>index.php?w=member_message&t=message" class="new-message" title="新消息"><?php echo intval($output['message_num']); ?></a>
          <?php }?>
        </div>
        <dl>
          <dt><a href="<?php echo urlMember('member_information', 'member');?>" title="修改资料"><?php echo $output['member_info']['member_name'];?></a></dt>
          <dd>会员等级：
            <?php if ($output['member_info']['level_name']){ ?>
            <div class="wt-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?>会员</div>
            <?php } ?>
          </dd>
          <dd>上次登录：<?php echo date('Y年m月d日',$output['member_info']['member_old_login_time']);?> </dd>
          <dd>登录绑定：
            <div class="user-account">
              <ul>
                <li id="qq"><a href="<?php echo urlMember('member_bind', 'qqbind');?>" title="登录绑定QQ账号" <?php if (!empty($output['member_info']['member_qqopenid'])){?>class="have"<?php }?>> <span class="icon"></span> </a> </li>
                <li id="weichat"><a href="<?php echo urlMember('member_bind', 'weixinbind');?>" title="登录绑定微信账号" <?php if (!empty($output['member_info']['weixin_unionid'])){?>class="have"<?php }?>> <span class="icon"></span></a> </li>
                <li id="weibo"><a href="<?php echo urlMember('member_bind', 'sinabind');?>" title="登录绑定微博账号" <?php if (!empty($output['member_info']['member_sinaopenid'])){?>class="have"<?php }?>> <span class="icon"></span></a> </li>
              </ul>
            </div>
          </dd>
        </dl>
      </div>
      <div class="wtm-set-menu">
        <dl class="zhaq">
          <dt>账户安全</dt>
          <dd>
            <ul>
              <li><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_pwd'));?>"><span class="zhaq01"></span><sub></sub>
                <h5>修改密码</h5>
                </a> </li>
              <li <?php if($output['member_info']['member_email_bind'] == '1') {?>class="have"<?php }?>><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_email'));?>"><span class="zhaq02"></span><sub></sub>
                <h5>邮箱绑定</h5>
                </a> </li>
              <li <?php if($output['member_info']['member_mobile_bind'] == '1') {?>class="have"<?php }?>><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_mobile'));?>"><span class="zhaq03"></span><sub></sub>
                <h5>手机绑定</h5>
                </a> </li>
              <li <?php if($output['member_info']['member_paypwd'] != '') {?>class="have"<?php }?>><a href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd'));?>"><span class="zhaq04"></span><sub></sub>
                <h5>支付密码</h5>
                </a> </li>
            </ul>
          </dd>
        </dl>
        <dl class="zhcc">
          <dt>账户财产</dt>
          <dd>
            <ul>
              <li><a href="<?php echo urlMember('predeposit', 'recharge_add');?>"> <span class="zhcc01"></span>
                <h5>在线充值</h5>
                </a> </li>
              <li><a href="<?php echo urlMember('predeposit', 'rechargecard_add');?>"> <span class="zhcc02"></span>
                <h5>充值卡充值</h5>
                </a> </li>
              <li><a href="<?php echo urlMember('member_voucher', 'voucher_binding')?>"><span class="zhcc03"></span>
                <h5>领取代金券</h5>
                </a> </li>
              <li><a href="<?php echo urlMember('member_coupon', 'rp_binding');?>"> <span class="zhcc04"></span>
                <h5>领取优惠券</h5>
                </a> </li>
            </ul>
          </dd>
        </dl>
        <dl class="xgsz">
          <dt>相关设置</dt>
          <dd>
            <ul class="trade-function-03">
              <li><a href="<?php echo urlMember('member_address', 'address');?>"><span class="xgsz01"></span>
                <h5>收货地址</h5>
                </a> </li>
              <li><a href="<?php echo urlMember('member_message', 'setting');?>"><span class="xgsz02"></span>
                <h5>消息接收</h5>
                </a> </li>
              
            </ul>
          </dd>
        </dl>
      </div>
    </div>
  </div>
   
    <?php require_once($tpl_file);?>
  </div>
  <div class="clear"></div>
</div>
<?php require_once template('layout/footer');?>
</body></html>