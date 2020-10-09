$(function(){
    var key = getCookie('key');
    if (key === null) {
        window.location.href = WapSiteUrl + '/ member/login.html';
        return;
    }
    var post_data = 0;
    $('#feedbackbtn').click(function(){
		if (post_data) {
		    errorTipsShow("<p>正在处理中，请勿重复点击！</p>");
            return false;
        }
        var feedback = $('#feedback').val();
        if (feedback == '') {
            $.sDialog({
                skin:"red",
                content:'请填写反馈内容',
                okBtn:false,
                cancelBtn:false
            });
            return false;
        }
        post_data = 1;
        $.ajax({
            url:ApiUrl+"/index.php?w=member_feedback&t=feedback_add",
            type:"post",
            dataType:"json",
            data:{key:key, feedback:feedback},
            success:function (result){
                if(checkLogin(result.login)){
                    if(!result.datas.error){
                        errorTipsShow('<p>提交成功</p>');

                        setTimeout(function(){
                            window.location.href = WapSiteUrl + '/html/member/member.html';
                        }, 3000);
                    }else{
                        errorTipsShow('<p>' + result.datas.error + '</p>');
                    }
                }
            }
        });
    });
});