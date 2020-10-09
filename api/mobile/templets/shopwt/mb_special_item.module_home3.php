<?php defined('ShopWT') or exit('Access Denied By ShopWT'); ?>

<div class="index_block home3">
    <?php if(!empty($vv['title'])) {?>
    <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div class="content">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div wttype="item_image" class="item">
            <a wttype="btn_item" href="javascript:;" data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>"><img wttype="image" src="<?php echo $item['image']; ?>" alt=""></a>
        </div>
    <?php } ?>
    </div>
</div>
