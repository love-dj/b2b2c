// by sh opwt v 5 .3
$(function() {

	var e = getCookie("key");

	if (!e) {
		window.location.href = WapSiteUrl + "/html/member/login.html";
		return 
	}
	var pay_sn=getQueryString('pay_sn');
		$.ajax({
			type:'post',
            url:ApiUrl+"/index.php?w=recharge&t=recharge_order",
			data:{key:e,pay_sn:pay_sn},
			dataType:'json',
			success:function(result){
				if(!result.datas.error){
					var data = result.datas;					
					data.ApiUrl = ApiUrl;
					data.key = e;
					
					template.helper('p2f', function(s) {
						return (parseFloat(s) || 0).toFixed(2);
					});
					var html = template.render('rechargeinfo-tmpl', data);
					$("#rechargeinfo").html(html);	
				}
				else{
					alert(result.datas.error);
					location.href="member.html?w=member";
				}				
			}
		});
	
	$('#rechargeinfo').on('click','.check-payment',function() {
        var pay_sn = $(this).attr('data-paySn');
        toPay(pay_sn,'recharge','recharge_order');
        return false;
    });
       
	


});