//shopwt
$(function(){
	var ac_id = getQueryString('ac_id')
	var keyword = decodeURIComponent(getQueryString("keyword"));

    if (keyword != "") {

        $("#artkeyword").val(keyword);

    }
	if (ac_id=='' && keyword == "") {
    	window.location.href = WapSiteUrl + '/index.html';
    	return;
	}
	else {
		//类型列表
		$.ajax({
			url:ApiUrl+"/index.php?w=article_class&t=index",
			type:'get',
			data:{ac_id:ac_id},
			jsonp:'callback',
			dataType:'jsonp',
			success:function(result){
				gc_id = $(this).attr('date-id');
				var data = result.datas;
				data.WapSiteUrl = WapSiteUrl;
               data.default_id =ac_id;
				var html = template.render('article-class', data);				
				$("#nav-show-class").html(html);
                var ele = $("#nav-show-class");
                ele.width((ele.find("li").length + 1) * (ele.find("li").width()+20));
                myScroll = new IScroll('.nav-content', { mouseWheel: true, scrollX:true, click: true });
			}
		});
      	//点击分类
        $('#nav-show-class').on('click','.gc_active', function(){
            ac_id = $(this).attr('date-id');
            //类型子类列表
            $.ajax({
                url:ApiUrl+"/index.php?w=article&t=article_list",
                type:'get',
                data:{ac_id:ac_id,keyword:keyword},
                jsonp:'callback',
                dataType:'jsonp',
                success:function(result){
                    var data = result.datas;
                    $("#art_name ,#art_title").html(data.article_type_name);
                    data.WapSiteUrl = WapSiteUrl;
                    var html = template.render('article-list', data);				
                    $("#article-content").html(html);
                }
            });
            $(this).parent().addClass('active').siblings().removeClass("active");
            myScroll.scrollToElement(document.querySelector('.active'), 1000, true, true);
        });
		//类型子类列表
		$.ajax({
			url:ApiUrl+"/index.php?w=article&t=article_list",
			type:'get',
			data:{ac_id:ac_id,keyword:keyword},
			jsonp:'callback',
			dataType:'jsonp',
			success:function(result){
				var data = result.datas;
				$("#art_name ,#art_title").html(data.article_type_name);
				data.WapSiteUrl = WapSiteUrl;
               
				var html = template.render('article-list', data);				
				$("#article-content").html(html);
			}
		});
	}	
	$('#serach_article').click(function(){

		var keyword = encodeURIComponent($('#artkeyword').val());

        location.href = WapSiteUrl+'/html/article_list.html?keyword='+keyword;

    });
	
});