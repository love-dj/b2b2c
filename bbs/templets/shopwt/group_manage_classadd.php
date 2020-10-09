<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="eject_con">
  <div id="ms-warning"></div>
  <form id="addclass_form" action="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_add&c_id=<?php echo $output['c_id'];?>" method="post" class="base-form-style">
    <input type="hidden" value="ok" name="form_submit">
    <dl>
      <dt><?php echo $lang['bbs_tclass_name'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="text" name="name" class="w200 text"  />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['bbs_tclass_sort'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="text" name="sort" class="w50 text" value="255" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['bbs_tclass_status'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="radio" name="status" value="1" checked="checked" />
        <?php echo $lang['wt_yes'];?>&nbsp;
        <input type="radio" name="status" value="0" />
        <?php echo $lang['wt_no'];?> </dd>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd><a class="submit-btn" wttype="submit-btn" href="Javascript: void(0)"><?php echo $lang['wt_submit'];?></a></dd>
    </dl>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('a[wttype="submit-btn"]').click(function(){
		$('#addclass_form').submit();
	});
	
    $('#addclass_form').validate({
        errorLabelContainer: $('#ms-warning'),
        invalidHandler: function(form, validator) {
               $('#ms-warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('addclass_form', '<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=class_add&c_id=<?php echo $output['c_id'];?>', '', 'onerror');
    	},
        rules : {
        	name : {
                required : true,
            	maxlength : 4
            },
            sort : {
                required : true,
                digits : true,
                max : 255
            }
        },
        messages : {
        	name : {
                required : '<?php echo $lang['bbs_tclass_name_not_null'];?>',
            	maxlength : '<?php echo $lang['bbs_tclass_man_maxlength'];?>'

            },
            sort  : {
                required : '<?php echo $lang['bbs_tclass_sort_not_null'];?>',
                digits : '<?php echo $lang['bbs_tclass_sort_is_digits'];?>',
                max : '<?php echo $lang['bbs_tclass_sort_max'];?>'
            }
        }
    });
});
</script> 