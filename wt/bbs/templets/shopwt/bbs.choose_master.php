<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<input type="hidden" name="form_submit" value="ok" />
<div class="wtap-form-default">
  <dl class="row">
    <dt class="tit">
      <label for="searchname"><?php echo $lang['wt_member_name'];?></label>
    </dt>
    <dd class="opt">
      <input type="text" name="searchname" id="searchname" class="input-txt"/>
      <input type="submit" wttype="cm_s" value="<?php echo $lang['wt_search'];?>" class="input-btn" />
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label><?php echo $lang['wt_result'];?></label>
    </dt>
    <dd class="opt">
      <select id="searchresult" class="w350" size="7" name="searchresult">
      </select>
    </dd>
  </dl>
  <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="confirmBtn"><?php echo $lang['wt_ok'];?></a></div>
</div>
<script>
var searchUrl = '<?php echo $output['search_url'];?>';
$(function(){
	$('#searchname').focus();
	$('input[wttype="cm_s"]').click(function(){
		$.getJSON(searchUrl,{name:$('#searchname').val()},function(data){
			$('#searchresult').html('');
			if(data && data.length != 0){
				for(var i=0 ; i<data.length ; i++){
					$('#searchresult').append('<option value=\'{"id":"'+data[i].member_id+'","name":"'+data[i].member_name+'"}\'>'+data[i].member_name+'</option>');
				}
			}else{
				$('#searchresult').append('<option><?php echo $lang['bbs_choose_master_result_null'];?></option>');
				$('#confirmBtn').unbind().click(function(){DialogManager.close('choose_master');});
			}
		});
	});
	$('#confirmBtn').click(function(){
		var data = $('#searchresult').val();
		if(data == null){
			return false;
		}
	    eval( "data = "+data);
	    chooseReturn(data);
	    DialogManager.close('choose_master');
	});
});
</script>