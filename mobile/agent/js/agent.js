$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
  
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?w=member_agent&t=index",
        data:{key:key},
        dataType:'json',
        success:function(result){
			checkLogin(result.login);
            if(result.code == 200){

                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.datas.member_info.avatar + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.datas.member_info.member_name+'<sup style="top: 24px;right: -6rem;">代理区域：' + result.datas.member_info.area_name + '</sup></span> </div>'
                    + '</div>'
                    + '<div class="member-collect"><span><a href="#"><em>' + result.datas.member_info.available_fx_trad + '</em>'
                    + '<p>已结算佣金</p>'
                    + '</a> </span><span><a href="#"><em>' +result.datas.member_info.freeze_fx_trad + '</em>'
                    + '<p>待结算佣金</p>'
                    + '</a> </span><span><a href="#"><em>' +result.datas.member_info.tx_cash + '</em>'
                    + '<p>已提现佣金</p>'
                    + '</a> </span></div>';
                //渲染页面
                
                $(".member-top").html(html);
               
                return false;
				
            }else{
				alert(result.datas.error);
				if(result.is_agent == 0){
					location.href = WapSiteUrl+'/html/member/apply_agent.html';
				}else{
					location.href = WapSiteUrl+'/html/member/member.html';
				}
				
            }
        }
    });
    
});