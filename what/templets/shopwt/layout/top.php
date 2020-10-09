<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWT">
<meta name="copyright" content="ShopWT Inc. All Rights Reserved">
<link href="<?php echo WHAT_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo WHAT_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
_CHARSET = '<?php echo strtolower(CHARSET);?>';
SITEURL = '<?php echo BASE_SITE_URL;?>';
BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';
var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script id="wt_dialog" type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo WHAT_STATIC_SITE_URL;?>/js/jquery.masonry.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo WHAT_STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".ms-box").mouseenter(function(){
            $("#what_search_type_list").show();
        });
        $(".ms-box").mouseleave(function(){
            $("#what_search_type_list").hide();
        });
        $("#what_search_type_list li").click(function(){
            $("#what_search span").text($(this).text());
            $("#what_search span").attr("search_type",$(this).attr("search_type"));
            $("#w").val($(this).attr("search_type"));
            $("#what_search_type_list").hide();
            $("#what_search").show();
        });
        $("#btn_search").click(function(){
            $("#form_search").submit();
        });

        /**
         * 同步第三方应用
         **/
            $("[wt_type='share_app_switch']").click(function(){
                    if($(this).attr("checked") == "checked") {
                    $(this).parent().find("[wt_type='bindbtn']").each(function(){
                        var data_str = $(this).attr('data-param');
                        eval( "data_str = "+data_str);
                        //判断是否已经绑定
                        var isbind = $(this).attr('attr_isbind');
                        if(isbind == '1'){//已经绑定
                        $(this).removeClass(data_str.apikey+'-disable');
                        $(this).addClass(data_str.apikey+'-enable');
                        $("#checkapp_"+data_str.apikey).val(data_str.apikey);
                        } else {
                        $(this).removeClass(data_str.apikey+'-enable');
                        $(this).addClass(data_str.apikey+'-disable');
                        $("#checkapp_"+data_str.apikey).val('');
                        }
                        });
                    } else {
                    $("[wt_type='bindbtn']").each(function(){
                        var data_str = $(this).attr('data-param');
                        eval( "data_str = "+data_str);
                        $(this).removeClass(data_str.apikey+'-enable');
                        $(this).addClass(data_str.apikey+'-disable');
                        $("#checkapp_"+data_str.apikey).val('');
                        });
                    }
            });
    $("[wt_type='bindbtn']").bind('click',function(){
            var data_str = $(this).attr('data-param');
            eval( "data_str = "+data_str);
            //判断是否已经绑定
            var isbind = $(this).attr('attr_isbind');
            if(isbind == '1'){//已经绑定
            if($("#checkapp_"+data_str.apikey).val() == ''){
            if($("[wt_type='share_app_switch']").attr("checked") == "checked") {
            $(this).removeClass(data_str.apikey+'-disable');
            $(this).addClass(data_str.apikey+'-enable');
            $("#checkapp_"+data_str.apikey).val(data_str.apikey);
            }
            }else{
            $(this).removeClass(data_str.apikey+'-enable');
            $(this).addClass(data_str.apikey+'-disable');
            $("#checkapp_"+data_str.apikey).val('');
            }
            }else{
            var html = $("#bindtooltip_module").text();
            //替换关键字
            html = html.replace(/@apikey/g,data_str.apikey);
            html = html.replace(/@apiname/g,data_str.apiname);
            html_form("bindtooltip", "<?php echo $lang['what_share_account_link'];?>", html, 360, 0);
            window.open('<?php echo MEMBER_SITE_URL.DS;?>api.php?w=sharebind&type='+data_str.apikey);
            }
    });
    $("#finishbtn").live('click',function(){
            var data_str = $(this).attr('data-param');
            eval( "data_str = "+data_str);
            //验证是否绑定成功
            var url = '<?php echo urlMember('member_sharemanage');?>&t=checkbind&callback=?';
            $.getJSON(url, {'k':data_str.apikey}, function(data){
                DialogManager.close('bindtooltip');
                if (data.done)
                {
                $("[wt_type='appitem_"+data_str.apikey+"']").addClass('check');
                $("[wt_type='appitem_"+data_str.apikey+"']").removeClass('disable');
                $('#checkapp_'+data_str.apikey).val('1');
                $("[wt_type='appitem_"+data_str.apikey+"']").find('i').attr('attr_isbind','1');
                }
                else
                {
                showDialog(data.msg, 'notice');
                }
                });
            });
		//我的商城
		$(".head-user-mall dl").hover(function() {
			$(this).addClass("hover");
		},
		function() {
			$(this).removeClass("hover");
		});
		$('.head-user-mall .my-mall').mouseover(function(){// 最近浏览的商品
			load_history_information();
			$(this).unbind('mouseover');
		});
    });
