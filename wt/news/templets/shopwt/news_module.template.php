<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="news-page-module-frame module-style-<?php echo "<?php echo \$value['module_style']?>";?>">
<div class="news-module-frame">
    <?php if($output['module_display_title']) { ?>
    <div class="news-module-frame-title">
        <?php require('news_module.assembly_title.php');?>
    </div>
    <?php } ?>
    <?php if(!empty($output['frame_structure']) && is_array($output['frame_structure'])) {?>
    <?php foreach ($output['frame_structure'] as $key=>$value) {?>
    <?php if(empty($value['child'])) { ?>
    <div wttype="news_module_content" class="news-module-frame-<?php echo $value['name'];?>">
        <?php if(!empty($output['frame_block'][$key])) { ?>
        <?php $block_name = $key;?>
        <?php require('news_module.assembly_'.$output['frame_block'][$key].'.php');?>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div wttype="news_module_content" class="news-module-frame-<?php echo $value['name'];?>">
        <?php foreach($value['child'] as $key_child=>$value_child) { ?>
        <div class="news-module-frame-<?php echo $value_child['name'];?>">
            <?php if(!empty($output['frame_block'][$key_child])) { ?>
            <?php $block_name = $key_child;?>
            <?php require('news_module.assembly_'.$output['frame_block'][$key_child].'.php');?>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php } ?>
    <div class="clear"></div>
    <?php } ?>
</div>
</div>

