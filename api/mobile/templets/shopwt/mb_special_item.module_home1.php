<?php defined('ShopWT') or exit('Access Denied By ShopWT'); ?>

<div class="index_block home1">
    <?php if(!empty($vv['title'])) {?>
    <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div class="content">
        <div wttype="item_image" class="item">
            <a wttype="btn_item" href="javascript:;" data-type="<?php echo $vv['type']; ?>" data-data="<?php echo $vv['data']; ?>">
                <img wttype="image" src="<?php echo $vv['image']; ?>" alt="">
            </a>
        </div>
    </div>
</div>
