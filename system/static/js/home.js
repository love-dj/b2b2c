$(function(){
	//代金券兑换功能
    $("[wt_type='exchangebtn']").live('click',function(){
    	var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    ajaxget('index.php?w=pointvoucher&t=voucherexchange&dialog=1&vid='+data_str.vid);
	    return false;
    });
    //优惠券兑换功能
    $("[wt_type='rptexchangebtn']").live('click',function(){
    	var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    ajaxget('index.php?w=pointcoupon&t=rptexchange&dialog=1&tid='+data_str.tid);
	    return false;
    });
});