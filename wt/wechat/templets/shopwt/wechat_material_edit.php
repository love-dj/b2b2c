<link type="text/css" href="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/material.css" rel="stylesheet" />
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
	 <div class="fixed-bar">
      <div class="item-title"><a class="back" href="index.php?w=material" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['material_manage'];?> - 编辑</h3>
        <h5><?php echo $lang['material_edit'];?></h5>
      </div>
    </div>
  </div>

  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
  <input type="hidden" name="form_submit" value="ok" />
  <?php if(!empty($_GET['mid'])){?>
  <input type="hidden" name="mid" value="<?php echo $_GET['mid'];?>" />
  <?php }?>
<div class="wtap-form-default" style=" height: 100%;  overflow-y: auto;">
  	<div id="material">
        <div class="m_lefter multi">
          <div class="time"><?php echo date('Y-m-d',$output['material']['material_addtime']);?></div>
          <?php
		  	if(!empty($output['material']['items'])){
				foreach($output['material']['items'] as $key=>$value){
		  ?>
          <div class="<?php echo $key==0 ? 'first' : 'list';?>" id="multi_msg_<?php echo $key;?>">
            <div class="info">
              <div class="img"><img src="<?php echo UPLOAD_SITE_URL.$value['ImgPath'];?>" /></div>
              <div class="title"><?php echo $value['Title'];?></div>
            </div>
            <div class="control"><a href="#mod"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a></div>
            <input type="hidden" name="Title[]" value="<?php echo $value['Title'];?>" />
            <input type="hidden" name="Url[]" value="<?php echo $value['Url'];?>" />
            <input type="hidden" name="ImgPath[]" value="<?php echo UPLOAD_SITE_URL.$value['ImgPath'];?>" />
            <textarea style="display:none" name="TextContents[]"><?php echo $value['TextContents'];?></textarea>
          </div>
          <?php }?>
          <?php if(count($output['material']['items'])==1){?>
          <div class="list" style="display:none">
            <div class="info">
              <div class="title"><?php echo $lang['material_item_title'];?></div>
              <div class="img"><?php echo $lang['material_item_image'];?></div>
            </div>
            <div class="control"> <a href="#mod"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a> <a href="#del"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/del.gif" /></a> </div>
            <input type="hidden" name="Title[]" value="" />
            <input type="hidden" name="Url[]" value="" />
            <input type="hidden" name="ImgPath[]" value="" />
            <textarea style="display:none" name="TextContents[]"></textarea>
          </div>
          <?php }}else{?>
          <div class="first" id="multi_msg_0">
            <div class="info">
              <div class="img"><?php echo $lang['material_item_openimage'];?></div>
              <div class="title"><?php echo $lang['material_item_title'];?></div>
            </div>
            <div class="control"><a href="#mod"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a></div>
            <input type="hidden" name="Title[]" value="" />
            <input type="hidden" name="Url[]" value="" />
            <input type="hidden" name="ImgPath[]" value="" />
            <textarea style="display:none" name="TextContents[]"></textarea>
          </div>
          <div class="list" style="display:none">
            <div class="info">
              <div class="title"><?php echo $lang['material_item_title'];?></div>
              <div class="img"><?php echo $lang['material_item_image'];?></div>
            </div>
            <div class="control"> <a href="#mod"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/mod.gif" /></a> <a href="#del"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/del.gif" /></a> </div>
            <input type="hidden" name="Title[]" value="" />
            <input type="hidden" name="Url[]" value="" />
            <input type="hidden" name="ImgPath[]" value="" />
            <textarea style="display:none" name="TextContents[]"></textarea>
          </div>
          <?php }?>
          <div class="add"><a href="#add"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/add.gif" align="absmiddle" /> <?php echo $lang['material_item_add'];?></a></div>
        </div>
        <div class="m_righter">
          <div class="mod_form">
            <div class="jt"><img src="<?php echo ADMIN_TEMPLATES_URL?>/css/weixin/jt.gif" /></div>
            <div class="m_form"> <span class="fc_red">*</span> <?php echo $lang['material_item_title'];?><br />
              <div class="input">
                <input name="inputTitle" value="<?php echo !empty($output['material']['items']) ? $output['material']['items'][0]['Title'] : ''; ?>" type="text" />
              </div>
              <div class="blank9"></div>
              <span class="fc_red">*</span> <?php echo $lang['material_item_openimage'];?> <span class="tips"><?php echo $lang['material_item_image_size'];?><span class="big_img_size_tips">640*360px</span></span><br />
              <div class="blank6"></div>
				<div class="input-file-show"><span class="show">
				<?php if(empty($output['material']['items'][0]['ImgPath'])) { ?>
				<a class="nyroModal" rel="gal" href="<?php echo ADMIN_TEMPLATES_URL.'/images/preview.png';?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo ADMIN_TEMPLATES_URL.'/images/preview.png';?>>')" onMouseOut="toolTip()"></i></a>
				<?php } else { ?>
				<a class="nyroModal" rel="gal" href="<?php echo !empty($output['material']['items']) ? UPLOAD_SITE_URL.$output['material']['items'][0]['ImgPath'] : ''; ?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo !empty($output['material']['items']) ? UPLOAD_SITE_URL.$output['material']['items'][0]['ImgPath'] : ''; ?>>')" onMouseOut="toolTip()"></i> </a>
				<?php } ?>
				</span> <span class="type-file-box">
				<input type="text" name="imgpath" ret="multi_msg_0" value="<?php echo !empty($output['material']['items']) ? UPLOAD_SITE_URL.$output['material']['items'][0]['ImgPath'] : ''; ?>" class="type-file-text" />
				<input type='button' name='button' id='button' value='请选择图片' class='type-file-button' />
				<input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true">
				</span></div>
              <div class="blank12"></div>
              <br />简短介绍<br />
              <div class="intro">
                <textarea name="inputContent"><?php echo !empty($output['material']['items']) ? $output['material']['items'][0]['TextContents'] : ''; ?></textarea>
              </div>
              <div class="blank3"></div>
              <span class="fc_red">*</span> <?php echo $lang['material_item_link'];?><br />
              <div class="input">
                <input name="inputUrl" value="<?php echo !empty($output['material']['items']) ? $output['material']['items'][0]['Url'] : ''; ?>" type="text" />
              </div>
            </div>
          </div>
        </div>
    </div>
	</div>
        <div class="clear"></div>
		<div class="bot" style="border-top: 1px solid #e5e5e5; padding-top: 10px;"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script>
