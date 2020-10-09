<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分销团队管理</h3>
        <h5>商城会员团队订单的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="相关操作时应注意，操作会影响会员的佣金">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
          <li>订单佣金如果出现发放错误，可以点击收回佣金</li>
      </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=team_order&t=get_xml',
            colModel : [
            {display: '操作', name : 'operation', width : 40, sortable : false, align: 'center', className: 'handle'},
            {display: '订单编号', name : 'order_sn', width : 150, sortable : true, align: 'center'},
            {display: '买家id', name : 'buyer_id', width : 50, sortable : true, align: 'center'},
            {display: '订单价格', name : 'order_amount', width : 80, sortable : true, align: 'center'},
            {display: '佣金基数', name : 'commission_amount', width : 80, sortable : true, align: 'center'},
            {display: '佣金来源', name : 'commission_type', width : 100, sortable : true, align: 'center'},
            {display: '计算方式', name : 'calculation_type', width : 50, sortable : true, align: 'center'},
            {display: '奖金', name : 'commission_list', width : 200, sortable : true, align: 'center'},
            {display: '订单状态', name : 'order_state', width : 100, sortable : true, align: 'center'},
            {display: '退款状态', name : 'refund_status', width : 100, sortable : true, align: 'center'},
            {display: '佣金状态', name : 'status', width : 100, sortable : true, align: 'center'},
            {display: '订单时间', name : 'order_time', width : 120, sortable : true, align: 'center'},
            {display: '发放时间', name : 'settle_time', width : 120, sortable : true, align: 'center'},
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '团队订单管理'
        });
    });
</script>