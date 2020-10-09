<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($output['bbs_info']['bbs_status'] == 1){?>
<link href="<?php echo BBS_TEMPLATES_URL;?>/css/ubb.css" rel="stylesheet" type="text/css">
<div class="group warp-all">
  <?php require_once bbs_template('group.top');?>
  <div class="group-post">
    <h3><?php echo $lang['wt_release_new_theme'];?>...</h3>
    <div class="stat"><span class="noborder"><?php echo $lang['bbs_today'].$lang['wt_colon'];?><em><?php echo $output['todaythcount'];?></em></span><span><?php echo $lang['bbs_theme'].$lang['wt_colon'];?><em><?php echo $output['bbs_info']['bbs_thcount'];?></em></span><span><?php echo $lang['bbs_firend'].$lang['wt_colon'];?><em><?php echo $output['bbs_info']['bbs_mcount'];?></em></span></div>
    <div class="clear">&nbsp;</div>
    <div class="thread-layer">
      <div class="input-style">
        <?php 
        if(!intval(C('bbs_istalk'))){
          echo $lang['bbs_theme_cannot_be_published'];
        }else if($_SESSION['is_login'] != 1){
          echo $lang['bbs_not_login_prompt'].'<a href="javascript:void(0);" wttype="login">'.$lang['wt_login'].'</a>';
        }else if(in_array($output['identity'], array(0,5))){
          echo $lang['bbs_not_join_prompt_one'].'<a href="javascript:void(0);" wttype="apply">'.$lang['bbs_not_join_prompt_two'].'</a>'.$lang['bbs_not_join_prompt_three'];
        }else if($output['identity'] == 4){
          echo $lang['bbs_waiting_verify_prompt'];
        }else if($output['identity'] == 6){
          echo $lang['bbs_nospeak_prompt'];
        }else{
          echo "<p>&nbsp;</p>";
        }
        ?>
      </div>
      <div class="button-style-tp"><a href="<?php echo urlbbs('theme', 'new_theme', array('sp' => 1, 'c_id' => $output['c_id']));?>">发投票</a></div>
      <div class="button-style"><?php echo $lang['wt_release_new_theme'];?></div>
      
    </div>
    <!-- 编辑器 S -->
    <?php require_once bbs_template('group.editor');?>
    <!-- 编辑器 E -->
    <div class="clear"></div>
  </div>
  <div class="base-box mt20">
    <div class="mainbox">
      <div class="base-tab-menu">
        <ul class="base-tab-nav">
          <li class="selected"><a href="index.php?w=group&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_theme'];?></a></li>
          <li><a href="index.php?w=group&t=group_member&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_firend'];?></a></li>
          <li><a href="index.php?w=group&t=group_goods&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['wt_goods'];?></a></li>
        </ul>
      </div>
      <div class="group-list-bar">
        <div class="group-theme-class"> <a href="index.php?w=group&c_id=<?php echo $output['c_id'];?>" <?php if($output['thc_id'] == ''){?>class="selected"<?php }?>><i></i><?php echo $lang['bbs_all'];?></a>
          <?php if(!empty($output['thclass_list'])){?>
          <?php foreach ($output['thclass_list'] as $val){?>
          <a href="javascript:void(0);" onclick="replaceParam('thc_id','<?php echo $val['thclass_id'];?>');" <?php if($output['thc_id'] == $val['thclass_id']){?>class="selected"<?php }?>><i></i><?php echo $val['thclass_name'];?></a>
          <?php }?>
          <?php }?>
        </div>
        <div class="group-theme-read-control">
          <div class="read-model">
            <a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&cream=1&c_id=<?php echo $output['c_id'];?>" title="<?php echo $lang['bbs_digest_theme'];?>"><i class="digest"></i></a>
            <a href="javascript:void(0);" onclick="replaceParam('type','preview');" <?php if($output['display_mode'] == 'preview'){?>class="selected"<?php }?>><i class="preview"></i><?php echo $lang['bbs_preview'];?></a>
            <a href="javascript:void(0);" onclick="replaceParam('type','list');" <?php if($output['display_mode'] == 'list'){?>class="selected"<?php }?>><i class="list"></i><?php echo $lang['bbs_list'];?></a>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="<?php if($output['display_mode'] == 'preview'){?>read-model-preview<?php }else{?>read-model-list<?php }?>">
        <div class="group-theme-top"><span class="theme"><?php echo $lang['bbs_theme'];?></span><span class="writer"><?php echo $lang['bbs_author'];?></span><span class="stat"><?php echo $lang['bbs_reply_or_see'];?></span><span class="lastspeak"><?php echo $lang['bbs_last_speak'];?></span></div>
        <?php if(!empty($output['theme_list'])){?>
        <ul class="group-theme-list">
          <?php foreach($output['theme_list'] as $val){?>
          <li wttype="li<?php echo $val['theme_id'];?>">
            <div class="theme-icon"><i class="<?php if($val['is_stick'] == 1){
            	echo 'top';
            }elseif($val['is_digest'] == 1){
            	echo 'digest';
            }elseif($val['is_shut'] == 1){
            	echo 'close';
            }elseif($val['theme_special']==1){
				echo 'poll';
            }else{
            	echo 'normal';
            }?>" title=""></i></div>
            <div class="theme-title">
              <a href="javascript:void(0);" class="theme-class"><?php echo $lang['wt_brackets1'];?><?php if($val['thclass_name'] != ''){echo $val['thclass_name'];}else{echo $lang['wt_default'];}?><?php echo $lang['wt_brackets2'];?></a>
              <?php if($output['m_readperm'] >= $val['theme_readperm']){?>
              <a href="index.php?w=theme&t=theme_detail&c_id=<?php echo $output['c_id'];?>&t_id=<?php echo $val['theme_id'];?>" class="theme-name"><?php echo $val['theme_name'];if($val['theme_readperm'] > 0){ echo '<font>'.L('wt_brackets1,bbs_read_permissions').'lv'.$val['theme_readperm'].L('wt_brackets2').'</font>';}?></a>
              <?php }else{?>
              <a href="javascript:void(0);" class="theme-name" onclick="showError('<?php echo L('bbs_permission_denied');?>');"><?php echo $val['theme_name'];if($val['theme_readperm'] > 0){ echo '<font>'.L('wt_brackets1,bbs_read_permissions').'lv'.$val['theme_readperm'].L('wt_brackets2').'</font>';}?></a>
              <?php }?>
              <?php if($val['is_closed'] == '0' && $output['m_readperm'] >= $val['theme_readperm']){?>
              <a href="javascript:void(0);" class="read-unfold" wttype="theme_read" data-param="<?php echo $val['theme_id'];?>"><i></i><?php echo $lang['bbs_unfold_theme'];?></a>
              <?php }?>
            </div>
            <?php if($val['is_closed'] == '0'){?>
            <div class="theme-intro">
              <p class="theme-intro-txt"><?php echo replaceUBBTag($val['theme_content']);?></p>
              <?php if($output['m_readperm'] >= $val['theme_readperm']){?>
              <?php if(isset($output['affix_list'][$val['theme_id']])){?>
              <div class="theme-intro-pic">
                <?php $array = array_slice($output['affix_list'][$val['theme_id']], 0, 3);foreach($array as $v){ ?>
                <div class="thumb"><a href="javascript:void(0);" class="read-unfold"><img src="<?php echo themeImageUrl($v['affix_filethumb']);?>" class="t-img"/> </a> <span wttype="theme_read"><?php echo $lang['bbs_click_image_unfold'];?></span></div>
                <?php }?>
              </div>
              <?php }?>
              <?php }?>
            </div>
            <div class="theme-detail-content" style="display:none;"></div>
            <div class="quick-reply-box" style="display:none;"><i></i>
              <div class="quick-reply-2"></div>
            </div>
            <?php }else{?>
            <div class="theme-intro"><?php echo $lang['bbs_be_nospeak_member'];?></div>
            <?php }?>
            <div class="theme-writer"><i title="<?php echo $lang['bbs_author'];?>"></i><a href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['member_id'];?>" target="_blank" title="<?php echo $val['member_name'];?>"><?php echo $val['member_name'];?></a><?php echo memberIdentity($val['is_identity']);?></div>
            <div class="theme-writer-time" title="<?php echo $lang['bbs_publish_time'];?>"><?php echo @date('Y-m-d', $val['lastspeak_time']);?></div>
            <div class="theme-stat" title="<?php echo $lang['bbs_browsecount_one'].$val['theme_browsecount'].$lang['bbs_browsecount_two'].$val['theme_commentcount'].$lang['bbs_commentcount_one'];;?>"><em><?php echo $val['theme_commentcount'];?></em>/<em><?php echo $val['theme_browsecount'];?></em></div>
            <?php if($val['lastspeak_name'] != ''){?>
            <div class="theme-lastspeak-name"><i title="<?php echo $lang['bbs_lastspeak_member'];?>"></i><a href="<?php echo BASE_SITE_URL;?>/index.php?w=sns_bbs&mid=<?php echo $val['lastspeak_id'];?>" target="_blank" title="<?php echo $val['lastspeak_name'];?>"><?php echo $val['lastspeak_name'];?></a></div>
            <div class="theme-lastspeak-time" title="<?php echo $lang['bbs_lastspeak_time'];?>"><?php echo @date('Y-m-d', $val['lastspeak_time']);?></div>
            <?php }else{?>
            <div class="theme-noreply">-&nbsp;<?php echo $lang['bbs_no_comment'];?>&nbsp;-</div>
            <?php }?>
          </li>
          <?php }?>
        </ul>
        <div class="pagination"><?php echo $output['show_page'];?></div>
        <?php }else{?>
        <div class="no-theme"><span><i></i><?php if($_GET['cream'] == 1){echo $lang['bbs_no_digest'];}else{echo $lang['bbs_no_theme'];}?></span></div>
        <?php }?>
      </div>
    </div>
    <?php require_once bbs_template('group.sidebar');?>
    <div class="clear"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.charCount.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/custom.min.js"></script> 
