<script type="text/javascript">
$(document).ready(function(){
    $("#submitBtn").click(function(){
        $("#add_form").submit();
    });
});
</script>
<div class="page">
	  <div class="fixed-bar">
      <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_wechat_api'];?></h3>
        <h5>设置微信公众号接口</h5>
      </div>
    </div>
  </div>

  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="wid" value="<?php echo $output['api_account']['wechat_id'];?>" />
  <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
         <label for="token"><?php echo $lang['wechat_api_url']; ?>:</label>
        </dt>
        <dd class="opt">
			<?php echo MOBILE_SITE_URL.'/index.php?w=weixin&wsn='.$output['api_account']['wechat_sn'];?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
			<label for="token"><?php echo $lang['wechat_token']; ?>:</label>
        </dt>
        <dd class="opt">
		  	<input type="text" class="input-txt" name="wechat_token" id="wechat_token" value="<?php echo $output['api_account']['wechat_token'];?>" readonly="readonly" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
		<dl class="row">
			<dt class="tit">
				<label for="type"><?php echo $lang['wechat_type']; ?>:</label>
			</dt>
			<dd class="opt">
				 <?php foreach($lang['wechat_type_name'] as $key=>$value){?>
				<input type="radio" name="type" value="<?php echo $key;?>" id="type_<?php echo $key;?>"<?php echo $output['api_account']['wechat_type']==$key ? ' checked' : '';?> /><label for="wechat_type_<?php echo $key;?>"><?php echo $value;?></label>&nbsp;&nbsp;
			   <?php }?>
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
        </dl>
	    <dl class="row">
			<dt class="tit">
				<label for="appid"><?php echo $lang['wechat_appid']; ?>:</label>
			</dt>
			<dd class="opt">
				<input id="appid" name="appid" value="<?php echo $output['api_account']['wechat_appid'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="appsecret"><?php echo $lang['wechat_appsecret'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="appsecret" name="appsecret" value="<?php echo $output['api_account']['wechat_appsecret'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="name"><?php echo $lang['wechat_name'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="name" name="name" value="<?php echo $output['api_account']['wechat_name'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="email"><?php echo $lang['wechat_email'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="email" name="email" value="<?php echo $output['api_account']['wechat_email'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="preid"><?php echo $lang['wechat_preid'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="preid" name="preid" value="<?php echo $output['api_account']['wechat_preid'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="account"><?php echo $lang['wechat_account'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="account" name="account" value="<?php echo $output['api_account']['wechat_account'];?>"  class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="encodingtype"><?php echo $lang['wechat_encodingtype'];?>:</label>
			</dt>
			<dd class="opt">
				<select name="encodingtype">
           <?php foreach($lang['wechat_encodingtype_name'] as $k=>$v){?>
           	<option value="<?php echo $k;?>"<?php echo $output['api_account']['wechat_encodingtype']==$k ? ' selected' : '';?>><?php echo $v;?></option>
           <?php }?>
           </select>
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
	    <dl class="row">
			<dt class="tit">
				<label for="encoding"><?php echo $lang['wechat_encoding'];?>:</label>
			</dt>
			<dd class="opt">
				<input id="encoding" name="encoding" value="<?php echo $output['api_account']['wechat_encoding'];?>" class="input-txt" type="text" />
			  <span class="err"></span>
			  <p class="notic"></p>
			</dd>
		</dl>
		 <div class="bot"><a id="submitBtn" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
	  
  
  </div>
   
  </form>
</div>
