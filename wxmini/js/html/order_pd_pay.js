var key = getCookie('key');
var payment_code;
 // 现在支付方式
 function toPay(pay_sn,w,t) {
     $.ajax({
         type:'post',
         url:ApiUrl+'/index.php?w='+w+'&t='+t,
         data:{
             key:key,
             pay_sn:pay_sn
             },
         dataType:'json',
         success: function(result){
             checkLogin(result.login);
             if (result.datas.error) {
				  $.sDialog({
                     skin:"red",
                     content:result.datas.error,
                     okBtn:false,
                     cancelBtn:false
                 });
                 return false;
             }
             // 从下到上动态显示隐藏内容
             $.animationUp({valve:'',scroll:''});
             
             // 需要支付金额
             $('#onlineTotal').html(result.datas.pdinfo.pdr_amount);
             payment_code = '';
             if (!$.isEmptyObject(result.datas.payment_list)) {
                 var readytoWXPay = false;
                 var readytoAliPay = false;
				 var readytoWXH5Pay = false;
                 var m = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
                 if (parseInt(m && m[1] || 0) >= 5) {
                     // 微信内浏览器
                     readytoWXPay = true;
                 } else {
                     readytoAliPay = true;
					 readytoWXH5Pay = true;
                 }
                 for (var i=0; i<result.datas.payment_list.length; i++) {
                     var _payment_code = result.datas.payment_list[i].payment_code;
                     if (_payment_code == 'alipay' && readytoAliPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                     if (_payment_code == 'wxpay_jsapi' && readytoWXPay) {
                         $('#'+ _payment_code).parents('label').show();
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                     if (_payment_code == 'wxpay_h5' && readytoWXH5Pay) {
                         $('#wxpay_h5').parents('label').show(); 
                         if (payment_code == '') {
                             payment_code = _payment_code;
                             $('#'+_payment_code).attr('checked', true).parents('label').addClass('checked');
                         }
                     }
                 }
             }

             $('#alipay').click(function(){
                 payment_code = 'alipay';
             });
             
             $('#wxpay_jsapi').click(function(){
                 payment_code = 'wxpay_jsapi';
             });
             $('#wxpay_h5').click(function(){
                 payment_code = 'wxpay_h5';
             });
             $('#toPay').click(function(){
                 if (payment_code == '') {
				  $.sDialog({
                     skin:"red",
                     content:"请选择支付方式",
                     okBtn:false,
                     cancelBtn:false
                 });
                     return false;
                 }
                goToPayment(pay_sn);
                 
             });
         }
     });
 }

 function goToPayment(pay_sn) {
     location.href = ApiUrl+'/index.php?w=member_payment_recharge&t=pd_pay&key=' + key + '&pay_sn=' + pay_sn + '&pdr=1&payment_code=' + payment_code;
 }
