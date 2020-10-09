<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['sms_set'];?></h3>
        <h5><?php echo $lang['sms_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>在这里可以设置ShopWT提供的短信服务商完成设置。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
     <dl class="row">
      <dt class="tit"><span><?php echo $lang['wt_sms_type'];?></span></dt>
        <dd class="opt">
          <ul class="wtap-account-container-list">
            <?php if($output['list_setting']['wt_sms_type']==1){ ?>
          <li>
          <input type="radio" name="wt_sms_type" value="1" checked="checked" />
           <label for="wt_sms_type"><?php echo $lang['wt_sms_dxb'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="2" />
           <label for="wt_sms_type"><?php echo $lang['wt_sms_yp'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="3" />
           <label for="wt_sms_type">阿里云通信</label>
          </li>
		  <li>
          <input type="radio" name="wt_sms_type" value="4" />
           <label for="wt_sms_type">云短信网</label>
          </li>
          <?php }elseif($output['list_setting']['wt_sms_type']==2){ ?>
          <li>
           <input type="radio" name="wt_sms_type" value="1" /><label for="wt_sms_type"><?php echo $lang['wt_sms_dxb'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="2" checked="checked" />
           <label for="wt_sms_type"><?php echo $lang['wt_sms_yp'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="3" />
           <label for="wt_sms_type">阿里云通信</label>
          </li>
		  <li>
          <input type="radio" name="wt_sms_type" value="4" />
           <label for="wt_sms_type">云短信网</label>
          </li>
		  <?php }elseif($output['list_setting']['wt_sms_type']==4){ ?>
          <li>
           <input type="radio" name="wt_sms_type" value="1" /><label for="wt_sms_type"><?php echo $lang['wt_sms_dxb'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="2" />
           <label for="wt_sms_type"><?php echo $lang['wt_sms_yp'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="3" />
           <label for="wt_sms_type">阿里云通信</label>
          </li>
		  <li>
          <input type="radio" name="wt_sms_type" value="4" checked="checked" />
           <label for="wt_sms_type">云短信网</label>
          </li>
         <?php }else{ ?>
          <li>
           <input type="radio" name="wt_sms_type" value="1" /><label for="wt_sms_type"><?php echo $lang['wt_sms_dxb'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="2"/>
           <label for="wt_sms_type"><?php echo $lang['wt_sms_yp'];?></label>
          </li><li>
          <input type="radio" name="wt_sms_type" value="3"  checked="checked" />
           <label for="wt_sms_type">阿里云通信</label>
          </li>
		  <li>
          <input type="radio" name="wt_sms_type" value="4" />
           <label for="wt_sms_type">云短信网</label>
          </li>
          <?php }?>
            </ul>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wt_sms_tgs"><?php echo $lang['wt_sms_tgs'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_sms_tgs" name="wt_sms_tgs" value="<?php echo $output['list_setting']['wt_sms_tgs'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_sms_tgs_notice'];?></p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="wt_sms_zh"><?php echo $lang['wt_sms_zh'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_sms_zh" name="wt_sms_zh" value="<?php echo $output['list_setting']['wt_sms_zh'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_sms_zh_notice'];?>[云短信]填写接口帐号,[阿里云通信]填写AccessKey</p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="wt_sms_pw"><?php echo $lang['wt_sms_pw'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_sms_pw" name="wt_sms_pw" value="<?php echo $output['list_setting']['wt_sms_pw'];?>" class="input-txt" type="password" />
          <p class="notic"><?php echo $lang['wt_sms_pw_notice'];?>，[云短信]填写登录密码</p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="wt_sms_key"><?php echo $lang['wt_sms_key'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_sms_key" name="wt_sms_key" value="<?php echo $output['list_setting']['wt_sms_key'];?>" class="input-txt" type="password" />
          <p class="notic"><?php echo $lang['wt_sms_key_notice'];?>
			[阿里云通信]填写Access Key Secret
		</p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label for="wt_sms_signature"><?php echo $lang['wt_sms_signature'];?></label>
        </dt>
        <dd class="opt">
          <input id="wt_sms_signature" name="wt_sms_signature" value="<?php echo $output['list_setting']['wt_sms_signature'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['wt_sms_signature_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wt_sms_bz"><?php echo $lang['wt_sms_bz'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="wt_sms_bz" rows="6" class="tarea" id="wt_sms_bz"><?php echo $output['list_setting']['wt_sms_bz'];?></textarea>
          <p class="notic"><?php echo $lang['wt_sms_bz_notice'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" onclick="document.form1.submit()"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>