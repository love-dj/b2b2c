<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wt-bbs warp-all">
    <div class="focus-banner flexslider">
      <ul class="slides">
        <?php if(!empty($output['loginpic']) && is_array($output['loginpic'])){?>
        <?php foreach($output['loginpic'] as $val){?>
        <li><a href="<?php if($val['url'] != ''){echo $val['url'];}else{echo 'javascript:void(0);';}?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS.'/'.$val['pic'];?>"></a></li>
        <?php }?>
        <?php }?>
      </ul>
    </div>
</div>
<div class="homepage">
  <div class="warp-all recommend-group">
    <div class="title">
      <h3><i></i><?php echo $lang['bbs_recommend_group'];?></h3>
    </div>
    <div class="content">
      <ul id="mycarousel1" class="jcarousel-skin-tango">
        <?php if(!empty($output['rbbs_list'])){?>
        <?php foreach($output['rbbs_list'] as $val){?>
        <li title="<?php echo $val['bbs_name'];?>"><img src="<?php echo bbsLogo($val['bbs_id']);?>" /><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>">
          <p class="extra"><?php echo $val['bbs_name'];?></p>
          </a> </li>
        <?php }?>
        <?php }?>
      </ul>
    </div>
  </div>
  <div class="warp-all">
    <div class="layout-l">
      <div class="recommend-theme">
        <div class="title">
          <h3><?php echo $lang['bbs_recommend_theme'];?></h3>
        </div>
        <div class="content">
          <ul class="recommend-theme-list">
            <?php if(!empty($output['theme_list'])){?>
            <?php foreach($output['theme_list'] as $val){?>
            <li>
              <dl>
                <dt class="theme-title" title="<?php echo $val['theme_name'];?>"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $val['bbs_id'];?>&t_id=<?php echo $val['theme_id'];?>"><?php echo $val['theme_name'];?></a></dt>
                <dd class="thumb"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $val['bbs_id'];?>&t_id=<?php echo $val['theme_id'];?>"><img src="<?php echo $val['affix'];?>" class="t-img" /></a></dd>
                <dd class="group-name" title="<?php echo $lang['bbs_come_from'];?><?php echo $val['bbs_name'];?>"><?php echo $lang['bbs_come_from'];?><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name'];?></a></dd>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
        <div class="title">
          <h3><?php echo $lang['bbs_friend_show_order'];?></h3>
        </div>
        <div class="content show-goods">
          <?php if(!empty($output['gtheme_list'])){?>
          <?php foreach ($output['gtheme_list'] as $val){?>
          <dl>
            <dt class="theme-title" title="<?php echo $val['theme_name'];?>"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $val['bbs_id'];?>&t_id=<?php echo $val['theme_id'];?>"><?php echo $val['theme_name'];?></a></dt>
            <dd class="theme-info"><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>" class="member-name" title="<?php echo $val['member_name'];?>"><?php echo $val['member_name'];?></a><span class="group-name"><?php echo $lang['bbs_come_from'];?><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>" title="<?php echo $val['bbs_name'];?>"><?php echo $val['bbs_name'];?></a></span>
            <dd class="member-avatar"><img src="<?php echo getMemberAvatarForID($val['member_id']);?>"/></dd>
            <?php if(!empty($output['thg_list'][$val['theme_id']])){?>
            <dd class="goods-list">
              <ul>
                <?php foreach($output['thg_list'][$val['theme_id']] as $val){?>
                <li class="thumb"><a href="<?php echo $val['thg_url'];?>"><img src="<?php echo $val['image'];?>" class="t-img" /></a></li>
                <?php }?>
              </ul>
            </dd>
            <?php }?>
          </dl>
          <?php }?>
          <?php }?>
        </div>
      </div>
    </div>
    <div class="layout-r">
      <?php require_once bbs_template('index.themetop');?>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js" charset="utf-8"></script> 
<!-- 引入幻灯片JS --> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.flexslider.min.js"></script> 
<script>
$(function(){
// 绑定幻灯片事件 
	$('.flexslider').flexslider();
	//图片轮换
    	$('#mycarousel1').jcarousel({visible: 8,itemFallbackDimension: 300});

	//横高局中比例缩放隐藏显示图片
	$(window).load(function () {
		$(".recommend-theme-list .t-img").VMiddleImg({"width":145,"height":96});
		$(".good-member .t-img").VMiddleImg({"width":140,"height":96});
		$(".show-goods .t-img").VMiddleImg({"width":30,"height":30});
	});
});
$(function() {
	$(".tabs-nav > li > a").mouseover(function(e) {
	if (e.target == this) {
		var tabs = $(this).parent().parent().children("li");
		var panels = $(this).parents('.theme-top:first').find(".tabs-panel");
		var index = $.inArray(this, $(this).parent().parent().find("a"));
		if (panels.eq(index)[0]) {
			tabs.removeClass("tabs-selected")
				.eq(index).addClass("tabs-selected");
			panels.addClass("tabs-hide")
				.eq(index).removeClass("tabs-hide");
		}
	}
	});
});
</script> 