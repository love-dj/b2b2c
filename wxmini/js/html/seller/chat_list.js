$(function() {
    var e = getCookie("seller_key");
    if (!e) {
        window.location.href = WapSiteUrl + "/html/seller/seller.html";
    }
    template.helper("isEmpty",
    function(e) {
        for (var t in e) {
            return false
        }
        return true
    });
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?w=seller_chat&t=get_user_list",
        data: {
            key: e,
            recent: 1
        },
        dataType: "json",
        success: function(t) {
            // checkLogin(t.login);
            var a = t.datas;
            $("#messageList").html(template.render("messageListScript", a));
            $(".msg-list-del").click(function() {
                var t = $(this).attr("t_id");
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?w=seller_chat&t=del_msg",
                    data: {
                        key: e,
                        t_id: t
                    },
                    dataType: "json",
                    success: function(e) {
                        if (e.code == 200) {
                            location.reload()
                        } else {
                            $.sDialog({
                                skin: "red",
                                content: e.datas.error,
                                okBtn: false,
                                cancelBtn: false
                            });
                            return false
                        }
                    }
                })
            })
        }
    })
});