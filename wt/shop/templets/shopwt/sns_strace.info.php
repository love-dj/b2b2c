<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wtap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>动态标题</label>
    </dt>
    <dd class="opt">
      <?php echo parsesmiles($output['strace_info']['strace_title'])?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>动态内容</label>
    </dt>
    <dd class="opt">
      <?php echo $output['strace_info']['strace_content'];?>
    </dd>
  </dl>
</div>
