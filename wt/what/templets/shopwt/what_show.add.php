<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=show&t=show_manage" title="返回广告列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_what_show_manage'];?> - 新增/编辑广告条<?php if(isset($output['show_info']['show_name'])) echo $output['show_info']['show_name'];?></h3>
        <h5><?php echo $lang['wt_what_show_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?w=show&t=show_save">
    <input name="show_id" type="hidden" value="<?php echo $output['show_info']['show_id'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label  for="show_name"><?php echo $lang['what_show_type'];?> </label>
        </dt>
        <dd class="opt">
          <select name="show_type">
            <?php if(!empty($output['show_type_list']) && is_array($output['show_type_list'])) {?>
            <?php foreach($output['show_type_list'] as $key=>$value) {?>
            <option value="<?php echo $key;?>" <?php if($key==$output['show_info']['show_type']) {echo 'selected';}?>><?php echo $value;?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['what_show_type_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_name"><?php echo $lang['what_show_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php if(isset($output['show_info']['show_name'])) echo $output['show_info']['show_name'];?>" name="show_name" id="show_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_image"><em>*</em><?php echo $lang['what_show_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <?php if(!empty($output['show_info']['show_image'])) { ?>
            <span class="show"> <a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'shopwt'.DS.$output['show_info']['show_image'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS.'shopwt'.DS.$output['show_info']['show_image'];?>>')" onMouseOut="toolTip()"></i></a> </span>
            <?php } ?>
            <span class="type-file-box">
            <input name="old_show_image" type="hidden" value="<?php echo $output['show_info']['show_image'];?>" />
            <input name="show_image" type="file" class="type-file-file" id="show_image" size="30" hidefocus="true" wt_type="what_goods_show_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span> </div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['what_show_image_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_url"><?php echo $lang['what_show_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php if(isset($output['show_info']['show_url'])) echo $output['show_info']['show_url'];?>" name="show_url" id="show_url" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['what_show_url_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_sort"><em>*</em><?php echo $lang['wt_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="show_sort" name="show_sort" type="text" class="input-txt" value="<?php echo !isset($output['show_info'])?'255':$output['show_info']['show_sort'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    //文件上传
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />";
    $(textButton).insertBefore("#show_image");
    $("#show_image").change(function(){
        $("#textfield1").val($("#show_image").val());
    });

    $("#submit").click(function(){
        $("#add_form").submit();
    });

    $("input[wt_type='what_goods_show_image']").live("change", function(){
		var src = getFullPath($(this)[0]);
		$(this).parent().prev().find('.low_source').attr('src',src);
		$(this).parent().find('input[class="type-file-text"]').val($(this).val());
	});

    $('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        <?php if(empty($output['show_info'])) { ?>
            show_image: {
                required : true
            },
            <?php } ?>
            show_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
        <?php if(empty($output['show_info'])) { ?>
            show_image: {
                required : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['what_show_image_error'];?>"
            },
            <?php } ?>
            show_sort: {
                required : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_required'];?>",
                digits: "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_digits'];?>",
                max : jQuery.validator.format("<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_max'];?>"),
                min : jQuery.validator.format("<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_min'];?>")
            }
        }
    });
	// 点击查看图片
	$('.nyroModal').nyroModal();
});
</script>