<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="eject_con">
  <div id="ms-warning"></div>
  <form id="fsadd_form" action="<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=friendship_add&c_id=<?php echo $output['c_id'];?>" method="post" class="base-form-style">
    <input type="hidden" value="ok" name="form_submit">
    <dl>
      <dt><?php echo $lang['bbs_name'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="text" name="name" class="w200 text" />
        <a href="javascript:void(0);" wttype="fsadd_search"><?php echo $lang['wt_search'];?></a> </dd>
      <dd>
        <select name="cid" wttype="fsadd_select" class="w200" style="height: 100px" size='7'>
          <option value='0'><?php echo $lang['wt_common_pselect'];?></option>
        </select>
        <input type="hidden" name="cname" id="cname" value="" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['fbbs_sort'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="text" name="sort" class="w50 text" value="255" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['bbs_tclass_sort'].$lang['wt_colon'];?></dt>
      <dd>
        <input type="radio" name="status" value="1" checked="checked" />
        <?php echo $lang['wt_show'];?>&nbsp;
        <input type="radio" name="status" value="0" />
        <?php echo $lang['wt_hide'];?> </dd>
    </dl>
    <dl class="bottom">
      <dt>&nbsp;</dt>
      <dd><a class="submit-btn" wttype="submit-btn" href="Javascript: void(0)"><?php echo $lang['wt_submit'];?></a></dd>
    </dl>
  </form>
</div>
<script type="text/javascript">
var c_id = <?php echo $output['c_id'];?>;
$(function(){
	$('a[wttype="submit-btn"]').click(function(){
		$('#fsadd_form').submit();
	});

    $('#fsadd_form').validate({
        errorLabelContainer: $('#ms-warning'),
        invalidHandler: function(form, validator) {
               $('#ms-warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('fsadd_form', '<?php echo BBS_SITE_URL;?>/index.php?w=manage&t=friendship_add&c_id='+c_id, '', 'onerror');
    	},
        rules : {
        	cid : {
            	min : 1
        	},
            sort : {
                required : true,
                digits : true,
                max : 255
            }
        },
        messages : {
        	cid : {
            	min : '<?php echo $lang['fbbs_please_choose'];?>'
        	},
            sort  : {
                required : '<?php echo $lang['fbbs_sort_not_null'];?>',
                digits : '<?php echo $lang['bbs_tclass_sort_is_digits'];?>',
                max : '<?php echo $lang['bbs_tclass_sort_max'];?>'
            }
        }
    });

	$('a[wttype="fsadd_search"]').click(function(){
		var name = $('input[name="name"]').val();
		$.getJSON(BBS_SITE_URL+'/index.php?w=manage&t=search_bbs&c_id='+c_id+'&name='+name, function(data){
			if(data){
				var select = $('select[wttype="fsadd_select"]');
				select.html('<option value=\'0\'><?php echo $lang['wt_common_pselect'];?></option>');
				$.each(data, function(e, d){
					$('<option value="'+d.bbs_id+'">'+d.bbs_name+'</option>').appendTo(select);
				});
				select.parent('dd').show();
			}
		});
    });

	$('select[wttype="fsadd_select"]').change(function(){
		var val = parseInt($(this).val());
		if(val == 0){
			$('#cname').val('');
		}else{
			var html = $(this).find('option:selected').html();
			$('#cname').val(html);
		}
	});
});
</script> 