<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>

<?php if ($output['isOwnShop']) { ?>
  <a class="wtbtn wtbtn-mint" href="javascript:void(0);" wttype="select_goods"><i class="icon-plus-sign"></i>添加商品</a>
<?php } else { ?>
  <?php if(empty($output['booth_quota'])) { ?>
  <a class="wtbtn wtbtn-aqua" href="<?php echo urlShop('store_sale_booth', 'booth_quota_add');?>" title="购买套餐"><i class="icon-money"></i>购买套餐</a>
  <?php } else { ?>
  <?php if ($output['booth_quota']['booth_state'] == 1) {?>
  <a class="wtbtn wtbtn-mint" href="javascript:void(0);" wttype="select_goods" style="right:100px"><i class="icon-plus-sign"></i>添加商品</a>
  <?php } ?>
  <a class="wtbtn wtbtn wtbtn-aqua" href="<?php echo urlShop('store_sale_booth', 'booth_renew');?>"><i class="icon-money"></i>套餐续费</a>
  <?php } ?>
<?php } ?>

</div>

<?php if ($output['isOwnShop']) { ?>
<div class="alert alert-block mt10">
  <ul>
    <li>1、被推荐商品将在该商品所在的分类及其上级分类的商品列表左侧随机出现。</li>
    <?php if (C('sale_booth_goods_sum') != 0) {?>
    <li>2、您最多可以推荐<?php echo C('sale_booth_goods_sum');?>个商品。</li>
    <?php }?>
  </ul>
</div>
<?php } else { ?>
<!-- 有可用套餐，发布活动 -->
<div class="alert alert-block mt10">
<?php if (empty($output['booth_quota']) || $output['booth_quota']['booth_state'] == 0) {?>
  <strong>你还没有购买套餐或套餐已经过期，请购买或续费套餐。</strong>
<?php } else {?>
  <strong>套餐过期时间<?php echo $lang['wt_colon'];?></strong> <strong style=" color:#F00;"><?php echo date('Y-m-d H:i:s',$output['booth_quota']['booth_quota_endtime']);?></strong>
<?php }?>
  <ul>
    <li>1、点击购买套餐或续费套餐可以购买或续费套餐</li>
    <li>2、<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>。</li>
    <li>3、被推荐商品将在该商品所在的分类及其上级分类的商品列表左侧随机出现。</li>
    <?php if (C('sale_booth_goods_sum') != 0) {?>
    <li>4、您最多可以推荐<?php echo C('sale_booth_goods_sum');?>个商品。</li>
    <?php }?>
  </ul>
</div>
<?php } ?>

