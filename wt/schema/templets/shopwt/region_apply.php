<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style>
    .aa{
        border:1px solid;
        background-color: #0EB800;
        color: #fff;
    }
    .bb{

    }
    .cc{
        border:1px solid;
        background-color: #fa0808;
        color: #fff;
    }

</style>
<div class="page" >
<!--    <div class="fixed-bar">-->
<!--        <div class="item-title">-->
<!--            <div class="subject">-->
<!--                <h3>区域代理申请</h3>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<div id="flexigrid"  ></div>

<script src="\admin\resource\js\layer\layer.js"></script>
<script type="text/javascript">
    $(function(){

        $("#flexigrid").flexigrid({
            url: 'index.php?act=region_apply&op=get_xml',
            colModel : [

                {display: '会员ID', name : 'member_id', width : 100, sortable : true, align: 'center'},
                {display: '会员名', name : 'account', width : 200, sortable : true, align: 'center'},

                {display: '姓名', name : 'truename', width : 200, sortable : true, align: 'center'},

                {display: '手机', name : 'phone', width : 119, sortable : true, align: 'center'},
                {display: '申请区域', name : 'agent_area_name', width : 200, sortable : true, align: 'center'},
                {display: '申请等级', name : 'agent_level', width : 200, sortable : true, align: 'center'},
                {display: '申请时间', name : 'add_time', width : 200, sortable : true, align: 'center' },
                {display: '审核状态', name : 'chk_status', width : 200, sortable : true, align: 'center' },

                {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'}

            ],

//            searchitems : [
//                {display: '操作人', name : 'admin_name'},
//                {display: '操作内容', name : 'content'}
//            ],

            sortname: "id",
            sortorder: "asc",
            title: '区域代理管理'
        });
    });

    function check_(id){

        var url="index.php?act=region_apply&op=check_apply";
        layer.confirm('请您给出审核结果？', {
            btn: ['通过','不通过'] //按钮
        }, function(){ //通过
            $.post(url,{id:id,status:1},function(e){
                if(e.status===1){
                    layer.msg(e.msg,{icon:1});
                    window.location.reload();
                }else{
                    layer.msg(e.msg,{icon:2});
                }
            },'json')
        }, function(){ //不通过
            $.post(url,{id:id,status:2},function(e){
                if(e.status===1){
                    layer.msg(e.msg,{icon:1});
                    window.location.reload();
                }else{
                    layer.msg(e.msg,{icon:2});

                }
            },'json')
        });
    }

function del(id){

    var url="index.php?act=region_apply&op=del";
    layer.confirm('您确定删除？', {
        btn: ['确定','取消'] //按钮
    }, function(){ //确定
        $.post(url,{id:id},function(e){
            if(e.status===1){
                layer.msg(e.msg,{icon:1});
                window.location.reload();
            }else{
                layer.msg(e.msg,{icon:2});
            }
        },'json')
    });





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
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?act=admin_log&op=list_del",
            data: "del_id="+id,
            success: function(data){
                if (data.state){
                    $("#flexigrid").flexReload();
                } else {
                    alert(data.msg);
                }
            }
        });
    }




</script>