</script>
</head>
<body>
<!-- 头 -->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="wt-topbar w">
  <div class="topbar wrapper">
    <div class="service fl">
      <div class="home"><a href="<?php echo BASE_SITE_URL;?>" title="<?php echo $output['setting_config']['site_name']; ?>" target="_self">商城首页</a></div>
      <div class="tel"><?php echo $lang['wt_phone'];?><b><?php echo $output['setting_config']['wt_phone']; ?></b></div>
      <div class="m-mx"><span><i></i><a href="<?php echo WAP_SITE_URL;?>"><?php echo $lang['wt_wap'];?></a></span>
        <div>
          <?php if (C('mobile_isuse') && C('mobile_app')){?>
          <dl class="down_app">
            <dd>
              <div class="qrcode"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" width="120" height="120"></div>
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
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
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
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_message&t=message"><?php echo $lang['wt_msg'];?>(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_order" class="arrow"><?php echo $lang['wt_order'];?><i></i></a></li>
              <li><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_consult&t=my_consult"><?php echo $lang['wt_consult'];?>(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_favorite_goods&t=fglist" class="arrow"><?php echo $lang['wt_house'];?><i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_voucher"><?php echo $lang['wt_voucher'];?>(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_points" class="arrow"><?php echo $lang['wt_points'];?><i></i></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="my-history">
            <div class="part-title">
              <h4><?php echo $lang['wt_browse'];?></h4>
              <span style="float:right;"><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_goodsbrowse&t=list"><?php echo $lang['wt_history'];?></a></span> </div>
            <ul>
              <li class="no-goods"><img class="loading" src="<?php echo WHAT_TEMPLATES_URL;?>/images/loading.gif" /></li>
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
$(function(){
	$(".right-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

});
</script>
<!-- 导航 -->
<header id="topHeader">
  <div class="warp-all">
    <div class="wt-logo"> <a href="<?php echo WHAT_SITE_URL;?>">
      <?php if(C('what_logo')) { ?>
      <img src="<?php echo what_IMG_URL.DS.C('what_logo');?>" class="pngFix">
      <?php } else { ?>
      <img src="<?php echo what_IMG_URL.DS.'what_logo.png';?>" class="pngFix">
      <?php } ?>
      </a> </div>
    <div class="wt-header-pic"> <a href="<?php echo WHAT_SITE_URL;?>">
      <?php if(C('what_header_pic')) { ?>
      <img src="<?php echo what_IMG_URL.DS.C('what_header_pic');?>" class="pngFix">
      <?php } else { ?>
      <img src="<?php echo what_IMG_URL.DS.'right_banner.gif';?>" class="pngFix">
      <?php } ?>
      </a> </div>
    <div class="wt-search">
      <div class="ms-box">
        <div id="what_search" class="ms-type">
          <?php if(in_array($_GET['w'],array_keys($output['search_type']))) { ?>
          <span id="what_search_type" search_type="<?php echo $_GET['w'];?>"><?php echo $output['search_type'][$_GET['w']];?></span>
          <?php } else { ?>
          <span id="what_search_type" search_type="<?php echo key($output['search_type']);?>"><?php echo current($output['search_type']);?></span>
          <?php } ?>
          <i></i> </div>
        <ul class="ms-list" id="what_search_type_list" style="display:none;">
          <?php if(!empty($output['search_type']) && is_array($output['search_type'])) {?>
          <?php foreach($output['search_type'] as $key=>$val) {?>
          <li search_type="<?php echo $key;?>"><?php echo $val;?></li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <div class="ms-form">
        <form id="form_search" method="get" action="<?php echo WHAT_SITE_URL;?>/index.php">
          <?php if(in_array($_GET['w'],array_keys($output['search_type']))) { ?>
          <input id="w" name="w" type="hidden" value="<?php echo $_GET['w'];?>"/>
          <?php } else { ?>
          <input id="w" name="w" type="hidden" value="goods"/>
          <?php } ?>
          <?php if(isset($_GET['goods_class_root_id'])) { ?>
          <input name="goods_class_root_id" type="hidden" value="<?php echo $_GET['goods_class_root_id'];?>"/>
          <?php } ?>
          <?php if(isset($_GET['goods_class_menu_id'])) { ?>
          <input name="goods_class_menu_id" type="hidden" value="<?php echo $_GET['goods_class_menu_id'];?>"/>
          <?php } ?>
          <input id="keyword" name="keyword" type="text" class="input-text" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>" maxlength="60" x-webkit-speech="" lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" />
          <input id="btn_search" type="button" class="input-button pngFix">
        </form>
      </div>
    </div>
  </div>
</header>
<div id="navBar" class="pngFix">
  <div id="navBox">
    <ul class="wt-nav-menu">
      <li <?php echo $output['index_sign'] == 'index'&&$output['index_sign'] != '0'?'class="current"':'class="link"'; ?>><a href="<?php echo WHAT_SITE_URL;?>" class="pngFix"><span class="pngFix"><?php echo $lang['wt_index'];?></span></a></li>
      <li <?php echo $output['index_sign'] == 'goods'&&$output['index_sign'] != '0'?'class="current"':'class="link"'; ?>><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=goods" class="pngFix"><span class="pngFix"><?php echo $lang['wt_what_goods'];?></span></a></li>
      <!--
      <li <?php echo $output['index_sign'] == 'album'&&$output['index_sign'] != '0'?'class="current"':'class="link"'; ?>><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=album"><span><?php echo $lang['wt_what_album'];?></span></a></li>
      -->
      <li <?php echo $output['index_sign'] == 'personal'&&$output['index_sign'] != '0'?'class="current"':'class="link"'; ?>><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=personal" class="pngFix"><span class="pngFix"><?php echo $lang['wt_what_personal'];?></span></a></li>
      <li <?php echo $output['index_sign'] == 'store'&&$output['index_sign'] != '0'?'class="current"':'class="link"'; ?>><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=store" class="pngFix"><span class="pngFix"><?php echo $lang['wt_what_store'];?></span></a></li>
    </ul>
    <div class="wtMall-user">
      <?php $member_avatar = WHAT_TEMPLATES_URL.DS.'images'.DS.'default_user_portrait.gif' ?>
      <?php if(isset($_SESSION['is_login'])) { ?>
      <?php $member_avatar = getMemberAvatar($_SESSION['avatar']); ?>
      <?php } ?>
      <div class="head-portrait"><span class="thumb size32" title="<?php echo $_SESSION['member_name'];?>"><i></i><img src="<?php echo $member_avatar;?>" onload="javascript:DrawImage(this,30,30);" /></span></div>
      <ul class="sub-menu">
        <?php if(isset($_SESSION['is_login'])) {?>
        <li class="pngFix"><a href="javascript:void(0)"><span title="<?php echo $_SESSION['member_name'];?>"><?php echo $_SESSION['member_name'];?></span><i></i></a>
          <ul>
            <li><a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=goods'?>"><?php echo $lang['wt_what_goods'];?></a></li>
            <li><a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=personal'?>"><?php echo $lang['wt_what_personal'];?></a></li>
            <!--
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=album'?>"><?php echo $lang['wt_what_album'];?></a> </li>
            -->
          </ul>
        </li>
        <?php } else { ?>
        <li class="no-sub pngFix"><a href="<?php echo urlLogin('login', 'index');?>"><?php echo $lang['wt_login'];?></a></li>
        <?php } ?>
        <li class="pngFix"><a href="javascript:void(0)"><?php echo $lang['wt_publish'];?><i></i></a>
          <ul>
            <li><a href="<?php echo WHAT_SITE_URL.'/index.php?w=publish&t=goods_buy';?>"><?php echo $lang['what_goods_buy'];?></a> </li>
            <li><a href="<?php echo WHAT_SITE_URL.'/index.php?w=publish&t=goods_favorites';?>"><?php echo $lang['what_goods_favorite'];?></a> </li>
            <li><a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=personal&publish=personal';?>"><?php echo $lang['wt_what_personal'];?></a> </li>
            <!--
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=publish&t=album';?>"><?php echo $lang['wt_what_album'];?></a> </li>
            -->
          </ul>
        </li>
        <li class="pngFix"><a href="javascript:void(0)"><?php echo $lang['what_text_like'];?><i></i></a>
          <ul>
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=like_list&type=goods'?>"><?php echo $lang['wt_what_goods'];?></a> </li>
            <!--
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=like_list&type=personal'?>"><?php echo $lang['wt_what_album'];?></a> </li>
            -->
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=like_list&type=personal'?>"><?php echo $lang['wt_what_personal'];?></a> </li>
            <li> <a href="<?php echo WHAT_SITE_URL.'/index.php?w=home&t=like_list&type=store'?>"><?php echo $lang['wt_what_store'];?></a> </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
