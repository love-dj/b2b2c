<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.wt_slider.js" charset="utf-8"></script>
<div id="store_decoration_area" class="store-decoration-page">
    <?php if(!empty($output['block_list']) && is_array($output['block_list'])) {?>
    <?php foreach($output['block_list'] as $block) {?>
    <?php require('store_decoration_block.php');?>
    <?php } ?>
    <?php } ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //幻灯片
        $('[wttype="store_decoration_slide"]').wt_slider();
    });
</script>
