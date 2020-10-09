<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if (is_array($output['code_show_list']['code_info']) && !empty($output['code_show_list']['code_info'])) { ?>
    <?php foreach ($output['code_show_list']['code_info'] as $key => $val) { ?>
        <a href="<?php echo $val['pic_url'];?>" target="_blank" title="<?php echo $val['pic_name'];?>">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>" alt="" class="wt-login-enter-ad"/>
        </a>
    <?php } ?>
<?php } ?>
