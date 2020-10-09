$(function(){
    var key = getCookie('key');
    if (key) {
        window.location.href = WapSiteUrl+'/html/member/member.html';
        return;
    }
    $.getJSON(ApiUrl + '/index.php?w=connect&t=get_state&c=connect_sms_reg', function(result){
        if (result.datas != '0') {
            $('.register-tab').show();
        }
    });
    
	$.sValid.init({//注册验证
        rules:{
        	username:"required",
            userpwd:"required",            
            password_confirm:"required",
            email:{
            	required:true,
            	email:true
            }
        },
        messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!", 
            password_confirm:"确认密码必填!",
            email:{
            	required:"邮件必填!",
            	email:"邮件格式不正确"
            }
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
	var register_member = 0;
	$('#registerbtn').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
		var username = $("input[name=username]").val();
		var pwd = $("input[name=pwd]").val();
		var password_confirm = $("input[name=password_confirm]").val();
		var email = $("input[name=email]").val();
		var client = 'wap';
		if (register_member) {
		    errorTipsShow("<p>正在处理中，请勿重复点击！</p>");
            return false;
        }
		if($.sValid()){
		    register_member = 1;
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?w=login&t=register",	
				data:{username:username,password:pwd,password_confirm:password_confirm,email:email,client:client},
				dataType:'json',
				success:function(result){
					if(!result.datas.error){
						if(typeof(result.datas.key)=='undefined'){
							return false;
						}else{
                            // 更新cookie购物车
                            updateCookieCart(result.datas.key);
							addCookie('username',result.datas.username);
							addCookie('key',result.datas.key);
							location.href = WapSiteUrl+'/html/member/member.html';
						}
		                errorTipsHide();
					}else{
		                errorTipsShow("<p>"+result.datas.error+"</p>");
		                register_member = 0;
					}
				}
			});			
		}
	});
});