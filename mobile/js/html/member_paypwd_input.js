$(function() {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/html/member/login.html";
        return
    }
    //loadVercode();
    $("#refreshcode").bind("click",
    function() {
        loadVercode()
    });
    $.ajax({
        type: "get",
        url: ApiUrl + "/index.php?w=member_account&t=get_paypwd_info",
        data: {
            key: e
        },
        dataType: "json",
        success: function(e) {
            if (e.code == 200) {
                if (!e.datas.state) {
                    errorTipsShow("<p>请先设置支付密码</p>");
                    setTimeout("location.href = WapSiteUrl+'/html/member/member_paypwd_step1.html'", 2e3)
                }
            }
        }
    });
    $.sValid.init({
        rules: {
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            }
			/*,
            captcha: {
                required: true,
                minlength: 4
            }*/
        },
        messages: {
            password: {
                required: "请填写支付密码",
                minlength: "请正确填写支付密码",
                maxlength: "请正确填写支付密码"
            }
			/*,
            captcha: {
                required: "请填写图形验证码",
                minlength: "图形验证码不正确"
            }*/
        },
        callback: function(e, a, t) {
            if (e.length > 0) {
                var r = "";
                $.map(a,
                function(e, a) {
                    r += "<p>" + e + "</p>"
                });
                errorTipsShow(r)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#nextform").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if ($.sValid()) {
            var a = $.trim($("#password").val());
            var t = $.trim($("#captcha").val());
            var r = $.trim($("#codekey").val());
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?w=member_account&t=check_paypwd",
                data: {
                    key: e,
                    password: a,
                    captcha: t,
                    codekey: r
                },
                dataType: "json",
                success: function(e) {
                    if (e.code == 200) {
                        location.href = WapSiteUrl + "/html/member/member_mobile_bind.html"
                    } else {
                        errorTipsShow("<p>" + e.datas.error + "</p>");
                        
                    }
                }
            })
        }
    })
});