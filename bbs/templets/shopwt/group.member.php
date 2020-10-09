<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="group warp-all">
<?php require_once bbs_template('group.top');?>
<div class="base-box mt20">
  <div class="mainbox">
    <div class="base-tab-menu">
      <ul class="base-tab-nav">
        <li><a href="index.php?w=group&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_theme'];?></a></li>
        <li class="selected"><a href="index.php?w=group&t=group_member&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_firend'];?></a></li>
        <li><a href="index.php?w=group&t=group_goods&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['wt_goods'];?></a></li>
      </ul>
    </div>
    <div class="group-member">
      <?php if(in_array($output['identity'], array(1,2,3,6))){?>
      <ul class="group-member-list">
      <h3><?php echo $lang['bbs_my_cart'];?></h3>
        <li>
          <dl class="member-info">
            <dt class="member-name"><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $output['cm_info']['member_id'];?>"><?php echo $output['cm_info']['member_name'];?></a></dt>
            <dd class="member-avatar-m"><img src="<?php echo getMemberAvatarForID($output['cm_info']['member_id']);?>" /></dd>
            <dd class="time"><em><?php echo @date('Y-m-d', $output['cm_info']['cm_jointime']);?></em><?php echo $lang['bbs_join'];?></dd>
            <dd><?php echo memberIdentity($output['cm_info']['is_identity']);?>&nbsp;<?php echo memberLevelHtml($output['cm_info']);?></dd>
            <dd class="member-intro-edit"><i></i><a href="javascript:void(0);" wttype="cmEdit"><?php echo $lang['wt_edit'];?></a></dd>
          </dl>
          <p class="intro" title="<?php if($output['cm_info']['cm_intro'] != ''){echo $output['cm_info']['cm_intro'];}else{echo $lang['bbs_introduction_null'];}?>"><?php if($output['cm_info']['cm_intro'] != ''){echo $output['cm_info']['cm_intro'];}else{echo $lang['bbs_introduction_null'];}?></p>
        </li>
      </ul>
      <div class="clear"></div>
      <?php }?>
      <ul class="group-member-list">
      <h3><?php echo $lang['bbs_other_friend'];?></h3>
      <?php if(!empty($output['cm_list'])){?>
      <?php foreach ($output['cm_list'] as $val){?>
        <li>
          <dl class="member-info">
            <dt class="member-name"><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>"><?php echo $val['member_name'];?></a></dt>
            <dd class="member-avatar-m"><img src="<?php echo getMemberAvatarForID($val['member_id']);?>" /></dd>
            <dd class="time"><em><?php echo @date('Y-m-d', $val['cm_jointime']);?></em><?php echo $lang['bbs_join'];?></dd>
            <dd><?php echo memberIdentity($val['is_identity']);?>&nbsp;<?php echo memberLevelHtml($val);?></dd>
          </dl>
          <p class="intro" title="<?php if($val['cm_intro'] != ''){echo $val['cm_intro'];}else{echo $lang['bbs_introduction_null'];}?>"><?php if($val['cm_intro'] != ''){echo $val['cm_intro'];}else{echo $lang['bbs_introduction_null'];}?></p>
        </li>
      <?php }?>
      <?php }?>
      </ul>
      <div class="clear"></div>
      <div class="pagination"><?php echo $output['show_page'];?></div>
    </div>
  </div>
  <?php require_once bbs_template('group.sidebar');?>
</div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script> 
<script>
$(function(){
	$('a[wttype="cmEdit"]').click(function(){
		if(_ISLOGIN){
    		_uri = "<?php echo BBS_SITE_URL;?>/index.php?w=group&t=group_memberedit&inajax=1&c_id=<?php echo $output['c_id'];?>";
    		CUR_DIALOG = ajax_form('memberedit', '<?php echo $lang['bbs_edit_personal_information'];?>', _uri, 520);
		}
	});
});
</script>