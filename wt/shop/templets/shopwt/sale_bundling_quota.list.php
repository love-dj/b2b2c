<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_sale_bundling'];?></h3>
        <h5><?php echo $lang['wt_sale_bundling_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="index.php?w=sale_bundling&t=bundling_list"><?php echo $lang['bundling_list'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['bundling_quota'];?></a></li>
        <li><a href="index.php?w=sale_bundling&t=bundling_setting"><?php echo $lang['bundling_setting'];?></a></li>
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
      <li><?php echo $lang['bundling_quota_list_prompts'];?></li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=sale_bundling&t=bundling_quota_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '购买数量', name: 'bl_quota_month', width: 80, sortable: false, align: 'center'},
            {display: '开始时间', name: 'start_time_text', width: 120, sortable: false, align: 'center'},
            {display: '结束时间', name: 'end_time_text', width: 120, sortable: false, align: 'center'},
            {display: '状态', name: 'state_text', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "bl_quota_id",
        sortorder: "desc",
        title: '加价购套餐列表'
    });
});

</script>
