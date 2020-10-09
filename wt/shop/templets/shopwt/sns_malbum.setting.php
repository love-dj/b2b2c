<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_member_album_manage'];?></h3>
        <h5><?php echo $lang['wt_member_album_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="index.php?w=sns_malbum&t=class_list"><?php echo $lang['snsalbum_class_list'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['snsalbum_album_setting'];?></a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="form_setting" id="form_setting">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['snsalbum_allow_upload_max_count'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['list_setting']['malbum_max_sum'];?>" name="malbum_max_sum" id="malbum_max_sum">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['snsalbum_allow_upload_max_count_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#submitBtn").click(function(){
		if($("#form_setting").valid()){
			$("#form_setting").submit();
		}
	});
	
	$('#form_setting').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	malbum_max_sum : {
            	required : true,
            	digits   : true
            }
        },
        messages : {
        	malbum_max_sum  : {
            	required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['snsalbum_pls_input_figures'];?>',
            	digits   : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['snsalbum_pls_input_figures'];?>'
            }
        }
    });
});
</script>