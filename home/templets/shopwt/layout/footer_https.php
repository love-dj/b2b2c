<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="qt-footer-other login-footer">
<?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>  <a href="http://www.miibeian.gov.cn" rel="nofollow" target="_blank" n><?php echo $output['setting_config']['icp_number']; ?></a>
</div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['wt_debug_trace_title'];?></legend>
    <div>
      <?php print_r(Tpl::showTrace());?>
    </div>
  </fieldset>
</div>
<?php }?>
