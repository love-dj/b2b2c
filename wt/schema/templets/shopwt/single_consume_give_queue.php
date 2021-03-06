<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page" style="margin-bottom:30px; ">
  <div class="fixed-bar">
    <div class="item-title">
        <div class="subject" >
            <h3 style="line-height:50px;">满额赠送队列</h3>
        </div>

        <div class="container">
            <form style="display: inline-block;" method="post" action="index.php?act=single_consume_give_queue">
                <input class="form-control" name="member_keyword"   type="text" value="<?php echo $_POST['member_keyword'] ;?>" placeholder="会员ID/会员名/手机">
                返还状态:
                <select name="back_state" style="width: 107px;">
                    <option value=""  >请选择</option>
                    <option value="1" <?php echo $_POST['back_state']==1?'selected':'' ;?>>返还中</option>
                    <option value="2" <?php echo $_POST['back_state']==2?'selected':'' ;?>>完成</option>
                </select>
                时间范围
                <input class="form-control" name="start_time" type="date" value="<?php echo $_POST['start_time'] ;?>" style="border-radius: 4px;padding: 2px 4px;border: 1px solid;;"/>至
                <input class="form-control" name="end_time" type="date"   value="<?php echo $_POST['end_time'] ;?>"  style="border-radius: 4px;padding: 2px 4px;border: 1px solid;"/>
                <button  class="btn btn-success" style="margin-left: 17px;border-radius:11px;height: 30px"  ><i class="fa fa-search"></i> 搜索</button>
                <a href="index.php?act=single_consume_give_queue&op=export_single_consume_return&member_keyword=<?php echo $_POST['member_keyword'] ;?>&back_state=<?php echo $_POST['back_state'] ;?>&start_time=<?php echo $_POST['start_time'] ;?>&end_time=<?php echo $_POST['end_time'] ;?>" style="margin-left: 46px;border: 1px solid;padding: 3px;cursor: pointer">导出EXCEL</a>
            </form>

        </div>

    </div>

    </div>
  </div>
<style>
    .tr th{
        font-size: 15px;
        text-align: center;
        width:216px;
        color: #555;
        font-family: "Microsoft Yahei", "Lucida Grande", Verdana, Lucida, Helvetica, Arial, sans-serif;
        line-height: 22px;
        padding:8px;
        font-weight: 800;
    }
    .td td{
        font-size: 16px;
        text-align: center;
        width:216px;
        color: #777;
        border-bottom: solid 1px #F5F5F5;
        padding: 11px 8px;
    }
    #btn{
        cursor: pointer;
        background-color: #0EB800;
        color: #fff;
        width: 47px;
        height: 29px;
        border-radius: 30px;
        padding:3px;
        font-size:13px;
    }
</style>
<div>
    <table>
        <tr class="tr" style="background-color:#cccccc38">

            <th style="width: 96px;">会员ID</th>
            <th style="width: 135px;">会员</th>
            <th style="width: 135px;">时间</th>
            <th >已返现金额</th>
            <th >未返现金额</th>
            <th >最近一次返现时间</th>
            <th>最近一次返现金额</th>
            <th>状态</th>


        </tr>

        <?php foreach($output['list'] as $k=>$value){?>
            <tr class="td frozen">

                <td><?php echo $value['member_id'] ; ?></td>
                <td><?php echo $value['buyer_name'] ; ?></td>
                <td style="font-size: 14px;"><?php echo date('Y-m-d H:i:s',$value['add_time']) ; ?></td>

                <td><?php echo $value['gived_money'].'元';?></td>
                <td><?php echo $value['no_gived_money'].'元';?></td>


                <td style="font-size: 14px;"><?php echo empty($value['recent_time'])?'':date('Y-m-d H:i:s',$value['recent_time']) ; ?></td>
                <td><?php echo $value['recent_money'] ; ?></td>

                <?php
                    if($value['status']==1){
                        echo '<td style="color:#fff;"><span style="border:1px solid;background-color: #3bb154;padding: 3px;font-size: 13px;">返还中</span></td>';
                    }elseif($value['status']==2){
                        echo '<td style="color: #fff;"><span style="border:1px solid;background-color: #fa0808;padding: 3px;font-size: 13px;">完成</span></td>';
                    }

                ;?>
             <!--   <td>
                    <a href="javascript:void 0;" style="margin-left: 30px;" onclick="fg_delete(<?php echo $value['id'] ;?>)"  ><i class='fa fa-trash-o'></i>删除权益</a>

                </td>
                -->
   <?PHP  } ?>


        </tr>
    </table>
</div>

<!--<div style="margin-top: 10px;">-->
<!--<button type="button" id="btn"  >冻结</button>-->
<!--</div>-->

<div class="pagination" style="margin-top: 11px;">
    <?php echo $output['page'] ;?>
<!--    --><?php //echo $output['count'] ?>
</div>


    <div id="flexigrid"  ></div>

<script type="text/javascript">


    $('#btn').click(function (){
        var trans = new Array;
        $('.frozen').each(function(){
            var _this = $(this).find('td:first').find("input[type='checkbox']");
             if(_this.is(':checked')){
                 trans.push(_this.val());
             }
        });
        if(trans.length==0)return;
        var str = trans.join(',');
        console.log(str);

        if(confirm('您确认要冻结这些选项吗？')){
            $.post("index.php?act=full_amount_give_queue&op=forzen",{data:str},function(e){
                if(e.status==1){
                    window.location.reload();
                }
            },'json');

        } else {
            return false;
        }



    });

    function fg_delete(id) {

        if(confirm('删除后将不能恢复，确认删除这1项吗？')){
            location.href = 'index.php?act=full_amount_give_queue&op=del_queue&id='+id;
        } else {
            return false;
        }

    }






</script>
