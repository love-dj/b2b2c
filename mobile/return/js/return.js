$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
  
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?w=member_dis_return&t=index",
        data:{key:key},
        dataType:'json',
        success:function(result){
			checkLogin(result.login);
            if(result.code == 200){
				
				//验证两个消费返利模块是否开启
                var buy_return_isuse = parseInt(result.datas.buy_return_isuse);
                var full_return_isuse = parseInt(result.datas.full_return_isuse);
                if(buy_return_isuse != 1){
                    $("#buy_list").css("display","none");
                    $("#buy_log").css("display","none");
                }
				
				if(full_return_isuse != 1 ){
                    $("#full_list").css("display","none");
                    $("#full_log").css("display","none");
                }
				

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