<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['floor_list']) && is_array($output['floor_list'])){ ?>

<ul id="show_list" class="floor_list">
  <?php foreach($output['floor_list'] as $k => $v){ ?>
  <li><i class="fa fa-list-alt"></i>模块：<?php echo $v['web_name'];?><a href="javascript:;" class="wtap-btn-mini wtap-btn-green" onclick="select_floor(<?php echo $v['web_id'];?>);" f_id="<?php echo $v['web_id'];?>" f_name="<?php echo $v['web_name'];?>"><i class="fa fa-plus-square-o"></i>添加</a><span class="ok"><i class="fa fa-check-bbs-o"></i>已添加</span></li>
  <?php } ?>
</ul>
<div id="show_page_floor" class="pagination"> <?php echo $output['show_page'];?> </div>
<div class="clear"></div>
<?php }else { ?>
<?php echo $lang['wt_no_record'];?>
<?php } ?>
<script type="text/javascript">
	$('#show_page_floor .demo').ajaxContent({
		target:'#show_floor_list'
	});
	$("#show_list li").each(function(){
	    var f_id = $(this).find("a").attr("f_id");
	    if($("#floor_"+f_id).size()>0) $(this).addClass("selected");
	});
</script>