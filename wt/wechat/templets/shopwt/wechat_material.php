<link type="text/css" href="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/material.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/blocksit.min.js"></script>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>
					<?php echo $lang['material_manage'];?>
				</h3>
				<h5>设置素材信息</h5>
			</div>
			<?php echo $output['top_link'];?>
		</div>
	</div>
	<!-- 操作说明 -->
	<div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span> </div>
		<ul>
			<li>素材信息用于微信关注、自定义菜单等功能里选择图文消息。</li>
		</ul>
	</div>
<div class="flexigrid">
	<div class="mDiv">
		<div class="ftitle">
			<h3>素材信息列表</h3>
		</div>
		<div class="sDiv">
			<div class="sDiv2">
				<form method="get" name="formSearch" id="formSearch">
				  <input type="hidden" value="<?php echo $_GET['w'];?>" name="w">
				  <input type="hidden" value="<?php echo $_GET['t'];?>" name="t">
					<select name="fields" class="select">
					 <?php foreach($lang['material_type'] as $k => $v){ ?>
						<option value="<?php echo $k;?>" <?php if(!empty($_GET['material_type']) && $_GET['material_type'] == $k){?>selected<?php }?>><?php echo $v;?></option>
					<?php } ?>
					<input size="30" name="keywords" id="keywords" class="qsbox"  value="<?php echo empty($_GET['keywords']) ? '' : $_GET['keywords'];?>" placeholder="搜索相关数据..." type="text"> <input class="btn" id="wtsubmit" value="<?php echo $lang['wt_query'];?>" type="button">
					 </form>
			</div>
		</div>
	</div>
	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
			  <div id="material_list">
 	 <div class="list">
     	<?php if(!empty($output['material_list'])){?>
        <?php foreach($output['material_list'] as $key=>$value){?>
        <?php if($value['material_type']==2){?>
        <div class="item multi">
          <div class="time"><?php echo date("Y-m-d",$value['material_addtime']);?></div>
		  <?php foreach($value['material_content'] as $k=>$v){?>
          <div class="<?php echo $k>0 ? "other" : "first" ?>">
            <div class="info">
              <div class="img"><img src="<?php echo UPLOAD_SITE_URL.$v['ImgPath'] ?>" /></div>
              <div class="title"><?php echo $v['Title'] ?></div>
            </div>
          </div>
		  <?php }?>
          <div class="mod_del">
            <div class="mod"><a href="index.php?w=material&t=material_edit&mid=<?php echo $value['material_id'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a></div>
            <div class="del"><a href="index.php?w=material&t=material_del&mid=<?php echo $value['material_id'];?>" onClick="if(!confirm('<?php echo $lang['material_delete_tips'];?>')){return false};"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/del.gif" /></a></div>
          </div>
        </div>
        <?php }else{?>
        <div class="item one">
        <?php foreach($value['material_content'] as $k=>$v){?>
          <div class="title"><?php echo $v['Title'] ?></div>
          <div><?php echo date("Y-m-d",$value['material_addtime']) ?></div>
          <div class="img"><img src="<?php echo UPLOAD_SITE_URL.$v['ImgPath'] ?>" /></div>
          <div class="txt"><?php echo str_replace(array("\r\n", "\r", "\n"), "<br />",$v['TextContents']);?></div>
        <?php }?>
          <div class="mod_del">
            <div class="mod"><a href="index.php?w=material&t=material_edit&mid=<?php echo $value['material_id'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a></div>
            <div class="del"><a href="index.php?w=material&t=material_del&mid=<?php echo $value['material_id'];?>" onClick="if(!confirm('<?php echo $lang['material_delete_tips'];?>')){return false};"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/del.gif" /></a></div>
          </div>
        </div>
        <?php }?>
        <?php }?>
        <?php }?>
     </div>
  </div>
		</div>
	</div>
	<div class="pDiv">
			<div class="pagination">
				<?php echo $output['page'];?>
			</div>
		<div style="clear:both"></div>
	</div>
</div>
</div>
<script>
$(function(){
    $('#wtsubmit').click(function(){
    	$('#formSearch').submit();
    });
	
	$(window).load( function() {
		$('#material_list .list').BlocksIt({
			numOfCol: 4,
			offsetX: 8,
			offsetY: 8,
			blockElement: '.item'
		});
	});
	
	//window resize
	var currentWidth = 1460;
	$(window).resize(function() {
		var winWidth = $(window).width();
		var conWidth;
		if(winWidth < 730) {
			conWidth = 365;
			col = 1
		} else if(winWidth < 1095) {
			conWidth = 730;
			col = 2
		} else if(winWidth < 1460) {
			conWidth = 1095;
			col = 4;
		} else{
			conWidth = 1460;
			col = 4;
		}
		if(conWidth != currentWidth) {
			currentWidth = conWidth;
			$('#material_list .list').width(conWidth);
			$('#material_list .list').BlocksIt({
				numOfCol: col,
				offsetX: 8,
				offsetY: 8
			});
		}
	});
});
</script>
