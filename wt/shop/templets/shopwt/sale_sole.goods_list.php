<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
<?php if ($output['store_info']) { ?>
      <a class="back" href="index.php?w=sale_sole&t=sole_quota_list" title="返回套餐列表">
        <i class="fa fa-arrow-bbs-o-left"></i>
      </a>
      <div class="subject">
        <h3>手机专享 - 查看/管理店铺“<?php echo $output['store_info']['store_name']; ?>”参与活动的商品</h3>
        <h5>商城手机专享优惠活动设置与管理</h5>
      </div>
<?php } else { ?>
      <div class="subject">
        <h3>手机专享</h3>
        <h5>商城手机专享优惠活动设置与管理</h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="JavaScript:void(0);" class="current">商品列表</a></li>
        <li><a href="<?php echo urlAdminShop('sale_sole', 'sole_quota_list');?>">套餐列表</a></li>
        <li><a href="<?php echo urlAdminShop('sale_sole', 'sole_setting');?>"><?php echo $lang['wt_config'];?></a></li>
      </ul>
<?php } ?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>该促销为移动端（Wap、Android，IOS）专门享有</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=sale_sole&t=goods_list_xml&store_id=<?php echo $output['store_id']; ?>',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '商品SKU', name: 'goods_id', width: 400, sortable: false, align: 'left'},
            {display: '商品名称', name: 'goods_name', width: 400, sortable: false, align: 'left'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '商品分类', name: 'goods_name', width: 100, sortable: false, align: 'left'},
            {display: '商品图片', name: 'goods_img_url', width: 80, sortable: false, align: 'center'},
            {display: '商品价格', name: 'goods_price', width: 80, sortable: false, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }

                    var href = '<?php echo urlAdminShop('sale_sole', 'del_goods', array(
                        'goods_id' => '__IDS__',
                    )); ?>'.replace('__IDS__', ids.join(','));

                    $.getJSON(href, function(d) {
                        if (d && d.result) {
                            $("#flexigrid").flexReload();
                        } else {
                            alert(d && d.message || '操作失败！');
                        }
                    });
                }
            }
        ],
        searchitems: [
            {display: '商品SKU', name: 'goods_id', isdefault: true}
        ],
        sortname: "goods_id",
        sortorder: "desc",
        title: '手机专享商品列表'
    });
});

$('a[data-href]').live('click', function() {
    if ($(this).hasClass('confirm-del-on-click') && !confirm('确定删除?')) {
        return false;
    }

    $.getJSON($(this).attr('data-href'), function(d) {
        if (d && d.result) {
            $("#flexigrid").flexReload();
        } else {
            alert(d && d.message || '操作失败！');
        }
    });
});

</script>
