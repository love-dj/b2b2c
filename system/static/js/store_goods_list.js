$(function(){
    // ajax获取商品列表
    $('i[wttype="ajaxGoodsList"]').toggle(
        function(){
            $(this).removeClass('icon-plus-sign').addClass('icon-minus-sign');
            var _parenttr = $(this).parents('tr');
            var _commonid = $(this).attr('data-comminid');
            var _div = _parenttr.next().find('.wtsc-goods-sku');
            if (_div.html() == '') {
                $.getJSON('index.php?w=store_goods_online&t=get_goods_list_ajax' , {commonid : _commonid}, function(date){
                    if (date != 'false') {
                        var _ul = $('<ul class="wtsc-goods-sku-list"></ul>');
                        $.each(date, function(i, o){
                            var _li = $('<li></li>');
                            $('<div class="goods-thumb" title="商家货号：' + o.goods_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.goods_image + '" ></a></div>').appendTo(_li);
                            $('<div class="goods-sku">SKU：<em>' + o.goods_id + '</em></div>').appendTo(_li);
                            $(o.goods_spec).appendTo(_li);
                            $('<div class="goods-price">价格：<em title="&yen;' + o.goods_price + '">&yen;' + o.goods_price + '</em></div>').appendTo(_li);
                            $('<div class="goods-storage" ' + o.alarm + '>库存：<em title="' + o.goods_storage + '" ' + o.alarm + '>' + o.goods_storage + '</em></div>').appendTo(_li);
                            $('<div><a href="' + o.url + '" target="_blank" class="wtbtn-mini">查看商品详情</a></div>').appendTo(_li);
                            $('<div><a href="javascript:;" class="wtbtn-mini wtbtn-lavander mt5" onclick="ajax_form(\'ajax_jingle\', \'编辑独立描述\', \'index.php?w=store_goods_online&t=edit_iframe_ajax&goods_id=' + o.goods_id + '\', \'915\')">编辑独立描述</a></div>').appendTo(_li);
                            _li.appendTo(_ul);
                        });
                        _ul.appendTo(_div);
                        _parenttr.next().show();
                        _div.perfectScrollbar();
                    }
                });
            } else {
            	_parenttr.next().show()
            }
        },
        function(){
            $(this).removeClass('icon-minus-sign').addClass('icon-plus-sign');
            $(this).parents('tr').next().hide();
        }
    );
});