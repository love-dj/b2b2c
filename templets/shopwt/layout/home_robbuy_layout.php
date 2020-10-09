<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php include template('layout/common_layout');?>
<?php include template('layout/cur_local');?>
<?php require_once($tpl_file);?>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/home_index.js" ></script>
<script language="JavaScript">
 //浮动导航  waypoints.js
$("#wtgCategory").waypoint(function(event, direction) {
	$(this).parent().toggleClass('sticky', direction === "down");
	event.stopPropagation();
});
//鼠标触及更替li样式
$(document).ready(function(){
    $("#list").hide();
    $("#button_show").click(function(){
        $("#list").toggle();
    });
    $("#button_close").click(function(){
        $("#list").hide();
    });
});
</script>
<?php require_once template('footer');?>
</body></html>