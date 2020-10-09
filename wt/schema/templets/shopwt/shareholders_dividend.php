<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page" style="margin-bottom:30px; ">
  <div class="fixed-bar">
    <div class="item-title">
<!--      <div class="subject">-->
<!--        <h3>区域代理管理</h3>-->
<!--          <h5>区域代理概况（区域代理总人数:--><?php //echo 18 ?><!--）</h5>-->
<!---->
<!--      </div>-->
        <button type="button" id="jiazai">加载</button>
        <div class="container">
            <form style="display: inline-block;" method="post" action="index.php?act=region_management&op=search">
                <input class="form-control" name="member_keyword" id="" type="text" value="" placeholder="会员ID/会员名/手机">
                <input class="form-control" name="area_keyword" id="" type="text" value="" placeholder="区域名称">
                <select name='level_keyword' class='form-control' style="width: 141px;height:27px;">
                    <option value=''>区域等级</option>
                    <option value='1' >省</option>
                    <option value='2' >市</option>
                    <option value='3' >区/县</option>
                </select>
                时间范围
                <input class="form-control" name="start_time" type="date" value=" " style="border-radius: 4px;padding: 2px 4px;border: 1px solid;;"/>至
                <input class="form-control" name="end_time" type="date" value=" "  style="border-radius: 4px;padding: 2px 4px;border: 1px solid;"/>

                <button type="button" class="btn btn-success" style="margin-left: 17px;border-radius:11px;height: 40px"  ><i class="fa fa-search"></i> 搜索</button>
                <a style="margin-left: 46px;border: 1px solid;padding: 3px;cursor: pointer">导出EXCEL</a>
            </form>
<!--            <a class="btn btn-primary" href="index.php?act=region_management&op=addmanagement" style="margin-bottom: 5px;border: 1px solid;padding: 5px;margin-left: 356px;"><i class="fa fa-plus"></i> 增加区域代理</a>-->

        </div>

    </div>

    </div>
  </div>


<div id="flexigrid"  ></div>

<script type="text/javascript">
    $(function(){

        $("#flexigrid").flexigrid({
            url: 'index.php?act=region_management&op=get_xml',
            colModel : [
                {display: 'ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
                {display: '会员名', name : 'account', width : 120, sortable : true, align: 'center'},
//                {display: '姓名/手机', name : 'member_mobile', width : 200, sortable : true, align: 'center'},
                {display: '成为代理时间', name : 'become_agent_time', width : 119, sortable : true, align: 'center'},
                {display: '代理区域', name : 'agent_area_name', width : 200, sortable : true, align: 'center'},
                {display: '代理等级', name : 'agent_level', width : 200, sortable : true, align: 'center'},
                {display: '区域消费总额', name : 'area_total', width : 200, sortable : true, align: 'center' },
                {display: '分红比例', name : 'agent_dividend_rate', width : 200, sortable : true, align: 'center' },
                {display: '累计结算金额', name : 'set_money', width : 200, sortable : true, align: 'center' },
//                {display: '已结算分红佣金', name : 'distribute_total', width : 200, sortable : true, align: 'center' }
//                {display: '未结算分红佣金', name : 'distribute_total', width : 200, sortable : true, align: 'center' }
                {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'},

            ],
            buttons : [
                {display: '<i class="fa fa-plus"></i>新增区域代理', name : 'add', bclass : 'add', title : '新增数据',onpress : fg_operation  }

            ],
            sortname: "id",
            sortorder: "asc",
            title: '区域代理管理'
        });



    });

    function fg_operation(name, bDiv) {

        if (name == 'add') {
            window.location.href = 'index.php?act=region_management&op=addmanagement';
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
        location.href = 'index.php?act=region_management&op=del_management&id='+id;
    }



    function search_agent(){
        var member_keyword = $('[name="member_keyword"]').val();
        var area_keyword = $('[name="area_keyword"]').val();
        var level_keyword = $('[name="level_keyword"]').val();
        var start_time = $('[name="start_time"]').val();
        var end_time = $('[name="end_time"]').val();

        console.log(member_keyword,area_keyword,level_keyword,start_time,end_time);
        $("#flexigrid").flexigrid({
            url: 'index.php?act=region_management&op=get_xml',
            colModel : [
                {display: 'ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
                {display: '会员名', name : 'account', width : 120, sortable : true, align: 'center'},

                {display: '成为代理时间', name : 'become_agent_time', width : 119, sortable : true, align: 'center'},
                {display: '代理区域', name : 'agent_area_name', width : 200, sortable : true, align: 'center'},
                {display: '代理等级', name : 'agent_level', width : 200, sortable : true, align: 'center'},
                {display: '区域消费总额', name : 'area_total', width : 200, sortable : true, align: 'center' },
                {display: '分红比例', name : 'agent_dividend_rate', width : 200, sortable : true, align: 'center' },
                {display: '累计结算金额', name : 'set_money', width : 200, sortable : true, align: 'center' },

                {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'}

            ],
            buttons : [
                {display: '<i class="fa fa-plus"></i>新增区域代理', name : 'add', bclass : 'add', title : '新增数据',onpress : fg_operation  }

            ],
            sortname: "id",
            sortorder: "asc",
            title: '区域代理管理'
        });
    }

//
//    $('#jiazai').click(function(){
//
//        $("#flexigrid").flexigrid({
//
//            url: 'index.php?act=region_management&op=get_xml',
//            colModel : [
//                {display: 'ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
//                {display: '会员名', name : 'account', width : 120, sortable : true, align: 'center'},
//
//                {display: '成为代理时间', name : 'become_agent_time', width : 119, sortable : true, align: 'center'},
//                {display: '代理区域', name : 'agent_area_name', width : 200, sortable : true, align: 'center'},
//                {display: '代理等级', name : 'agent_level', width : 200, sortable : true, align: 'center'},
//                {display: '区域消费总额', name : 'area_total', width : 200, sortable : true, align: 'center' },
//                {display: '分红比例', name : 'agent_dividend_rate', width : 200, sortable : true, align: 'center' },
//                {display: '累计结算金额', name : 'set_money', width : 200, sortable : true, align: 'center' },
//
//                {display: '操作', name : 'operation', width : 200, sortable : false, align: 'center', className: 'handle'}
//
//            ],
//            buttons : [
//                {display: '<i class="fa fa-plus"></i>新增区域代理', name : 'add', bclass : 'add', title : '新增数据',onpress : fg_operation  }
//
//            ],
//            sortname: "id",
//            sortorder: "asc",
//            title: '区域代理管理'
//        });
//
//
//
//    });
</script>
