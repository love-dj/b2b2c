<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['nc_schema_team_manage'];?></h3>
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
            url: 'index.php?act=schema_team_manage&op=get_xml',
            colModel : [
                {display: '用户编号', name : 'mid', width : 100, sortable : true, align: 'center'},
                {display: '会员昵称', name : 'nickname', width : 200, sortable : true, align: 'center'},
                {display: '真实姓名', name : 'truename', width : 200, sortable : true, align: 'center'},
                {display: '推荐人编号', name : 'parent_id', width : 100, sortable : true, align: 'center'},
                {display: '推荐人姓名', name : 'parent_name', width : 100, sortable : true, align: 'center'},
                {display: '团队等级', name : 'level_name', width : 200, sortable : true, align: 'center'},
                {display: '累计佣金', name : 'distribute_total', width : 200, sortable : true, align: 'center' }
            ],
            sortname: "id",
            sortorder: "asc",
            title: '团队会员管理'
        });
    });
</script>