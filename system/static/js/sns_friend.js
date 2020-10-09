$(function(){
	//加关注
	$("[wt_type='followbtn']").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        $.getJSON(MEMBER_SITE_URL + '/index.php?w=member_snsfriend&t=addfollow&mid='+data_str.mid, function(data){
        	if(data){
        		var obj = $('#recordone_'+data_str.mid);
        		obj.find('[wt_type="signmodule"]').children().hide();
        		if(data.state == 2){
        			obj.find('[wt_type=\"mutualsign\"]').show();
        		}else{
        			obj.find('[wt_type=\"followsign\"]').show();
        		}
    			showSucc('关注成功');
        	}else{
        		showError('关注失败');
        	}
        });
        return false;
	});
	//取消关注
	$("[wt_type='cancelbtn']").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        $.getJSON(MEMBER_SITE_URL + '/index.php?w=member_snsfriend&t=delfollow&mid='+data_str.mid, function(data){
        	if(data){
        		$('#recordone_'+data_str.mid).hide();
        		showSucc('取消成功');
        	}else{
        		showError('取消失败');
        	}
        });
        return false;
	});
	// 批量关注
	$('*[wttype="batchFollow"]').live('click', function(){
		eval("data_str = "+$(this).attr('data-param'));
		ajax_get_confirm('',MEMBER_SITE_URL + '/index.php?w=member_snsfriend&t=batch_addfollow&ids='+data_str.ids);
	});
});