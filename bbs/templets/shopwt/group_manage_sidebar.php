<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="my-info">
  <div class="avatar"><img src="<?php echo getMemberAvatarForID($_SESSION['member_id']);?>" /><a href="<?php echo urlMember('member_information', 'avatar');?>" title="<?php echo $lang['wt_edit_avatar'];?>"><?php echo $lang['wt_edit_avatar'];?></a></div>
  <dl>
    <dt>
      <h2><a href="index.php?w=p_center" target="_blank"><?php echo $_SESSION['member_name'];?></a></h2>
    </dt>
    <dd><span><?php echo $lang['bbs_theme'].$lang['wt_colon'];?><em>(<b><?php echo $output['cm_info']['cm_thcount'];?></b>)</em></span><span><?php echo $lang['bbs_reply'].$lang['wt_colon'];?><em>(<b><?php echo $output['cm_info']['cm_comcount'];?></b>)</em></span></dd>
  </dl>
</div>
<div class="side-tab-nav">
  <ul class="tabs-nav">
    <li class="tabs-selected"><a href="javascript:void(0)"><?php echo $lang['my_bbs'];?></a></li>
    <li><a href="javascript:void(0)"><?php echo $lang['manage_bbs'];?></a></li>
  </ul>
  <div class="my-bbs-list tabs-panel">
    <?php if (!empty($output['bbs_array'])){?>
    <?php foreach ($output['bbs_array'] as $val){?>
    <dl <?php if($val['is_identity'] == 3){?>wttype="member"<?php }?>>
      <dt class="name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>" title="<?php echo $val['bbs_name'];?>" ><?php echo $val['bbs_name'];?></a></dt>
      <dd class="pic"><span class="thumb size50"><i></i><a href="javascript:void(0);"><img src="<?php echo bbsLogo($val['bbs_id']);?>" /></a></span></dd>
      <dd class="createtime"><?php echo $lang['bbs_created_at'];?><em class="ml5"><?php echo @date('Y-m-d', $val['bbs_addtime']);?></em></dd>
      <dd class="number"><em><?php echo $val['bbs_mcount'];?></em><?php echo $lang['bbs_members'];?></dd>
    </dl>
    <?php }?>
    <?php }?>
  </div>
</div>
<script>
$(function() {
	$(".tabs-nav > li > a").click(function(e) {
		$(".tabs-nav > li > a").parent().removeClass('tabs-selected');
		var parent = $(this).parent();
		parent.addClass('tabs-selected');
		if($(".tabs-nav > li").eq(1).hasClass('tabs-selected')){
			$('[wttype="member"]').hide();
		}else{
			$('[wttype="member"]').show();
		}
	});
});
</script> 