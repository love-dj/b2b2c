$(function() { 
    //判断是否有商家用户名保存到cookie 
    var e = getCookie("seller_key");
    if (!e) {
        location.href = "login.html"
    }

    var vr_code = getQueryString("vr_code");
    var r = document.referrer;
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
                alert(e.datas.error);
                location.href = r
            }else{
                var data = e.datas;
                var html = template.render('order_vr', data);
                $("#order_info").html(html);
                $(".vr_code").html('兑换码：'+vr_code);
                $(".goods_content").html('商品信息：'+data.goods_name);
                $(".goods_sn").html('订单号：'+data.order_sn);
                $(".liuyan").html('留言：'+data.buyer_msg);
            }
        }
    });


});