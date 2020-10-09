<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo ($lang['wt_member_path_'.$output['menu_sign']]==''?'':$lang['wt_member_path_'.$output['menu_sign']].'_').$output['html_title'];?></title>
<meta name="keywords" content="<?php echo C('site_keywords'); ?>" />
<meta name="description" content="<?php echo C('site_description'); ?>" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/sns.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link id="skin_link" href="<?php echo SHOP_TEMPLATES_URL;?>/sns/style/<?php echo $output['skin_style'];?>.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo STATIC_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo STATIC_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo BASE_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var BASE_SITE_URL = '<?php echo BASE_SITE_URL;?>';var MAX_RECORDNUM = <?php echo $output['max_recordnum'];?>;var STATIC_SITE_URL = '<?php echo STATIC_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.charCount.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/dialog/dialog.js" id="wt_dialog" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/sns.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/sns_store.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/smilies/smilies.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/smilies/smilies_data.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.caretInsert.js" charset="utf-8"></script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<header id="header" class="pngFix">
  <div class="wrapper">
    <h1 id="logo" title="<?php echo C('site_name'); ?>"><a href="<?php echo BASE_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('member_logo'); ?>" alt="<?php echo C('site_name'); ?>" class="pngFix"></a></h1>
    <h2><?php echo $lang['wt_mysns']?></h2>
    <div class="search">
      <form id="formSearch" name="formSearch" action="index.php" method="get">
        <input id="search_wt" type="hidden" value="search" name="w">
        <input id="keyword" class="wts-search-input-text" type="text" lang="zh-CN" x-webkit-grammar="builtin:search" onwebkitspeechchange="foo()" x-webkit-speech="" name="keyword" style="color: rgb(153, 153, 153);">
        <a class="wts-search-btn-mall" wttype="search_in_shop" href="javascript:void(0)" onClick="$('#formSearch').submit();"> <span><?php echo $lang['wt_common_search'];?></span> </a>
      </form>
    </div>
    <?php if($output['relation'] != 0){?>
    <ul class="menu">
      <li class="noborder"><a href="<?php echo urlShop('member', 'home');?>"><?php echo $lang['sns_return_my_shop'];?></a></li>
      <li><a href="javascript:void(0)" class="my-friend"><?php echo $lang['sns_my_attention'];?><i></i></a>
        <div class="friend-menu">
          <dl>
            <?php if(!empty($output['my_attention'])){
            foreach($output['my_attention'] as $val){?>
            <dd><a href="index.php?w=member_snshome&mid=<?php echo $val['friend_tomid'];?>"><img src="<?php if ($val['friend_tomavatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$val['friend_tomavatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.$output['setting_config']['default_user_portrait']; } ?>" /><?php echo $val['friend_tomname']?></a></dd>
            <?php }}else{?>
            <dd><a href="javascript:void(0);"><?php echo $lang['sns_no_attention_tips'];?></a></dd>
            <?php }?>
          </dl>
          <p>
            <?php if(!empty($output['my_attention'])){?>
            <a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_snsfriend&t=follow"><?php echo $lang['sns_attention_more'];?></a>
            <?php }else{?>
            <a href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_snsfriend&t=find"><?php echo $lang['sns_search_friend'];?></a>
            <?php }?>
          </p>
        </div>
      </li>
      <li><a href="index.php?w=member_snshome"><?php echo $lang['sns_my_personal_homepage'];?></a></li>
    </ul>
    <?php }?>
  </div>
