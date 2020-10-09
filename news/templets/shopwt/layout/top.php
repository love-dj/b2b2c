<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo empty($output['seo_title'])?$output['html_title']:$output['seo_title'].'-'.$output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<link href="<?php echo NEWS_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo NEWS_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';var COOKIE_PRE = '<?php echo COOKIE_PRE;?>'; var _CHARSET = '<?php echo strtolower(CHARSET);?>'; var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>'; var SITEURL = '<?php echo BASE_SITE_URL;?>'; var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>'; var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script id="wt_dialog" type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo NEWS_STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript">
var LOADING_IMAGE = '<?php echo getLoadingImage();?>';
$(function(){
	//search
	$("#searchNEWS").children('ul').children('li').click(function(){
		$(this).parent().children('li').removeClass("current");
		$(this).addClass("current");
        $("#form_search").attr("action", $(this).attr("action"));
        $("#w").val($(this).attr("w"));
        $("#t").val($(this).attr("t"));
	});
    var search_current_item = $("#searchNEWS").children('ul').children('li.current');
    $("#form_search").attr("action", search_current_item.attr("action"));
    $("#w").val(search_current_item.attr("w"));
    $("#t").val(search_current_item.attr("t"));
	//登录开关状态
	var connect_qq = "<?php echo C('qq_isuse');?>";
	var connect_sn = "<?php echo C('sina_isuse');?>";
	var connect_wx = "<?php echo C('weixin_isuse');?>";
	var connect_wx_appid = "<?php echo C('weixin_appid');?>";
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
              <li class="no-goods"><img class="loading" src="<?php echo NEWS_TEMPLATES_URL;?>/images/loading.gif" /></li>
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
	$(".right-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

});
</script>
<header id="topHeader">
  <div class="warp-all">
    <div class="news-logo"> <a href="<?php echo NEWS_SITE_URL;?>">
      <?php if(empty($output['setting_config']['news_logo'])) { ?>
      <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.'logo.png';?>">
      <?php } else { ?>
      <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_NEWS.DS.$output['setting_config']['news_logo'];?>">
      <?php } ?>
      </a> </div>
    <div class="search-news" id="searchNEWS">
      <ul class="tab">
        <li <?php if($_GET['w'] != 'picture' ) echo 'class="current"'; ?> action="<?php echo NEWS_SITE_URL.DS;?>index.php" w="article" t="article_search"><?php echo $lang['news_article'];?><i></i></li>
        <li <?php if($_GET['w'] == 'picture' ) echo 'class="current"'; ?> action="<?php echo NEWS_SITE_URL.DS;?>index.php" w="picture" t="picture_search"><?php echo $lang['news_picture'];?><i></i></li>
        <li action="<?php echo BASE_SITE_URL.DS;?>index.php" w="search"><?php echo $lang['news_goods'];?><i></i></li>
      </ul>
      <div class="form-box">
        <form id="form_search" method="get" action="" >
          <input id="w" name="w" type="hidden" />
          <input id="t" name="t" type="hidden" />
          <input id="keyword" name="keyword" type="text" class="input-text" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>" maxlength="60" x-webkit-speech="" lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" />
          <input id="btn_search" type="submit" class="input-btn" value="<?php echo $lang['news_text_search'];?>">
        </form>
      </div>
    </div>
   <div class="news-write">
     <ul>
     <?php if($_SESSION['is_login'] == '1'){?>
      <li><a href="<?php echo NEWS_SITE_URL;?>/index.php?w=publish&t=publish_article"><i class="b"></i>我要投稿</a></li>
      <li><a href="<?php echo NEWS_SITE_URL;?>/index.php?w=publish&t=publish_picture"><i class="d"></i>发布图刊</a></li>
      <?php } else { ?>
      <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=login&t=index&ref_url=<?php echo NEWS_SITE_URL;?>/index.php?w=publish&t=publish_article"><i class="b"></i>我要投稿</a></li>
      <li><a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=login&t=index&ref_url=<?php echo NEWS_SITE_URL;?>/index.php?w=publish&t=publish_picture"><i class="d"></i>发布图刊</a></li>
      <?php } ?>
	</ul>
    </div>
  </div>
</header>