<?php }else if($output['bbs_info']['bbs_status'] == 2){?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon02"></i><h3><?php echo $lang['bbs_is_under_approval'];?></h3></div>
</div>
<?php }else if($output['bbs_info']['bbs_status'] == 3){?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon03"></i><h3><?php echo $lang['bbs_approval_fail'];?></h3><?php if($output['bbs_info']['bbs_statusinfo'] != ''){echo '<h5>'.$lang['bbs_reason'].$lang['wt_colon'].$output['bbs_info']['bbs_statusinfo'].'</h5>'; }?></div>
</div>
<?php }else{?>
<div class="warp-all">
  <div class="bbs-status"><i class="icon01"></i><h3><?php echo $lang['bbs_is_closed'];?></h3><?php if($output['bbs_info']['bbs_statusinfo'] != ''){echo '<h5>'.$lang['bbs_reason'].$lang['wt_colon'].$output['bbs_info']['bbs_statusinfo'].'</h5>'; }?></div>
</div>
<?php }?>
<script>
/* 替换参数 */
function replaceParam(key, value, arg)
{
	if(!arguments[2]) arg = 'string';
    var params = location.search.substr(1).split('&');
    var found  = false;
    for (var i = 0; i < params.length; i++)
    {
        param = params[i];
        arr   = param.split('=');
        pKey  = arr[0];
        if(arg == 'string'){
	        if (pKey == key)
	        {
	            params[i] = key + '=' + value;
	            found = true;
	        }
        }else{
        	for(var j = 0; j < key.length; j++){
        		if(pKey ==  key[j]){
        			params[i] = key[j] + '=' + value[j];
    	            found = true;
        		}
        	}
        }
    }
    if (!found)
    {
        if (arg == 'string'){
            value = transform_char(value);
            params.push(key + '=' + value);
        }else{
        	for(var j = 0; j < key.length; j++){
        		params.push(key[j] + '=' + transform_char(value[j]));
        	}
        }
    }
    location.assign(BBS_SITE_URL + '/index.php?' + params.join('&'));
}
</script>