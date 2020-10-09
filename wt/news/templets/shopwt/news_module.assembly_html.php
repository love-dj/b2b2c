<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!-- 文章　-->
<div class="news-module-assembly-html">
    <div class="content-box">
        <div id="<?php echo $block_name;?>_html_content" wttype="object_module_edit">
            <?php echo "<?php echo html_entity_decode(\$module_content['".$block_name."_html_content']);?>";?>
        </div>
        <?php echo "<?php if(\$output['edit_flag']) { ?>";?>
        <div class="news-index-module-handle"><a wttype="btn_module_html_edit" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_html_edit'];?>"><?php echo $lang['news_index_module_html_edit'];?></a></div>
        <?php echo "<?php } ?>";?>
    </div>
</div>