</header>
<div id="container" class="wrapper mt20">
  <div class="user-info">
    <div class="user-face">
      <div class="hover-box"><span class="thumb size120"><i></i><img src="<?php if ($output['master_info']['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$output['master_info']['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" />
        <?php if($output['relation'] == 3){?>
        <a href="<?php echo urlMember('member_information', 'avatar');?>" title="<?php echo $lang['wt_updateavatar'];?>"><?php echo $lang['sns_change_avatar'];?></a>
        <?php }?>
        </span></div>
    </div>
    <dl class="user-data">
      <dt>
        <h2><?php echo !empty($output['master_info']['member_truename'])?$output['master_info']['member_truename']:$output['master_info']['member_name'];?></h2>
        <span class="add-friend ml10"><span style="<?php if($output['relation'] != 2){?>display:none;<?php }?>" wt_type="mutualsign"><?php echo $lang['sns_mutual_attention'];?></span><span style="<?php if($output['relation'] != 4){?>display:none;<?php }?>" wt_type="followsign"><?php echo $lang['sns_already_attention'];?></span>
        <?php if($output['relation'] == 1){?>
        <a data-param='{"mid":"<?php echo $output['master_info']['member_id'];?>"}' wt_type="followbtn" href="javascript:void(0)"><?php echo $lang['sns_add_attention'];?></a>
        <?php }?>
        </span> </dt>
      <dd>
        <?php if($output['master_info']['member_sex'] ==1 ){?>
        <span class="male pngFix"><?php echo $lang['sns_man'];?></span>
        <?php }else if($output['master_info']['member_sex'] == 2){?>
        <span class="female pngFix"><?php echo $lang['sns_woman'];?></span>
        <?php }?>
        <span class="location"><?php echo $output['master_info']['member_areainfo'];?></span>
        <?php if(!empty($output['master_info']['tagname'])){?>
        <span class="tag"><?php echo $lang['sns_interest_label'].$lang['wt_colon'];?>
        <?php foreach ($output['master_info']['tagname'] as $val){?>
        <em><?php echo $val;?></em>
        <?php }?>
        </span>
        <?php }?>
        <?php if ($output['relation'] == '3'){?>
        <span><a href="<?php echo urlMember('member_information', 'member');?>" title="<?php echo $lang['sns_edit_data'];?>"><?php echo $lang['sns_edit_data'];?></a></span>
        <?php }?>
      </dd>
    </dl>
    <div class="user-stat">
      <dl class="noborder">
        <dd><?php echo $output['master_info']['fan_count'];?></dd>
        <dt><?php echo $lang['sns_fans'];?></dt>
      </dl>
      <dl>
        <dd><?php echo $output['master_info']['attention_count'];?></dd>
        <dt><?php echo $lang['sns_attention'];?></dt>
      </dl>
      <dl>
        <dd><?php echo $output['master_info']['member_snsvisitnum'];?></dd>
        <dt><?php echo $lang['sns_visit'];?></dt>
      </dl>
    </div>
  </div>
  <div class="sns-nav">
    <ul>
      <li><a <?php if($output['menu_sign'] == 'snshome'){?>class="current"<?php }?> href="index.php?w=member_snshome<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="home pngFix"></i><?php echo $lang['wt_index'];?></a></li>
      <li><a <?php if($output['menu_sign'] == 'sharegoods'){?>class="current"<?php }?> href="index.php?w=member_snshome&t=shareglist<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="goods pngFix"></i><?php echo $lang['sns_treasure'];?></a></li>
      <li><a <?php if($output['menu_sign'] == 'sharestore'){?>class="current"<?php }?> href="index.php?w=member_snshome&t=storelist<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="store pngFix"></i><?php echo $lang['sns_store'];?></a></li>
      <li><a <?php if($output['menu_sign'] == 'snsalbum'){?>class="current"<?php }?> href="index.php?w=sns_album<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="album pngFix"></i><?php echo $lang['sns_album'];?></a></li>
      <li><a <?php if($output['menu_sign'] == 'snstrace'){?>class="current"<?php }?> href="index.php?w=member_snshome&t=trace<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="news pngFix"></i><?php echo $lang['sns_fresh_news'];?></a></li>
      <?php if(intval(C('bbs_isuse'))){?>
      <li><a <?php if($output['menu_sign'] == 'bbs'){?>class="current"<?php }?> href="index.php?w=sns_bbs<?php if(!empty($output['master_id'])){ echo '&mid='.$output['master_id']; }?>"><i class="bbs pngFix"></i><?php echo $lang['sns_group'];?></a></li>
      <?php }?>
    </ul>
    <?php if($output['relation'] == '3'){?>
    <div class="skin"><a href="javascript:void(0)" title="<?php echo $lang['sns_change_skin'];?>" class="pngFix" wttype="change_skin"><?php echo $lang['sns_change_skin'];?></a></div>
    <?php }?>
  </div>
  <div class="sns-main">
    <?php
		require_once($tpl_file);
		?>
  </div>
  <!-- 表情弹出层 -->
  <div id="smilies_div" class="smilies-module"></div>
</div>
<?php
require_once template('footer');
?>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js" ></script> 
<script type="text/javascript" language="javascript">
var max_recordnum = '<?php echo $output['max_recordnum'];?>';

$(function(){
	// 显示关注好友名单
  $(".my-friend").click(function(){
    $(".friend-menu").slideToggle("1000");
  });

	//存在多规格时的价格修改
	$('a[wttype="change_skin"]').click(function(){
		ajax_form('change_skin', '<?php echo $lang['sns_change_skin'];?>', SITEURL + '/index.php?w=sns_setting&t=change_skin', '480');
	});
});
</script>
</body>
</html>
