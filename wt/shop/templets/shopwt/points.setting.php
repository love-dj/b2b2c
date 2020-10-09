<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_member_pointsmanage']?></h3>
        <h5><?php echo $lang['wt_member_pointsmanage_subhead']?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="index.php?w=points&t=pointslog"><?php echo $lang['admin_points_log_title']?></a></li>
        <li><a href="JavaScript:void(0);" class="current">规则设置</a></li>
        <li><a href="index.php?w=points&t=addpoints">积分增减</a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <div class="title">
        <h3>会员日常获取积分设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_reg']; ?></dt>
        <dd class="opt">
          <input id="points_reg" name="points_reg" value="<?php echo $output['list_setting']['points_reg'];?>" class="input-txt" type="text">
        </dd>
      </dl>	  
	    <dl class="row">
            <dt class="tit">注册防刷: </dt>
               <dd class="opt">
			   <div class="onoff">				
				<label for="points_ip_reg_isuse_1" class="cb-enable <?php if($output['list_setting']['points_ip_reg_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
                    <label for="points_ip_reg_isuse_0" class="cb-disable <?php if($output['list_setting']['points_ip_reg_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
                    <input id="points_ip_reg_isuse_1" name="points_ip_reg_isuse" <?php if($output['list_setting']['points_ip_reg_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
                    <input id="points_ip_reg_isuse_0" name="points_ip_reg_isuse" <?php if($output['list_setting']['points_ip_reg_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
				</div>
				<p class="notic">开启积分注册防刷，单个ip一天只能获得一次注册积分。</p>
               </dd>
        </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_login'];?></dt>
        <dd class="opt">
          <input id="points_login" name="points_login" value="<?php echo $output['list_setting']['points_login'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_comments']; ?></dt>
        <dd class="opt">
          <input id="points_comments" name="points_comments" value="<?php echo $output['list_setting']['points_comments'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <div class="title">
        <h3>会员<?php echo $lang['points_number_order']; ?>时积分获取设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_orderrate'];?></dt>
        <dd class="opt">
          <input id="points_orderrate" name="points_orderrate" value="<?php echo $output['list_setting']['points_orderrate'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_orderrate_tip']; ?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_ordermax']; ?></dt>
        <dd class="opt">
          <input id="points_ordermax" name="points_ordermax" value="<?php echo $output['list_setting']['points_ordermax'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_ordermax_tip'];?></p>
        </dd>
      </dl>
	  
       <dl class="row">
        <dt class="tit">
          <label for="points_invite">邀请注册</label>
        </dt>
        <dd class="opt">
          <input id="points_invite" name="points_invite" value="<?php echo $output['list_setting']['points_invite'];?>" class="w60" type="text" /><i></i>
          <p class="notic">邀请非会员注册时给邀请人的积分数</p>
        </dd>
      </dl>
             <!--dl class="row">
        <dt class="tit">
          <label for="points_rebate">返利比例</label>
        </dt>
        <dd class="opt">
          <input id="points_rebate" name="points_rebate" value="<?php echo $output['list_setting']['points_rebate'];?>" class="w60" type="text" /><i>%</i>
          <p class="notic">被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</p>
        </dd>
      </dl-->
	<dl class="row">
		<dt class="tit">邀请会员消费返积分: </dt>
		   <dd class="opt">
		   <div class="onoff">				
			<label for="invite_points_isuse_1" class="cb-enable <?php if($output['list_setting']['invite_points_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
				<label for="invite_points_isuse_0" class="cb-disable <?php if($output['list_setting']['invite_points_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
				<input id="invite_points_isuse_1" name="invite_points_isuse" <?php if($output['list_setting']['invite_points_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
				<input id="invite_points_isuse_0" name="invite_points_isuse" <?php if($output['list_setting']['invite_points_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<p class="notic">开启后，邀请人可以获得邀请的会员购物消费所获得积分的百分比的积分。</p>
		   </dd>
	</dl>
	  <dl class="row">
        <dt class="tit">一级邀请人返积分比例</dt>
        <dd class="opt">
          <input id="invite_points_one" name="invite_points_one" value="<?php echo $output['list_setting']['invite_points_one'];?>" class="txt" type="text" style="width:60px;">%
          <p class="notic">被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</p>
        </dd>
      </dl>
	        <dl class="row">
        <dt class="tit">二级邀请人返积分比例</dt>
        <dd class="opt">
          <input id="invite_points_two" name="invite_points_two" value="<?php echo $output['list_setting']['invite_points_two'];?>" class="txt" type="text" style="width:60px;">%
          <p class="notic">被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</p>
        </dd>
      </dl>
	        <dl class="row">
        <dt class="tit">三级邀请人返积分比例</dt>
        <dd class="opt">
          <input id="invite_points_three" name="invite_points_three" value="<?php echo $output['list_setting']['invite_points_three'];?>" class="txt" type="text" style="width:60px;">%
          <p class="notic">被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</p>
        </dd>
      </dl>
	  <div class="title">
        <h3>会员积分抵现设定</h3>
      </div>
	    <dl class="row">
            <dt class="tit">积分抵用: </dt>
               <dd class="opt">
			   <div class="onoff">				
				<label for="points_money_isuse_1" class="cb-enable <?php if($output['list_setting']['points_money_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
                    <label for="points_money_isuse_0" class="cb-disable <?php if($output['list_setting']['points_money_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
                    <input id="points_money_isuse_1" name="points_money_isuse" <?php if($output['list_setting']['points_money_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
                    <input id="points_money_isuse_0" name="points_money_isuse" <?php if($output['list_setting']['points_money_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
				</div>
				<p class="notic">开启积分抵用，将允许会员在下订单前使用积分，按照设置的比例抵去订单应付金额，来达到优惠的目的。</p>
               </dd>
        </dl>
	   <dl class="row">
        <dt class="tit">每积分抵用比例</dt>
        <dd class="opt">
          <input id="points_money_parity" name="points_money_parity" value="<?php echo $output['list_setting']['points_money_parity'];?>" class="txt" type="text" style="width:60px;">
          <p class="notic">例：设置为0.01，则1积分抵用0.01元，100积分抵用1元</p>
        </dd>
      </dl>
	  <dl class="row">
        <dt class="tit">订单支付抵用比例</dt>
        <dd class="opt">
          <input id="member_points_payrate" name="member_points_payrate" value="<?php echo $output['list_setting']['member_points_payrate'];?>" class="txt" type="text" style="width:60px;">%
          <p class="notic">订单最大可抵用积分的比列，范围0~100数字。例：设置为0，则禁止积分抵用，100最大可全额积分抵用支付</p>
        </dd>
      </dl>
	  <div class="title">
        <h3>会员积分分享设定</h3>
      </div>
	   <dl class="row">
            <dt class="tit">积分分享: </dt>
               <dd class="opt">
			   <div class="onoff">				
				<label for="points_share_isuse_1" class="cb-enable <?php if($output['list_setting']['points_share_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
                    <label for="points_share_isuse_0" class="cb-disable <?php if($output['list_setting']['points_share_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
                    <input id="points_share_isuse_1" name="points_share_isuse" <?php if($output['list_setting']['points_share_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
                    <input id="points_share_isuse_0" name="points_share_isuse" <?php if($output['list_setting']['points_share_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
				</div>
				<p class="notic">开启积分分享功能后，会员分享可获得积分。</p>
               </dd>
        </dl>
		<dl class="row">
        <dt class="tit">单次分享可获得积分</dt>
        <dd class="opt">
          <input id="points_share_onepoint" name="points_share_onepoint" value="<?php echo $output['list_setting']['points_share_onepoint'];?>" class="input-txt" type="text">
          <p class="notic">指每次分享后可获得积分数</p>
        </dd>
      </dl>
	  	<dl class="row">
        <dt class="tit">每天分享总积分</dt>
        <dd class="opt">
          <input id="points_share_daypoint" name="points_share_daypoint" value="<?php echo $output['list_setting']['points_share_daypoint'];?>" class="input-txt" type="text">
          <p class="notic">每天分享最高可获得总积分数</p>
        </dd>
      </dl>
	  
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>

$(function(){
    $("#submitBtn").click(function(){
        if($("#settingForm").valid()){
            $("#settingForm").submit();
        }
    });
});
</script> 
