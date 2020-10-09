<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="news-index-module-show1">
  <ul id="show_content" wttype="object_module_edit">
    <?php echo html_entity_decode($module_content['show_content']);?>
  </ul>
  <?php if($output['edit_flag']) { ?>
  <div class="news-index-module-handle"><a wttype="btn_module_image_edit" href="JavaScript:void(0);" image_count="1" class="tip-r" data-title="<?php echo $lang['news_index_module_show_edit_title'];?>" title="<?php echo $lang['news_index_module_show_edit_title'];?>"><?php echo $lang['news_index_module_show_edit'];?></a></div>
  <?php } ?>
</div>
