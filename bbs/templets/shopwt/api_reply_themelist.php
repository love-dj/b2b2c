<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<ul class="bbs-reply-themelist">
    <?php if(!empty($output['reply_themelist']) && is_array($output['reply_themelist'])) {?>
    <?php foreach($output['reply_themelist'] as $key=>$value) {?>
    <li class="bbs-theme-item">
    <span class="bbs-theme-thclass_name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $value['bbs_id'];?>&thc_id=<?php echo $value['thclass_id'];?>" target="_blank">[<?php echo empty($value['thclass_name'])?$lang['wt_default']:$value['thclass_name'];?>]</a></span>
    <span class="bbs-theme-name"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=theme&t=theme_detail&c_id=<?php echo $value['bbs_id'];?>&t_id=<?php echo $value['theme_id'];?>" target="_blank"><?php echo $value['theme_name'];?></a></span>
    </li>
    <?php } ?>
    <?php } ?>
</ul>
