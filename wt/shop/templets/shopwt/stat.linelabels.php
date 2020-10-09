<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div id="container_<?php echo $output['stattype'];?>"></div>
<script>
$(function () {
	$('#container_<?php echo $output['stattype'];?>').highcharts(<?php echo $output['stat_json'];?>);
});
</script>