<?php if ($output['isOwnShop'] || (!empty($output['booth_quota']) && $output['booth_quota']['booth_state'] == 1)) { ?>
<!-- 商品搜索 -->
<div wttype="div_goods_select" class="div-goods-select" style="display: none;">
    <table class="search-form">
      <tr><th class="w150"><strong>第一步：搜索店内商品</strong></th><td class="w160"><input wttype="search_goods_name" type="text w150" class="text" name="goods_name" value=""/></td>
        <td class="w70 tc"><label class="submit-border"><input wttype="btn_search_goods" type="button" value="<?php echo $lang['wt_search'];?>" class="submit"/></label></td><td class="w10"></td><td><p class="hint">不输入名称直接搜索将显示店内所有出售中的商品</p></td>
      </tr>
    </table>
  <div wttype="div_goods_search_result" class="search-result"></div>
  <a wttype="btn_hide_goods_select" class="close" href="javascript:void(0);">X</a> </div>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w50"></th>
      <th class="tl">商品名称</th>
      <th class="w180">价格</th>
      <th class="w110"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>

  <tbody wttype="choose_goods_list">
    <tr wttype="tr_no_sale" style="display:none;">
      <td colspan="20" class="norecord"><div class="no-sale"><i class="zw"></i><span>推荐展位列表暂无内容，请选择添加平台推荐展位商品。</span></div></td>
    </tr>
    <?php if(!empty($output['goods_list'])) { ?>
    <?php foreach($output['goods_list'] as $key=>$val){?>
    <tr class="bd-line">
      <td></td>
      <td><div class="pic-thumb"><a href="<?php echo $val['url'];?>" target="black"><img src="<?php echo $val['goods_image'];?>"/></a></div></td>
      <td class="tl">
        <dl class="goods-name">
          <dt><a href="<?php echo $val['url'];?>" target="_blank"><?php echo $val['goods_name'];?></a></dt>
          <dd><?php echo $output['goodsclass_list'][$val['gc_id']]['gc_name'];?></dd>
        </dl>
      </td>
      <td class="goods-price">￥<?php echo wtPriceFormat($val['goods_price']);?></td>
      <td class="nscs-table-handle">
        <span><a class="btn-grapefruit" href='javascript:void(0);' wttype="del_choosed" data-param="{gid:<?php echo $val['goods_id'];?>}"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a></span></td>
    </tr>
    <?php }?>
    <?php } ?>
  </tbody>
</table>
<?php }else{?>
<!-- 没有可用套餐，购买 -->
<table class="wtsc-default-table wtsc-sale-buy">
  <tbody>
    <tr>
      <td colspan="20" class="norecord"><div class="no-sale"><i class="zw"></i><span>您还没有购买套餐，或该促销活动已经关闭。<br />请先购买套餐，再查看活动列表。</span></div></td>
    </tr>
  </tbody>
</table>
<?php }?>
<script>
$(function(){
    // 验证是否已经选择商品
    choosed_goods();

    // 显示搜索框
    $('a[wttype="select_goods"]').click(function(){
        $('div[wttype="div_goods_select"]').show();
    });
    // 隐藏搜索框
    $('a[wttype="btn_hide_goods_select"]').click(function(){
        $('div[wttype="div_goods_select"]').hide();
    });

    // 搜索商品
    $('input[wttype="btn_search_goods"]').click(function(){
        _url = '<?php echo urlShop('store_sale_booth', 'booth_select_goods');?>';
        $('div[wttype="div_goods_search_result"]').html('').load(_url + '&goods_name=' + $('input[wttype="search_goods_name"]').val());
    });
    $('div[wttype="div_goods_select"]').on('click', '.demo', function(){
        $('div[wttype="div_goods_search_result"]').load($(this).attr('href'));
        return false;
    });

    // 选择商品
    $('div[wttype="div_goods_select"]').on('click', 'a[wttype="a_choose_goods"]', function(){
        _url = '<?php echo urlShop('store_sale_booth', 'choosed_goods');?>';
        eval('var data_str = ' + $(this).attr('data-param'));
        $.getJSON(_url, {gid : data_str.gid}, function(data){
            if (data.result == 'true') {
                // 插入数据
                $('<tr class="bd-line"></tr>')
                    .append('<td></td>')
                    .append('<td><div class="pic-thumb"><a target="_blank" href="' + data.url + '"><img src="' + data.goods_image + '"></a></div></td>')
                    .append('<td class="tl"><dl class="goods-name"><dt><a target="_blank" href="' + data.url + '">' + data.goods_name + '</a></dt><dd>' + data.gc_name + '</dd></dl></td>')
                    .append('<td>' + data.goods_price + '</td>')
                    .append('<td class="nscs-table-handle"><span><a class="btn-grapefruit" href="javascript:void(0);" data-param="{gid:'+ data.goods_id +'}" wttype="del_choosed"><i class="icon-trash"></i><p>删除</p></a></span></td>')
                    .appendTo('tbody[wttype="choose_goods_list"]');
                // 验证是否已经选择商品
                choosed_goods();
            } else {
                showError(data.msg);
            }
        });
    });

    // 删除商品
    $('tbody[wttype="choose_goods_list"]').on('click','a[wttype="del_choosed"]', function(){
        $this = $(this);
        _url = '<?php echo urlShop('store_sale_booth', 'del_choosed_goods');?>';
        eval('var data_str = ' + $(this).attr('data-param'));
        $.getJSON(_url, {gid : data_str.gid}, function(data){
            if (data.result == 'true') {
                $this.parents('tr:first').fadeOut("slow",function(){
                    $(this).remove();
                    choosed_goods();
                });
            } else {
                showError(data.msg);
            }
        });
    });
});

// 验证是否已经选择商品
function choosed_goods() {
    if ($('tbody[wttype="choose_goods_list"]').children('tr').length == 1) {
        $('tr[wttype="tr_no_sale"]').show();
    } else {
        $('tr[wttype="tr_no_sale"]').hide();
    }
}
</script>