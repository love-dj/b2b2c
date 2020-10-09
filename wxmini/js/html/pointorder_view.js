// 积分订单 shopwt
$(function() {
    var r = getCookie("key");
    if (!r) {
        window.location.href = WapSiteUrl + "/html/member/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?w=member_pointorder&t=order_info", {
        key: r,
        order_id: getQueryString("order_id")
    },
    function(t) {
        t.datas.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", t.datas));
        $(".cancel-order").click(e);
        $(".sure-order").click(o);        
        
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
            url: ApiUrl + "/index.php?w=member_pointorder&t=cancel_order",
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
        $.sDialog({
            content: "确定收到了货物吗？",
            okFn: function() {
                i(r)
            }
        })
    }
    function i(e) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=member_pointorder&t=receiving_order",
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
   
});