$(function() {
	var r = getCookie("seller_key"); 
    if (!r) {
        window.location.href = WapSiteUrl + "/html/seller/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?w=seller_order&t=order_info", {
        key: r,
        order_id: getQueryString("order_id")
    },
    function(t) {
        t.datas.order_info.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", t.datas.order_info));
        $(".cancel-order").click(e);
        $(".sure-order").click(o);
        $(".evaluation-order").click(d);
        $(".evaluation-again-order").click(a);
        $(".all_refund_order").click(n);
        $(".goods-refund").click(c);
        $(".goods-return").click(_);
        $(".viewdelivery-order").click(l);
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=seller_order&t=get_current_deliver",
            data: {
                key: r,
                order_id: getQueryString("order_id")
            },
            dataType: "json",
            success: function(r) {
                // checkLogin(r.login);
                var e = r && r.datas;
                if (e.deliver_info) {
                    $("#delivery_content").html(e.deliver_info.context);
                    $("#delivery_time").html(e.deliver_info.time)
                }
            }
        })
    });
    function e() {
        var r = $(this).attr("order_id");
        $.sDialog({
            content: "确定取消订单？",
            okFn: function() {
                t(r)
            }
        })
    }
    function t(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=member_order&t=order_cancel",
            data: {
                order_id: e,
                key: r
            },
            dataType: "json",
            success: function(r) {
                if (r.datas && r.datas == 1) {
                    window.location.reload()
                }
            }
        })
    }
    function o() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/html/seller/order_deliver_send.html?order_id=" + r 
    }
    function i(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=member_order&t=order_receive",
            data: {
                order_id: e,
                key: r
            },
            dataType: "json",
            success: function(r) {
                if (r.datas && r.datas == 1) {
                    window.location.reload()
                }
            }
        })
    }
    function d() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/html/seller/member_evaluation.html?order_id=" + r
    }
    function a() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/html/seller/member_evaluation_again.html?order_id=" + r
    }
    function n() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/html/seller/refund_all.html?order_id=" + r
    }
    function l() {
        var r = $(this).attr("order_id");
        location.href = WapSiteUrl + "/html/seller/order_delivery.html?order_id=" + r
    }
    function c() {
        var r = $(this).attr("order_id");
        var e = $(this).attr("order_goods_id");
        location.href = WapSiteUrl + "/html/seller/refund.html?order_id=" + r + "&order_goods_id=" + e
    }
    function _() {
        var r = $(this).attr("order_id");
        var e = $(this).attr("order_goods_id");
        location.href = WapSiteUrl + "/html/seller/return.html?order_id=" + r + "&order_goods_id=" + e
    }
});