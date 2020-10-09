<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_set'];?></h3>
        <h5><?php echo $lang['wt_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>在这里可以设置平台的基本功能。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="wt_mail"><?php echo $lang['wt_mail'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_mail" name="wt_mail" value="<?php echo $output['list_setting']['wt_mail'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_mail_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wt_phone"><?php echo $lang['wt_phone'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_phone" name="wt_phone" value="<?php echo $output['list_setting']['wt_phone'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_phone_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wt_qq"><?php echo $lang['wt_qq'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_qq" name="wt_qq" value="<?php echo $output['list_setting']['wt_qq'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_qq_notice'];?></p>
        </dd>
      </dl>     
            <dl class="row">
        <dt class="tit">
          <label for="wt_time"><?php echo $lang['wt_time'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_time" name="wt_time" value="<?php echo $output['list_setting']['wt_time'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_time_notice'];?></p>
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
             <dl class="row">
        <dt class="tit">
          <label for="points_rebate">返利比例</label>
        </dt>
        <dd class="opt">
          <input id="points_rebate" name="points_rebate" value="<?php echo $output['list_setting']['points_rebate'];?>" class="w60" type="text" /><i>%</i>
          <p class="notic">被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</p>
        </dd>
      </dl>
       
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.form1.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>