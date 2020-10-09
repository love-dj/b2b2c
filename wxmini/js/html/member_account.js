$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
  
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?w=member_account&t=index",
        data:{key:key},
        dataType:'json',
        success:function(result){
            if(result.code == 200){
				$('#avator_img').attr('src',result.datas.avatar);
            	if (result.datas.m_state) {
            		$('#mobile_link').attr('href','member_mobile_modify.html');
            		$('#mobile_value').html(result.datas.mobile);
            	}
				if (!result.datas.p_state) {
            		$('#paypwd_tips').html('未设置');
            	}
            }else{
            }
        }
    });
    
});