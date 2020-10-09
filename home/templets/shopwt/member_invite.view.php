<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu'); ?>
    </div>
    <div class="alert alert-block">
  <h4>您的推广连接</h4>
	<ul><li><?php echo $output['member_info']['myurl'];?></li></ul>
  </div>
    <ul class="bind-account-list">
            <li style="width: 50%;">
      <div class="account-item"><span class="website-icon"><img src="<?php echo $output['member_info']['myurl_src'];?>" width="100%"></span>
        <dl>
          <dt>二维码推广</dt>
          <dd>鼠标右键将图片另存为本地</dd>
                    <dd class="operate">
                        <a class="wtbtn wtbtn-mint" href="<?php echo $output['member_info']['mydownurl'];?>">下载二维码</a>
                      </dd>
        </dl>
      </div>
    </li>
                <li style="width: 50%;">
      <div class="account-item" style="padding-left: 160px; height: 250px;"><span class="website-icon" style="width: 150px; height: 248px;"><img src="<?php echo $output['member_info']['myurlhb_src'];?>" width="100%"></span>
        <dl>
          <dt>海报推广</dt>
          <dd>鼠标右键将图片另存为本地或点击下载</dd>
          <dd class="operate">
                        <a class="wtbtn wtbtn-mint" href="<?php echo $output['member_info']['mydownurl_hb'];?>">下载海报</a>
                      </dd>
        </dl>
      </div>
    </li>
          </ul>
</div>