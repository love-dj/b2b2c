<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=bbs_manage&t=bbs_list" title="返回社区列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_manage'];?> - <?php echo $output['bbs_info']['bbs_status'] == 2 ? '审核' : $lang['wt_edit'];?>社区“<?php echo $output['bbs_info']['bbs_name'];?>”</h3>
        <h5><?php echo $lang['wt_bbs_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="bbs_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="c_id" value="<?php echo $output['bbs_info']['bbs_id'];?>" />
    <input type="hidden" name="c_oldimg" value="<?php echo $output['bbs_info']['bbs_img'];?>" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="c_name"><em>*</em><?php echo $lang['bbs_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_name" id="c_name" class="input-txt" value="<?php echo $output['bbs_info']['bbs_name'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="mastername"><em>*</em><?php echo $lang['bbs_member_identity_master'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="mastername" id="mastername" class="input-txt" readonly value="<?php echo $output['bbs_info']['bbs_mastername'];?>" />
          <input type="hidden" name="masterid" id="masterid" value="<?php echo $output['bbs_info']['bbs_masterid'];?>" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="" for="classid"><?php echo $lang['bbs_class'];?></label>
        </dt>
        <dd class="opt">
          <select name="classid">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php if(!empty($output['class_list'])){?>
            <?php foreach ($output['class_list'] as $val){?>
            <option value="<?php echo $val['class_id'];?>" <?php if($output['bbs_info']['class_id'] == $val['class_id']){echo 'selected="selected"';}?>><?php echo $val['class_name'];?></option>
            <?php }?>
            <?php }?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_desc"><?php echo $lang['bbs_desc'];?></label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_desc" id="c_desc"><?php echo $output['bbs_info']['bbs_desc'];?></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_desc_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_tag"><?php echo $lang['bbs_tag'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" name="c_tag" class="input-txt" value="<?php echo $output['bbs_info']['bbs_tag'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_tag_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_notice"><?php echo $lang['bbs_notice'];?></label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_notice" id="c_notice"><?php echo $output['bbs_info']['bbs_notice'];?></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['bbs_notice_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['bbs_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo bbsLogo($output['bbs_info']['bbs_id']);?>?<?php echo microtime();?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo bbsLogo($output['bbs_info']['bbs_id']);?>?<?php echo microtime();?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="_pic" name="_pic" type="file" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="c_img" id="c_img" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_status"><?php echo $lang['bbs_ststus']?></label>
        </dt>
        <dd class="opt">
          <label>
            <input type="radio" name="c_status" <?php if($output['bbs_info']['bbs_status'] == 0){ echo 'checked="checked"';}?> value="0" />
            <?php echo $lang['wt_close'];?></label>
          <label>
            <input type="radio" name="c_status" <?php if($output['bbs_info']['bbs_status'] == 1){ echo 'checked="checked"';}?> value="1" />
            <?php echo $lang['wt_open'];?></label>
          <label>
            <input type="radio" name="c_status" <?php if($output['bbs_info']['bbs_status'] == 2){ echo 'checked="checked"';}?> value="2" />
            <?php echo $lang['bbs_verifying'];?></label>
          <label>
            <input type="radio" name="c_status" <?php if($output['bbs_info']['bbs_status'] == 3){ echo 'checked="checked"';}?> value="3" />
            <?php echo $lang['bbs_verify_fail'];?></label>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="bbs_statusinfo" <?php if($output['bbs_info']['bbs_status'] == 1 || $output['bbs_info']['bbs_status'] == 2){ echo 'style="display:none;"';}?>>
        <dt class="tit">
          <label for="c_statusinfo"><?php echo $lang['bbs_verify_fail_reason'];?></label>
        </dt>
        <dd class="opt">
          <textarea class="tarea" rows="6" name="c_statusinfo" id="c_statusinfo"><?php echo $output['bbs_info']['bbs_statusinfo'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="c_recommend"><?php echo $lang['bbs_is_recommend'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="c_recommend1" class="cb-enable <?php if($output['bbs_info']['is_recommend'] == 1) echo 'selected';?>" ><?php echo $lang['wt_yes'];?></label>
            <label for="c_recommend0" class="cb-disable <?php if($output['bbs_info']['is_recommend'] == 0) echo 'selected';?>" ><?php echo $lang['wt_no'];?></label>
            <input id="c_recommend1" name="c_recommend" <?php if($output['bbs_info']['is_recommend'] == 1) echo 'checked="checked"';?> value="1" type="radio">
            <input id="c_recommend0" name="c_recommend" <?php if($output['bbs_info']['is_recommend'] == 0) echo 'checked="checked"';?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="is_hot">是否热门</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="is_hot1" class="cb-enable <?php if($output['bbs_info']['is_hot'] == 1) echo 'selected';?>" ><?php echo $lang['wt_yes'];?></label>
            <label for="is_hot0" class="cb-disable <?php if($output['bbs_info']['is_hot'] == 0) echo 'selected';?>" ><?php echo $lang['wt_no'];?></label>
            <input id="is_hot1" name="is_hot" <?php if($output['bbs_info']['is_hot'] == 1) echo 'checked="checked"';?> value="1" type="radio">
            <input id="is_hot0" name="is_hot" <?php if($output['bbs_info']['is_hot'] == 0) echo 'checked="checked"';?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script>
//裁剪图片后返回接收函数
function call_back(picname){
	$('#c_img').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS;?>/group/'+picname+'?'+Math.random());
}
// 选择圈主
function chooseReturn(data) {
    $('#mastername').val(data.name);$('#masterid').val(data.id);
}
//按钮先执行验证再提交表单
$(function(){
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
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'<?php echo ADMIN_SITE_URL?>/index.php?w=common&t=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_BBS;?>/group',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['wt_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?w=common&t=pic_cut&type=bbs&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BBS;?>/group/<?php echo $_GET['c_id'];?>.jpg&url='+data.url,690);
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
	}	
	// 关闭或审核失败原因控制
	$('input[name="c_status"]').click(function(){
		statusinfo($(this).val());
	});
	// 选择圈主弹出框
	$('#mastername').focus(function(){
		ajax_form('choose_master', '<?php echo $lang['bbs_choose_master'];?>', 'index.php?w=bbs_manage&t=choose_master&c_id=<?php echo $output['bbs_info']['bbs_id'];?>', 640);
	});
	$("#submitBtn").click(function(){
		if($("#bbs_form").valid()){
			$("#bbs_form").submit();
		}
	});
	$('#bbs_form').validate({
         errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	c_name : {
        		required : true,
        		minlength : 4,
        		maxlength : 12,
            	remote : {
            		url : 'index.php?w=bbs_manage&t=check_bbs_name&id=<?php echo $output['bbs_info']['bbs_id'];?>',
                    type: 'get',
                    data:{
                    	name : function(){
                            return $('#c_name').val();
                        }
                    }
            	}
            },
            mastername : {
            	required : true,
                remote   : {
                    url :'index.php?w=bbs_manage&t=check_member&c_id=<?php echo $output['bbs_info']['bbs_id'];?>&branch=ok',
                    type:'get',
                    data:{
                        name : function(){
                            return $('#mastername').val();
                        },
                        id : function(){
                            return $('#masterid').val();
                        }
                    }
                }
            },
            c_desc : {
            	maxlength : 240
            },
            c_tag : {
                maxlength : 50
            },
            c_notice : {
                maxlength : 240
            },
            c_sort : {
            	digits : true,
            	max : 255
            },
            c_statusinfo : {
				maxlength : 240
            }
        },
        messages : {
            c_name : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_name_not_null'];?>',
        		minlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_name_length_4_12'];?>',
                maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_name_length_4_12'];?>',
            	remote   : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_change_name_please'];?>'
            },
            mastername : {
            	required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_choose_master_please'];?>',
            	remote   : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_master_choose_error'];?>'
            },
            c_desc : {
            	maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_desc_maxlength'];?>'
            },
            c_tag : {
                maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_tag_maxlength'];?>'
            },
            c_notice : {
                maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_notice_maxlength'];?>'
            },
            c_sort : {
            	digits : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_sort_digits'];?>',
            	max : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_sort_max'];?>'
            },
            c_statusinfo : {
				maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['bbs_verify_fail_reason_maxlength'];?>'
            }
        }
    });
});
function statusinfo(val){
	if(val == '0' || val == '3'){
		$('#bbs_statusinfo').show();
	}else if(val == '1' || val == '2'){
		$('#bbs_statusinfo').hide();
	}
}
</script> 
