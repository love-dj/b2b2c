<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/main.css" rel="stylesheet" type="text/css">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?w=mb_redpacket"><span><?php echo $lang['wt_manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['wt_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_name">红包名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_name" name="packet_name" class="txt"></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation" >开始时间:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="start_time" name="start_time" class="txt"/></td>
          <td class="vatop tips">请输入活动开始时间</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" >结束时间:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="end_time" name="end_time" class="txt"/></td>
          <td class="vatop tips">请输入活动结束时间</td>
        </tr> 
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_number">红包数量:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_number" name="packet_number" class="txt"></td>
		  <td class="vatop tips">为了服务器更高效率生成红包，请输入10的整数，例如：10、20、50、100、200，建议单次生成红包不要超过1000</td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="packet_amount">红包总金额:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="packet_amount" name="packet_amount" class="txt"></td>
		  <td class="vatop tips">为了服务器更高效率生成红包，请输入10的整数，例如：10、20、50、100、200</td>
        </tr>
		<!--tr>
          <td colspan="2" class="required"><label>红包指定有效期:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="valid_date" name="valid_date" class="txt date"/></td>
          <td class="vatop tips">指定某一日期为红包的有效期<span style="color:red;">(与“红包有效期”只能二选一)</span></td>
        </tr> 
		<tr>
          <td colspan="2" class="required"><label>红包有效期:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="valid_date2" name="valid_date2" style="width:200px"/> 天内</td>
          <td class="vatop tips">自红包领取后指定时间内有效，1为领取当天有效<span style="color:red;">(与“红包指定有效期”只能二选一)</span></td>
        </tr--> 
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="win_rate">中奖机率:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="win_rate" name="win_rate" class="txt" value="100"></td>
        </tr>
		<!--tr class="noborder">
          <td colspan="2"><label>活动描述:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="packet_descript" style="width:500px;height:250px;"></textarea></td>
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
	//$("#valid_date").datepicker({dateFormat: 'yy-mm-dd'});
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
			/* valid_date: {
				date      : false
        	},
			valid_date2: {
				number: true
        	}, */
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
		/* 	valid_date:{
			},
			valid_date2: {
				number: '该有效期必须为数字',
        	}, */
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