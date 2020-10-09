<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/main.css" rel="stylesheet" type="text/css">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?w=mb_redpacket"><span><?php echo $lang['wt_manage'];?></span></a></li>
        <li><a href="index.php?w=mb_redpacket&t=new"><span><?php echo $lang['wt_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['wt_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['mb_redpacket']['id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_name">红包名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_name" name="packet_name" class="txt" value="<?php echo $output['mb_redpacket']['packet_name']; ?>"></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation" >开始时间:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="start_time" name="start_time" class="txt" value="<?php echo date('Y-m-d',$output['mb_redpacket']['start_time']); ?>"/></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" >结束时间:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="end_time" name="end_time" class="txt" value="<?php echo date('Y-m-d',$output['mb_redpacket']['end_time']); ?>"/></td>
          <td class="vatop tips"></td>
        </tr> 
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_number">红包数量:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_number" name="packet_number" class="txt" value="<?php echo $output['mb_redpacket']['packet_number']; ?>" readonly></td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_amount">红包总金额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_amount" name="packet_amount" class="txt" value="<?php echo $output['mb_redpacket']['packet_amount']; ?>" readonly></td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="win_rate">中奖机率:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="win_rate" name="win_rate" class="txt" value="<?php echo $output['mb_redpacket']['win_rate']; ?>"></td>
        </tr>
		<!--tr class="noborder">
          <td colspan="2"><label>活动描述:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="packet_descript" style="width:500px;height:250px;"><?php echo $output['mb_redpacket']['packet_descript']; ?></textarea></td>
		  <td class="vatop tips">单条活动描述结束后请换行</td>
        </tr-->
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['wt_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#add_form").valid()){
     $("#add_form").submit();
	}
	});
});
$(document).ready(function(){
	$("#start_time").datepicker({dateFormat: 'yy-mm-dd'});
	$("#end_time").datepicker({dateFormat: 'yy-mm-dd'});
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
        	packet_name: {
        		required : true
        	},
        	start_time: {
        		required : true,
				date      : false
        	},
        	end_time: {
        		required : true,
				date      : false
        	},
        	packet_number: {
        		required: true,
				number  : true,
				min     : 1
			},
			packet_amount:{
				required : true,
				number: true,
        		min:1
			},
        	win_rate: {
        		required : true,
				number: true,
        		min:1,
        		max:100
        	}
        },
        messages : {
        	packet_name: {
        		required : '红包名称不能为空'
        	},
        	start_time: {
        		required : '开始时间不能为空'
        	},
        	end_time: {
        		required : '结束时间不能为空'
        	},
			packet_number: {
        		required : '红包数量不能为空',
				number   : '红包数量必须为数字',
				min      : '红包数量最小为1个'
			},
			packet_amount:{
				required : '红包总金额不能为空',
				number: '红包总金额必须为数字',
        		min:'红包总金额最小为1',
			},
        	win_rate: {
        		required : '中奖机率不能为空',
				number: '中奖机率必须为数字',
        		min:'中奖机率最小为1',
        		max:'中奖机率最大为100'
        	}
        }
	});
});
</script>