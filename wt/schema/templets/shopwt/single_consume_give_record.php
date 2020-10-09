<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page" style="margin-bottom:30px; ">
  <div class="fixed-bar">
    <div class="item-title">
        <div class="subject" >
            <h3 style="line-height:50px;">满额赠送记录</h3>
        </div>

        <div class="container">
            <form style="display: inline-block;" method="post" action="index.php?act=single_consume_give_record">
                <input class="form-control" name="member_keyword" id="" type="text" value="<?php echo $_POST['member_keyword'] ;?>" placeholder="会员ID/会员名/手机">
                时间范围
                <input class="form-control" name="start_time" type="date" value="<?php echo $_POST['start_time'] ;?>" style="border-radius: 4px;padding: 2px 4px;border: 1px solid;;"/>至
                <input class="form-control" name="end_time" type="date"   value="<?php echo $_POST['end_time'] ;?>"  style="border-radius: 4px;padding: 2px 4px;border: 1px solid;"/>
                <button  class="btn btn-success" style="margin-left: 17px;border-radius:11px;height: 30px"  ><i class="fa fa-search"></i> 搜索</button>
                <a href="index.php?act=single_consume_give_record&op=export_single_consume_record&member_keyword=<?php echo $_POST['member_keyword'] ;?>&start_time=<?php echo $_POST['start_time'] ;?>&end_time=<?php echo $_POST['end_time'] ;?>" style="margin-left: 46px;border: 1px solid;padding: 3px;cursor: pointer">导出EXCEL</a>
            </form>

        </div>

    </div>

    </div>
  </div>
<style>
    .tr th{
        font-size: 15px;
        text-align: center;
        width:460px;
        color: #555;
        font-family: "Microsoft Yahei", "Lucida Grande", Verdana, Lucida, Helvetica, Arial, sans-serif;
        line-height: 22px;
        padding:8px;
        font-weight: 800;
    }
    .td td{
        font-size: 13px;
        text-align: center;
        width:216px;
        color: #777;
        border-bottom: solid 1px #F5F5F5;
        padding: 11px 8px;
    }
</style>
<div>
    <table>
        <tr class="tr" style="background-color:#cccccc38">
            <th style="width: 270px;">ID</th>
            <th style="width: 300px;">会员</th>
            <th style="width: 270px;">时间</th>
            <th style="width: 400px;">返现方式</th>
<!--            <th style="width: 400px;">每期返现比例</th>-->
            <th style="width: 400px;">本期赠送金额</th>

<!--            <th>操作</th>-->

        </tr>

        <?php foreach($output['list'] as $k=>$value){?>
            <tr class="td">
                <td><?php echo $value['member_id'] ; ?></td>
                <td><?php echo $value['buyer_name'] ; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$value['add_time']) ; ?></td>
                <td><?php echo $value['give_type']==1?'递减返现':'等比例返现' ; ?> </td>
                <td><?php echo $value['give_money'].'元' ; ?></td>
               <!-- <td>
                    <a class='btn ' href="index.php?act=region_management&op=editmanagement&id=<?php echo $value['id'] ;?>  " >设置</a>
                    <a href="javascript:void 0;" style="margin-left: 30px;" onclick="fg_delete(<?php echo $value['id'] ;?>//)"  ><i class='fa fa-trash-o'></i>删除</a>

                </td>
                -->
   <?PHP  } ?>


        </tr>
    </table>
</div>

<div class="pagination" style="margin-top: 11px;">
    <?php echo $output['page'] ;?>
<!--    --><?php //echo $output['count'] ?>
</div>


    <div id="flexigrid"  ></div>

<script type="text/javascript">



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



     


</script>
