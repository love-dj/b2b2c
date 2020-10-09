<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['schema_order'];?></h3>
    </div>
</div>
</div>
<div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
      <ul>
          <li></li>
      </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?act=schema_order&op=get_xml',
            colModel : [
            {display: '订单编号', name : 'order_sn', width : 150, sortable : true, align: 'center'},
            {display: '买家id', name : 'buyer_id', width : 100, sortable : true, align: 'center'},
            {display: '订单价格', name : 'order_amount', width : 100, sortable : true, align: 'center'},
            {display: '推荐人', name : 'parent_id', width : 100, sortable : true, align: 'center'},
            {display: '推荐人姓名', name : 'parent_name', width : 100, sortable : true, align: 'center'},
            {display: '分销层级', name : 'commission_rate', width : 100, sortable : true, align: 'center'},
            {display: '佣金比例', name : 'commission_ratio', width : 100, sortable : true, align: 'center'},
            {display: '佣金金额', name : 'commission', width : 100, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '分销订单管理'
        });
    });
</script>