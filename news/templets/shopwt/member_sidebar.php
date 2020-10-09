<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<dl class="member-info">
  <dt class="avatar">
    <?php if($output['publisher_info']['type'] === 2) { ?>
    <img src="<?php echo NEWS_TEMPLATES_URL;?>/images/admin.gif" />
    <?php } else { ?>
    <img src="<?php echo getMemberAvatar($output['publisher_info']['avatar']);?>" alt="<?php echo $output['publisher_info']['name'];?>" />
    <?php } ?>
  </dt>
  <dd class="username"><?php echo $output['publisher_info']['name'];?></dd>
  <dd class="type">（<?php echo $output['publisher_info']['type'] === 2?$lang['news_article_type_admin']:$lang['news_article_type_member'];?>）</dd>
</dl>
