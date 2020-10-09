<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>手机专享</h3>
        <h5>商城手机专享优惠活动设置与管理</h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="<?php echo urlAdminShop('sale_sole', 'goods_list');?>">商品列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">套餐列表</a></li>
        <li><a href="<?php echo urlAdminShop('sale_sole', 'sole_setting');?>"><?php echo $lang['wt_config'];?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>商家购买手机专享活动的列表。</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=sale_sole&t=sole_quota_list_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '开始时间', name: 'start_time_text', width: 120, sortable: false, align: 'center'},
            {display: '结束时间', name: 'end_time_text', width: 120, sortable: false, align: 'center'},
            {display: '状态', name: 'state_text', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "sole_quota_id",
        sortorder: "desc",
        title: '手机专享套餐列表'
    });
});

</script>
