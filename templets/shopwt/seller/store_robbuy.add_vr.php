<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form id="add_form" action="index.php?w=store_robbuy&t=robbuy_save&vr=1" method="post" enctype="multipart/form-data">
    <dl>
      <dt><i class="required">*</i><?php echo $lang['group_name'].$lang['wt_colon'];?></dt>
      <dd>
        <input class="w400 text" name="robbuy_name" type="text" id="robbuy_name" value="" maxlength="30"  />
        <span></span>
        <p class="hint"><?php echo $lang['group_name_tip'];?></p>
      </dd>
    </dl>
    <dl>
      <dt>抢购副标题<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="w400 text" name="remark" type="text" id="remark" value="" maxlength="30"  />
        <span></span>
        <p class="hint">抢购活动副标题最多可输入30个字符</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['start_time'];?><?php echo $lang['wt_colon'];?></dt>
      <dd>
          <input id="start_time" name="start_time" type="text" class="text w130" /><em class="add-on"><i class="icon-calendar"></i></em><span></span>
          <p class="hint"><?php echo '抢购开始时间不能小于'.date('Y-m-d H:i', $output['robbuy_start_time']);?></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['end_time'];?><?php echo $lang['wt_colon'];?></dt>
      <dd>
          <input id="end_time" name="end_time" type="text" class="text w130"/><em class="add-on"><i class="icon-calendar"></i></em><span></span>
          <p class="hint">
            抢购结束时间不能大于虚拟商品的过期时间
            <span id="vr-expire-time"></span>
