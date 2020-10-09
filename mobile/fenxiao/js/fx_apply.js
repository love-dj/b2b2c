$(function(){
    var key = getCookie('key');
	var predepoit_=0;
	//获取预存款余额
	$.getJSON(ApiUrl + '/index.php?w=member_fx&t=my_asset', {'key':key}, function(result){
		
		$("#allmoney").html(result.datas.available_fx_trad);
		$("#bankuser").html(result.datas.bill_user_name);
		$("#bill_type_number").html(result.datas.bill_type_number);
		$("#bill_bank_name").html(result.datas.bill_bank_name);
		predepoit_=result.datas.available_fx_trad;
	});	
	
	var referurl = document.referrer;//上级网址
	$("input[name=referurl]").val(referurl);
	$.sValid.init({
        rules:{
            pdc_amount:"required",
			password:"required"
        },
        messages:{
            pdc_amount:"请输入提现金额！",
			password:"请输入支付密码！",
            
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
				 $.sDialog({
							skin:"red",
							content:errorHtml,
							okBtn:false,
							cancelBtn:false
						});
                //$(".error-tips").html(errorHtml).show();
            }else{
                 $(".error-tips").html("").hide();
            }
        }  
    });
	$('#btn').click(function(){
		var tradc_amount = $('#tradc_amount').val();
		if(tradc_amount =='')
		{
			alert('请输入提现金额！');
			return false;
		}
		var pay_pwd = $('#pay_pwd').val();
		if(pay_pwd =='')
		{
			alert('请输入提现金额！');
			return false;
		}
		var key = getCookie('key');
		if(key==''){
			location.href = '../html/member/login.html';
		}
		if(parseFloat(predepoit_)<parseFloat(tradc_amount))
		{
			alert('提现金额不能大于预存款余额！');
			return false;
		}
		var client = 'wap';
		if($.sValid()){
	        $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?w=member_fx&t=cash_apply",	
				data:{tradc_amount:tradc_amount,pay_pwd:pay_pwd,key:key,client:client},
				dataType:'json',
				success:function(result){
					if(!result.datas.error){
						$(".error-tips").hide();
						location.href = 'fx_cash.html';
						
					}else{
						  $.sDialog({
							skin:"red",
							content:result.datas.error,
							okBtn:false,
							cancelBtn:false
						}); 
						//$(".error-tips").html(result.datas.error).show();
					}
				}
			 });  
        }
	});
});