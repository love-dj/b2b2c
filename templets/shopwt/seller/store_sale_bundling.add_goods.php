<div class="div-goods-select">
  <table class="search-form">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <th><?php echo $lang['bundling_goods_store_class'];?></th>
        <td class="w160"><select name="stc_id" class="w150">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if($val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if($child_val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }}}}?>
          </select></td>
        <th><?php echo $lang['bundling_goods_name'];?></th>
        <td class="w160"><input type="text" name="b_search_keyword" class="text" value="<?php echo $_GET['keyword'];?>" /></td>
        <td class="tc w70"><a href="index.php?w=store_sale_bundling&t=bundling_add_goods" wttype="search_a" class="wts-btn"><i class="icon-search"></i><?php echo $lang['wt_search'];?></a></td>
        <td class="w10"></td>
      </tr>
    </tbody>
  </table>
  <div class="search-result" style="width:739px;">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="goods-list" wttype="bundling_goods_add_tbody" style=" width:760px;">
      <?php foreach ($output['goods_list'] as $val){?>
      <li wttype="<?php echo $val['goods_id'];?>">
        <div class="goods-thumb"><img src="<?php echo cthumb($val['goods_image'], 240, $_SESSION['store_id']);?>" wttype="<?php echo $val['goods_image'];?>" /></div>
        <dl class="goods-content">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']))?>" target="_blank" title="<?php echo $lang['bundling_goods_name'].'/'.$lang['bundling_goods_code'];?><?php echo $val['goods_name'];?><?php  if($val['goods_serial'] != ''){ echo $val['goods_serial'];}?>"><?php echo $val['goods_name'];?></a></dt>
          <dd><?php echo $lang['bundling_goods_price'];?>¥<?php echo wtPriceFormat($val['goods_price']);?></dd>
          <dd><?php echo $lang['bundling_goods_storage'];?><?php echo $val['goods_storage'].$lang['piece'];?></dd>
        </dl>
        <div data-param="{gid:<?php echo $val['goods_id'];?>,image:'<?php echo $val['goods_image'];?>',src:'<?php echo cthumb($val['goods_image'], 60, $_SESSION['store_id']);?>',gname:'<?php echo $val['goods_name'];?>',gprice:'<?php echo $val['goods_price'];?>',gstorang:'<?php echo $val['goods_storage'];?>'}"><a href="JavaScript:void(0);" class="wtbtn-mini wtbtn-mint" onclick="bundling_goods_add($(this))"><i class="icon-plus"></i><?php echo $lang['bundling_goods_add_bundling'];?></a></div>
      </li>
      <?php }?>
    </ul>
    <?php }else{?>
    <div class="norecord">
      <div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div>
    </div>
    <?php }?>
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    <div class="pagination"><?php echo $output['show_page']; ?></div>
    <?php }?>
  </div>
</div>
<script>
$(function(){
	/* ajax添加商品  */
	$('.demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SHOP_TEMPLATES_URL+"/images/loading.gif",
		target:'#bundling_add_goods_ajaxContent'
	});

	$('a[wttype="search_a"]').click(function(){
		$(this).attr('href', $(this).attr('href')+'&stc_id='+$('select[name="stc_id"]').val()+ '&' +$.param({'keyword':$('input[name="b_search_keyword"]').val()}));
		$('a[wttype="search_a"]').ajaxContent({
			event:'dblclick', //mouseover
			loaderType:'img',
			loadingMsg:'<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif',
			target:'#bundling_add_goods_ajaxContent'
		});
		$(this).dblclick();
		return false;
	});


	// 验证商品是否已经被选择。
	O = $('input[wttype="goods_id"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('ul[wttype="bundling_goods_add_tbody"] li');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('wttype')]) != 'undefined'){
				$(this).children(':last').html('<a href="JavaScript:void(0);" onclick="bundling_operate_delete($(\'#bundling_tr_'+$(this).attr('wttype')+'\'), '+$(this).attr('wttype')+')" class="wtbtn-mini wtbtn-bittersweet"><i class="icon-ban-bbs"></i><?php echo $lang['bundling_goods_add_bundling_exit'];?></a>');
			}
		});
	}
});

/* 添加商品 */
function bundling_goods_add(o){
	// 验证商品是否已经添加。
	var _bundlingtr = $('tbody[wttype="bundling_data"] tr:not(:first)');
	if(typeof(_bundlingtr) != 'undefined'){
		if(_bundlingtr.length == <?php echo C('sale_bundling_goods_sum');?>){
			alert('<?php printf($lang['bundling_goods_add_enough_prompt'], C('sale_bundling_goods_sum'));?>');
			return false;
		}
	}

    eval('var _data = ' + o.parent().attr('data-param'));
    if (_data.gstrong == 0) {
        alert('<?php echo $lang['bundling_goods_storage_not_enough'];?>');
        return false;
    }
    // 隐藏第一个tr
    $('tbody[wttype="bundling_data"]').children(':first').hide();
    // 插入数据
    $('<tr id="bundling_tr_' + _data.gid + '"></tr>')
        .append('<input type="hidden" wttype="goods_id" name="goods[g_' + _data.gid + '][gid]" value="' + _data.gid + '">')
        .append('<td class="w70"><input type="checkbox" name="goods[g_' + _data.gid + '][appoint]" value="1" checked="checked"></td>')
        .append('<td class="w50 "><div class="pic-thumb"><img wttype="bundling_data_img" ncname="' + _data.image + '" src="' + _data.src + '" onload="javascript:DrawImage(this,60,60)"></span></div></td>')
        .append('<td class="tl"><dl class="goods-name"><dt style="width: 300px;">' + _data.gname + '</dt></dl></td>')
        .append('<td class="w90 goods-price" wttype="bundling_data_price">' + _data.gprice + '</td>')
        .append('<td class="w90"><input type="text" wttype="price" name="goods[g_' + _data.gid + '][price]" value="' + _data.gprice + '" class="text w70"></td>')
        .append('<td class="nscs-table-handle w90"><span><a href="javascript:void(0);" onclick="bundling_operate_delete($(\'#bundling_tr_' + _data.gid + '\'), ' + _data.gid + ')" class="btn-bittersweet"><i class="icon-ban-bbs"></i><p><?php echo $lang['bundling_goods_remove'];?></p></a></span></td>')
        .fadeIn().appendTo('tbody[wttype="bundling_data"]');

    $('li[wttype="' + _data.gid + '"]').children(':last').html('<a href="JavaScript:void(0);" class="wtbtn-mini wtbtn-bittersweet" onclick="bundling_operate_delete($(\'#bundling_tr_' + _data.gid + '\'), ' + _data.gid + ')"><i class="icon-ban-bbs"></i><?php echo $lang['bundling_goods_add_bundling_exit'];?></a>');
    count_cost_price_sum();
    count_price_sum();
}

</script> 