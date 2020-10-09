<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php if ($output['hide_rightbar'] != 1) {?>
<div id="vToolbar" class="wt-appbar">
  <div class="wt-appbar-tabs" id="siteslidebar">
    <?php if ($_SESSION['is_login']) {?>
    <div class="user ta_delay" wttype="a-barUserInfo">
      <div class="avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo getMemberAvatar($_SESSION['avatar']);?>" rel="lazy" /></div>
      <p>我的商城</p>
    </div>
    <div class="user-info" wttype="barUserInfo" style="display:none;"><i class="arrow"></i>
      <div class="avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo getMemberAvatar($_SESSION['avatar']);?>" rel="lazy" />
        <a href="<?php echo urlMember('member_information', 'avatar');?>" title="修改头像"><div class="frame"></div></a>
      </div>
      <dl>
        <dt>Hi, <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a></dt>
        <dd>当前等级：<strong wttype="barMemberGrade"><?php echo $output['member_info']['level_name'];?></strong></dd>
        <dd>当前经验值：<strong wttype="barMemberExp"><?php echo $output['member_info']['member_exppoints'];?></strong></dd>
      </dl>
	 	<div class="wt-appbar-menu">
            <ul>
              <li><a href="<?php echo urlMember('member_message','message');?>"><?php echo $lang['wt_msg'];?>(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo urlShop('member_order','index');?>" class="arrow"><?php echo $lang['wt_order'];?><i></i></a></li>
              <li><a href="<?php echo urlShop('member_consult','my_consult');?>"><?php echo $lang['wt_consult'];?>(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo urlShop('member_favorite_goods','fglist');?>" class="arrow"><?php echo $lang['wt_house'];?><i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo urlMember('member_voucher','index');?>"><?php echo $lang['wt_voucher'];?>(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo urlMember('member_points','index');?>" class="arrow"><?php echo $lang['wt_points'];?><i></i></a></li>
              <?php } ?>
            </ul>
          </div>
    </div>
    <?php } else {?>
    <div class="user ta_delay" wttype="a-barLoginBox">
      <div class="avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo getMemberAvatar($_SESSION['avatar']);?>" rel="lazy" /></div>
      <p>请登录</p>
    </div>
    <div class="user-login-box" wttype="barLoginBox" style="display:none;"> <i class="arrow"></i> <a href="javascript:void(0);" class="close-a" wttype="close-barLoginBox" title="关闭">X</a>
      <form id="login_form" method="post" action="<?php echo urlLogin('login', 'login');?>" onsubmit="ajaxpost('login_form', '', '', 'onerror')">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="wthash" type="hidden" value="<?php echo getWthash('login','index');?>" />
        <dl>
          <dt>用户名</dt>
          <dd>
            <input type="text" class="text" tabindex="1" autocomplete="off"  name="user_name" autofocus >
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>密&nbsp;&nbsp;&nbsp;&nbsp;码</dt>
          <dd>
            <input tabindex="2" type="password" class="text" name="password" autocomplete="off">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt>验证码</dt>
          <dd>
            <input tabindex="3" type="text" name="captcha" autocomplete="off" class="text w130" id="captcha2" maxlength="4" size="10" />
            <img src="" name="codeimage" border="0" id="codeimage" title="更换验证码" onclick="javascript:document.getElementById('codeimage').src='<?php echo BASE_SITE_URL?>/index.php?w=vercode&type=30x92&c=' + Math.random();" class="vt">
            <label></label>
          </dd>
        </dl>
        <?php } ?>
            <div class="bottom">
              <input type="submit" class="submit" value="确认">
              <input type="hidden" value="" name="ref_url">
              <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1){?>
              <span class="enter">
              <?php if (C('weixin_isuse') == 1){?>
              <a class="wx" href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo urlLogin('connect_wx', 'index');?>', 360);" title="微信账号登录"><i></i></a>
              <?php } ?>
              <?php if (C('sina_isuse') == 1){?>
              <a class="sina" href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_sina" title="新浪微博账号登录"><i></i></a>
              <?php } ?>
              <?php if (C('qq_isuse') == 1){?>
              <a class="qq" href="<?php echo MEMBER_SITE_URL;?>/index.php?w=connect_qq" title="QQ账号登录"><i></i></a>
              <?php } ?>
				</span>
              <?php } ?>
			<h4><?php echo $lang['wt_otherlogintip'];?></h4>
          </div>
      </form>
    </div>
    <?php }?>
    <ul class="tools">
    <?php if(C('node_chat')){ ?>
      <li><a href="javascript:void(0);" id="chat_show_user" class="chat ta_delay"><div class="tools_img"></div><span>聊天</span><i id="new_msg" class="new_msg" style="display:none;"></i></a></li>
        <?php } else {?>
         <li><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $output['setting_config']['wt_qq']; ?>&amp;site=qq&amp;menu=yes" target="_blank" id="chat_show_user" class="chat ta_delay"><div class="tools_img"></div><span>QQ客服</span><i id="new_msg" class="new_msg" style="display:none;"></i></a></li>
     <?php } ?>
      <?php if (!$output['hidden_rtoolbar_cart']) { ?>
      <li><a href="javascript:void(0);" id="rtoolbar_cart" class="cart ta_delay"><div class="tools_img"></div><span>购物车</span><i id="rtoobar_cart_count" class="new_msg" style="display:none;"></i></a></li>
      <?php } ?>
      <?php if (!$output['hidden_rtoolbar_compare']) { ?>
      <li><a href="javascript:void(0);" id="compare" class="compare ta_delay"><div class="tools_img"></div><span>对比</span></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('member_goodsbrowse','list');?>" class="track ta_delay"><div class="tools_img"></div><span>我的足迹</span></a></li>
    </ul>
    <div class="content-box" id="content-compare">
      <div class="top">
        <h3>商品对比</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="comparelist"></div>
    </div>
    <div class="content-box" id="content-cart">
      <div class="top">
        <h3>我的购物车</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="rtoolbar_cartlist"></div>
    </div>
    <a id="activator" href="javascript:void(0);" class="wt-appbar-hide"></a>
	<a id="gotop" href="javascript:void(0);" class="wt-appbar-gotop"></a>
	</div>
  <div class="wt-hidebar" id="wtHideBar">
    <div class="wt-hidebar-bg">
      <?php if ($_SESSION['is_login']) {?>
      <div class="user-avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo getMemberAvatar($_SESSION['avatar']);?>" rel="lazy" /></div>
      <?php } else {?>
      <div class="user-avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo getMemberAvatar($_SESSION['avatar']);?>" rel="lazy"/></div>
      <?php }?>
      <div class="frame"></div>
      <div class="show"></div>
  </div>
</div>
</div>
<?php } ?>
<?php if ($output['setting_config']['wt_top_banner_status']>0 && $output['index_sign'] == 'index' && $output['index_sign'] != '0'){ ?>
<div style=" background:<?php echo $output['setting_config']['wt_top_banner_color']; ?>;">
  <div class="wrapper" id="top-banner" style="display: none;">
      <a href="javascript:void(0);" class="close" title="关闭"></a>
      <a href="<?php echo $output['setting_config']['wt_top_banner_url']; ?>" title="<?php echo $output['setting_config']['wt_top_banner_name']; ?>"><img border="0" src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['wt_top_banner_pic']; ?>" alt="" rel="lazy" /></a>
  </div>
</div>
<?php } ?>
<div class="wt-topbar w">
  <div class="topbar wrapper">
    <div class="service fl">
      <?php if($output['index_sign'] != 'index' && $output['index_sign'] != '1'){?>
      <div class="home"><a href="<?php echo BASE_SITE_URL;?>" title="<?php echo $output['setting_config']['site_name']; ?>" target="_self">商城首页</a></div>
      <?php } ?>
      <div class="tel"><?php echo $lang['wt_phone'];?><b><?php echo $output['setting_config']['wt_phone']; ?></b></div>
      <div class="m-mx"> <span><i></i><a href="<?php echo WAP_SITE_URL;?>"><?php echo $lang['wt_wap'];?></a></span>
        <div>
          <?php if (C('mobile_isuse') && C('mobile_app')){?>
          <dl class="down_app">
            <dd>
              <div class="qrcode"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" width="120" height="120" rel="lazy" /></div>
              <div class="hint">
                <h4><?php echo $lang['wt_mobile_client'];?></h4>
                <?php echo $lang['wt_scancode'];?></div>
              <div class="addurl">
                <?php if (C('mobile_apk')){?>
                <a href="<?php echo C('mobile_apk');?>" target="_blank"><i class="icon-android"></i>Android</a>
                <?php } ?>
                <?php if (C('mobile_ios')){?>
                <a href="<?php echo C('mobile_ios');?>" target="_blank"><i class="icon-apple"></i>iPhone</a>
                <?php } ?>
              </div>
            </dd>
          </dl>
          <?php } ?>
        </div>
      </div>
      <div class="left">
      <div id="TopCity"></div>
      </div>
    </div>
    <div class="right-menu">
      <dl>
        <dt><?php echo $lang['wt_service'];?><i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 2));?>"><?php echo $lang['wt_help'];?></a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 5));?>"><?php echo $lang['wt_customer'];?></a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 6));?>"><?php echo $lang['wt_custom'];?></a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><a href="<?php echo urlShop('show_joinin','index');?>" title="<?php echo $lang['wt_seller'];?>"><?php echo $lang['wt_seller'];?></a><i></i></dt>
        <dd>
          <ul>
		    <li><a href="<?php echo BASE_SITE_URL;?>/index.php?w=show_joinin&t=index" title="<?php echo $lang['wt_enter'];?>"><?php echo $lang['wt_enter'];?></a></li>
            <li><a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" title="<?php echo $lang['wt_seller_login'];?>"><?php echo $lang['wt_seller_login'];?></a></li>
          </ul>
	 </dd>
      </dl>
      <?php
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	      foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	      }
      }
      if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){
      	?>
      <dl>
        <dt><?php echo $lang['wt_site_nav'];?><i></i></dt>
        <dd>
          <ul>
            <?php foreach($output['nav_list_top'] as $nav){?>
            <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        echo ' href="';
        switch($nav['nav_type']) {
        	case '0':echo $nav['nav_url'];break;
        	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	case '2':echo urlShop('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	case '3':echo urlShop('activity', 'index', array('activity_id'=>$nav['item_id']));break;
        }
        echo '"';
        ?>><?php echo $nav['nav_title'];?></a></li>
            <?php }?>
          </ul>
        </dd>
      </dl>
      <?php } ?>
    </div>
    <div class="head-user-mall">
    <dl class="my-mall">
        <dt><span class="ico"></span><a href="<?php echo urlShop('member', 'home');?>" title="<?php echo $lang['wt_menber'];?>"><?php echo $lang['wt_menber'];?></a><i class="arrow"></i></dt>
        <dd>
          <div class="my-top-menu">
            <ul>
              <li><a href="<?php echo urlMember('member_message','message');?>"><?php echo $lang['wt_msg'];?>(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo urlShop('member_order','index');?>" class="arrow"><?php echo $lang['wt_order'];?><i></i></a></li>
              <li><a href="<?php echo urlShop('member_consult','my_consult');?>"><?php echo $lang['wt_consult'];?>(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo urlShop('member_favorite_goods','fglist');?>" class="arrow"><?php echo $lang['wt_house'];?><i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo urlMember('member_voucher','index');?>"><?php echo $lang['wt_voucher'];?>(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo urlMember('member_points','index');?>" class="arrow"><?php echo $lang['wt_points'];?><i></i></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="my-history">
            <div class="part-title">
              <h4><?php echo $lang['wt_browse'];?></h4>
              <span style="float:right;"><a href="<?php echo urlShop('member_goodsbrowse','list');?>"><?php echo $lang['wt_history'];?></a></span> </div>
            <ul>
              <li class="no-goods"><img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /></li>
            </ul>
          </div>
        </dd>
      </dl>
    </div>
    <div class="user-entry">
      <?php echo $lang['wt_hello'];?>, <?php if($_SESSION['is_login'] == '1'){?>
      <span> <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a>
      <?php if ($output['member_info']['level_name']){ ?>
      <div class="wt-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
      <?php } ?>
      </span><span class="wr"><a href="<?php echo urlLogin('login','logout');?>"><?php echo $lang['wt_logout'];?></a></span>
      <?php }else{?>
      <span class="wr"><a class="login" href="<?php echo urlMember('login');?>">请<?php echo $lang['wt_login'];?></a> <a href="<?php echo urlLogin('login','register');?>"><?php echo $lang['wt_register'];?></a></span>
      <?php }?>
      <span><a href="<?php echo BASE_SITE_URL;?>/index.php?w=invite"><?php echo $lang['wt_invite'];?></a></span>
    </div>
  </div>
</div>
<script type="text/javascript">
var getcity = '全国';
//登录开关
var connect_qq = "<?php echo C('qq_isuse')?>";
var connect_sn = "<?php echo C('sina_isuse')?>";
var connect_wx = "<?php echo C('weixin_isuse')?>";
var connect_wx_appid = "<?php echo C('weixin_appid');?>";

$(function() {
	if (($.cookie("wt0"))) {
		tCity = $.cookie("wt0").split(',');
		$("#tbarea").html(tCity[2]);
	} else {
		$("#tbarea").html(getcity);
	}
	$('#gotop').click(function(){
	        $('html, body').animate({
	            scrollTop: 0
	        }, 500);
	});
	var wtkey = getCookie('wtkey');
		if(wtkey){
		$("#top-banner").hide();
		} else {
			$("#top-banner").slideDown(800);
			}
		$("#top-banner .close").click(function(){
			setCookie('wtkey','yes',1);
			$("#top-banner").hide();
	});
		$(".head-user-mall dl").hover(function() {
			$(this).addClass("hover");
		},
		function() {
			$(this).removeClass("hover");
		});
		$('.head-user-mall .my-mall').mouseover(function(){
			load_history_information();
			$(this).unbind('mouseover');
		});
	
		$('#activator').click(function() {
			$('#content-cart').animate({'right': '-250px'});
			$('#content-compare').animate({'right': '-250px'});
			$('#vToolbar').animate({'right': '-60px'}, 300,
			function() {
				$('#wtHideBar').animate({'right': '59px'},	300);
			});
	        $('div[wttype^="bar"]').hide();
		});
		$('#wtHideBar').click(function() {
			$('#wtHideBar').animate({
				'right': '-86px'
			},
			300,
			function() {
				$('#content-cart').animate({'right': '-250px'});
				$('#content-compare').animate({'right': '-250px'});
				$('#vToolbar').animate({'right': '6px'},300);
			});
		});
    $("#compare").click(function(){
    	if ($("#content-compare").css('right') == '-250px') {
 		   loadCompare(false);
 		   $('#content-cart').animate({'right': '-250px'});
  		   $("#content-compare").animate({right:'0px'});
    	} else {
    		$(".close").click();
    		$(".chat-list").css("display",'none');
        }
	});
    $("#rtoolbar_cart").click(function(){
        if ($("#content-cart").css('right') == '-250px') {
         	$('#content-compare').animate({'right': '-250px'});
    		$("#content-cart").animate({right:'0px'});
    		if (!$("#rtoolbar_cartlist").html()) {
    			$("#rtoolbar_cartlist").load('<?php echo BASE_SITE_URL;?>/index.php?w=cart&t=ajax_load&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	$(".close").click(function(){
		$(".content-box").animate({right:'-250px'});
      });

	$(".right-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

    // 右侧bar用户信息
    $('div[wttype="a-barUserInfo"]').click(function(){
        $('div[wttype="barUserInfo"]').toggle();
    });
    // 右侧bar登录
    $('div[wttype="a-barLoginBox"]').click(function(){
        $('div[wttype="barLoginBox"]').toggle();
        document.getElementById('codeimage').src='<?php echo BASE_SITE_URL?>/index.php?w=vercode&type=30x92&c=' + Math.random();
    });
    $('a[wttype="close-barLoginBox"]').click(function(){
        $('div[wttype="barLoginBox"]').toggle();
    });
    <?php if ($output['cart_goods_num'] > 0) { ?>
    $('#rtoobar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
    <?php } ?>
    });
	
(function() {
	var ReplaceTpl = function(str, o, regexp) {
		return str.replace(regexp || /\\?\{([^{}]+)\}/g, function(match, name) {
			return (o[name] === undefined) ? '' : o[name];
		});
	};
	$.ReplaceTpl = ReplaceTpl;
	String.prototype.wtReplaceTpl = function(option) {
		return ReplaceTpl(this, option);
	};
})();
window.ShopWT = {},
    ["Array", "Object", "Function", "String", "Number", "Date", "Boolean", "regexp"].forEach(function(e) {
        ShopWT["is" + e] = function(t) {
            return jQuery.type(t) === e.toLowerCase()
        }
    }),
    jQuery.extend(ShopWT, {
        isEmpty: function(e) {
            return ShopWT.isBoolean(e) ? !e: null == e ? !0 : ShopWT.isNumber(e) ? 0 >= e: ShopWT.isArray(e) || ShopWT.isString(e) || e instanceof jQuery ? 0 === e.length: 0 === Object.keys(e).length
        },
    });
var topMyCity = function($) {
    "use strict";
    var areaUrl = SITEURL + '/index.php?w=index&t=area_list&area_id=0';
    var $element = {};
    var tmpl = '<a href="javascript:;" class="area"><span class="icon-map-marker"></span><span id="shortCutAreaName" title="{areaName}" data-id="{areaId}">{areaName}</span> <i></i></a>'
    function _init() {
        $element.TopCity = $("#TopCity");
        _getAreaList();
        "MjJTaG9wTkMySW5jMjIy";
    }
    function _getAreaIdByCookie() {
        return $.cookie("wt0");
    }
    function _setAreaIdByCookie(value) {
        return $.cookie("wt0", value, {
            expires: 30,
            path: '/'
        });
    }
    function _getAreaList() {
        $.getJSON(areaUrl, function(data) {
            if (data.code == "200") {
                _buildElement(data.data.areaList)
            }
        })
    }
    function _buildElement(data) {
        var myCookie = _getAreaIdByCookie(),
            myCity = !ShopWT.isEmpty(myCookie) ? myCookie.split(",") : "";
        var a = $.map(data, function(n, index) {
		//5.3	
		if (ShopWT.isEmpty($.cookie("wt0"))){
			$.each(data,function(i){
				if(getcity == this.areaName){	
						$.cookie("wt0", [this.areaId, this.areaDeep, this.areaName].join(","), {
						expires: 30,
						path: '/'
					});
				}
			});
		}
            return '<div class="item"><a href="javascript:;" data-deep="' + n.areaDeep + '" data-id="' + n.areaId + '" class="' +
                ((myCity.length && n.areaId == myCity[0]) ? "selected" : "") + '">' + n.areaName + '</a></div>';
        });
 if (a.length) {
            a.splice(0, 0, '<div class="item"><a href="javascript:;" data-deep="1" data-id="0" class="">全国</a></div>');
        	}
        $element.TopCity
            .append(tmpl.wtReplaceTpl(myCity ? {
                areaId: myCity[0],
                areaName: myCity[2]
            } : {
                areaId: 0,
                areaName: getcity
            }))
            .append(
                a.length ? '<div id="shortCutAreaList" class="area-list">' + a.join('') + '</div>' : '');
    }
	
	//更换当前URL
	function changeURLPar(destiny, par, par_value){
		var pattern = par+'=([^&]*)';
		var replaceText = par+'='+par_value;
		if (destiny.match(pattern)){
			var tmp = '/\\'+par+'=[^&]*/';
			tmp = destiny.replace(eval(tmp), replaceText);
			return (tmp);
		} else {
			if (destiny.match('[\?]')) {
				return destiny+'&'+ replaceText;
			} else {
				return destiny+'?'+replaceText;
			}
		}
		return destiny+'\n'+par+'\n'+par_value;
	} 

    function bindEvents() {
        $element.TopCity.on("click", "a[data-id]", function() {
            var $this = $(this);
            $("#shortCutAreaList a").removeClass("selected");
            $this.addClass("selected");
            $("#shortCutAreaName")
                .html($this.html())
                .attr({
                    title: $this.html(),
                    "data-id": $this.data("id") ? $this.data("id") : 0
                });
            //修改cookie
            _setAreaIdByCookie([$this.data("id"), $this.data("deep"), $this.html()].join(","));
			$('.region').html("请选择地区");
			$.cookie('dregion', '', { expires: -1 });
			var geturl = changeURLPar(window.location.href, 'area_id', $this.data("id"));
            location.replace(geturl);
			//location.reload(geturl);
        })
    }

    return {
        init: function() {
            _init();
            bindEvents();
        },
        getAreaIdByCookie: _getAreaIdByCookie
    };
}(jQuery);
$(function() {
	topMyCity.init();

 });
</script>