$(function(){
    // 发送手机验证码
    var mobile = getQueryString("mobile");
    var sec_val = getQueryString("captcha");
    var sec_key = getQueryString("codekey");
    $('#usermobile').html(mobile);
    send_sms(mobile, sec_val, sec_key);
    $('#again').click(function(){
        sec_val = $('#captcha').val();
        sec_key = $('#codekey').val();
        send_sms(mobile, sec_val, sec_key);
    });
    
    $('#find_password_code').click(function(){
        if (!$(this).parent().hasClass('ok')) {
            return false;
        }
        var captcha = $('#mobilecode').val();
        if (captcha.length == 0) {
            errorTipsShow('<p>请填写短信验证码<p>');
        }
        check_sms_captcha(mobile, captcha);
        return false;
        
    });

    //加载验证码
    //loadVercode();
    $("#refreshcode").bind('click',function(){
        loadVercode();
    });
});
// 发送手机验证码
function send_sms(mobile, sec_val, sec_key) {
    $.getJSON(ApiUrl+'/index.php?w=connect&t=get_sms_captcha', {type:3,phone:mobile,sec_val:sec_val,sec_key:sec_key}, function(result){
        if(!result.datas.error){
            $.sDialog({
                skin:"green",
                content:'发送成功',
                okBtn:false,
                cancelBtn:false
            });
            $('.code-again').hide();
            $('.code-countdown').show().find('em').html(result.datas.sms_time);
            var times_Countdown = setInterval(function(){
                var em = $('.code-countdown').find('em');
                var t = parseInt(em.html() - 1);
                if (t == 0) {
                    $('.code-again').show();
                    $('.code-countdown').hide();
                    clearInterval(times_Countdown);
                } else {
                    em.html(t);
                }
            },1000);
        }else{
            //loadVercode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}

function check_sms_captcha(mobile, captcha) {
    $.getJSON(ApiUrl + '/index.php?w=connect&t=check_sms_captcha', {type:3,phone:mobile,captcha:captcha }, function(result){
        if (!result.datas.error) {
            window.location.href = 'find_password_password.html?mobile=' + mobile + '&captcha=' + captcha;
        } else {
            //loadVercode();
            errorTipsShow('<p>' + result.datas.error + '<p>');
        }
    });
}