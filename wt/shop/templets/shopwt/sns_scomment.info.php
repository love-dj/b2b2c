<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wtap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>评论内容</label>
    </dt>
    <dd class="opt">
      <?php echo parsesmiles($output['scomm_info']['scomm_content']);?>
    </dd>
  </dl>
</div>
