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
            rc_sn:"required",
            captcha:"required"
        },
        messages:{
            rc_sn:"请输入平台充值卡号",
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
            var rc_sn = $.trim($("#rc_sn").val());
            var captcha = $.trim($("#captcha").val());
            var codekey = $.trim($("#codekey").val());
            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?w=member_fund&t=rechargecard_add",
                data:{key:key,rc_sn:rc_sn,captcha:captcha,codekey:codekey},
                dataType:'json',
                success:function(result){
                    if(result.code == 200){
                        location.href = WapSiteUrl+'/html/member/rechargecardlog_list.html';
                    }else{
                        loadVercode();
                        errorTipsShow('<p>' + result.datas.error + '</p>');
                    }
                }
            });
        }
    });
});
