<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!-- 标题 -->
<div class="news-module-title">
    <h2 id="news_module_title" wttype="object_module_edit"><?php echo "<?php echo \$module_content['news_module_title'];?>";?></h2>
    <?php echo "<?php if(\$output['edit_flag']) { ?>";?>
    <div class="news-index-module-handle"><a wttype="btn_module_title_edit" href="JavaScript:void(0);" class="tip-r" title="<?php echo $lang['news_index_module_title_edit'];?>"><?php echo $lang['news_index_module_title_edit'];?></a></div>
    <?php echo "<?php } ?>";?>
</div>