$(function(){
	$("#submit").click(function(){
        $("#add_form").submit();
    });
	
	$('input[class="type-file-file"]').change(uploadChange);
	
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == ''){
			return false;
		}
		ajaxFileUpload();
	}
	
	function ajaxFileUpload(){
		$.ajaxFileUpload
		(
			{
				url:'index.php?w=common&t=pic_upload&form_submit=ok&uploadpath=weixin/material',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						$('input[name=imgpath]').val(data.url);
						$('#view_img').attr('src',data.url);
						$('#'+$('input[name=imgpath]').attr('ret')+' input[name=ImgPath\\[\\]]').val(data.url);
						$('#'+$('input[name=imgpath]').attr('ret')+' .img').html('<img src="'+data.url+'" />');
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
	
	var material_multi_list_even=function(){
		$('.multi .first, .multi .list').each(function(){
			var children=$(this).children('.control');
			$(this).mouseover(function(){children.css({display:'block'});});
			$(this).mouseout(function(){children.css({display:'none'});});
				
			children.children('a[href*=#del]').click(function(){
				$(this).parent().parent().remove();
				$('.multi .first a[href*=#mod]').click();
				$('.mod_form').css({top:37});
			});
				
			children.children('a[href*=#mod]').click(function(){
				var position=$(this).parent().offset();
				var material_form_position=$('#add_form').offset();
				var cur_id='#'+$(this).parent().parent().attr('id');
				$('.mod_form').css({top:position.top-material_form_position.top});
				$('.mod_form input[name=imgpath]').attr("ret",$(this).parent().parent().attr('id'));
				$('.mod_form input[name=inputUrl]').val($(cur_id+' input[name=Url\\[\\]]').val());
				$('.mod_form input[name=inputTitle]').val($(cur_id+' input[name=Title\\[\\]]').val());
				$('.mod_form textarea[name=inputContent]').val($(cur_id+' textarea[name=TextContents\\[\\]]').val());
				$('.mod_form input[name=imgpath]').val($(cur_id+' input[name=ImgPath\\[\\]]').val());
				if($(cur_id+' input[name=ImgPath\\[\\]]').val()!=''){
					$('#view_img').attr('src',$(cur_id+' input[name=ImgPath\\[\\]]').val());
				}else{
					$('#view_img').removeAttr('src');
				}
				$('.big_img_size_tips').html(cur_id=='#multi_msg_0'?'640*360px':'300*300px');
				$('.multi').data('cur_id', cur_id);
				
			});
		});
	}
	
	$('.multi').data('cur_id', '#'+$('.multi .first').attr('id'));
		
	$('.mod_form input').filter('[name=inputTitle]').on('keyup paste blur', function(){
		var cur_id=$('.multi').data('cur_id');
		$(cur_id+' input[name=Title\\[\\]]').val($(this).val());
		$(cur_id+' .title').html($(this).val());
	})
	
	$('.mod_form textarea').filter('[name=inputContent]').on('keyup paste blur', function(){
		var cur_id=$('.multi').data('cur_id');
		$(cur_id+' textarea[name=TextContents\\[\\]]').html($(this).val());
	})
	
	$('.mod_form input').filter('[name=inputUrl]').on('keyup paste blur', function(){
		var cur_id=$('.multi').data('cur_id');
		$(cur_id+' input[name=Url\\[\\]]').val($(this).val());
	})
		
	$('.mod_form img').filter('.btn_select_url').on('click', function(){
		var id=$('.multi').data('cur_id').replace("#multi_msg_","");
	})
		
	material_multi_list_even();
	$('a[href=#add]').click(function(){
		$(this).blur();
		if($('.multi .list').size()>=7){
			alert('<?php echo $lang['material_item_max'];?>');
			return false;
		}
		$('.multi .list, a[href*=#mod], a[href*=#del]').off();
		$('<div class="list" id="multi_msg_'+Math.floor(Math.random()*1000000)+'">'+$('.multi .list:last').html()+'</div>').insertAfter($('.multi .list:last'));
		$('.multi .list:last').children('.info').children('.title').html('<?php echo $lang['material_item_title'];?>').siblings('.img').html('<?php echo $lang['material_item_image'];?>');
		$('.multi .list:last input').filter('[name=Title\\[\\]]').val('').end().filter('[name=Url\\[\\]]').val('').end().filter('[name=ImgPath\\[\\]]').val('');
		material_multi_list_even();
	});
});
</script> 
