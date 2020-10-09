<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
            <h3>淘宝接口</h3>
            <h5>淘宝开放平台账号设置</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>在【买什么频道-发表买心得】里插入调用淘宝网或天猫的商品链接淘宝数据，进行展示分享，站外购买链接。</li>
	  <li>在【社区频道-发表新话题】里插入调用淘宝网或天猫的商品链接淘宝数据，进行展示分享、站外购买链接。</li>
	  <li>在【商家中心-发表店铺动态】里插入调用淘宝网或天猫的商品链接淘宝数据，进行展示分享、站外购买链接。</li>
    </ul>
  </div>
  <form id="add_form" method="post" action="index.php?w=taobao_api&t=taobao_api_save">
    <div class="wtap-form-default">
      <!-- 淘宝接口开关 -->
      <dl class="row">
        <dt class="tit">
          <label>淘宝接口开关</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="taobao_isuse_1" class="cb-enable <?php if($output['setting']['taobao_api_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_open'];?>"><?php echo $lang['wt_open'];?></label>
            <label for="taobao_isuse_0" class="cb-disable <?php if($output['setting']['taobao_api_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wt_close'];?>"><?php echo $lang['wt_close'];?></label>
            <input type="radio" id="taobao_isuse_1" name="taobao_api_isuse" value="1" <?php echo $output['setting']['taobao_api_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="taobao_isuse_0" name="taobao_api_isuse" value="0" <?php echo $output['setting']['taobao_api_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="taobao_app_key">淘宝应用标识(APP KEY)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['taobao_app_key'];?>" name="taobao_app_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"><a class="wtap-btn" target="_blank" href="http://open.taobao.com">立即在线申请</a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="taobao_secret_key">淘宝应用密钥(APP SECRET)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['taobao_secret_key'];?>" name="taobao_secret_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });
});
</script> 
