<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="eject_con group_apply">
  <div id="member_warning"></div>
  <form id="memberedit" action="<?php echo BBS_SITE_URL;?>/index.php?w=group&t=group_memberedit&c_id=<?php echo $output['c_id'];?>" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><?php echo $lang['bbs_self_introduction'];?></dt>
      <dd>
        <h4><i class="b"></i><?php echo $lang['bbs_introduction_desc'];?></h4>
        <h5><?php echo $lang['bbs_introduction_example'];?></h5>
        <textarea name="intro" class="textarea"><?php echo $output['member_info']['cm_intro'];?></textarea>
      </dd>
    </dl>
    <div class="bottom"> <a class="submit-btn" wttype="apply_submit" href="Javascript: void(0)"><?php echo $lang['wt_submit'];?></a><a class="cancel-btn" wttype="apply_cancel" href="Javascript: void(0)"><?php echo $lang['wt_cancel'];?></a>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('a[wttype="apply_submit"]').click(function(){
   	 if($("#memberedit").valid()){
    	    $("#memberedit").submit();
   		}
	});
	$('a[wttype="apply_cancel"]').click(function(){
		DialogManager.close('memberedit');
	});
    $('#memberedit').validate({
        errorLabelContainer: $('#member_warning'),
        invalidHandler: function(form, validator) {
               $('#member_warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('memberedit', '<?php echo BBS_SITE_URL;?>/index.php?w=group&t=group_memberedit&c_id=<?php echo $output['c_id'];?>', '', 'onerror') 
    	},
        rules : {
            intro : {
                required : true,
            	maxlength : 140
            }
        },
        messages : {
            intro  : {
                required : '<?php echo $lang['bbs_introduction_not_null'];?>',
            	maxlength : '<?php echo $lang['bbs_introduction_maxlength'];?>'
            }
        }
    });
});
</script> 