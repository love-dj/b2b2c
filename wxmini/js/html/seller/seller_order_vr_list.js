var page = pagesize; 
var curpage = 1; 
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = '';
    
$(function(){
    var key = getCookie('seller_key');
    if(!key){
        window.location.href = WapSiteUrl+'/html/seller/login.html';
    }

    if (getQueryString('data-state') != '') {
        $('#filtrate_ul').find('li').has('a[data-state="' + getQueryString('data-state')  + '"]').addClass('selected').siblings().removeClass("selected");
    }

    $('#search_btn').click(function(){
        reset = true;
        initPage();
    });

    $('#fixed_nav').waypoint(function() {
        $('#fixed_nav').toggleClass('fixed');
    }, {
        offset: '50'
    });

    function initPage(){
        if (reset) {
            curpage = 1;
            hasMore = true;
        }
        $('.loading').remove();
        if (!hasMore) {
            return false;
        }
        hasMore = false;
        var state_type = $('#filtrate_ul').find('.selected').find('a').attr('data-state');
        var orderKey = $('#order_key').val();
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?w=seller_order&t=order_vr_list&page="+page+"&curpage="+curpage,
            data:{key:key, state_type:state_type, order_key : orderKey},
            dataType:'json',
            success:function(result){
                //检测是否登录了
                checkSellerLogin(result.login);
                curpage++;
                hasMore = result.hasmore;
                if (!hasMore) {
                    get_footer();
                }
                if (result.datas.order_list.length <= 0) {
                    $('#footer').addClass('posa');
                } else {
                    $('#footer').removeClass('posa');
                }
                var data = result;
                //页面地址
                data.WapSiteUrl = WapSiteUrl;
                data.ApiUrl = ApiUrl;
                data.key = getCookie('seller_key');
                template.helper('$getLocalTime', function (nS) {
                    var d = new Date(parseInt(nS) * 1000);
                    var s = '';
                    s += d.getFullYear() + '年';
                    s += (d.getMonth() + 1) + '月';
                    s += d.getDate() + '日 ';
                    s += d.getHours() + ':';
                    s += d.getMinutes();
                    return s;
                });
                template.helper('p2f', function(s) {
                    return (parseFloat(s) || 0).toFixed(2);
                });
                template.helper('parseInt', function(s) {
                    return parseInt(s);
                });
                var html = template.render('order-list-tmpl', data);
                if (reset) {
                    reset = false;
                    $("#order-list").html(html);
                } else {
                    $("#order-list").append(html);
                }
            }
        });

    }
    

    // 取消
    $('#order-list').on('click','.cancel-order', cancelOrder);

    //取消订单
    function cancelOrder(){
		var e = $(this).attr("order_id");
        var os = $(this).attr("order_sn");
        $.sDialog({
            content: "<div style='text-align:left;'><h6>订单号："+os+"</h6><h6>取消原因：</h6><h6><input type='radio'  name='cancelreason' value='无法备齐货物'>无法备齐货物</h6><h6><input type='radio' name='cancelreason' value='不是有效的订单'>不是有效的订单</h6><h6><input type='radio' name='cancelreason' value='买家主动要求'>买家主动要求</h6><h6><input type='radio' name='cancelreason' checked value='其他原因'>其他原因</h6></div>",
            okFn: function() {
				var rt=$("input[name='cancelreason']:checked").val();
				 cancelOrderId(e,rt);
            }
        });
    }
	
	function cancelOrderId(r,rt) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?w=seller_order&t=order_vr_cancel",
            data: {
                order_id: r,
				reason:rt,
                key: key
            },
            dataType: "json",
            success: function(key) {
                if (key.datas && key.datas == 1) {
					$.sDialog({
                        skin: "red",
                        content: "订单取消成功",
                        okBtn: true,
						okFn:function(){
						 reset = true;
						 initPage();
						},
                        cancelBtn: false
                    })

                   
                } else {
                    $.sDialog({
                        skin: "red",
                        content: key.datas.error,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            }
        })
    }
    
    $('#filtrate_ul').find('a').click(function(){
        $('#filtrate_ul').find('li').removeClass('selected');
        $(this).parent().addClass('selected').siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0,0);
        initPage();
    });

    //初始化页面
    initPage();
    $(window).scroll(function(){
        if(($(window).scrollTop() + $(window).height() > $(document).height()-1)){
            initPage();
        }
    });
});

function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({
            url: WapSiteUrl + "/js/html/seller/seller_footer.js",
            dataType: "script"
        })
    }
}