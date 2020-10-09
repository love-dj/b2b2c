<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page" >


    <div id="flexigrid"  ></div>

    <script type="text/javascript">
        $(function(){

            $("#flexigrid").flexigrid({
                url: 'index.php?act=region_fenhong_record&op=get_xml',
                colModel : [
                    {display: '会员ID', name : 'member_id', width : 70, sortable : true, align: 'center'},
                    {display: '会员名', name : 'account', width : 100, sortable : true, align: 'center'},

                    {display: '手机', name : 'phone', width : 119, sortable : true, align: 'center'},
                    {display: '代理区域', name : 'agent_area_name', width : 200, sortable : true, align: 'center'},
                    {display: '代理等级', name : 'agent_level', width : 70, sortable : true, align: 'center'},

                    {display: '时间', name : 'add_time', width : 200, sortable : true, align: 'center' },
                    {display: '订单号', name : 'order_sn', width : 200, sortable : true, align: 'center' },
                    {display: '订单金额', name : 'order_goods_amount', width : 100, sortable : true, align: 'center' },
                    {display: '分红比例', name : 'agent_dividend_rate', width : 100, sortable : true, align: 'center' },

                    {display: '分红金额', name : 'money', width : 200, sortable : true, align: 'center' },
                    {display: '分红状态', name : 'status', width : 100, sortable : true, align: 'center' }


//                    {display: '审核状态', name : 'chk_status', width : 200, sortable : true, align: 'center' },
//                    {display: '操作', name : 'distribute_total', width : 200, sortable : true, align: 'center' }

                ],
                sortname: "id",
                sortorder: "asc",
                title: '区域分红记录'
            });
        });
    </script>
