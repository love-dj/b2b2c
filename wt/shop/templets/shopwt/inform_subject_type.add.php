<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=inform&t=inform_subject_type_list" title="返回举报类型列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['inform_manage_title'];?> - <?php echo $lang['wt_new'];?></h3>
        <h5><?php echo $lang['inform_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?w=inform&t=inform_subject_type_save" name="form1">
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['inform_type'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="inform_type_name" name="inform_type_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['inform_type_desc'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="inform_type_desc" rows="6" id="inform_type_desc" class="tarea"></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="btn_add"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#goods_class_form").valid()){
     $("#goods_class_form").submit();
	}
	});
});
//
$(document).ready(function(){
    //添加按钮的单击事件
    $("#btn_add").click(function(){
        submit_form();
    });
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	inform_type_name: {
        		required : true,
                maxlength : 50
        	},
        	inform_type_desc: {
                required : true,
                maxlength : 100
            }
        },
        messages : {
      		inform_type_name: {
       			required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['inform_type_null'];?>',
       			maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['inform_type_null'];?>'
	    	},
	    	inform_type_desc: {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['inform_type_desc_null'];?>',
                maxlength: '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['inform_type_desc_null'];?>'
		    }
        }
	});
});
//submit函数
function submit_form(submit_type){
	$('#add_form').submit();
}
</script>