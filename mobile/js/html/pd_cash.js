$(function(){
    var key = getCookie('key');
	var predepoit_=0;
	//获取预存款余额
	$.getJSON(ApiUrl + '/index.php?w=member_index&t=my_asset', {'key':key,'fields':'predepoit'}, function(result){
		
		$("#allmoney").html(result.datas.predepoit);
		predepoit_=result.datas.predepoit;
	});	
	
	var referurl = document.referrer;//上级网址
	$("input[name=referurl]").val(referurl);
	$.sValid.init({
        rules:{
            pdc_amount:"required",
			pdc_bank_name:"required",
			pdc_bank_no:"required",
			pdc_bank_user:"required",
			mobilenum:"required",
			password:"required"
        },
        messages:{
            pdc_amount:"请输入提现金额！",
			pdc_bank_name:"请输入收款银行！",
			pdc_bank_no:"请输入收款账号！",
			pdc_bank_user:"请输入开户人姓名！",
			mobilenum:"请输入手机号码！",
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
		var pdc_amount = $('#pdc_amount').val();
		var pdc_bank_name = $('#pdc_bank_name').val();
		var pdc_bank_no = $('#pdc_bank_no').val();
		var pdc_bank_user = $('#pdc_bank_user').val();
		var mobilenum = $('#mobilenum').val();
		var password = $('#password').val();
		var key = getCookie('key');
		if(key==''){
			location.href = 'login.html';
		}
		if(parseFloat(predepoit_)<parseFloat(pdc_amount))
		{
			alert('提现金额不能大于预存款余额！');
			return false;
		}
		var client = 'wap';
		if($.sValid()){
	          $.ajax({
				type:'post',
				url:ApiUrl+"/index.php?w=recharge&t=pd_cash_add",	
				data:{pdc_amount:pdc_amount,pdc_bank_name:pdc_bank_name,pdc_bank_no:pdc_bank_no,pdc_bank_user:pdc_bank_user,mobilenum:mobilenum,password:password,key:key,client:client},
				dataType:'json',
				success:function(result){
					if(!result.datas.error){
						$(".error-tips").hide();
						location.href = 'pdcashlist.html';
						
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