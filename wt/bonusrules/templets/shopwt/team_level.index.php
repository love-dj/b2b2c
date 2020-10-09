<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>团队等级管理</h3>
        <h5>商城会员团队等级的管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li>修改相应等级参数后，会影响之后的分销数据，对之前的并无影响。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
  $(function(){
      $("#flexigrid").flexigrid({
          url: 'index.php?w=team_level&t=get_xml',
          colModel : [
          {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'},
          {display: '等级权重', name : 'level_weight', width : 100, sortable : true, align: 'center'},
          {display: '等级名称', name : 'level_name', width : 100, sortable : true, align: 'center'},
          {display: '层级奖励比例', name : 'layer_rate', width : 100, sortable : true, align: 'center'},
          {display: '提成层数', name : 'commission_layers', width : 100, sortable : true, align: 'center'},
          {display: '平级层级', name : 'same_layers', width : 100, sortable : true, align: 'center'},
          {display: '平级奖励比例', name : 'same_layer_rate', width : 100, sortable : true, align: 'center'},
          ],
          buttons : [
          {display: '<i class="fa fa-plus"></i>添加新等级', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
          {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operation }
          ],        
          sortname: "id",
          sortorder: "asc",
          title: '分销商等级列表'
      });
  });

  function fg_operation(name, bDiv) {
      if (name == 'add') {
          window.location.href = 'index.php?w=team_level&t=add_level';
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
      if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
          id = id.join(',');
      } else {
          return false;
      }
      location.href = 'index.php?w=team_level&t=del_level&del_id='+id;
  }
</script> 
