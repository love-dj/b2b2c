<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

  <div id="friendsShare" class="normal">
    <div class="outline">
      <div class="title">
        <h3>好友动态</h3>
      </div>
      <?php if (!empty($output['follow_list']) && is_array($output['follow_list'])) { ?>
       <div class="wtm-friends-share">
            <ul id="friendsShareList" class="jcarousel-skin-tango">
            <?php foreach($output['follow_list'] as $k => $v){ ?>
              <li>
                <div class="wtm-friend-avatar"><a href="index.php?w=member_snshome&t=trace&mid=<?php echo $v['member_id'];?>" target="_blank"><img  src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>"></a></div>
                <dl>
                  <dt class="wtm-friend-name"><a href="index.php?w=member_snshome&t=trace&mid=<?php echo $v['member_id'];?>" target="_blank"><?php echo $v['friend_tomname']; ?></a></dt>
                  <dd>动态<?php echo $output['tracelist'][$v['member_id']];?>条</dd>
                </dl>
              </li>
            <?php } ?>
            </ul>
            <div class="more"><a target="_blank" href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_snsfriend&t=follow">查看我的全部好友</a></div>
          </div>
     <?php } else { ?>
      <dl class="null-tip">
        <dt></dt>
        <dd>
          <h4>您的好友最近没有什么动静</h4>
          <h5>关注其他用户成为好友可将您的动态进行分享</h5>
          <p><a target="_blank" href="<?php echo MEMBER_SITE_URL;?>/index.php?w=member_snsfriend&t=follow" class="wtbtn-mini" >查看我的全部好友</a></p>
        </dd>
      </dl>
    <?php } ?>  
    </div>
  </div>
  <div id="bbs" class="normal">
    <div class="outline">
      <div class="title">
        <h3>我的社区</h3>
      </div>
    <?php if(!empty($output['bbs_list'])){?>
      <div class="wtm-bbs">
            <ul id="bbsList" class="jcarousel-skin-tango">
              <?php foreach($output['bbs_list'] as $val){?>
              <li>
                <div class="wtm-bbs-pic"><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>" target="_blank"><img alt="" src="<?php echo bbsLogo($val['bbs_id']);?>"></a></div>
                <dl>
                  <dt><a href="<?php echo BBS_SITE_URL;?>/index.php?w=group&c_id=<?php echo $val['bbs_id'];?>" target="_blank"><?php echo $val['bbs_name']?></a></dt>
                  <dd><?php echo $val['bbs_mcount'];?>个成员</dd>
                </dl>
              </li>
            <?php } ?>
            </ul>
            <div class="more"><a target="_blank" href="<?php echo BBS_SITE_URL;?>/index.php?w=p_center&t=my_group">查看我的所有社区</a></div>
          </div>
      <?php } else {?>
      <dl class="null-tip">
        <dt></dt>
        <dd>
          <h4>您还没有自己的社区</h4>
          <h5>您可以创建或加入感兴趣的社交社区</h5>
          <p><a target="_blank" href="<?php echo BBS_SITE_URL;?>/index.php?w=index&t=add_group" class="wtbtn-mini">创建社区</a></p>
        </dd>
      </dl>
      <?php } ?>
    </div>
  </div>
  <div id="browseMark" class="normal">
    <div class="outline">
      <div class="title">
        <h3>我的足迹</h3>
      </div>
      <?php if (!empty($output['viewed_goods']) && is_array($output['viewed_goods'])) { ?>
      <div class="wtm-browse-mark">
            <ul id="browseMarkList" class="jcarousel-skin-tango">
            <?php foreach($output['viewed_goods'] as $goods_id => $goods_content) { ?>
              <li>
                <div class="wtm-goods-pic"><a href="<?php echo $goods_content['url'];?>" target="_blank"><img alt="" src="<?php echo $goods_content['goods_image'];?>"></a></div>
                <dl>
                  <dt class="wtm-goods-name"><a href="<?php echo $goods_content['url'];?>" title="<?php echo $goods_content['goods_name'];?>" target="_blank"><?php echo $goods_content['goods_name'];?></a></dt>
                  <dd class="wtm-goods-price"><em>￥<?php echo wtPriceFormat($goods_content['goods_sale_price']);?></em></dd>
                </dl>
              </li>
            <?php } ?>
            </ul>
            <div class="more"><a href="<?php echo BASE_SITE_URL;?>/index.php?w=member_goodsbrowse&t=list" target="_blank">查看所有商品</a></div>
          </div>
       <?php } else { ?>
          <dl class="null-tip">
            <dt></dt>
            <dd>
              <h4>您的商品浏览记录为空</h4>
              <h5>赶紧去商城看看促销活动吧</h5>
              <p><a target="_blank" href="<?php echo BASE_SITE_URL;?>" class="wtbtn-mini">浏览商品</a></p>
            </dd>
          </dl>
       <?php } ?>
    </div>
  </div>
<script>
//信息轮换
$.getScript("<?php echo STATIC_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js", function(){
    $('#favoritesGoodsList').jcarousel({visible: 4,itemFallbackDimension: 300});
	$('#favoritesStoreList').jcarousel({visible: 3,itemFallbackDimension: 300});
	$('#friendsShareList').jcarousel({visible: 3,itemFallbackDimension: 300});
	$('#bbsList').jcarousel({visible: 3,itemFallbackDimension: 300});
	$('#browseMarkList').jcarousel({visible: 3,itemFallbackDimension: 300});
});
</script>
