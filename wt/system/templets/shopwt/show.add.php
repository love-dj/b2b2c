<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=show&t=show&ap_id=<?php echo $_GET['ap_id'];?>" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['show_index_manage'];?> - 新增广告</h3>
        <h5><?php echo $lang['show_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="show_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default" id="main_table">
      <dl class="row">
        <dt class="tit">
          <label for="show_name"><em>*</em><?php echo $lang['show_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="show_name" id="show_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ap_id"><em>*</em><?php echo $lang['show_ap_select'];?></label>
        </dt>
        <dd class="opt">
          <select name="ap_id" id="ap_id">
            <?php
				 foreach ($output['ap_list'] as $k=>$v){
				 	echo "<option value='".$v['ap_id']."' ap_type='".$v['ap_class']."' ap_width='".$v['ap_width']."' >".$v['ap_name'];
				 	if($v['ap_class'] == '1'){
				 		echo "(".$v['ap_width'].")";
				 		$word_length = $v['ap_width'];
				 	}else{
				 		echo "(".$v['ap_width']."*".$v['ap_height'].")";
				 	}
				 	echo "</option>";
				 }
				?>
          </select>
          <input type="hidden" name="aptype_hidden" id="aptype_hidden"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_start_time"><em>*</em><?php echo $lang['show_start_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="show_start_time" id="show_start_time" class="txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="show_end_time"><em>*</em><?php echo $lang['show_end_time'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="show_end_time" id="show_end_time" class="txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="show_pic">
        <dt class="tit">
          <label for="file_show_pic"><?php echo $lang['show_img_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_show_pic" name="show_pic" size="30" hidefocus="true"  wt_type="upload_file_show_pic" title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['show_edit_support'];?>gif,jpg,jpeg,png</p>
        </dd>
      </dl>
      <dl class="row" id="show_pic_url">
        <dt class="tit">
          <label for="type_show_pic_url"> <?php echo $lang['show_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="show_pic_url" class="input-txt" id="type_show_pic_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['show_url_donotadd'];?></p>
        </dd>
      </dl>
      <dl class="row" id="show_word">
        <dt class="tit">
          <label for="type_show_word"><?php echo $lang['show_word_content'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="show_word" id="type_show_word" class="input-txt">
          <span class="err"></span>
          <p class="notic" id="show_word_len"></p>
        </dd>
      </dl>
      <dl class="row" id="show_word_url">
        <dt class="tit">
          <label for="type_show_word_url"> <?php echo $lang['show_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="show_word_url" class="input-txt" id="type_show_word_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['show_url_donotadd'];?></p>
        </dd>
      </dl>
      <dl class="row" id="show_flash_swf">
        <dt class="tit">
          <label for="file_flash_swf"><?php echo $lang['show_flash_upload'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_flash_swf" name="flash_swf" size="30" hidefocus="true"  title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['show_please_file_swf_file'];?></p>
        </dd>
      </dl>
      <dl class="row" id="show_flash_url">
        <dt class="tit">
          <label for="type_show_flash_url"><?php echo $lang['show_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="flash_url" class="input-txt" id="type_show_flash_url">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['show_url_donotadd'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#show_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#show_end_time').datepicker({dateFormat: 'yy-mm-dd'});

    $('#show_pic').hide();
    $('#show_pic_url').hide();
    $('#show_word').hide();
    $('#show_word_url').hide();
    $('#show_flash_swf').hide();
    $('#show_flash_url').hide();

    $('#ap_id').change(function(){
    	var select   = document.getElementById("ap_id");
    	var ap_type  = select.item(select.selectedIndex).getAttribute("ap_type");
    	var ap_width = select.item(select.selectedIndex).getAttribute("ap_width");
        if(ap_type == '0'){
    	    $('#show_pic').show();
            $('#show_pic_url').show();
            $('#show_word').hide();
            $('#show_word_url').hide();
            $('#show_flash_swf').hide();
            $('#show_flash_url').hide();
        }
        if(ap_type == '1'){
        	$('#show_word').show();
            $('#show_word_url').show();
            $('#show_word_len').html("<span>最多"+ap_width+"个字</span><input type='hidden' name='show_word_len' value='"+ap_width+"'>");
            $('#show_pic').hide();
            $('#show_pic_url').hide();
            $('#show_flash_swf').hide();
            $('#show_flash_url').hide();
        }
        if(ap_type == '3'){
        	$('#show_flash_swf').show();
            $('#show_flash_url').show();
            $('#show_pic').hide();
            $('#show_pic_url').hide();
            $('#show_word').hide();
            $('#show_word_url').hide();
        }
        $("#aptype_hidden").val(ap_type);
    });
});
</script>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#show_form").valid()){
     $("#show_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#show_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parents('dl').find('span.err');
            error_td.append(error);
        },
        rules : {
        	show_name : {
                required : true
            },
            aptype_hidden : {
                required : true
            },
            show_start_time  : {
                required : true,
                date	 : false
            },
            show_end_time  : {
            	required : true,
                date	 : false
            },
			show_pic:{
				required :function(){ if($("#show_pic").css('display') == 'block'){return true;}else{return false}},
				accept : 'png|jpe?g|gif'
			},
			flash_swf:{
				required :function(){ if($("#show_flash_swf").css('display') == 'block'){return true;}else{return false}},
				accept : 'swf'
			}
        },
        messages : {
        	show_name : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['show_can_not_null'];?>'
            },
            aptype_hidden : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['must_select_ap_id'];?>'
            },
            show_start_time  : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['show_start_time_can_not_null']; ?>'
            },
            show_end_time  : {
            	required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['show_end_time_can_not_null']; ?>'
            },
            show_pic: {
        		required : '<i class="fa fa-exclamation-bbs"></i>请上传图片',
				accept   : '<i class="fa fa-exclamation-bbs"></i>图片格式错误'
			},
			flash_swf: {
        		required : '<i class="fa fa-exclamation-bbs"></i>请上传Flash',
				accept   : '<i class="fa fa-exclamation-bbs"></i>Flash格式错误'
			}
        }
    });
});
</script>
<script type="text/javascript">
$(function(){
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_show_pic");
    $("#file_show_pic").change(function(){
	$("#textfield1").val($("#file_show_pic").val());
    });

	var textButton="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_flash_swf");
    $("#file_flash_swf").change(function(){
	$("#textfield3").val($("#file_flash_swf").val());
    });
    $('#ap_id').val('<?php echo $_GET['ap_id'];?>');
    $('#ap_id').change();
});
</script>