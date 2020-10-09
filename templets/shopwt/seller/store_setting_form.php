<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form method="post"  action="index.php?w=store_setting&t=store_setting" enctype="multipart/form-data" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt><?php echo $lang['store_setting_grade'].$lang['wt_colon'];?></dt>
      <dd>
        <p><?php echo $output['store_grade']['sg_name']; ?></p>
        </dd>
    </dl>
    <dl>
      <dt>店铺名称：</dt>
      <dd>
       <input type="text" value="<?php echo $output['store_info']['store_name'];?>" name="store_name" class="text w400">
        </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_setting_store_zy'].$lang['wt_colon']; ?></dt>
      <dd>
          <textarea name="store_zy" rows="2" class="textarea w400"  maxlength="50" ><?php echo $output['store_info']['store_zy'];?></textarea>
        <p class="hint"><?php echo $lang['store_create_store_zy_hint'];?></p>
      </dd>
    </dl>
    
     <dl>
      <dt>店铺二维码：</dt>
      <dd>
         <p><img src="<?php echo storeQRCode($output['store_info']['store_id']);?>"  title="店铺原网址：<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']));?>"></p>
        <p class="hint">保存后，生成新的二维码</p>
      </dd>
    </dl>
    
    
    <dl>
      <dt><?php echo $lang['store_setting_change_label'].$lang['wt_colon'];?></dt>
      <dd>
        <div class="wtsc-upload-thumb store-logo" wttype="store_label">
          <p><?php if(empty($output['store_info']['store_label'])){ ?>
          <i class="icon-picture"></i>
          <?php } else {?>
          <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$output['store_info']['store_label'];?>" />
          <?php }?></p>
        </div>
        <div class="wtsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="store_label" id="storeLablePic" wt_type="change_store_label"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
        <p class="hint"><?php echo $lang['store_setting_label_tip'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_setting_change_banner'].$lang['wt_colon'];?> </dt>
      <dd>
        <div class="wtsc-upload-thumb store-banner" wttype="store_banner">
          <p><?php if(empty($output['store_info']['store_banner'])){?>
          <i class="icon-picture"></i>
          <?php }else{?>
          <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$output['store_info']['store_banner'];?>" />
          <?php }?></p>
        </div>
        <div class="wtsc-upload-btn"> <a href="javascript:void(0);"><span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="store_banner" id="storeBannerPic" wt_type="change_store_banner"/>
          </span>
          <p><i class="icon-upload-alt"></i>图片上传</p>
          </a> </div>
        <p class="hint"><?php echo $lang['store_setting_banner_tip'];?></p>
      </dd>
    </dl>
      <dl class="setup store-logo">
        <dt><?php echo $lang['store_setting_change_avatar'].$lang['wt_colon'];?> </dt>
        <dd>
          <p>
          <img src="<?php if(empty($output['store_info']['store_avatar'])){ echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.$output['setting_config']['default_store_avatar'];}else{ echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$output['store_info']['store_avatar'];}?>" wt_type="store_avatar" />
          </p>
          <p>
          <input type="hidden" value="" name="store_avatar" id="store_avatar">
          <input name="_pic" type="file"  hidefocus="true" id="_pic"/>
          </p>
          <p class="hint"><?php echo $lang['store_setting_sign_tip'];?></p>
        </dd>
      </dl>
    <?php if($output['subdomain'] == '1'){?>
    <dl>
      <dt><?php echo $lang['store_setting_uri'].$lang['wt_colon'];?></dt>
      <dd>
        <?php if($output['subdomain_edit'] == '1' || empty($output['store_info']['store_domain'])){?>
        <p>
          <input type="text" class="text"  name="store_domain" value="<?php echo $output['store_info']['store_domain'];?>"  />
          &nbsp;<?php echo '.'.SUBDOMAIN_SUFFIX;?> &nbsp;</p>
        <p class="hint"><?php echo $lang['store_setting_uri_tip'];?>: <?php echo $output['setting_config']['subdomain_length'];?>
          <?php if($output['subdomain_edit'] == '1'){?>
          &nbsp; &nbsp;<?php echo $lang['store_setting_domain_times'];?>: <?php echo $output['store_info']['store_domain_times'];?> &nbsp; &nbsp;<?php echo $lang['store_setting_domain_times_max'];?>: <?php echo $output['subdomain_times'];?>
          <?php }else {?>
          &nbsp; &nbsp;<?php echo $lang['store_setting_domain_notice'];?>
          <?php }?>
        </p>
        <?php }else {?>
        <p><?php echo $output['store_info']['store_domain'];?><?php echo '.'.SUBDOMAIN_SUFFIX;?> &nbsp;</p>
        <p class="hint"><?php echo $lang['store_setting_domain_tip'];?>
          <?php if($output['setting_config']['subdomain_edit'] == '1'){?>
          &nbsp; &nbsp;<?php echo $lang['store_setting_domain_times'];?>: <?php echo $output['store_info']['store_domain_times'];?> &nbsp; &nbsp;<?php echo $lang['store_setting_domain_times_max'];?>: <?php echo $output['subdomain_times'];?>
          <?php }?>
        </p>
        <?php }?>
      </dd>
    </dl>
    <?php }?>
    <dl>
      <dt>QQ<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="w200 text" name="store_qq" type="text"  id="store_qq" value="<?php echo $output['store_info']['store_qq'];?>" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_setting_wangwang'].$lang['wt_colon'];?></dt>
      <dd>
        <input class="text w200" name="store_ww" type="text"  id="store_ww" value="<?php echo $output['store_info']['store_ww'];?>" />
      </dd>
    </dl>
    <dl>
      <dt>店铺电话<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="text w200" name="store_phone" maxlength="20" type="text"  id="store_phone" value="<?php echo $output['store_info']['store_phone'];?>" />
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_setting_seo_keywords'].$lang['wt_colon']; ?></dt>
      <dd>
        <p>
          <input class="text w400" name="seo_keywords" type="text"  value="<?php echo $output['store_info']['store_keywords'];?>" />
        </p>
        <p class="hint"><?php echo $lang['store_setting_seo_keywords_help']; ?></p>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_setting_seo_description'].$lang['wt_colon']; ?></dt>
      <dd>
        <p>
          <textarea name="seo_description" rows="3" class="textarea w400" id="remark_input" ><?php echo $output['store_info']['store_description'];?></textarea>
        </p>
        <p class="hint"><?php echo $lang['store_setting_seo_description_help']; ?></p>
      </dd>
    </dl>
    <dl>
      <dt>虚拟商品兑换码生成前缀<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <input class="text w50" name="store_vrcode_prefix" type="text" maxlength="3" value="<?php echo $output['store_info']['store_vrcode_prefix'];?>" />
        <span></span>
        <p class="hint">该设置将作为虚拟商品兑换码的一部分，用于区别不同店铺之间的兑换码，增加兑换码使用的安全性，只接受字母或数字，最多3个字符。</p>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['store_goods_class_submit'];?>" /></label>
      </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
var SITEURL = "<?php echo BASE_SITE_URL; ?>";
//裁剪图片后返回接收函数
function call_back(picname){
	$('#store_avatar').val(picname);
	$('img[wt_type="store_avatar"]').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE;?>/'+picname);
	$('#_pic').val('');
}
$(function(){
	$('#_pic').change(uploadChange);
	function uploadChange(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?w=cut&t=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_STORE;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['wt_cut'];?>','index.php?w=cut&t=pic_cut&x=100&y=100&resize=1&url='+data.url,680);
					}else{
						alert(data.msg);$('#_pic').bind('change',uploadChange);
					}
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('#_pic').bind('change',uploadChange);
				}
			}
		)
	};
	$('input[wt_type="change_store_banner"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('div[wttype="store_banner"]').find('p').html('<img src="'+src+'">');
	});
	$('input[wt_type="change_store_label"]').change(function(){
		var src = getFullPath($(this)[0]);
		$('div[wttype="store_label"]').find('p').html('<img src="'+src+'">');
	});
	jQuery.validator.addMethod("domain", function(value, element) {
			return this.optional(element) || /^[\w\-]+$/i.test(value);
		}, "");
	$('#my_store_form').validate({
    	submitHandler:function(form){
    		ajaxpost('my_store_form', '', '', 'onerror')
    	},
		rules : {
        	<?php if(($output['subdomain'] == '1') && ($output['subdomain_edit'] == '1' || empty($output['store_info']['store_domain']))){?>
					store_domain: {
						domain: true,
		        rangelength:[<?php echo $output['subdomain_length'][0];?>, <?php echo $output['subdomain_length'][1];?>]
					}
          <?php }?>
        },
        messages : {
        	<?php if(($output['subdomain'] == '1') && ($output['subdomain_edit'] == '1' || empty($output['store_info']['store_domain']))){?>
					store_domain: {
						domain: '<?php echo $lang['store_setting_domain_valid'];?>',
		        rangelength:'<?php echo $lang['store_setting_domain_rangelength'];?>'
					}
          <?php }?>
        }
    });

    // ajax 修改店铺二维码
    $('#a_store_code').click(function(){
    	$('#img_store_code').attr('src','');
		$.getJSON($(this).attr('href'),function(data){
			$('#img_store_code').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.DS;?>'+data);
		});
		return false;
    });

});
</script>
