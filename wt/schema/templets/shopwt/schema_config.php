<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_schema_config'];?></h3>
      </div>
        <!-- <ul class="tab-base nc-row">
            <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['nc_schema_config'];?></a>
            <li><a href="index.php?act=schema_config&op=settlement"><?php echo $lang['nc_schema_settlement'];?></a>
            <li><a href="index.php?act=schema_config&op=active"><?php echo $lang['nc_schema_goods_activity'];?></a>
            <li><a href="index.php?act=schema_config&op=center"><?php echo $lang['nc_schema_center'];?></a>
        </ul> -->
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=schema_config&op=schema_config_save">
    <div class="ncap-form-default">
      <!-- 开启分销 -->
      <dl class="row">
        <dt class="tit">
          <label for="schema_isuse"><?php echo $lang['schema_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="schema_isuse_1" class="cb-enable <?php if($output['setting']['schema_isuse'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['schema_open'];?></label>
            <input type="radio" id="schema_isuse_1" name="schema_isuse" value="1" <?php echo $output['setting']['schema_isuse']==1?'checked=checked':''; ?>>
            <label for="schema_isuse_0" class="cb-disable <?php if($output['setting']['schema_isuse'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['schema_close'];?></label>
            <input type="radio" id="schema_isuse_0" name="schema_isuse" value="0" <?php echo $output['setting']['schema_isuse']==0?'checked=checked':''; ?>>
          </div>          
          <p class="notic"><?php echo $lang['notice_isuse'];?></p>
        </dd>
      </dl>   

      <!-- 等级升级设置 -->
      <dl class="row">
        <dt class="tit">
          <label for="schema_condition"><?php echo $lang['schema_levelup'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="schema_condition_1" class="cb-enable <?php if($output['setting']['schema_condition'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['schema_or'];?></label>
            <input type="radio" id="schema_condition_1" name="schema_condition" value="1" <?php echo $output['setting']['schema_condition']==1?'checked=checked':''; ?>>
            <label for="schema_condition_0" class="cb-disable <?php if($output['setting']['schema_condition'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['schema_and'];?></label>            
            <input type="radio" id="schema_condition_0" name="schema_condition" value="0" <?php echo $output['setting']['schema_condition']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['notice_condition_or'];?></p>
          <p class="notic"><?php echo $lang['notice_condition_and'];?></p>
        </dd>
      </dl>
      <!-- 分销层级设置 -->
      <dl class="row">
        <dt class="tit">
            <?php echo $lang['schema_layer'];?>          
        </dt>
        <dd class="opt">
          <select for="schema_layer">
            <option value="1"><?php echo $lang['schema_layer_one'];?></option>
            <option value="2"><?php echo $lang['schema_layer_two'];?></option>
            <option value="3"><?php echo $lang['schema_layer_three'];?></option>
          </select>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="schema_layer_one_ratio"><?php echo $lang['schema_layer_one_ratio'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['schema_layer_one_ratio'];?>" name="schema_layer_one_ratio" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="schema_layer_two_ratio"><?php echo $lang['schema_layer_two_ratio'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['schema_layer_two_ratio'];?>" name="schema_layer_two_ratio" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="schema_layer_three_ratio"><?php echo $lang['schema_layer_three_ratio'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['schema_layer_three_ratio'];?>" name="schema_layer_three_ratio" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <!-- 分销内购-->
      <dl class="row">
        <dt class="tit">
          <label for="schema_inner"><?php echo $lang['schema_inner'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="schema_inner_1" class="cb-enable <?php if($output['setting']['schema_inner'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['schema_open'];?></label>
            <input type="radio" id="schema_inner_1" name="schema_inner" value="1" <?php echo $output['setting']['schema_inner']==1?'checked=checked':''; ?>>
            <label for="schema_inner_0" class="cb-disable <?php if($output['setting']['schema_inner'] == '0'){ ?>selected<?php } ?>" "><?php echo $lang['schema_close'];?></label>
            <input type="radio" id="schema_inner_0" name="schema_inner" value="0" <?php echo $output['setting']['schema_inner']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['notice_inner'];?></p>
        </dd>`
      </dl>

      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>

  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    
    $("#submit").click(function(){
      $("#add_form").submit();
    }); 
});
</script> 
