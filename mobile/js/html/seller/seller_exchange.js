$(function() {
    //判断是否有商家用户名保存到cookie  
    var e = getCookie("seller_key");
    if (!e) {
        window.location.href = WapSiteUrl + "/html/seller/login.html";
	 return
    }


    $("#submitbtn").click(function() {
        var vr_code = $("#vr_code").val();

        $.ajax({
            url: ApiUrl + "/index.php?w=seller_order_vr&t=exchange",
            data: {
                vr_code:vr_code
            },
            type: "get",
            jsonp:'callback',
            dataType:'json',
            success: function(e) {
                if (e.datas.error) {
                    errorTipsShow("<p>" + e.datas.error + "</p>")
                }else{
                    var data = e.datas;
                    var html = template.render('order_vr', data);
                    $("#order_info").html(html);
					$(".wtm-inp-con").hide();
                    $(".vr_code").html('兑换码：'+vr_code);
                    $(".goods_content").html('商品信息：'+data.goods_name);
                    $(".goods_sn").html('订单号：'+data.order_sn);
					$(".buyer_name").html('会员名：'+data.buyer_name);
                    $(".liuyan").html('留言：'+data.buyer_msg);
					$(".order-state").html('状态：已消费');
					errorTipsShow("<p>验证成功</p>");
                }
            }
        });
    });
$("#loginbtn").click(function() {
	$(".wtm-inp-con").show();
});
});