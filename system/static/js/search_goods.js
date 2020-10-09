$(function(){
    /* 筛选事件 */
    $('span[wttype="span_filter"]').click(function(){
    	_i = $(this).find('i');
    	location.assign($(this).find('i').attr('data-uri'));
        return false;
    });
    $("#search_by_price").click(function(){
        replaceParam('price', $(this).siblings("input:first").val() + '-' + $(this).siblings("input:last").val());
        return false;
    });

	// 筛选的下拉展开
	$(".select").hover(function(){
		$(this).addClass("over").next().css("display","block");
	},function(){
		$(this).removeClass("over").next().css("display","none");
	});
	$(".option").hover(function(){
		$(this).css("display","block");
	},function(){
		$(this).css("display","none");
	});
	$('.list_pic').find('dl').live('mouseout',function(){
		$(this).find('.slide-show').hide();
	});
	/*  */
	$('.slide_tiny').live('mouseover',function(){
		small_image = $(this).attr('wttype');
		$(this).parents('.slide-show').find('img:first').attr('src',small_image);
	});

    //复选框筛选
    $("[wt_type='more-filter']").mouseover(function(){
        $("[wt_type='more-filter']").addClass('box-hover');
    });
    $("[wt_type='more-filter']").mouseout(function(){
        $("[wt_type='more-filter']").removeClass('box-hover');
    });

    //鼠标经过弹出图片信息
    $(".item").hover(
        function() {
            $(this).find(".goods-content").animate({"top": "180px"}, 400, "swing");
        },function() {
            $(this).find(".goods-content").stop(true,false).animate({"top": "230px"}, 400, "swing");
        }
    );
    // 加入购物车
    $(window).resize(function() {
        $('a[wttype="add_cart"]').click(function() {
            flyToCart($(this));
        });
    });
    $('a[wttype="add_cart"]').click(function() {
        flyToCart($(this));
    });
     function flyToCart($this) {
        var rtoolbar_offset_left = $("#rtoolbar_cart").offset().left;
        var rtoolbar_offset_top = $("#rtoolbar_cart").offset().top-$(document).scrollTop();
        var img = $this.parents('li:first').find('img:first').attr('src');
        var data_gid = $this.attr('data-gid');
        var flyer = $('<img class="u-flyer" src="'+img+'" style="z-index:999">');
        flyer.fly({
            start: {
                left: $this.offset().left,
                top: $this.offset().top-$(document).scrollTop()-450
            },
            end: {
                left: rtoolbar_offset_left,
                top: rtoolbar_offset_top,
                width: 0,
                height: 0
            },
            onEnd: function(){
                addcart(data_gid,1,'');
                flyer.remove();
            }
        });
     }
    // 立即购买
    $('a[wttype="buy_now"]').click(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        $("#goods_id").val(data_str.goods_id+'|1');
        $("#buynow_form").submit();
    });
    // 图片切换效果
    $('.goods-small-pic').find('a').mouseover(function(){
        $(this).parents('li:first').addClass('selected').siblings().removeClass('selected');
        var _src = $(this).find('img').attr('src');
        _src = _src.replace('_60.', '_240.');
        _src = _src.replace('-60', '-240');
        $(this).parents('.goods-warp').find('.goods-pic').find('img').attr('src', _src);
    });
    
    // 品牌按首字母切换
    $('ul[wttype="ul_initial"] > li').mouseover(function(){
        $(this).addClass('current').siblings().removeClass('current');
        if ($(this).attr('data-initial') == 'all') {
            $('ul[wttype="ul_brand"] > li').show();
            return;
        }
        $('ul[wttype="ul_brand"] > li').hide();
        $('ul[wttype="ul_brand"] > li[data-initial="'+$(this).attr('data-initial')+'"]').show();
    });
    // 品牌显示筛选
    $('span[wttype="brand_show"]').toggle(
        function(){
            $('ul[wttype="ul_initial"]').show();
            $('ul[wttype="ul_brand"] > li').show();
            $(this).html('<i class="icon-angle-up"></i>收起');
        },function(){
            $('ul[wttype="ul_initial"]').hide();
            $('ul[wttype="ul_brand"] > li:gt(13)').hide();
            $('ul[wttype="ul_brand"] > li:lt(14)').show();
            $(this).html('<i class="icon-angle-down"></i>更多');
        }
    );
});

function setcookie(name,value){
    var Days = 30;   
    var exp  = new Date();   
    exp.setTime(exp.getTime() + Days*24*60*60*1000);   
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();   
}

/* 替换参数 */
function replaceParam(key, value, arg)
{
	if(!arguments[2]) arg = 'string';
    var params = PURL;
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        // 如果存在分页，跳转到第一页
        if (pKey == 'curpage')
        {
            params[i] = 'curpage=1';
        }
        if(arg == 'string'){
	        if (pKey == key)
	        {
	            params[i] = key + '=' + value;
	            found = true;
	        }
        }else{
        	for(var j = 0; j < key.length; j++){
        		if(pKey ==  key[j]){
        			params[i] = key[j] + '=' + value[j];
    	            found = true;
        		}
        	}
        }
    }
    if (!found)
    {
        if (arg == 'string'){
            value = transform_char(value);
            params.push(key + '=' + value);
        }else{
        	for(var j = 0; j < key.length; j++){
        		params.push(key[j] + '=' + transform_char(value[j]));
        	}
        }
    }
    location.assign(SITEURL + '/index.php?' + params.join('&'));
}

/* 删除参数 */
function dropParam(key, id, arg)
{
	if(!arguments[2]) arg = 'string';
    var params = location.search.substr(1).split('&');
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if(arg == 'string'){

	        if (pKey == key)
	        {
	            params.splice(i, 1);
	        }
        }else if(arg == 'del'){
            pVal = arr[1].split(',');
            for (var j=0; j<pVal.length; j++){
            	if(pKey == key && pVal[j] == id){
            		pVal.splice(j, 1);
            		params.splice(i, 1, pKey+'='+pVal);
            	}
            }
        }else{
        	for(var j = 0; j < key.length; j++){
        		if(pKey == key[j]){
        			params.splice(i, 1);i--;
        		}
        	}
        }
    }
    location.assign(SITEURL + '/index.php?' + params.join('&'));
}
