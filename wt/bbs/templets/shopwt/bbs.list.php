<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_manage'];?></h3>
        <h5><?php echo $lang['wt_bbs_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['wt_manage'];?></a></li>
        <li><a href="index.php?w=bbs_manage&t=bbs_verify"><?php echo $lang['bbs_wait_verify'];?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['bbs_prompts_one'];?></li>
      <li><?php echo $lang['bbs_prompts_two'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=bbs_manage&t=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '社区ID', name : 'bbs_id', width : 40, sortable : true, align: 'left'},
            {display: '社区名称', name : 'bbs_name', width : 140, sortable : true, align: 'left'},
            {display: '社区logo', name : 'bbs_img', width : 80, sortable : true, align: 'center'},
            {display: '圈主ID', name : 'bbs_masterid', width : 40, sortable : true, align: 'left'},
            {display: '圈主名称', name : 'bbs_mastername', width : 120, sortable : true, align: 'left'},
            {display: '社区状态', name : 'bbs_status', width : 150, sortable : true, align: 'left'},
            {display: '创建时间', name : 'bbs_addtime', width : 120, sortable : true, align: 'center'},
            {display: '是否推荐', name : 'is_recommend', width : 120, sortable : true, align: 'center'},
            {display: '是否热门', name : 'is_hot', width : 150, sortable : true, align: 'center'},
            {display: '成员数', name : 'bbs_mcount', width : 120, sortable : true, align: 'left'},
            {display: '话题数', name : 'bbs_thcount', width : 120, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '社区ID', name : 'bbs_id'},
            {display: '社区名称', name : 'bbs_name'},
            {display: '圈主ID', name : 'bbs_masterid'},
            {display: '圈主名称', name : 'bbs_mastername'}
            ],
        sortname: "bbs_id",
        sortorder: "desc",
        title: '社区列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=bbs_manage&t=bbs_add';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?w=bbs_manage&t=bbs_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>