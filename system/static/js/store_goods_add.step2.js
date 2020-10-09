$(function(){
    $('div[wttype="spec_div"]').perfectScrollbar();
    //电脑端手机端tab切换
    $(".tabs").tabs();
    // 取消回车提交表单
    $('input').keypress(function(e){
        var key = window.event ? e.keyCode : e.which;
        if (key.toString() == "13") {
         return false;
        }
    });
    // 添加店铺分类
    $("#add_sgcategory").unbind().click(function(){
        $(".sgcategory:last").after($(".sgcategory:last").clone(true).val(0));
    });
    // 选择店铺分类
    $('.sgcategory').unbind().change( function(){
        var _val = $(this).val();       // 记录选择的值
        $(this).val('0');               // 已选择值清零
        // 验证是否已经选择
        if (!checkSGC(_val)) {
            alert('该分类已经选择,请选择其他分类');
            return false;
        }
        $(this).val(_val);              // 重新赋值
    });
    
    /* 商品图片ajax上传 */
    $('#goods_image').fileupload({
        dataType: 'json',
        url: SITEURL + '/index.php?w=store_goods_add&t=image_upload&upload_type=uploadedfile',
        formData: {name:'goods_image'},
        add: function (e,data) {
        	$('img[wttype="goods_image"]').attr('src', SHOP_TEMPLATES_URL + '/images/loading.gif');
            data.submit();
        },
        done: function (e,data) {
            var param = data.result;
            if (typeof(param.error) != 'undefined') {
                alert(param.error);
                $('img[wttype="goods_image"]').attr('src',DEFAULT_GOODS_IMAGE);
            } else {
                $('input[wttype="goods_image"]').val(param.name);
                $('img[wttype="goods_image"]').attr('src',param.thumb_name);
            }
        }
    });

    /* ajax打开图片空间 */
    // 商品主图使用
    $('a[wttype="show_image"]').unbind().ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_TEMPLATES_URL+"/images/loading.gif",
        target:'#demo'
    }).click(function(){
        $(this).hide();
        $('a[wttype="del_goods_demo"]').show();
    });
    $('a[wttype="del_goods_demo"]').unbind().click(function(){
        $('#demo').html('');
        $(this).hide();
        $('a[wttype="show_image"]').show();
    });
    // 商品描述使用
    $('a[wttype="show_desc"]').unbind().ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_TEMPLATES_URL+"/images/loading.gif",
        target:'#des_demo'
    }).click(function(){
        $(this).hide();
        $('a[wttype="del_desc"]').show();
    });
    $('a[wttype="del_desc"]').click(function(){
        $('#des_demo').html('');
        $(this).hide();
        $('a[wttype="show_desc"]').show();
    });
    $('#add_album').fileupload({
        dataType: 'json',
        url: SITEURL+'/index.php?w=store_goods_add&t=image_upload',
        formData: {name:'add_album'},
        add: function (e,data) {
            $('i[wttype="add_album_i"]').removeClass('icon-upload-alt').addClass('icon-spinner icon-spin icon-large').attr('data_type', parseInt($('i[wttype="add_album_i"]').attr('data_type'))+1);
            data.submit();
        },
        done: function (e,data) {
            var _counter = parseInt($('i[wttype="add_album_i"]').attr('data_type'));
            _counter -= 1;
            if (_counter == 0) {
                $('i[wttype="add_album_i"]').removeClass('icon-spinner icon-spin icon-large').addClass('icon-upload-alt');
                $('a[wttype="show_desc"]').click();
            }
            $('i[wttype="add_album_i"]').attr('data_type', _counter);
        }
    });
    /* ajax打开图片空间 end */
    
    // 商品属性
    attr_selected();
    $('select[wt_type="attr_select"]').change(function(){
        id = $(this).find('option:selected').attr('wt_type');
        name = $(this).attr('attr').replace(/__WT__/g,id);
        $(this).attr('name',name);
    });
    
    // 修改规格名称
    $('dl[wttype="spec_group_dl"]').on('click', 'input[type="checkbox"]', function(){
        pv = $(this).parents('li').find('span[wttype="pv_name"]');
        if(typeof(pv.find('input').val()) == 'undefined'){
            pv.html('<input type="text" maxlength="20" class="text" value="'+pv.html()+'" />');
        }else{
            pv.html(pv.find('input').val());
        }
    });
    
    $('span[wttype="pv_name"] > input').live('change',function(){
        change_img_name($(this));       // 修改相关的颜色名称
        into_array();           // 将选中的规格放入数组
        goods_stock_set();      // 生成库存配置
    });
    

    // 运费部分显示隐藏
    $('input[wttype="freight"]').click(function(){
            $('input[wttype="freight"]').nextAll('div[wttype="div_freight"]').hide();
            $(this).nextAll('div[wttype="div_freight"]').show();
    });
    // 商品视频显示隐藏
    $('input[wttype="video"]').click(function(){
        $('input[wttype="video"]').nextAll('div[wttype="div_video"]').hide();
        $(this).nextAll('div[wttype="div_video"]').show();
    });
    
    // 商品所在地 v1.0
    $("#region").wt_region({show_deep:3,tip_type:1});

    // 定时发布时间
    $('#starttime').datepicker({dateFormat: 'yy-mm-dd'});
    $('input[name="g_state"]').click(function(){
        if($(this).attr('wttype') == 'auto'){
            $('#starttime').removeAttr('disabled').css('background','');
            $('#starttime_H').removeAttr('disabled').css('background','');
            $('#starttime_i').removeAttr('disabled').css('background','');
        }else{
            $('#starttime').attr('disabled','disabled').css('background','#E7E7E7 none');
            $('#starttime_H').attr('disabled','disabled').css('background','#E7E7E7 none');
            $('#starttime_i').attr('disabled','disabled').css('background','#E7E7E7 none');
        }
    });
    
    // 计算折扣
    $('input[name="g_price"],input[name="g_marketprice"]').change(function(){
        discountCalculator();
    });
    
    /* AJAX添加规格值 */
    // 添加规格
    $('a[wttype="specAdd"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[wttype="specAdd1"]').hide();
        _parent.find('div[wttype="specAdd2"]').show();
        _parent.find('input').focus();
    });
    // 取消
    $('a[wttype="specAddCancel"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[wttype="specAdd1"]').show();
        _parent.find('div[wttype="specAdd2"]').hide();
        _parent.find('input').val('');
    });
    // 提交
    $('a[wttype="specAddSubmit"]').click(function(){
        var _parent = $(this).parents('li:first');
        eval('var data_str = ' + _parent.attr('data-param'));
        var _input = _parent.find('input');
        _parent.find('div[wttype="specAdd1"]').show();
        _parent.find('div[wttype="specAdd2"]').hide();
        $.getJSON(data_str.url, {gc_id : data_str.gc_id , sp_id : data_str.sp_id , name : _input.val()}, function(data){
            if (data.done) {
                _parent.before('<li><span wttype="input_checkbox"><input type="checkbox" name="sp_val[' + data_str.sp_id + '][' + data.value_id + ']" wt_type="' + data.value_id + '" value="' +_input.val()+ '" /></span><span wttype="pv_name">' + _input.val() + '</span></li>');
                _input.val('');
            }
        });
    });
    // 修改规格名称
    $('input[wttype="spec_name"]').change(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        if ($(this).val() == '') {
            $(this).val(data_str.name);
        }
        $('th[wttype="spec_name_' + data_str.id + '"]').html($(this).val());
    });
    // 批量设置价格、库存、预警值
    $('.batch > .icon-edit').click(function(){
        $('.batch > .batch-input').hide();
        $(this).next().show();
    });
    $('.batch-input > .close').click(function(){
        $(this).parent().hide();
    });
    $('.batch-input').on('click', '[data-type]',function(){
        var _value = $(this).prev().val();
        var _type = $(this).attr('data-type');
        if (_type == 'price') {
            _value = number_format(_value, 2);
        } else {
            _value = parseInt(_value);
        }
        if (_type == 'alarm' && _value > 255) {
            _value = 255;
        }
        if (isNaN(_value)) {
            _value = 0;
        }
        $('input[data_type="' + _type + '" ]').val(_value);
        $(this).parent().hide();
        $(this).prev().val('');
        if (_type == 'price') {
            computePrice();
        }
        if (_type == 'stock') {
            computeStock();
        }
    });
    
    /* AJAX选择品牌 */
    // 根据首字母查询
    $('.letter[wttype="letter"]').find('a[data-letter]').click(function(){
        var _url = $(this).parents('.brand-index:first').attr('data-url');
        var _tid = $(this).parents('.brand-index:first').attr('data-tid');
        var _letter = $(this).attr('data-letter');
        var _search = $(this).html();
        $.getJSON(_url, {type : 'letter', tid : _tid, letter : _letter}, function(data){
            insertBrand(data, _search);
        });
    });
	 $('.letter[wttype="letter"]').find('a[data-empty]').click(function(){
		 $('#b_name').val("");
		 });
	
	
    // 根据关键字查询
    $('.search[wttype="search"]').find('a').click(function(){
        var _url = $(this).parents('.brand-index:first').attr('data-url');
        var _tid = $(this).parents('.brand-index:first').attr('data-tid');
        var _keyword = $('#search_brand_keyword').val();
        $.getJSON(_url, {type : 'keyword', tid : _tid, keyword : _keyword}, function(data){
            insertBrand(data, _keyword);
        });
    });
    // 选择品牌
    $('ul[wttype="brand_list"]').on('click', 'li', function(){
        $('#b_id').val($(this).attr('data-id'));
        $('#b_name').val($(this).attr('data-name'));
        $('.wtsc-brand-select > .wtsc-brand-select-container').hide();
    });
    //搜索品牌列表滚条绑定
    $('div[wttype="brandList"]').perfectScrollbar();
    $('select[name="b_id"]').change(function(){
        getBrandName();
    });
    $('input[name="b_name"]').focus(function(){
        $('.wtsc-brand-select > .wtsc-brand-select-container').show();
    });
	//下拉隐藏显示品牌列表
        $('.add-on').click(function(){
            $('.wtsc-brand-select > .wtsc-brand-select-container').fadeToggle();
        });
    
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'left',
        alignY: 'top',
        offsetX: 5,
        offsetY: -78,
        allowTipHover: false
    });
    $('.tip2').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'right',
        alignY: 'center',
        offsetX: 5,
        offsetY: 0,
        allowTipHover: false
    });

    /* 虚拟控制 */
    // 虚拟商品有效期
    $('#g_vindate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    $('[name="is_gv"]').change(function(){
        if ($('#is_gv_1').prop("checked")) {
            $('[wttype="virtual_valid"]').show();
            $('[wttype="virtual_null"]').hide();
        } else {
            $('[wttype="virtual_valid"]').hide();
            $('[wttype="virtual_null"]').show();
            $('#g_vindate').val('');
            $('#g_vlimit').val('');
        }
    });
    
     /* 阶梯价格控制 sho pw t.c m V 6 . 5 */
    $('[name="is_bat"]').change(function(){
        if ($('#is_bat_1').prop("checked")) {
            $('[wttype="fbat_valid"]').show();
			$('[wttype="spec_group_dl"]').hide();
			$('[wttype="spec_dl"]').hide();
			$('.spec-bg').hide();
			$('[wttype="batedit"]').hide();
        } 
		else if ($('#is_bat_2').prop("checked")) {
            $('[wttype="fbat_valid"]').hide();
			$('[wttype="spec_group_dl"]').show();
			$('[wttype="spec_dl"]').show();
			$('.spec_dl').show();
			$('[wttype="batedit"]').hide();
        } else{
			$('[wttype="spec_group_dl"]').show();
            $('[wttype="fbat_valid"]').hide();
			$('[wttype="spec_dl"]').show();
			$('.spec_dl').show();
			$('[wttype="batedit"]').show();
        }
    	});
    /* 手机端 商品描述 */
    // 显示隐藏控制面板
    $('div[wttype="mobile_pannel"]').on('click', '.module', function(){
        mbPannelInit();
        $(this).siblings().removeClass('current').end().addClass('current');
    });
    // 上移
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="mp_up"]', function(){
        var _parents = $(this).parents('.module:first');
        _rs = mDataMove(_parents.index(), 0);
        if (!_rs) {
            return false;
        }
        _parents.clone().insertBefore(_parents.prev()).end().remove();
        mbPannelInit();
    });
    // 下移
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="mp_down"]', function(){
        var _parents = $(this).parents('.module:first');
        _rs = mDataMove(_parents.index(), 1);
        if (!_rs) {
            return false;
        }
        _parents.clone().insertAfter(_parents.next()).end().remove();
        mbPannelInit();
    });
    // 删除
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="mp_del"]', function(){
        var _parents = $(this).parents('.module:first');
        mDataRemove(_parents.index());
        _parents.remove();
        mbPannelInit();
    });
    // 编辑
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="mp_edit"]', function(){
        $('a[wttype="meat_cancel"]').click();
        var _parents = $(this).parents('.module:first');
        var _val = _parents.find('.text-div').html();
        $(this).parents('.module:first').html('')
            .append('<div class="content"></div>').find('.content')
            .append('<div class="wtsc-mea-text" wttype="mea_txt"></div>')
            .find('div[wttype="mea_txt"]')
            .append('<p id="meat_content_count" class="text-tip">')
            .append('<textarea class="textarea valid" data-old="' + _val + '" wttype="meat_content">' + _val + '</textarea>')
            .append('<div class="button"><a class="wtsc-btn wtsc-btn-blue" wttype="meat_edit_submit" href="javascript:void(0);">确认</a><a class="wtsc-btn ml10" wttype="meat_edit_cancel" href="javascript:void(0);">取消</a></div>')
            .append('<a class="text-close" wttype="meat_edit_cancel" href="javascript:void(0);">X</a>')
            .find('#meat_content_count').html('').end()
            .find('textarea[wttype="meat_content"]').unbind().charCount({
                allowed: 500,
                warning: 50,
                counterContainerID: 'meat_content_count',
                firstCounterText:   '还可以输入',
                endCounterText:     '字',
                errorCounterText:   '已经超出'
            });
    });
    // 编辑提交
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="meat_edit_submit"]', function(){
        var _parents = $(this).parents('.module:first');
        var _c = toTxt(_parents.find('textarea[wttype="meat_content"]').val().replace(/[\r\n]/g,''));
        var _cl = _c.length;
        if (_cl == 0 || _cl > 500) {
            return false;
        }
        _data = new Object;
        _data.type = 'text';
        _data.value = _c;
        _rs = mDataReplace(_parents.index(), _data);
        if (!_rs) {
            return false;
        }
        _parents.html('').append('<div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_edit" href="javascript:void(0);">编辑</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
            .append('<div class="cover"></div>');

    });
    // 编辑关闭
    $('div[wttype="mobile_pannel"]').on('click', '[wttype="meat_edit_cancel"]', function(){
        var _parents = $(this).parents('.module:first');
        var _c = _parents.find('textarea[wttype="meat_content"]').attr('data-old');
        _parents.html('').append('<div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_edit" href="javascript:void(0);">编辑</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>')
        .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
        .append('<div class="cover"></div>');
    });
    // 初始化控制面板
    mbPannelInit = function(){
        $('div[wttype="mobile_pannel"]')
            .find('a[wttype^="mp_"]').show().end()
            .find('.module')
            .first().find('a[wttype="mp_up"]').hide().end().end()
            .last().find('a[wttype="mp_down"]').hide();
    }
    // 添加文字按钮，显示文字输入框
    $('a[wttype="mb_add_txt"]').click(function(){
        $('div[wttype="mea_txt"]').show();
        $('a[wttype="meai_cancel"]').click();
        $('div[wttype="mobile_editor_area"]').find('textarea[wttype="meat_content"]').focus();
    $('div[wttype="mobile_editor_area"]').find('textarea[wttype="meat_content"]').unbind().charCount({
        allowed: 500,
        warning: 50,
        counterContainerID: 'meat_content_count',
        firstCounterText:   '还可以输入',
        endCounterText:     '字',
        errorCounterText:   '已经超出'
    });
	});
    // 关闭 文字输入框按钮
    $('a[wttype="meat_cancel"]').click(function(){
        $(this).parents('div[wttype="mea_txt"]').find('textarea[wttype="meat_content"]').val('').end().hide();
    });
    // 提交 文字输入框按钮
    $('a[wttype="meat_submit"]').click(function(){
        var _c = toTxt($('textarea[wttype="meat_content"]').val().replace(/[\r\n]/g,''));
        var _cl = _c.length;
        if (_cl == 0 || _cl > 500) {
            return false;
        }
        _data = new Object;
        _data.type = 'text';
        _data.value = _c;
        _rs = mDataInsert(_data);
        if (!_rs) {
            return false;
        }
        $('<div class="module m-text"></div>')
            .append('<div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_edit" href="javascript:void(0);">编辑</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
            .append('<div class="cover"></div>').appendTo('div[wttype="mobile_pannel"]');
        
        $('a[wttype="meat_cancel"]').click();
    });
    // 添加图片按钮，显示图片空间文字
    $('a[wttype="mb_add_img"]').click(function(){
        $('a[wttype="meat_cancel"]').click();
        $('div[wttype="mea_img"]').show().load('index.php?w=store_album&t=pic_list&item=mobile');
    });
    // 关闭 图片选择
    $('div[wttype="mobile_editor_area"]').on('click', 'a[wttype="meai_cancel"]', function(){
        $('div[wttype="mea_img"]').html('');
    });
    // 插图图片
    insert_mobile_img = function(data){
        _data = new Object;
        _data.type = 'image';
        _data.value = data;
        _rs = mDataInsert(_data);
        if (!_rs) {
            return false;
        }
        $('<div class="module m-image"></div>')
            .append('<div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_rpl" href="javascript:void(0);">替换</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="image-div"><img src="' + data + '"></div></div>')
            .append('<div class="cover"></div>').appendTo('div[wttype="mobile_pannel"]');
        
    }
    // 替换图片
    $('div[wttype="mobile_pannel"]').on('click', 'a[wttype="mp_rpl"]', function(){
        $('a[wttype="meat_cancel"]').click();
        $('div[wttype="mea_img"]').show().load('index.php?w=store_album&t=pic_list&item=mobile&type=replace');
    });
    // 插图图片
    replace_mobile_img = function(data){
        var _parents = $('div.m-image.current');
        _parents.find('img').attr('src', data);
        _data = new Object;
        _data.type = 'image';
        _data.value = data;
        mDataReplace(_parents.index(), _data);
    }
    // 插入数据
    mDataInsert = function(data){
        _m_data = mDataGet();
        _m_data.push(data);
        return mDataSet(_m_data);
    }
    // 数据移动 
    // type 0上移  1下移
    mDataMove = function(index, type) {
        _m_data = mDataGet();
        _data = _m_data.splice(index, 1);
        if (type) {
            index += 1;
        } else {
            index -= 1;
        }
        _m_data.splice(index, 0, _data[0]);
        return mDataSet(_m_data);
    }
    // 数据移除
    mDataRemove = function(index){
        _m_data = mDataGet();
        _m_data.splice(index, 1);     // 删除数据
        return mDataSet(_m_data);
    }
    // 替换数据
    mDataReplace = function(index, data){
        _m_data = mDataGet();
        _m_data.splice(index, 1, data);
        return mDataSet(_m_data);
    }
    // 获取数据
    mDataGet = function(){
        _m_body = $('input[name="m_body"]').val();
        if (_m_body == '' || _m_body == 'false') {
            var _m_data = new Array;
        } else {
            eval('var _m_data = ' + _m_body);
        }
        return _m_data;
    }
    // 设置数据
    mDataSet = function(data){
        var _i_c = 0;
        var _i_c_m = 20;
        var _t_c = 0;
        var _t_c_m = 5000;
        var _sign = true;
        $.each(data, function(i, n){
            if (n.type == 'image') {
                _i_c += 1;
                if (_i_c > _i_c_m) {
                    alert('只能选择'+_i_c_m+'张图片');
                    _sign = false;
                    return false;
                }
            } else if (n.type == 'text') {
                _t_c += n.value.length;
                if (_t_c > _t_c_m) {
                    alert('只能输入'+_t_c_m+'个字符');
                    _sign = false;
                    return false;
                }
            }
        });
        if (!_sign) {
            return false;
        }
        $('span[wttype="img_count_tip"]').html('还可以选择图片<em>' + (_i_c_m - _i_c) + '</em>张');
        $('span[wttype="txt_count_tip"]').html('还可以输入<em>' + (_t_c_m - _t_c) + '</em>字');
        _data = JSON.stringify(data);
        $('input[name="m_body"]').val(_data);
        return true;
    }
    // 转码
    toTxt = function(str) {
        var RexStr = /\<|\>|\"|\'|\&|\\/g
        str = str.replace(RexStr, function(MatchStr) {
            switch (MatchStr) {
            case "<":
                return "";
                break;
            case ">":
                return "";
                break;
            case "\"":
                return "";
                break;
            case "'":
                return "";
                break;
            case "&":
                return "";
                break;
            case "\\":
                return "";
                break;
            default:
                break;
            }
        })
        return str;
    }
});
// 计算商品库存
function computeStock(){
    // 库存
    var _stock = 0;
    $('input[data_type="stock"]').each(function(){
        if($(this).val() != ''){
            _stock += parseInt($(this).val());
        }
    });
    $('input[name="g_storage"]').val(_stock);
}

