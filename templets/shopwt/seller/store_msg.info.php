<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtsc-form-default">
  <dl>
    <dt>发送时间<?php echo $lang['wt_colon']; ?></dt>
    <dd>
      <p><?php echo date('Y-m-d H:i:d', $output['msg_list']['sm_addtime']);?></p>
    </dd>
  </dl>
  <dl>
    <dt>消息内容<?php echo $lang['wt_colon'];?></dt>
    <dd>
      <p><?php echo $output['msg_list']['sm_content']; ?></p>
    </dd>
  </dl>
  <div class="bottom"><a class="wtbtn" href="javascript:void(0);" onclick="CUR_DIALOG.close();">关闭窗口</a></div>
</div>
