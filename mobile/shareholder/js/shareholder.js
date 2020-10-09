$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
  
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?w=member_shareholder&t=index",
        data:{key:key},
        dataType:'json',
        success:function(result){
			checkLogin(result.login);
            if(result.code == 200){
                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.datas.member_info.avatar + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.datas.member_info.member_name+'<sup style="top:5px;right:-3rem;">' + result.datas.member_info.level_name + '</sup></span> </div>'
                    + '</div>';
                //渲染页面
                
                $(".member-top").html(html);
               
                return false;
				
            }else{
				alert(result.datas.error);
				location.href = WapSiteUrl+'/html/member/member.html';
            }
        }
    });
    
});