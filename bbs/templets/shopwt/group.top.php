<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="breadcrumd">
  <h4><?php echo $lang['bbs_current_location'].$lang['wt_colon'];?></h4>
  <?php if(!empty($output['breadcrumd'])){?>
    <?php foreach ($output['breadcrumd'] as $val){?>
    <?php if($val['link'] != ''){?>
    <a href="<?php echo $val['link'];?>"><?php echo $val['title'];?></a>
    <?php }else{echo $val['title'];}?>
    <?php }?>
  <?php }?>
</div>
<div class="group-top">
  <dl class="bbs-info">
    <dt class="name">
      <h2><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $output['c_id'];?>" class="group-name"><?php echo $output['bbs_info']['bbs_name'];?></a></h2>
      <?php switch ($output['identity']){
      	case 0:
      	case 5:
      		echo '<div class="button"><a href="javascript:void(0);" wttype="apply" class="btn"><i class="apply"></i>'.$lang['bbs_apply_to_join'].'</a></div>';
      		break;
      	case 1:
      		echo '<div class="button"><a href="index.php?w=manage&c_id='.$output['bbs_info']['bbs_id'].'" class="btn"><i class="manage"></i>'.$lang['manage_bbs'].'</a></div>';
      		if($output['bbs_info']['new_verifycount'] != 0)
      			echo '<div class="pending"><a href="index.php?w=manage&t=applying&c_id='.$output['c_id'].'">'.$lang['bbs_wait_verity_count'].'</a><sup>'.$output['bbs_info']['new_verifycount'].'</sup></div>';
      		if($output['bbs_info']['new_informcount'] != 0)
      			echo '<div class="pending"><a href="index.php?w=manage_inform&t=inform&c_id='.$output['c_id'].'">'.$lang['bbs_new_inform'].'</a><sup>'.$output['bbs_info']['new_informcount'].'</sup></div>';
      		if($output['bbs_info']['new_mapplycount'] != 0)
      			echo '<div class="pending"><a href="index.php?w=manage_mapply&c_id='.$output['c_id'].'">'.$lang['bbs_management_application'].'</a><sup>'.$output['bbs_info']['new_mapplycount'].'</sup></div>';
      		break;
      	case 2:
      		echo '<div class="button"><a href="index.php?w=manage&c_id='.$output['bbs_info']['bbs_id'].'" class="btn"><i class="manage"></i>'.$lang['manage_bbs'].'</a><a href="javascript:void(0);" wttype="quitGroup" class="btn"><i class="quit"></i>'.$lang['bbs_quit_group'].'</a></div>';
      		if($output['bbs_info']['new_verifycount'] != 0)
      			echo '<div class="pending"><a href="index.php?w=manage&t=applying&c_id='.$output['c_id'].'">'.$lang['bbs_wait_verity_count'].'</a><sup>'.$output['bbs_info']['new_verifycount'].'</sup></div>';
      		if($output['bbs_info']['new_informcount'] != 0)
      			echo '<div class="pending"><a href="index.php?w=manage_inform&t=inform&c_id='.$output['c_id'].'">'.$lang['bbs_new_inform'].'</a><sup>'.$output['bbs_info']['new_informcount'].'</sup></div>';
      		
      		break;
      	case 4:
      		echo '<div class="button"><a href="javascript:void(0);" class="btn">'.$lang['bbs_applying_wait_verify'].'</a></div>';
      		break;
      	case 3:
      	case 6:
      		echo '<div class="button"><a href="javascript:void(0);" wttype="quitGroup" class="btn"><i class="quit"></i>'.$lang['bbs_quit_group'].'</a></div>';
      		break;
      }?>
    </dt>
    <dd class="pic"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $output['c_id'];?>"><img src="<?php echo bbsLogo($output['bbs_info']['bbs_id']);?>"/></a></dd>
    <dd class="intro"><?php if($output['bbs_info']['bbs_desc'] != ''){ echo $output['bbs_info']['bbs_desc'];}else{ echo $lang['bbs_desc_null_default'];}?></dd>
    <dd class="manage">
      <span class="master">
        <?php echo $lang['bbs_manager'].$lang['wt_colon'];?><a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&t=theme&mid=<?php echo $output['creator']['member_id'];?>" wttype="mcard" data-param="{'id':<?php echo $output['creator']['member_id'];?>}"><i></i><?php echo $output['creator']['member_name'];?></a>
      </span>
      <span class="moderator">
        <?php echo $lang['bbs_administrate'].$lang['wt_colon'];?>
        <?php if(!empty($output['manager_list'])){foreach($output['manager_list'] as $val){?>
        <a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>"  wttype="mcard" data-param="{'id':<?php echo $val['member_id'];?>}"><i></i><?php echo $val['member_name'];?></a>
        <?php }}else{echo $lang['bbs_no_administrate'];}?>
        <?php if($output['bbs_info']['mapply_open'] == 1 && $output['identity'] == 3 && $output['cm_info']['cm_level'] >= $output['bbs_info']['mapply_ml']){?>
        <a href="javascript:void(0);" wttype="manageApply"><?php echo $lang['bbs_apply_to_be_a_management'];?></a>
        <?php }?>
      </span>
    </dd>
  </dl>
  <div class="bbs-create"><a href="javascript:void(0);" wttype="create_bbs"><i></i><?php echo $lang['bbs_create_my_new_bbs'];?></a></div>
  <div class="clear"></div>
</div>
<script type="text/javascript" src="<?php echo BBS_STATIC_SITE_URL;?>/js/group.js" charset="utf-8"></script> 
<script>
var c_id = <?php echo $output['c_id'];?>;
var identity = <?php echo $output['identity'];?>;
</script>
