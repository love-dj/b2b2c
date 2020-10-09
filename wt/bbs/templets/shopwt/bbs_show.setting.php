<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_showmanage'];?></h3>
        <h5><?php echo $lang['wt_bbs_showmanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['bbs_setting_show_prompts_one'];?></li>
      <li><?php echo $lang['bbs_setting_show_prompts_two'];?></li>
      <li><?php echo $lang['bbs_setting_show_prompts_three'];?></li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="old_show_pic1" value="<?php echo $output['list'][1]['pic'];?>" />
    <input type="hidden" name="old_show_pic2" value="<?php echo $output['list'][2]['pic'];?>" />
    <input type="hidden" name="old_show_pic3" value="<?php echo $output['list'][3]['pic'];?>" />
    <input type="hidden" name="old_show_pic4" value="<?php echo $output['list'][4]['pic'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['bbs_setting_show'];?>-01</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][1]['pic']);?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][1]['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="show_pic1" type="file" class="type-file-file" id="show_pic1" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="<?php echo $lang['bbs_setting_show_url_address'];?>" class="ml5"><i class="fa fa-link"></i><input class="input-txt ml5" type="text" name="show_url1" value="<?php echo $output['list'][1]['url'];?>" placeholder="<?php echo $lang['bbs_setting_show_url_address'];?>" /></label>
          <p class="notic">请使用宽度650像素，高度240像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['bbs_setting_show'];?>-02</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][2]['pic']);?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][2]['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="show_pic2" type="file" class="type-file-file" id="show_pic2" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield' id='textfield2' class='type-file-text' />
            <input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />
            </span></div>
         <label title="<?php echo $lang['bbs_setting_show_url_address'];?>" class="ml5"><i class="fa fa-link"></i><input class="input-txt ml5" type="text" name="show_url2" value="<?php echo $output['list'][2]['url'];?>" placeholder="<?php echo $lang['bbs_setting_show_url_address'];?>" /></label>
          <p class="notic">请使用宽度650像素，高度240像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['bbs_setting_show'];?>-03</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][3]['pic']);?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][3]['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="show_pic3" type="file" class="type-file-file" id="show_pic3" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield' id='textfield3' class='type-file-text' />
            <input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="<?php echo $lang['bbs_setting_show_url_address'];?>" class="ml5"><i class="fa fa-link"></i><input class="input-txt ml5" type="text" name="show_url3" value="<?php echo $output['list'][3]['url'];?>" placeholder="<?php echo $lang['bbs_setting_show_url_address'];?>" /></label>
          <p class="notic">请使用宽度650像素，高度240像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['bbs_setting_show'];?>-04</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][4]['pic']);?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.(ATTACH_BBS.'/'.$output['list'][4]['pic']);?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="show_pic4" type="file" class="type-file-file" id="show_pic4" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield' id='textfield4' class='type-file-text' />
            <input type='button' name='button' id='button4' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="<?php echo $lang['bbs_setting_show_url_address'];?>" class="ml5"><i class="fa fa-link"></i><input class="input-txt ml5" type="text" name="show_url4" value="<?php echo $output['list'][4]['url'];?>" placeholder="<?php echo $lang['bbs_setting_show_url_address'];?>" /></label>
          <p class="notic">请使用宽度650像素，高度240像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.form1.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$("#show_pic1").change(function(){$("#textfield1").val($(this).val());});
	$("#show_pic2").change(function(){$("#textfield2").val($(this).val());});
	$("#show_pic3").change(function(){$("#textfield3").val($(this).val());});
	$("#show_pic4").change(function(){$("#textfield4").val($(this).val());});
// 上传图片类型
$('input[class="type-file-file"]').change(function(){
	var filepatd=$(this).val();
	var extStart=filepatd.lastIndexOf(".");
	var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("<?php echo $lang['bbs_setting_show_img_check'];?>");
			$(this).attr('value','');
			return false;
		}
	});
	
$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');
$('.nyroModal').nyroModal();
});
</script>