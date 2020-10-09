<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<ul class="bbs-theme-list">
  <?php if(!empty($output['theme_list']) && is_array($output['theme_list'])) {?>
  <?php foreach($output['theme_list'] as $key=>$value) {?>
  <li class="bbs-theme-item">
    <div class="bbs-theme-pic news-thumb"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $value['bbs_id'];?>&t_id=<?php echo $value['theme_id'];?>" target="_blank"><img src="<?php echo $value['affix'];?>" class="t-img" /></a></div>
    <div class="bbs-theme-name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $value['bbs_id'];?>&t_id=<?php echo $value['theme_id'];?>" target="_blank"><?php echo $value['theme_name'];?></a></div>
    <div class="bbs-theme-bbs-name"><?php echo $lang['bbs_come_from'];?><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $value['bbs_id'];?>" target="_blank"><?php echo $value['bbs_name'];?></a></div>
  </li>
  <?php } ?>
  <?php } ?>
</ul>
