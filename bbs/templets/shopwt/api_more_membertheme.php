<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<ul class="bbs-more-membertheme">
  <?php if(!empty($output['more_membertheme']) && is_array($output['more_membertheme'])) {?>
  <?php foreach($output['more_membertheme'] as $key=>$value) {?>
  <li class="bbs-member-item">
    <div class="bbs-member-avatar"> <a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $value['member_id'];?>" target="_blank"> <img src="<?php echo getMemberAvatarForID($value['member_id']);?>" /> </a> </div>
    <div class="bbs-member-name"> <a target="_blank" href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $value['member_id'];?>" target="_blank"> <?php echo $value['member_name'];?> </a> </div>
    <div class="bbs-member-theme"> <a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $value['bbs_id'];?>&t_id=<?php echo $value['theme_id'];?>" target="_blank"> <?php echo $value['theme_name'];?> </a> </div>
  </li>
  <?php } ?>
  <?php } ?>
</ul>
