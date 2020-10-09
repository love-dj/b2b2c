<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page" style="margin-bottom:30px; ">
  <div class="fixed-bar">
    <div class="item-title">
<!--      <div class="subject">-->
<!--        <h3>区域代理管理</h3>-->
<!--          <h5>区域代理概况（区域代理总人数:--><?php //echo 18 ?><!--）</h5>-->
<!---->
<!--      </div>-->


        <div class="container" style="height:42px;">
            <a class="btn btn-primary" href="index.php?act=region_management&op=addmanagement" style="margin-bottom: 5px;border: 1px solid;padding: 5px;margin-right: 40px;background-color: rgb(196, 24, 45);color: #fff;"><i class="fa fa-plus"></i> 增加区域代理</a>

            <form style="display: inline-block;" method="post" action="index.php?act=region_management&op=region_management">
                <input class="form-control" name="member_keyword" id="" type="text" value="<?php echo $_POST['member_keyword'] ;?>" placeholder="会员ID/会员名/手机">
                <input class="form-control" name="area_keyword" id="" type="text" value="<?php echo $_POST['area_keyword'] ;?>" placeholder="区域名称">
                <select name='level_keyword' class='form-control' style="width: 141px;height:27px;" >
                    <option value=''  <?php if($_POST['level_keyword']==''){echo "selected";} ;?>>区域等级</option>
                    <option value='1' <?php if($_POST['level_keyword']==1){echo "selected";} ;?> >省</option>
                    <option value='2' <?php if($_POST['level_keyword']==2){echo "selected";} ;?>>市</option>
                    <option value='3' <?php if($_POST['level_keyword']==3){echo "selected";} ;?>>区/县</option>
                </select>
                时间范围
                <input class="form-control" name="start_time" type="date" value="<?php echo $_POST['start_time'] ;?>" style="border-radius: 4px;padding: 2px 4px;border: 1px solid;;"/>至
                <input class="form-control" name="end_time" type="date"   value="<?php echo $_POST['end_time'] ;?>"  style="border-radius: 4px;padding: 2px 4px;border: 1px solid;"/>

                <button  class="btn btn-success" style="margin-left: 17px;border-radius:11px;height: 30px"  ><i class="fa fa-search"></i> 搜索</button>
                <a href="index.php?act=region_management&op=export_agent&member_keyword=<?php echo $_POST['member_keyword'] ;?>&area_keyword=<?php echo $_POST['area_keyword'] ;?>&level_keyword=<?php echo $_POST['level_keyword'] ;?>&start_time=<?php echo $_POST['start_time'] ;?>&end_time=<?php echo $_POST['end_time'] ;?>" style="margin-left: 46px;border: 1px solid;padding: 3px;cursor: pointer">导出EXCEL</a>
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
            <th style="width: 96px;">ID</th>
            <th style="width: 135px;">会员名</th>
            <th style="width: 135px;">成为代理时间</th>
            <th >代理区域</th>
            <th >代理等级</th>
            <th >区域消费总额</th>
            <th >分红比例</th>
            <th>累计结算金额</th>
            <th>操作</th>

        </tr>

        <?php foreach($output['agent_list'] as $k=>$value){?>
            <tr class="td">
                <td><?php echo $value['member_id'] ; ?></td>
                <td><?php echo $value['account'] ; ?></td>
                <td><?php echo $value['become_agent_time'] ; ?></td>
                <td><?php echo $value['agent_area_name'] ; ?></td>
                <td><?php if($value['agent_level']==1){echo '省';}elseif($value['agent_level']==2){echo '市';}else{echo '区县';} ; ?></td>
                <td><?php echo $value['area_total'] ; ?></td>
                <td><?php echo $value['agent_dividend_rate'] ; ?></td>
                <td><?php echo $value['set_money'] ; ?></td>
                <td>
                    <a class='btn ' href="index.php?act=region_management&op=editmanagement&id=<?php echo $value['id'] ;?>  " >设置</a>
                    <a href="javascript:void 0;" style="margin-left: 30px;" onclick="fg_delete(<?php echo $value['id'] ;?>)"  ><i class='fa fa-trash-o'></i>删除</a>

                </td>
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

    }


</script>
