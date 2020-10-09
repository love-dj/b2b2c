<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminbbs('bbs_setting', 'super_list');?>" title="返回超管列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_setting'];?> - 设置社区超级管理员</h3>
        <h5><?php echo $lang['wt_bbs_setting_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="super_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="menber_name">会员名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="member_name" id="member_name" class="input-txt" value="" readonly />
          <input type="hidden" name="member_id" id="member_id" class="input-txt" value="" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script>
$(function(){
    //按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
		$("#super_form").submit();
	});

	$('#super_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            member_name : {
        		required : true
            }
        },
        messages : {
            member_name : {
                required : '<i class="fa fa-exclamation-bbs"></i>请选择会员'
            }
        }
    });

	// 选择圈主弹出框
	$('#member_name').focus(function(){
		ajax_form('choose_master', '选择会员', 'index.php?w=bbs_setting&t=choose_super', 640);
	});
});

function chooseReturn(data) {
    $('#member_name').val(data.name);
    $('#member_id').val(data.id);
}
</script> 
