<?php defined('ShopWT') or exit('Access Denied By ShopWT'); ?>

<div class="show_list">
    <div class="swipe-wrap">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div class="item" wttype="item_image">
            <a wttype="btn_item" href="javascript:;" data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>">
                <img wttype="image" src="<?php echo $item['image']; ?>" alt="">
            </a>
        </div>
    <?php } ?>
    </div>
</div>
