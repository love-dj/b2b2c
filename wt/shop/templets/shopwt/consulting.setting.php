<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['consulting_index_manage'];?></h3>
        <h5><?php echo $lang['consulting_index_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>在商品详细页咨询选项卡头部显示的文字提示。</li>
    </ul>
  </div>
  <form method="post" name="form_consultingsetting">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row"><dt class="tit"><label>咨询头部文字提示</label></dt>
       <dd class="opt"><?php showEditor('consult_prompt', $output['setting_list']['consult_prompt']);?><p class="notic"></p></dd></dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.form_consultingsetting.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
