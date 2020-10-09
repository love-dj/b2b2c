$(function(){
    var key = getCookie('key');
    if (key) {
		delCookie('username');
		delCookie('userid');
		delCookie('key');
        window.location.href = WapSiteUrl+'/html/member/member.html';
        return;
    }
    $.getJSON(ApiUrl + '/index.php?w=connect&t=get_state', function(result){
        var ua = navigator.userAgent.toLowerCase();
        var allow_login = 0;
        if (result.datas.connect_qq == '1') {
            allow_login = 1;
            $('.qq').parent().show();
        }
        if (result.datas.connect_sn == '1') {
            allow_login = 1;
            $('.weibo').parent().show();
        }
        if ((ua.indexOf('micromessenger') > -1) && result.datas.connect_wap_wx == '1') {
            allow_login = 1;
			$('.joint-login ul li').css("width","33.3%");
            $('.wx').parent().show();
        }
        if (allow_login) {
            $('.joint-login').show();
        }
    });
	var referurl = document.referrer;//上级网址
	//shopwt v5.2
	if (!referurl) {  
        try {  
            if (window.opener) {
                referrer = window.opener.location.href;  
            }  
        }  
        catch (e) {}  
    }  
	$.sValid.init({
        rules:{
            username:"required",
            userpwd:"required"
        },
        messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }  
    });
    var allow_submit = true;
	$('#loginbtn').click(function(){//会员登陆
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        if (allow_submit) {
            allow_submit = false;
        } else {
            return false;
        }
		var username = $('#username').val();
		var pwd = $('#userpwd').val();
		var client = 'wap';
		if($.sValid()){
	          $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?w=login",	
				data:{username:username,password:pwd,client:client},
				dataType:'json',
				success:function(result){
				    allow_submit = true;
					if(!result.datas.error){
						if(typeof(result.datas.key)=='undefined'){
							return false;
						}else{
						    var expireHours = 0;
						    if ($('#checkbox').prop('checked')) {
						        expireHours = 188;
						    }
						    // 更新cookie购物车
						    updateCookieCart(result.datas.key);
							addCookie('username',result.datas.username, expireHours);
							addCookie('key',result.datas.key, expireHours);
							if(result.datas.sell) {
								if(result.datas.sell.seller_name&&result.datas.sell.key){
									addCookie('seller_name',result.datas.sell.seller_name, expireHours);
									addCookie('store_name',result.datas.sell.store_name, expireHours);
									addCookie('seller_key',result.datas.sell.key, expireHours);
								}
							}
							location.href = referurl;
						}
		                errorTipsHide();
					}else{
		                errorTipsShow('<p>' + result.datas.error + '</p>');
					}
				}
			 });  
        }
	});
	
	$('.weibo').click(function(){
	    location.href = ApiUrl+'/index.php?w=connect&t=get_sina_oauth2';
	})
    $('.qq').click(function(){
        location.href = ApiUrl+'/index.php?w=connect&t=get_qq_oauth2';
    })
    $('.wx').click(function(){
        location.href = ApiUrl+'/index.php?w=connect&t=get_wxmini';
    })
});