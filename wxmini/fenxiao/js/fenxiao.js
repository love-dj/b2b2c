$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }
  
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?w=member_fx&t=index",
        data:{key:key},
        dataType:'json',
        success:function(result){
			checkLogin(result.login);
            if(result.code == 200){

                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.datas.member_info.avatar + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.datas.member_info.user_name+'<sup>' + result.datas.member_info.level_name + '</sup></span> </div>'
                    + '</div>'
                    + '<div class="member-collect"><span><a href="#"><em>' + result.datas.member_info.available_fx_trad + '</em>'
                    + '<p>可提现金额</p>'
                    + '</a> </span><span><a href="#"><em>' +result.datas.member_info.freeze_fx_trad + '</em>'
                    + '<p>冻结佣金金额</p>'
                    + '</a> </span><span><a href="bill_list.html"><i class="goods-browse"></i>'
                    + '<p>结算管理</p>'
                    + '</a> </span></div>';
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