<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!-- 引入幻灯片JS --> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.flexslider.min.js"></script> 
<div class="flexslider">
    <ul class="slides">
    <?php if(!empty($output['store_list_show_list']) && is_array($output['store_list_show_list'])) {?>
<!-- 绑定幻灯片事件 --> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.flexslider').flexslider();
    });
</script>
    <?php foreach($output['store_list_show_list'] as $key=>$value) {?>
    <li>
    <a href="<?php echo $value['show_url'];?>">
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'shopwt'.DS.$value['show_image'];?>"/> 
    </a>
    </li>
    <?php } ?>
    <?php } else { ?>
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'store_banner.jpg';?>"/> 
    <?php } ?>
</ul>
</div>
<?php
require("widget_store_list.php");
?>
