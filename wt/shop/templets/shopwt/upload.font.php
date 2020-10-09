<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_picture_set'];?></h3>
        <h5><?php echo $lang['wt_picture_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['font_help1'];?></li>
      <li><?php echo $lang['font_help2'];?></li>
    </ul>
  </div>
  <div class="wtap-form-default">
    <dl class="row">
      <dt class="tit">
        <label><?php echo $lang['font_info'];?></label>
      </dt>
      <dd class="opt">
        <?php if(!empty($output['file_list']) && is_array($output['file_list'])){?>
        <?php foreach($output['file_list'] as $key => $value){?>
        <?php echo $key;?><?php echo $lang['wt_colon'];?><?php echo $value;?>
        <?php }?>
        <?php }?>
      </dd>
    </dl>
  </div>
</div>
