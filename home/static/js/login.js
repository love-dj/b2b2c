$(function() {
    var login = {
        phoneRule: /^1[34578]\d{9}$/,
        isPhone: 0,
        isCode: 0,
        isAccount: 0,
        isPassword: 0,
        codeBtnStatus: 1,
        imgCodeStatus: 0,
        init: function() {
            var _this = this;
            $('.login-main input').focus(function() {
                $(this).nextAll('.warning-text').removeClass('show');
            }).blur(function() {
                var id = $(this).attr('id');
                switch (id) {
                case 'phone':
                    _this.getErrorInfo("#phone", _this.checkPhone());
                    break;
                case 'user_name':
                    _this.getErrorInfo("#user_name", _this.checkAccount());
                    break;
                case 'image_captcha':
                    _this.getErrorInfo("#image_captcha", _this.checkImgCaptcha());
                    break;
                case 'password':
                    _this.getErrorInfo("#password", _this.checkPasswd());
                    break
                }
            });
/*            $("#getcaptcha").keydown(function(e) {
                if (e.which == '13') {
                    $("#getcaptcha").blur();
                    $("#submit-phone").click()
                }
            });*/
            $("#password").keydown(function(e) {
                if (e.which == '13') {
                    $("#password").blur();
                    $("#submit-passwd").click()
                }
            });
            $(document).on("click", "#submit-phone", function() {
                if (!_this.getErrorInfo("#phone", _this.checkPhone())) return false;
                if (!_this.getErrorInfo("#image_captcha", _this.checkImgCaptcha())) return false;
				if (!_this.getErrorInfo("#sms_captcha", _this.checkCaptcha())) return false;
            });
			$(document).on("click", "#sms_text", function() {
				if (!_this.getErrorInfo("#phone", _this.checkPhone())) return false;
                if (!_this.getErrorInfo("#image_captcha", _this.checkImgCaptcha())) return false;
                if (!_this.getErrorInfo("#sms_captcha", _this.captchaChange())) return false;
            });
        },
        loginByAccount: function() {
            var _this = this;
            $("#submit-passwd").click(function() {
                if (!_this.getErrorInfo("#user_name", _this.checkAccount())) {
                    return false
                }
                if (!_this.getErrorInfo("#password", _this.checkPasswd())) {
                    return false
                }
            })
        },
        checkLogin: function() {
            if (this.isPhone && this.isCode) {
                return 1
            }
            if (this.isAccount && this.isPasswd) {
                return 2
            }
            return false
        },
        checkAccount: function() {
            var user_name = $("#user_name").val();
            login.isAccount = 0;
            if (user_name == '') {
                $("#submit-passwd").addClass('disabled');
                return '帐号不能为空!'
            }
            login.isAccount = 1;
            if (login.checkLogin() == 2) {
                $("#submit-passwd").removeClass('disabled')
            }
            return false
        },
        checkPasswd: function() {
            var password = $("#password").val();
            login.isPasswd = 0;
            if (password == '') {
                $("#submit-passwd").addClass('disabled');
                return '密码不能为空!'
            }
            login.isPasswd = 1;
            if (login.checkLogin() == 2) {
                $("#submit-passwd").removeClass('disabled')
            }
            return false
        },
        checkPhone: function() {
            var phone = $('#phone').val();
            login.isPhone = 0;
            if (phone == '') {
                $("#submit-phone").addClass('disabled');
                return '手机号不能为空!'
            }
            if (!this.phoneRule.test(phone)) {
                $("#submit-phone").addClass('disabled');
                return '手机号格式错误!'
            }
            login.isPhone = 1;
            if (login.checkLogin() == 1) {
                $("#submit-phone").removeClass('disabled')
            }
            return false
        },
        checkImgCaptcha: function() {
            if (login.imgCodeStatus == 1) return false;
            var imgCaptcha = $('#image_captcha').val();
            var captchaRull = /^[0-9a-zA-Z]{4}$/;
            if (imgCaptcha == '') {
                return '验证码不能为空!'
            } else if (!captchaRull.test(imgCaptcha)) {
                return '请输入右边的4位验证码!'
            } else {
                return false
            }
			
        },
        checkCaptcha: function() {
            var captcha = $('#sms_captcha').val();
            var captchaRull = /^\d{6}$/;
            if (captcha == '') {
                login.isCode = 0;
                $("#submit-phone").addClass('disabled');
                return '短信验证码不能为空!'
            } else if (!captchaRull.test(captcha)) {
                login.isCode = 0;
                $("#submit-phone").addClass('disabled');
                return '请输入收到的6位短信验证码!'
            } else {
                login.isCode = 1;
                if (login.checkLogin() == 1) {
                    $("#submit-phone").removeClass('disabled')
                }
                return false
            }
        },
		
       getErrorInfo: function(obj, errorInfo) {
            if (errorInfo) {
                this.getErrorInfo('.login-main input', false);
                var errorInfo = "<span><i class='danger-icon'></i>  " + errorInfo + "</span>";
                $(obj).addClass('danger').nextAll('.warning-text').html(errorInfo).addClass('show');
                return false
            } else {
                $(obj).removeClass('danger').nextAll('.warning-text').removeClass('show');
                return true
            }
        } ,
		
        time: 60,
        captchaChange: function() {
            var obj = $(".btn-captcha a");
            if (login.time == 60) {
                obj.parent().addClass('disabled')
            }
            if (login.time == 0) {
                login.time = 60;
                obj.html('重新发送');
                obj.parent().removeClass('disabled')
            } else {
                obj.html(login.time + '秒后重新发送');
                login.time--;
                setTimeout(function() {
                    login.captchaChange()
                }, 1000)
            }
        },
		
    };
    login.init();
});