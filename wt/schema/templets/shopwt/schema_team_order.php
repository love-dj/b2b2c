<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>团队订单管理</h3>
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
<style>
    .flexigrid .bDiv td div {
        height: auto;
    }
</style>
<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?act=schema_team_order&op=get_xml',
            colModel : [
                {display: '订单编号', name : 'order_sn', width : 200, sortable : true, align: 'center'},
                {display: '买家编号', name : 'buyer_id', width : 100, sortable : true, align: 'center'},
                {display: '买家姓名', name : 'buyer_name', width : 100, sortable : true, align: 'center'},
                {display: '订单价格', name : 'order_amount', width : 100, sortable : true, align: 'center'},
                {display: '推荐人编号', name : 'parent_id', width : 200, sortable : true, align: 'center'},
                {display: '推荐人姓名', name : 'parent_id', width : 200, sortable : true, align: 'center'},
                {display: '层级 / 会员名 / 佣金金额', name : 'commission', width : 300, sortable : true, align: 'left'},
                {display: '佣金状态', name : 'status', width : 200, sortable : true, align: 'center' },
            ],
            sortname: "id",
            sortorder: "asc",
            title: '团队订单管理'
        });
    });
</script>