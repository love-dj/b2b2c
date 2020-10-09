<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>区域代理管理</h3>
        <h5>商城会员区域代理身份的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li>撤掉会员代理身份后，以前已经发放的奖金不会受影响。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
  $(function(){
      $("#flexigrid").flexigrid({
          url: 'index.php?w=agent_user&t=get_xml',
          colModel : [
          {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'},
          {display: '会员ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
          {display: '会员姓名', name : 'member_truename', width : 100, sortable : true, align: 'center'},
          {display: '联系方式', name : 'member_mobile', width : 100, sortable : true, align: 'center'},
          {display: '状态', name : 'agent_check', width : 80, sortable : true, align: 'center'},
          {display: '代理申请时间', name : 'agent_apply_time', width : 120, sortable : true, align: 'center'},
          {display: '代理审核时间', name : 'agent_check_time', width : 120, sortable : true, align: 'center'},
          {display: '代理区域', name : 'area_name', width : 120, sortable : true, align: 'center'},
          {display: '提成比例', name : 'agent_rate', width : 80, sortable : true, align: 'center'},
          {display: '未结算提成', name : 'area_remain_commission', width : 100, sortable : true, align: 'center'},
          {display: '累计提成', name : 'area_commission', width : 100, sortable : true, align: 'center'},
          {display: '区域总消费金额', name : 'area_cost_money', width : 100, sortable : true, align: 'center'},
          ],
          buttons : [
          {display: '<i class="fa fa-plus"></i>添加新区域代理', name : 'add', bclass : 'add', title : '新增区域代理', onpress : fg_operation },
          {display: '<i class="fa fa-trash"></i>批量撤销区域代理身份', name : 'del', bclass : 'del', title : '将选定行数据批量操作', onpress : fg_operation }
          ],        
          sortname: "id",
          sortorder: "asc",
          title: '区域代理列表'
      });
  });

  function fg_operation(name, bDiv) {
      if (name == 'add') {
          window.location.href = 'index.php?w=agent_user&t=add_user';
      } else if (name == 'del') {
          if ($('.trSelected', bDiv).length == 0) {
              showError('请选择要操作的数据项！');
          }
          var itemids = new Array();
          $('.trSelected', bDiv).each(function(i){
              itemids[i] = $(this).attr('data-id');
          });
          fg_delete(itemids);
      }
  }

  function fg_delete(id) {
      if (typeof id == 'number') {
          var id = new Array(id.toString());
      };
      if(confirm('撤销后将不能恢复，确认撤销这 ' + id.length + ' 项吗？')){
          id = id.join(',');
      } else {
          return false;
      }
      location.href = 'index.php?w=agent_user&t=remove_user&del_id='+id;
  }
</script> 
