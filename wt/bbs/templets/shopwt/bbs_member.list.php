<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_membermanage'];?></h3>
        <h5><?php echo $lang['wt_bbs_membermanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['bbs_member_prompts_one'];?></li>
      <li><?php echo $lang['bbs_member_prompts_two'];?></li>
      <li><?php echo $lang['bbs_member_prompts_three'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div> 
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=bbs_member&t=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '成员ID', name : 'member_id', width : 40, sortable : true, align: 'left'},
            {display: '成员名称', name : 'member_name', width : 150, sortable : true, align: 'left'},
            {display: '社区ID', name : 'bbs_id', width : 40, sortable : true, align: 'left'},
            {display: '社区名称', name : 'bbs_name', width : 120, sortable : true, align: 'left'},
            {display: '是否推荐', name : 'is_recommend', width : 120, sortable : true, align: 'left'},
            {display: '加入时间', name : 'cm_jointime', width : 150, sortable : true, align: 'left'},
            {display: '成员身份', name : 'is_identity', width : 120, sortable : true, align: 'left'},
            {display: '明星成员', name : 'is_star', width : 120, sortable : true, align: 'left'},
            {display: '主题数', name : 'cm_thcount', width : 150, sortable : true, align: 'left'},
            {display: '最后发言', name : 'cm_lastspeaktime', width : 120, sortable : true, align: 'left'},
            {display: '明星成员', name : 'is_star', width : 120, sortable : true, align: 'left'},
            {display: '发言', name : 'is_allowspeak', width : 120, sortable : true, align: 'left'}
            ],
        searchitems : [
            {display: '成员ID', name : 'member_id'},
            {display: '成员名称', name : 'member_name'},
            {display: '社区ID', name : 'bbs_id'},
            {display: '社区名称', name : 'bbs_name'}
            ],
        sortname: "cm_jointime",
        sortorder: "asc",
        title: '社区成员列表'
    });
});

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?w=bbs_member&t=member_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}

function fg_recommend(id, value) {
    $.getJSON('index.php?w=bbs_member&t=member_recommend', {id:id, value:value}, function(data){
        if (data.state) {
            $("#flexigrid").flexReload();
        } else {
            showError(data.msg)
        }
    });
}
</script>