// 计算价格
function computePrice(){
    // 计算最低价格
    var _price = 0;var _price_sign = false;
    $('input[data_type="price"]').each(function(){
        if($(this).val() != '' && $(this)){
            if(!_price_sign){
                _price = parseFloat($(this).val());
                _price_sign = true;
            }else{
                _price = (parseFloat($(this).val())  > _price) ? _price : parseFloat($(this).val());
            }
        }
    });
    $('input[name="g_price"]').val(number_format(_price, 2));

    discountCalculator();       // 计算折扣
}

// 计算折扣
function discountCalculator() {
    var _price = parseFloat($('input[name="g_price"]').val());
    var _marketprice = parseFloat($('input[name="g_marketprice"]').val());
    if((!isNaN(_price) && _price != 0) && (!isNaN(_marketprice) && _marketprice != 0)){
        var _discount = parseInt(_price/_marketprice*100);
        $('input[name="g_discount"]').val(_discount);
    }
}

//获得商品名称
function getBrandName() {
    var brand_name = $('select[name="b_id"] > option:selected').html();
    $('input[name="b_name"]').val(brand_name);
}
//修改相关的颜色名称
function change_img_name(Obj){
     var S = Obj.parents('li').find('input[type="checkbox"]');
     S.val(Obj.val());
     var V = $('tr[wttype="file_tr_'+S.attr('wt_type')+'"]');
     V.find('span[wttype="pv_name"]').html(Obj.val());
     V.find('input[type="file"]').attr('name', Obj.val());
}
// 商品属性
function attr_selected(){
    $('select[wt_type="attr_select"] option:selected').each(function(){
        id = $(this).attr('wt_type');
        name = $(this).parents('select').attr('attr').replace(/__WT__/g,id);
        $(this).parents('select').attr('name',name);
    });
}
// 验证店铺分类是否重复
function checkSGC($val) {
    var _return = true;
    $('.sgcategory').each(function(){
        if ($val !=0 && $val == $(this).val()) {
            _return = false;
        }
    });
    return _return;
} 
/* 插入商品图片 */
function insert_img(name, src) {
    $('input[wttype="goods_image"]').val(name);
    $('img[wttype="goods_image"]').attr('src',src);
}

/* 插入编辑器 */
function insert_editor(file_path) {
    KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

function setArea(area1, area2) {
    $('#province_id').val(area1).change();
    $('#city_id').val(area2);
}

// 插入品牌
function insertBrand(param, search) {
    $('div[wttype="brandList"]').show();
    $('div[wttype="noBrandList"]').hide();
    var _ul = $('ul[wttype="brand_list"]');
    _ul.html('');
    if ($.isEmptyObject(param)) {
        $('div[wttype="brandList"]').hide();
        $('div[wttype="noBrandList"]').show().find('strong').html(search);
        return false;
    }
    $.each(param, function(i, n){
        $('<li data-id="' + n.brand_id + '" data-name="' + n.brand_name + '"><em>' + n.brand_initial + '</em>' + n.brand_name + '</li>').appendTo(_ul);
    });

    //搜索品牌列表滚条绑定
    $('div[wttype="brandList"]').perfectScrollbar('update');
}