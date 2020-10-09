<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=contract" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 开关</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>消费者保障服务开启后，店铺可以申请加入保障服务，为消费者提供商品筛选依据。</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">消费者保障服务</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="contract_allow_1" class="cb-enable <?php if($output['list_setting']['contract_allow'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="contract_allow_0" class="cb-disable <?php if($output['list_setting']['contract_allow'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="contract_allow_1" name="contract_allow" <?php if($output['list_setting']['contract_allow'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="contract_allow_0" name="contract_allow" <?php if($output['list_setting']['contract_allow'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
      $("#settingForm").submit();
	}
	});
});
</script>
