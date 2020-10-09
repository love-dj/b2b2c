<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="sidebar">
  <?php include template('sns/sns_sidebar_visitor');?>
  <?php include template('sns/sns_sidebar_messageboard');?>
</div>
<div class="left-content">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
  </div>
  <div class="bbs-group-list">
  <?php if(!empty($output['bbs_list'])){?>
    <ul>
    <?php foreach($output['bbs_list'] as $val){?>
      <li>
        <dl class="group-info">
          <dt class="group-name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><?php echo $val['bbs_name'];?></a></dt>
          <dd class="group-pic"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>"><img src="<?php echo bbsLogo($val['bbs_id']);?>" /></a></dd>
          <dd class="group-time"><?php $lang['sns_created_at'];?><em><?php echo @date('Y-m-d', $val['bbs_addtime']);?></em></dd>
          <dd class="group-total"><em><?php echo $val['bbs_mcount'];?></em><?php echo $lang['sns_members'];?></dd>
          <dd class="group-intro"><?php echo $val['bbs_desc'];?></dd>
        </dl>
      </li>
    <?php }?>
    </ul>
  <?php }else{?>
  <!-- 为空提示 START -->
  <div class="sns-norecord"><i class="bbs-ico pngFix"></i><span><?php echo $lang['sns_regrettably'];?><br />
    <?php if ($output['relation'] == 3){echo $lang['sns_me']; }else {?>TA<?php }?><?php echo $lang['sns_not_yet'];?><a href="<?php echo BBS_SITE_URL;?>" target="_blank"><?php echo $lang['sns_join_group'];?></a><?php echo $lang['sns_oh'];?></span></div>
  <?php }?>
  <!-- 为空提示 END --> 
  </div>
</div>
