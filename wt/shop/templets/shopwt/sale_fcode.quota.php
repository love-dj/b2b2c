<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>F码商品</h3>
        <h5>商城F码商品促销活动设置与管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>商家购买F码商品促销活动套餐列表。</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=sale_fcode&t=get_quota_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '店铺ID', name: 'store_id', width: 120, sortable: false, align: 'center'},
            {display: '店铺名称', name: 'store_name', width: 200, sortable: false, align: 'left'},
            {display: '开始时间', name: 'fcq_starttime', width: 120, sortable: false, align: 'center'},
            {display: '结束时间', name: 'fcq_endtime', width: 120, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '店铺ID', name: 'store_id'},
            {display: '店铺名称', name: 'store_name'}
        ],
        sortname: "fcq_endtime",
        sortorder: "desc",
        title: 'F码商品套餐列表'
    });
});

</script>
