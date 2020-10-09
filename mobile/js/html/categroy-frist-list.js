var myScroll,
    brandList = [],
    categoryChlidList = {};
$(function() {
    $("#header").on('click', '.header-inp', function(){
        location.href = WapSiteUrl + '/html/search.html';
    });
    $.getJSON(ApiUrl+"/index.php?w=goods_class&t=get_child_all_list", function(result){
		var data = result.datas;
		data.WapSiteUrl = WapSiteUrl;
		var html = template.render('category-one', data);
        for (var i in data.list) {
            var category = data.list[i];
            categoryChlidList[category.gc_id] = category.gc_list;
        }
		$("#categroy-cnt").html(html);
		myScroll = new IScroll('#categroy-cnt', { mouseWheel: true, click: true });
	});
	
	get_brand_recommend();
	
	$('#categroy-cnt').on('click','.category', function(){
	    $('.pre-loading').show();
	    $(this).parent().addClass('selected').siblings().removeClass("selected");
	    var categoryId = $(this).attr('date-id');
        var categoryList = categoryChlidList[categoryId];
        var html = template.render('category-two', {gc_list : categoryList,WapSiteUrl : WapSiteUrl});
        $("#categroy-rgt").html(html);
        $('.pre-loading').hide();
        new IScroll('#categroy-rgt', { mouseWheel: true, click: true });
        myScroll.scrollToElement(document.querySelector('.categroy-list li:nth-child(' + ($(this).parent().index()+1) + ')'), 1000);
	});

    $('#categroy-cnt').on('click','.brand', function(){
        $('.pre-loading').show();
        get_brand_recommend();
    });
});

function get_brand_recommend() {
    $('.category-item').removeClass('selected');
    $('.brand').parent().addClass('selected');
    $.getJSON(ApiUrl + '/index.php?w=brand&t=recommend_list', function(result){
        var data = result.datas;
        data.WapSiteUrl = WapSiteUrl;
        var html = template.render('brand-one', data);
        $("#categroy-rgt").html(html);
        $('.pre-loading').hide();
        new IScroll('#categroy-rgt', { mouseWheel: true, click: true });
    });
}