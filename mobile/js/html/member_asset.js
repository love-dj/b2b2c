$(function(){
    var key = getCookie('key');
    if (!key) {
        window.location.href = WapSiteUrl + '/html/member/login.html';
        return;
    }
    $.getJSON(ApiUrl + '/index.php?w=member_index&t=my_asset', {key:key}, function(result){
        checkLogin(result.login);
        $('#predepoit').html(result.datas.predepoit+' 元');
        $('#rcb').html(result.datas.available_rc_balance+' 元');
        $('#voucher').html(result.datas.voucher+' 张');
        $('#coupon').html(result.datas.coupon+' 个');
        $('#point').html(result.datas.point+' 分');
    });
});