<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_what_manage'];?></h3>
        <h5><?php echo $lang['wt_what_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?w=manage&t=manage_save">
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="what_isuse"><?php echo $lang['what_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable <?php if($output['setting']['what_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_open'];?>"><?php echo $lang['wt_open'];?></label>
            <label for="isuse_0" class="cb-disable <?php if($output['setting']['what_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_close'];?>"><?php echo $lang['wt_close'];?></label>
            <input type="radio" id="isuse_1" name="what_isuse" value="1" <?php echo $output['setting']['what_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="isuse_0" name="what_isuse" value="0" <?php echo $output['setting']['what_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['what_isuse_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="what_style"><?php echo $lang['what_style'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['what_style'];?>" name="what_style" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['what_style_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="class_image"><?php echo $lang['wt_what'].'LOGO';?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show">
            <?php if(empty($output['setting']['what_logo'])) { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'what_logo.png';?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'what_logo.png';?>>')" onMouseOut="toolTip()"></i></a>
            <?php } else { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.$output['setting']['what_logo'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.$output['setting']['what_logo'];?>>')" onMouseOut="toolTip()"></i> </a>
            <?php } ?>
            </span> <span class="type-file-box">
            <input name="what_logo" type="file" class="type-file-file" id="what_logo" size="30" hidefocus="true" wt_type="what_image">
            </span></div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="class_image"><?php echo $lang['what_header_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show">
            <?php if(empty($output['setting']['what_header_pic'])) { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'default_header_pic_image.png';?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'default_header_pic_image.png';?>>')" onMouseOut="toolTip()"></i></a>
            <?php } else { ?>
            <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.$output['setting']['what_header_pic'];?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.$output['setting']['what_header_pic'];?>>')" onMouseOut="toolTip()"></i> </a>
            <?php } ?>
            </span> <span class="type-file-box">
            <input name="what_header_pic" type="file" class="type-file-file" id="what_header_pic" size="30" hidefocus="true" wt_type="what_image">
            </span></div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="what_personal_limit"><?php echo $lang['what_personal_limit'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['what_personal_limit'];?>" name="what_personal_limit" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['what_personal_limit_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="what_seo_keywords"><?php echo $lang['what_seo_keywords'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['what_seo_keywords'];?>" name="what_seo_keywords" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="what_seo_description"><?php echo $lang['what_seo_description'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['what_seo_description'];?>" name="what_seo_description" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){

    //文件上传
    var textButton1="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />";
    $(textButton1).insertBefore("#what_logo");
    $("#what_logo").change(function(){
        $("#textfield1").val($("#what_logo").val());
    });
    var textButton2="<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />";
    $(textButton2).insertBefore("#what_header_pic");
    $("#what_header_pic").change(function(){
        $("#textfield2").val($("#what_header_pic").val());
    });
    $("input[wt_type='what_image']").live("change", function(){
		var src = getFullPath($(this)[0]);
		$(this).parent().prev().find('.low_source').attr('src',src);
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});

    $("#submit").click(function(){
        $("#add_form").submit();
    });

});
</script> 
