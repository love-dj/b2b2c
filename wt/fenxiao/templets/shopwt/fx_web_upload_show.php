<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script type="text/javascript">
    <?php if (is_array($output['pic']) && !empty($output['pic'])) { ?>
	parent.show_pic("<?php echo $output['pic']['pic_id'];?>","<?php echo $output['pic']['pic_img'];?>");
	<?php } ?>

</script>