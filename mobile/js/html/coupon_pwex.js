$(function() {
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl+'/html/member/login.html';
        return;
    }

    //加载验证码
    loadVercode();
    $("#refreshcode").bind('click',function(){
        loadVercode();
    });
    
    $.sValid.init({
        rules:{
            pwd_code:"required",
            captcha:"required"
        },
        messages:{
            pwd_code:"请填写优惠券卡密",
            captcha:"请填写验证码"
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

    $('#saveform').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }

        if($.sValid()){
            var pwd_code = $.trim($("#pwd_code").val());
            var captcha = $.trim($("#captcha").val());
            var codekey = $.trim($("#codekey").val());
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?w=member_coupon&t=rp_pwex",
                data:{key:key,pwd_code:pwd_code,captcha:captcha,codekey:codekey},
                dataType:'json',
                success:function(result){
                    if(result.code == 200){
                        location.href = WapSiteUrl+'/html/member/coupon_list.html';
                    }else{
                        loadVercode();
                        errorTipsShow('<p>' + result.datas.error + '</p>');
                    }
                }
            });
        }
    });
});
