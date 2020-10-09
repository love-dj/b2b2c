<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="sidebar">
  <?php if(in_array($output['identity'], array(1,2,3))){?>
  <div class="my-info">
    <div class="avatar"><img src="<?php echo getMemberAvatarForID($output['cm_info']['member_id']);?>"/><a href="<?php echo urlMember('member_information', 'avatar');?>" title="<?php echo $lang['wt_edit_avatar'];?>"><?php echo $lang['wt_edit_avatar'];?></a></div>
    <dl>
      <dt>
        <h3><a href="index.php?w=p_center" target="_blank"><?php echo $lang['wt_inro_personal_center'];?></a></h3>
      </dt>
      <dd>
        <?php echo $lang['wt_rank'].$lang['wt_colon'];?>
        <?php echo memberLevelHtml($output['cm_info']);?>
      </dd>
      <dd class="mt10">
        <?php echo $lang['wt_exp'].$lang['wt_colon'];?>
        <div class="cm-exp">
          <?php if($output['cm_info']['cm_level'] == 16){?>
          <p style="width: 100%;"> </p>
          <i> <?php echo $output['cm_info']['cm_exp'];?></i>
          <?php }else{?>
          <p style="width: <?php echo intval($output['cm_info']['cm_nextexp']) != 0?sprintf('%.2f%%', intval($output['cm_info']['cm_exp'])/intval($output['cm_info']['cm_nextexp'])*100):0;?>"> </p>
          <i> <?php echo $output['cm_info']['cm_exp'].'/'.$output['cm_info']['cm_nextexp'];?></i>
          <?php }?>
        </div>
      </dd>
    </dl>
  </div>
  <?php }?>
  <!--公告与信息-->
  <div class="side-tab-nav">
    <ul class="tabs-nav">
      <li class="tabs-selected"><a href="javascript:void(0)"><?php echo $lang['bbs_notice'];?></a></li>
      <li><a href="javascript:void(0)"><?php echo $lang['bbs_information'];?></a></li>
    </ul>
    <div class="sidebar-bbs-notice tabs-panel">
      <p>
        <?php if($output['bbs_info']['bbs_notice'] != ''){ echo $output['bbs_info']['bbs_notice'];}else{?>
        <span class="no-notice"><i></i><?php echo $lang['bbs_no_notice'];?></span>
        <?php }?>
      </p>
    </div>
    <div class="sidebar-bbs-info tabs-panel tabs-hide">
      <dl>
        <dt><?php echo $lang['bbs_belong_to_class'].$lang['wt_colon'];?></dt>
        <dd>
          <?php if($output['class_info']['class_name'] != ''){ echo $output['class_info']['class_name'];}else{echo $lang['wt_default'];}?>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bbs_build_time'].$lang['wt_colon'];?></dt>
        <dd><?php echo @date('Y-m-d',$output['bbs_info']['bbs_addtime']);?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bbs_friend_count'].$lang['wt_colon'];?></dt>
        <dd><?php echo $output['bbs_info']['bbs_mcount'];?> <?php echo $lang['bbs_person'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bbs_theme_amount'].$lang['wt_colon'];?></dt>
        <dd><?php echo $output['bbs_info']['bbs_thcount'];?> <?php echo $lang['bbs_item'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bbs_manager'].$lang['wt_colon'];?></dt>
        <dd><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&t=theme&mid=<?php echo $output['creator']['member_id'];?>" class="master" title="<?php echo $output['creator']['member_name'];?>" target="_blank"><i></i><?php echo $output['creator']['member_name'];?></a></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bbs_administrate'].$lang['wt_colon'];?></dt>
        <dd>
          <?php if(!empty($output['manager_list'])){?>
          <?php foreach ($output['manager_list'] as $val){?>
          <a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>" class="moderator" title="<?php echo $val['member_name'];?>" target="_blank"><i></i><?php echo $val['member_name'];?></a>
          <?php }?>
          <?php }else{?>
          <span><?php echo $lang['bbs_is_null'];?></span>
          <?php }?>
        </dd>
      </dl>
    </div>
  </div>
  <!--会员情况-->
  <div class="side-tab-nav">
    <ul class="tabs-nav">
      <li class="tabs-selected"><a href="javascript:void(0)"><?php echo $lang['bbs_star_firend'];?></a></li>
      <li><a href="javascript:void(0)"><?php echo $lang['bbs_jion_new'];?></a></li>
    </ul>
    <div class="sidebar-bbs-member tabs-panel">
      <?php if(!empty($output['star_member'])){?>
      <?php foreach($output['star_member'] as $val){?>
      <dl>
        <dt class="member-name"><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>"><?php echo $val['member_name'];?></a></dt>
        <dd class="member-avatar-s"><img src="<?php echo getMemberAvatarForID($val['member_id']);?>" /><em></em></dd>
        <dd class="theme-num" title="<?php echo $lang['bbs_theme_count'];?>">(<b><?php echo $val['cm_thcount'];?></b>)</dd>
      </dl>
      <?php }?>
      <?php }else{?>
      <p> <span class="no-member"><i></i><?php echo $lang['bbs_no_firend'];?></span> </p>
      <?php }?>
    </div>
    <div class="sidebar-bbs-member tabs-panel tabs-hide">
      <?php if(!empty($output['newest_member'])){?>
      <?php foreach($output['newest_member'] as $val){?>
      <dl>
        <dt class="member-name"><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>"><?php echo $val['member_name'];?></a></dt>
        <dd class="member-avatar-s"><img src="<?php echo getMemberAvatarForID($val['member_id']);?>" /></dd>
        <dd class="theme-num" title="<?php echo $lang['bbs_theme_count'];?>">(<b><?php echo $val['cm_thcount'];?></b>)</dd>
      </dl>
      <?php }?>
      <?php }?>
    </div>
  </div>
  <!--友情链接-->
  <?php if(!empty($output['friendship_list'])){?>
  <div class="sidebar-box">
    <div class="title">
      <h3><?php echo $lang['fbbs'];?></h3>
    </div>
    <div class="content">
      <ul class="sidebar-bbs-links">
        <?php foreach ($output['friendship_list'] as $val){?>
        <li><span class="thumb size32"><i></i><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['friendship_id'];?>" title="<?php echo $val['friendship_name'];?>"><img src="<?php echo bbsLogo($val['friendship_id']);?>" /></a></span></li>
        <?php }?>
      </ul>
    </div>
  </div>
  <?php }?>
</div>
<script>
$(function(){
	//帖子列表隔行变色
	$(".group-theme-list li:odd").css("background-color","#F8F9FA");
	$(".group-theme-list li:even").css("background-color","#FCFCFC");

//侧边栏tab切换
$(".tabs-nav > li > a").click(function(e) {
	if (e.target == this) {
		var tabs = $(this).parent().parent().children("li");
		var panels = $(this).parent().parent().parent().children(".tabs-panel");
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