<?php if (!$output['isOwnShop']) { ?>
            和抢购套餐的过期时间
            <span>（<?php echo date('Y-m-d H:i', $output['current_robbuy_quota']['end_time']); ?>）</span>
<?php } ?>
          </p>
      </dd>
    </dl>

    <dl>
      <dt><i class="required">*</i><?php echo $lang['robbuy_goods'].$lang['wt_colon'];?></dt>
      <dd>
      <div wttype="robbuy_goods_content" class="selected-group-goods " style="display:none;">
      <div class="goods-thumb"><img id="robbuy_goods_image" src=""/></div>
          <div class="goods-name">
          <a wttype="robbuy_goods_href" id="robbuy_goods_name" href="" target="_blank"></a>
          </div>
          <div class="goods-price">商城价：￥<span wttype="robbuy_goods_price"></span></div>
      </div>
      <a href="javascript:void(0);" id="btn_show_search_goods" class="wtbtn wtbtn-aqua">选择商品</a>
      <input id="robbuy_goods_id" name="robbuy_goods_id" type="hidden" value=""/>
      <span></span>
      <div id="div_search_goods" class="div-goods-select mt10" style="display: none;">
          <table class="search-form">
              <tr>
                  <th class="w150">
                      <strong>第一步：搜索店内商品</strong>
                  </th>
                  <td class="w160">
                      <input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/>
                  </td>
                  <td class="w70 tc">
                      <a href="javascript:void(0);" id="btn_search_goods" class="wtbtn"/><i class="icon-search"></i><?php echo $lang['wt_search'];?></a></td>
                    <td class="w10"></td>
                    <td>
                        <p class="hint">不输入名称直接搜索将显示店内所有虚拟商品。</p>
                    </td>
                </tr>
            </table>
            <div id="div_goods_search_result" class="search-result" style="width:739px;"></div>
            <a id="btn_hide_search_goods" class="close" href="javascript:void(0);">X</a>
        </div>
        <p class="hint"><?php echo $lang['robbuy_goods_explain'];?></p>
        </dd>
    </dl>
    <dl wttype="robbuy_goods_content" style="display:none;">
      <dt><?php echo $lang['robbuy_index_store_price'].$lang['wt_colon'];?></dt>
      <dd> <?php echo $lang['currency'];?><span wttype="robbuy_goods_price"></span></dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['robbuy_price'].$lang['wt_colon'];?></dt>
      <dd>
        <input class="w70 text" id="robbuy_price" name="robbuy_price" type="text" value=""/><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
        <p class="hint"><?php echo $lang['robbuy_price_tip'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>抢购活动图片<?php echo $lang['wt_colon'];?></dt>
      <dd>
      <div class="wtsc-upload-thumb robbuy-pic">
          <p><i class="icon-picture"></i>
          <img wttype="img_robbuy_image" style="display:none;" src=""/></p>
      </div>
        <input wttype="robbuy_image" name="robbuy_image" type="hidden" value="">
        <div class="wtsc-upload-btn">
            <a href="javascript:void(0);">
                <span>
                    <input type="file" hidefocus="true" size="1" class="input-file" name="robbuy_image" wttype="btn_upload_image"/>
                </span>
                <p><i class="icon-upload-alt"></i>图片上传</p>
            </a>
        </div>
        <span></span>
        <p class="hint"><?php echo $lang['group_pic_explain'];?></p>
        </dd>
    </dl>
    <dl>
      <dt>抢购推荐位图片<?php echo $lang['wt_colon'];?></dt>
      <dd>
      <div class="wtsc-upload-thumb robbuy-commend-pic">
          <p><i class="icon-picture"></i>
          <img wttype="img_robbuy_image" style="display:none;" src=""/></p>
      </div>
        <input wttype="robbuy_image" name="robbuy_image1" type="hidden" value="">
        <span></span>
        <div class="wtsc-upload-btn">
            <a href="javascript:void(0);">
                <span>
                    <input type="file" hidefocus="true" size="1" class="input-file" name="robbuy_image" wttype="btn_upload_image"/>
                </span>
                <p><i class="icon-upload-alt"></i>图片上传</p>
            </a>
        </div>
        <p class="hint"><?php echo $lang['group_pic_explain2'];?></p>
        </dd>
    </dl>
    <dl>
      <dt>抢购分类<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <select id="class" name="class" class="w80">
          <option value="">请选择...</option>
          <?php if (!empty($output['classlist'])) { ?>
          <?php foreach ($output['classlist'] as $class) { ?>
          <option value="<?php echo $class['class_id']; ?>"><?php echo $class['class_name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <select id="s_class" name="s_class" class="w80">
          <option value="">请选择...</option>
        </select>
        <span></span>
        <p class="hint">请选择本次虚拟抢购所属分类</p>
      </dd>
    </dl>
    <dl>
      <dt>抢购区域<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <select id="city" name="city" class="w80">
          <option value="">请选择...</option>
          <?php if (!empty($output['arealist'])) { ?>
          <?php foreach ($output['arealist'] as $area) { ?>
          <option value="<?php echo $area['area_id']; ?>"><?php echo $area['area_name']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <select id="area" name="area" class="w80">
          <option value="">请选择...</option>
        </select>
        <select id="mall" name="mall" class="w80">
          <option value="">请选择...</option>
        </select>
        <span></span>
        <p class="hint">请选择本次虚拟抢购所属地区</p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['virtual_quantity'].$lang['wt_colon'];?></dt>
      <dd>
        <input class="w70 text" id="virtual_quantity" name="virtual_quantity" type="text" value="0"/>
        <span></span>
        <p class="hint"><?php echo $lang['virtual_quantity_explain'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['sale_quantity'].$lang['wt_colon'];?></dt>
      <dd>
        <input class="w70 text" id="upper_limit" name="upper_limit" type="text" value="0"/>
        <span></span>
        <p class="hint">每个买家ID可抢购的最大数量，该数量不能大于虚拟商品本身的限购数量，不限数量请填"0"</p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['group_intro'].$lang['wt_colon'];?></dt>
      <dd>
        <?php showEditor('robbuy_intro','','740px','360px','','false',false);?>
        <p class="hr8"><a class="des_demo wtbtn" href="index.php?w=store_album&t=pic_list&item=robbuy"><i class="icon-picture"></i><?php echo $lang['store_goods_album_insert_users_photo'];?></a></p>
        <p id="des_demo" style="display:none;"></p>
      </dd>
    </dl>
    <div class="bottom"><label class="submit-border">
      <input type="submit" class="submit" value="<?php echo $lang['wt_submit'];?>"></label>
    </div>
  </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){

$("select[name=class]").change(function(){
    var class_id = $(this).val();
    $.ajax({
        type:'GET',
        url:'index.php?w=store_robbuy&t=ajax_vr_class&class_id='+class_id,
        success:function(json){
            var html = '<option value="">'+'请选择...'+'</option>';
            if(json){
                var data = eval("("+json+")");
                $.each(data,function(i,val){
                    html+='<option value="'+val.class_id+'">'+val.class_name+'</option>';
                });
            }
            $("select[name=s_class]").html(html);
        }
    });
});

$("select[name=city]").change(function(){
    var area_id = $(this).val();
    $.ajax({
        type:'GET',
        url:'index.php?w=store_robbuy&t=ajax_vr_area&area_id='+area_id,
        success:function(json){
            var html = '<option value="">'+'请选择...'+'</option>';
            var mall = '<option value="">'+'请选择...'+'</option>';
            if(json){
                var data = eval("("+json+")");
                $.each(data,function(i,val){
                    html+='<option value="'+val.area_id+'">'+val.area_name+'</option>';
                });
            }
            $("select[name=area]").html(html);
            $("select[name=mall]").html(mall);
        }
    });
});

$("select[name=area]").change(function(){
    var area_id = $(this).val();
    $.ajax({
        type:'GET',
        url:'index.php?w=store_robbuy&t=ajax_vr_area&area_id='+area_id,
        success:function(json){
            var html = '<option value="">'+'请选择...'+'</option>';
            if(json){
                var data = eval("("+json+")");
                $.each(data,function(i,val){
                    html+='<option value="'+val.area_id+'">'+val.area_name+'</option>';
                });
            }
            $("select[name=mall]").html(html);
        }
    });
});

    $('#start_time').datetimepicker({
        controlType: 'select'
    });

    $('#end_time').datetimepicker({
        controlType: 'select'
    });

    $('#btn_show_search_goods').on('click', function() {
        $('#div_search_goods').show();
    });

    $('#btn_hide_search_goods').on('click', function() {
        $('#div_search_goods').hide();
    });

    //搜索商品
    $('#btn_search_goods').on('click', function() {
        var url = "<?php echo urlShop('store_robbuy', 'search_vr_goods'); ?>";
        url += '&' + $.param({goods_name: $('#search_goods_name').val()});
        $('#div_goods_search_result').load(url);
    });

    $('#div_goods_search_result').on('click', 'a.demo', function() {
        $('#div_goods_search_result').load($(this).attr('href'));
        return false;
    });

    var vrExpireTime = 0;
    var vrLimitNum = 0;

    //选择商品
    $('#div_goods_search_result').on('click', '[wttype="btn_add_robbuy_goods"]', function() {
        var goods_commonid = $(this).attr('data-goods-commonid');
        $.get('<?php echo urlShop('store_robbuy', 'robbuy_goods_content'); ?>', {goods_commonid: goods_commonid}, function(data) {
            if(data.result) {
                $('#robbuy_goods_id').val(data.goods_id);
                $('#robbuy_goods_image').attr('src', data.goods_image);
                $('#robbuy_goods_name').text(data.goods_name);
                $('[wttype="robbuy_goods_price"]').text(data.goods_price);
                $('[wttype="robbuy_goods_href"]').attr('href', data.goods_href);
                $('[wttype="robbuy_goods_content"]').show();
                $('#div_search_goods').hide();
                // vr
                vrExpireTime = '' + data.virtual_indate;
                $('#vr-expire-time').html('（'+data.virtual_indate_str+'）');
                vrLimitNum = data.virtual_limit;
            } else {
                showError(data.message);
            }
        }, 'json');
    });

    //图片上传
    $('[wttype="btn_upload_image"]').fileupload({
        dataType: 'json',
            url: "<?php echo urlShop('store_robbuy', 'image_upload');?>",
            add: function(e, data) {
                $parent = $(this).parents('dd');
                $input = $parent.find('[wttype="robbuy_image"]');
                $img = $parent.find('[wttype="img_robbuy_image"]');
                data.formData = {old_robbuy_image:$input.val()};
                $img.attr('src', "<?php echo SHOP_TEMPLATES_URL.'/images/loading.gif';?>");
                data.submit();
            },
            done: function (e,data) {
                var result = data.result;
                $parent = $(this).parents('dd');
                $input = $parent.find('[wttype="robbuy_image"]');
                $img = $parent.find('[wttype="img_robbuy_image"]');
                if(result.result) {
                    $img.prev('i').hide();
                    $img.attr('src', result.file_url);
                    $img.show();
                    $input.val(result.file_name);
                } else {
                    showError(data.message);
                }
            }
    });

    jQuery.validator.methods.lessThanVrLimitNum = function(value, element) {
        var v = parseInt(value) || 0;
        if (v > 0 && vrLimitNum && vrLimitNum > 0 && v > vrLimitNum)
            return false;
        return true;
    };

    jQuery.validator.methods.lessThanVrExpireTime = function(value, element) {
        var ts = new Date(Date.parse(value.replace(/-/g, "/"))).getTime() / 1000;
        // console.log(vrExpireTime);
        // console.log(ts);
        return vrExpireTime > ts;
    };

    jQuery.validator.methods.greaterThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

    jQuery.validator.methods.lessThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 > date2;
    };

    jQuery.validator.methods.greaterThanStartDate = function(value, element) {
        var start_date = $("#start_time").val();
        var date1 = new Date(Date.parse(start_date.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

    jQuery.validator.methods.checkRobbuyGoods = function(value, element) {
        var start_time = $("#start_time").val();
        var result = true;
        $.ajax({
            type:"GET",
            url:'<?php echo urlShop('store_robbuy', 'check_robbuy_goods');?>',
            async:false,
            data:{start_time: start_time, goods_id: value},
            dataType: 'json',
            success: function(data){
                if(!data.result) {
                    result = false;
                }
            }
        });
        return result;
    };

    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        onfocusout: false,
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
        rules : {
            robbuy_name: {
                required : true
            },
            start_time : {
                required : true,
                greaterThanDate : '<?php echo date('Y-m-d H:i',$output['robbuy_start_time']);?>'
            },
            end_time : {
                required : true,
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<?php echo date('Y-m-d H:i',$output['current_robbuy_quota']['end_time']);?>',
<?php } ?>
                lessThanVrExpireTime : true,
                greaterThanStartDate : true
            },
            robbuy_goods_id: {
                required : true,
                checkRobbuyGoods: true
            },
            robbuy_price: {
                required : true,
                number : true,
                min : 0.01,
                max : 1000000
            },
            virtual_quantity: {
                required : true,
                digits : true
            },
            upper_limit: {
                required : true,
                digits : true,
                lessThanVrLimitNum : true
            },
            robbuy_image: {
                required : true
            }
        },
        messages : {
            robbuy_name: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['group_name_error'];?>'
            },
            start_time : {
                required : '<i class="icon-exclamation-sign"></i>抢购开始时间不能为空',
                greaterThanDate : '<i class="icon-exclamation-sign"></i><?php echo sprintf('抢购开始时间必须大于{0}',date('Y-m-d H:i',$output['current_robbuy_quota']['start_time']));?>'
            },
            end_time : {
                required : '<i class="icon-exclamation-sign"></i>抢购结束时间不能为空',
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<i class="icon-exclamation-sign"></i><?php echo sprintf('抢购结束时间必须小于{0}',date('Y-m-d H:i',$output['current_robbuy_quota']['end_time']));?>',
<?php } ?>
                lessThanVrExpireTime : '<i class="icon-exclamation-sign"></i>结束时间必须小于虚拟商品过期时间',
                greaterThanStartDate : '<i class="icon-exclamation-sign"></i>结束时间必须大于开始时间'
            },
            robbuy_goods_id: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['group_goods_error'];?>',
                checkRobbuyGoods: '该商品已经参加了同时段的活动'
            },
            robbuy_price: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['robbuy_price_error'];?>',
                number : '<i class="icon-exclamation-sign"></i><?php echo $lang['robbuy_price_error'];?>',
                min : '<i class="icon-exclamation-sign"></i><?php echo $lang['robbuy_price_error'];?>',
                max : '<i class="icon-exclamation-sign"></i><?php echo $lang['robbuy_price_error'];?>'
            },
            virtual_quantity: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['virtual_quantity_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['virtual_quantity_error'];?>'
            },
            upper_limit: {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['sale_quantity_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['sale_quantity_error'];?>',
                lessThanVrLimitNum : '虚拟抢购活动的限购数量不能大于虚拟商品本身的限购数量'
            },
            robbuy_image: {
                required : '<i class="icon-exclamation-sign"></i>抢购图片不能为空'
            }
        }
    });

	$('#li_1').click(function(){
		$('#li_1').attr('class','active');
		$('#li_2').attr('class','');
		$('#demo').hide();
	});

	$('#goods_demo').click(function(){
		$('#li_1').attr('class','');
		$('#li_2').attr('class','active');
		$('#demo').show();
	});

	$('.des_demo').click(function(){
		if($('#des_demo').css('display') == 'none'){
            $('#des_demo').show();
        }else{
            $('#des_demo').hide();
        }
	});

    $('.des_demo').ajaxContent({
        event:'click', //mouseover
            loaderType:"img",
            loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif",
            target:'#des_demo'
    });
});

function insert_editor(file_path){
	KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}
</script>
