<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wtap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>动态标题</label>
    </dt>
    <dd class="opt">
      <?php echo parsesmiles($output['trace_info']['trace_title'])?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>动态内容</label>
    </dt>
    <dd class="opt">
      <?php echo $output['trace_info']['trace_content'];?>
    </dd>
  </dl>
</div>
