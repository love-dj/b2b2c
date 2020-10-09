$(function() {
    //判断是否有商家用户名保存到cookie  
    var e = getCookie("seller_key");
    if (!e) {
        location.href = "login.html"
    }
    var r = getQueryString("order_id");
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?w=seller_order&t=search_deliver",
        data: {
            key: e,
            order_id: r
        },
        dataType: "json",
        success: function(e) {
            // checkLogin(e.login);
            var r = e && e.datas;
            if (!r) {
                r = {};
                r.err = "暂无物流信息"
            }
            var t = template.render("order-delivery-tmpl", r);
            $("#order-delivery").html(t)
        }
    })
});