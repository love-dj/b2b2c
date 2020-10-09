//账户充值 sh opwt v 5 .3
$(function() {

	var e = getCookie("key");

	if (!e) {

		window.location.href = WapSiteUrl + "/html/member/login.html";

		return ;

	}


	$.sValid.init({

		rules: {
			pdr_amount:"required"

		},

		messages: {
			pdr_amount:"请输入充值金额！"

		},

		callback: function(e, r, a) {

			if (e.length > 0) {

				var c = "";

				$.map(r, function(e, r) {

					c += "<p>" + e + "</p>";

				});

				errorTipsShow(c);

			} else {

				errorTipsHide();

			}

		}

	});

	$("#saveform").click(function() {

		if (!$(this).parent().hasClass("ok")) {

			return false;

		}

		if ($.sValid()) {
			var pdr_amount = $.trim($('#pdr_amount').val());
			var key = e;
			if (!key) {
				window.location.href = WapSiteUrl + "/html/member/login.html";
				return ;
			}
			var client = 'wap';
			/* var r = $("#rc_sn").val();

			var a = $.trim($("#captcha").val());

			var c = $.trim($("#codekey").val()); */

			$.ajax({

				type: "post",

				url:ApiUrl+"/index.php?w=recharge",	

				data:{pdr_amount:pdr_amount,key:key,client:client},

				dataType: "json",
				success:function(result){
					if(!result.datas.error){
						
						location.href = 'rechargeinfo.html?pay_sn='+result.datas.pay_sn;
						
					}else{
						 errorTipsShow("<p>"+result.datas.error+"<p>");
					}
				}

			});

		}

	});

});