<?php if(!empty($output['material_list'])){?>
  <div class="material_dialog">
 	 <div class="list">
        <?php foreach($output['material_list'] as $key=>$value){?>
        <?php if($value['material_type']==2){?>
        <div class="item multi" id="select_<?php echo $value['material_id'];?>">
          <div class="time"><?php echo date("Y-m-d",$value['material_addtime']);?></div>
		  <?php foreach($value['material_content'] as $k=>$v){?>
          <div class="<?php echo $k>0 ? "list" : "first" ?>">
            <div class="info">
              <div class="img"><img src="<?php echo UPLOAD_SITE_URL.$v['ImgPath'] ?>" /></div>
              <div class="title"><?php echo $v['Title'] ?></div>
            </div>
          </div>
		  <?php }?>
          <div class="mod_del">
            <a href="Javascript:select_material(<?php echo $value['material_id'];?>,<?php echo $value['material_type'];?>);">[选择]</a>
          </div>
        </div>
        <?php }else{?>
        <div class="item one" id="select_<?php echo $value['material_id'];?>">
        <?php foreach($value['material_content'] as $k=>$v){?>
          <div class="title"><?php echo $v['Title'] ?></div>
          <div><?php echo date("Y-m-d",$value['material_addtime']) ?></div>
          <div class="img"><img src="<?php echo UPLOAD_SITE_URL.$v['ImgPath'] ?>" /></div>
          <div class="txt"><?php echo str_replace(array("\r\n", "\r", "\n"), "<br />",$v['TextContents']);?></div>
        <?php }?>
          <div class="mod_del">
            <a href="Javascript:select_material(<?php echo $value['material_id'];?>,<?php echo $value['material_type'];?>);">[选择]</a>
          </div>
        </div>
        <?php }?>
        <?php if($key%4==3){?><div class="clear"></div><?php }?>
        <?php }?>
        <div class="clear"></div>
     </div>
  </div>
  <div id="show_goods_order" class="pagination"> <?php echo $output['show_page'];?> </div>
<?php }else{?>
<p class="no-record">没有找到任何记录</p>
<?php } ?>
<div class="clear"></div>
<script type="text/javascript">
	$('#show_goods_order .demo').ajaxContent({
		target:'#show_material_list'
	});
</script>
