<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if ($output['fan_list']) { ?>
  <ul class="wtm-friend-list">
    <?php foreach($output['fan_list'] as $k => $v){ ?>
    <li id="recordone_<?php echo $v['friend_frommid']; ?>">
      <div class="avatar"><a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['friend_frommid']));?>" target="_blank" data-param="{'id':<?php echo $v['friend_frommid'];?>}" wttype="mcard"><img src="<?php echo getMemberAvatar($v['member_avatar']);?>" alt="<?php echo $v['friend_frommname']; ?>"/></a></div>
      <dl class="info">
        <dt><a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['friend_frommid']));?>" title="<?php echo $v['friend_tomname']; ?>" target="_blank" data-param="{'id':<?php echo $v['friend_frommid'];?>}" wttype="mcard"><?php echo $v['friend_frommname']; ?></a><i class="<?php echo $v['sex_class'];?>"></i></dt>
        <dd class="area"><?php echo $v['member_areainfo'];?></dd>
        <dd><a href="<?php echo urlMember('member_message', 'sendmsg', array('member_id' => $v['friend_frommid']));?>" target="_blank" title="<?php echo $lang['wt_message'] ;?>"><i class="icon-envelope"></i><?php echo $lang['wt_message'] ;?></a></dd>
      </dl>
      <div class="follow" wt_type="signmodule"><p wt_type="mutualsign" style="<?php echo $v['friend_followstate']!=2?'display:none;':'';?>"><i></i><?php echo $lang['snsfriend_follow_eachother']?></p> <a href="javascript:void(0)" class="wtbtn-mini wtbtn-mint" wt_type="followbtn" data-param='{"mid":"<?php echo $v['friend_frommid'];?>"}' style="<?php echo $v['friend_followstate']==2?'display:none;':'';?>"><i class="icon-plus"></i><?php echo $lang['snsfriend_followbtn'];?></a> </div>
    </li>
    <?php } ?>
  </ul>
  <?php } else { ?>
  <div class="warning-option"><i></i><span><?php echo $lang['no_record'];?></span></div>
  <?php } ?>
  <?php if ($output['fan_list']) { ?>
  <div class="tc"><div class="pagination"> <?php echo $output['show_page']; ?> </div></div>
  </td>
  <?php } ?>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/sns_friend.js"></script> 
