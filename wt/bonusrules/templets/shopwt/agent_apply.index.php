<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>区域代理申请管理</h3>
        <h5>商城会员区域代理申请的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="相关操作时应注意，操作会影响会员的代理申请">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?w=agent_apply&t=get_xml',
            colModel : [
            {display: '操作', name : 'operation', width : 40, sortable : false, align: 'center', className: 'handle'},
            {display: '会员id', name : 'member_id', width : 50, sortable : true, align: 'center'},
            {display: '代理等级', name : 'agent_level', width : 80, sortable : true, align: 'center'},
            {display: '申请区域', name : 'area_info', width : 80, sortable : true, align: 'center'},
            {display: '用户手机号码', name : 'member_mobile', width : 100, sortable : true, align: 'center'},
            {display: '申请说明', name : 'remark', width : 200, sortable : true, align: 'center'},
            {display: '审核状态', name : 'status', width : 50, sortable : true, align: 'center'},
            {display: '申请时间', name : 'createtime', width : 150, sortable : true, align: 'center'},
            {display: '审核时间', name : 'updatetime', width : 150, sortable : true, align: 'center'}
            ],         
            sortname: "id",
            sortorder: "desc",
            title: '区域代理申请管理'
        });
    });
</script>