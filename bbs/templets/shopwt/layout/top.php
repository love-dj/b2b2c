<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
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
              <li class="no-goods"><img class="loading" src="<?php echo BBS_TEMPLATES_URL;?>/images/loading.gif" /></li>
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
//登录开关状态
var connect_qq = "<?php echo C('qq_isuse');?>";
var connect_sn = "<?php echo C('sina_isuse');?>";
var connect_wx = "<?php echo C('weixin_isuse');?>";
var connect_wx_appid = "<?php echo C('weixin_appid');?>";

</script>
<!-- 社区头部 -->
<header id="topHeader">
  <div class="warp-all">
    <div class="bbs-logo"><a href="<?php echo BBS_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS.'/'.C('bbs_logo');?>"/></a></div>
    <div class="bbs-search" id="bbsSearch">
      <form id="form_search" method="get" action="<?php echo BBS_SITE_URL;?>/index.php" >
        <input type="hidden" name="w" value="search" />
        <div class="input-box"><i class="icon"></i>
          <input id="keyword" name="keyword" type="text" class="input-text" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>" maxlength="60" x-webkit-speech="" lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" />
          <input id="btn_search" type="submit" class="input-btn" value="<?php echo $lang['wt_search_nbsp'];?>">
        </div>
        <div class="radio-box">
          <label>
            <input name="t" value="theme" type="radio" <?php if($output['search_sign']=='theme' || !isset($output['search_sign'])){?>checked="checked"<?php }?> />
            <h5><?php echo $lang['search_theme'];?></h5></label>
          <label>
            <input name="t" value="group" type="radio" <?php if($output['search_sign']=='group'){?>checked="checked"<?php }?> />
            <h5><?php echo $lang['search_bbs'];?></h5></label>
        </div>
      </form>
    </div>
    <div class="bbs-user">
      <h2><a href="<?php echo BBS_SITE_URL;?>/index.php?w=search&t=group"><?php echo $lang['wt_find_fascinating'];?></a></h2>
      <div class="head-portrait"><span class="thumb size20"> <?php if ($output['super']) {?><i title="超级管理员"></i><?php }?><img src="<?php  echo getMemberAvatarForID($_SESSION['member_id']);?>" /></span></div>
      <div class="user-login">
        <?php if($_SESSION['is_login']){?>
        <div class="my-group"><?php echo $lang['my_bbs'];?><span><i></i></span><span class="hidden" wttype="span-mygroup">
          </span> </div>
        <?php }else{?>
        <a href="Javascript:void(0)" wttype="login"><?php echo $lang['wt_login'];?></a> | <a href="<?php echo urlLogin('login','register');?>"><?php echo $lang['wt_register'];?></a>
        <?php }?>
      </div>
    </div>
  </div>
</header>

<div id="navBar" class="mb20">
  <div id="navBox">
    <ul class="wt-nav-menu">
      <li class="current"><a href="<?php echo BBS_SITE_URL;?>"><span><?php echo $lang['wt_index'];?></span></a></li>
      <?php if(!empty($output['class_list'])){?>
        <?php foreach ($output['class_list'] as $val){?>
        <li class="link"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=search&t=group&class_id=<?php echo $val['class_id'];?>&class_name=<?php echo $val['class_name'];?>"><span><?php echo $val['class_name'];?></a></span></li>
        <?php }?>
        <?php }?>
    </ul>
  </div>
</div>

