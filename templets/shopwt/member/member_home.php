<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div id="member_center_box" class="wtm-index-container">
</div>
<script type="text/javascript">
$(function(){
	var INFO_TYPE = ['member_info','order_info','goods_content','sns_info'];
	function _ajax_load(type) {
		$.ajax({
			   url: 'index.php?w=member&t=ajax_load_'+type,
			   success: function(html){
				   INFO_TYPE.shift();
				   if (INFO_TYPE[0]) {
					   _ajax_load(INFO_TYPE[0]);
				   }
				   $('#member_center_box').append(html);
			   }
		});
	}
	_ajax_load(INFO_TYPE[0])
});
</script>