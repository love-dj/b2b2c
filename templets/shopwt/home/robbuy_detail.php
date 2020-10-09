<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<?php require('robbuy_head.php');?>
<div class="wth-breadcrumb-box" style="display: block;">
  <div class="wth-breadcrumb wrapper"> <i class="icon-home"></i> <span> <a href="<?php echo urlShop(); ?>">首页</a> </span> <span class="arrow">></span>
<?php if ($output['robbuy_info']['is_vr']) { ?>
  <span><a href="<?php echo urlShop('robbuy', 'vr_robbuy_list'); ?>">虚拟抢购</a></span>
<?php } else { ?>
  <span><a href="<?php echo urlShop('robbuy', 'robbuy_list'); ?>">线上抢购</a></span>
<?php } ?>
  <span class="arrow">></span> <span><?php echo $output['robbuy_info']['robbuy_name'];?></span> </div>
</div>
<div class="wtg-container wrapper">
  <div class="wtg-box-l">
    <div class="wtg-main <?php echo $output['robbuy_info']['state_flag'];?>">
      <div class="wtg-group">
        <h2><?php echo $output['robbuy_info']['robbuy_name'];?></h2>
        <h3><?php echo $output['robbuy_info']['remark'];?></h3>
        <div class="wtg-item">
          <div class="pic"><img src="<?php echo gthumb($output['robbuy_info']['robbuy_image'],'max');?>" alt=""></div>
          <div class="button"><span><?php echo $lang['currency'];?><em><?php echo wtPriceFormat($output['robbuy_info']['robbuy_price']);?></em></span><a href="<?php echo $output['robbuy_info']['goods_url'];?>" target="_blank"><?php echo $output['robbuy_info']['button_text'];?></a></div>
          <div class="info" id="main-list-bar">
            <div class="prices">
              <dl>
                <dt><?php echo $lang['text_goods_price'];?></dt>
                <dd><del><?php echo $lang['currency'];?><?php echo wtPriceFormat($output['robbuy_info']['goods_price']);?></del></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['text_discount'];?></dt>
                <dd><em><?php echo $output['robbuy_info']['robbuy_rebate'];?><?php echo $lang['text_zhe'];?></em></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['text_save'];?></dt>
                <dd><em><?php echo $lang['currency'];?><?php echo sprintf("%01.2f",$output['robbuy_info']['goods_price']-$output['robbuy_info']['robbuy_price']);?></em></dd>
              </dl>
            </div>
            <div class="trim"></div>
            <div class="require">
              <h4><?php echo $lang['text_goods_buy'];?><em><?php echo $output['robbuy_info']['virtual_quantity']+$output['robbuy_info']['buy_quantity']; ?></em><?php echo $lang['text_piece'];?></h4>
              <p>
                <?php if ($output['buy_limit'] > 0) { ?>
                每次最多购买<em><?php echo $output['buy_limit']; ?></em>件，
                <?php } ?>
                数量有限，欲购从速!</p>
            </div>
            <div class="time time-remain" count_down="<?php echo $output['robbuy_info']['count_down']; ?>">
              <?php if(!empty($output['robbuy_info']['count_down'])) { ?>
              <!-- 倒计时 距离本期结束 -->
              <i class="icon-time"></i>剩余时间：<span time_id="d">0</span><strong><?php echo $lang['text_tian'];?></strong>
              <span time_id="h">0</span><strong><?php echo $lang['text_hour'];?></strong>
              <span time_id="m">0</span><strong><?php echo $lang['text_minute'];?></strong>
              <span time_id="s">0</span><strong><?php echo $lang['text_second'];?></strong>
              <?php } ?>
            </div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="floating-bar">
          <div class="button"><span><?php echo $lang['currency'];?><em><?php echo wtPriceFormat($output['robbuy_info']['robbuy_price']);?></em></span><a href="<?php echo $output['robbuy_info']['goods_url'];?>" target="_blank"><?php echo $output['robbuy_info']['button_text'];?></a></div>
          <div class="prices">
            <dl>
              <dt><?php echo $lang['text_goods_price'];?></dt>
              <dd><del><?php echo $lang['currency'];?><?php echo wtPriceFormat($output['robbuy_info']['goods_price']);?></del></dd>
            </dl>
            <dl>
              <dt><?php echo $lang['text_discount'];?></dt>
              <dd><em><?php echo $output['robbuy_info']['robbuy_rebate'];?><?php echo $lang['text_zhe'];?></em></dd>
            </dl>
            <dl>
              <dt><?php echo $lang['text_save'];?></dt>
              <dd><em><?php echo $lang['currency'];?><?php echo sprintf("%01.2f",$output['robbuy_info']['goods_price']-$output['robbuy_info']['robbuy_price']);?></em></dd>
            </dl>
            <dl>
              <dt>商品来自</dt>
              <dd><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['robbuy_info']['store_id']));?>"><?php echo $output['robbuy_info']['store_name'];?></a></dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
    
    <div class="wtg-title-bar">
      <ul class="tabs-nav">
        <li class="tabs-selected"><a href="javascript:void(0);"><?php echo $lang['goods_content'];?></a></li>
        <li><a href="javascript:void(0);"><?php echo $lang['buyer_list'];?></a></li>
        <li><a href="javascript:void(0);">商品评价(<?php echo $output['evaluate_info']['all'];?>)</a></li>
      </ul>
    </div>
    <div class="wtg-detail-content">
    <!-- S 店铺地址地图 -->
    <div class="wtg-store-map" id="wt-store-map" style="display:none;"></div>
    <!-- E 店铺地址地图 -->
      <?php if ($output['robbuy_info']['is_vr']) { ?>
      <div class="wtg-instructions">
        <h4>使用声明</h4>
        <ul>
          <li> 1. 本次抢购活动的最终有效期至
            <time><?php echo date("Y-m-d H:i", $output['robbuy_info']['end_time']); ?></time>
            <?php if ($output['goods_content']['virtual_indate'] > 0) { ?>
            ，兑换码/券的使用期限是
            <time><?php echo date("Y-m-d H:i", $output['goods_content']['virtual_indate']); ?></time>
            ，逾期未使用将被视为自动放弃兑换
            <?php } ?>
            。 </li>
          <li>2. 消费抢购兑换码/券时，请向商家提供系统发送的“虚拟抢购兑换码”，一码一销。</li>
          <?php if ($output['buy_limit'] > 0) { ?>
          <li>3. 单人每笔订单最多抢购<strong><?php echo $output['buy_limit']; ?></strong>个兑换码/券，如需更多请再次购买。</li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="wtg-intro"><?php echo $output['robbuy_info']['robbuy_intro'];?></div>
    </div>
    <div id="robbuy_order" class="wtg-detail-content hide"></div>
    <div class="wtg-detail-content hide">
      <div class="wtg-evaluate">
        <div class="top">
          <div class="rate">
            <p><strong><?php echo $output['evaluate_info']['good_percent'];?></strong><sub>%</sub>好评</p>
            <span>共有<?php echo $output['evaluate_info']['all'];?>人参与评分</span></div>
          <div class="percent">
            <dl>
              <dt>好评<em>(<?php echo $output['evaluate_info']['good_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['evaluate_info']['good_percent'];?>%"></i></dd>
            </dl>
            <dl>
              <dt>中评<em>(<?php echo $output['evaluate_info']['normal_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['evaluate_info']['normal_percent'];?>%"></i></dd>
            </dl>
            <dl>
              <dt>差评<em>(<?php echo $output['evaluate_info']['bad_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['evaluate_info']['bad_percent'];?>%"></i></dd>
            </dl>
          </div>
          <div class="btns"><span>您可对已购商品进行评价</span>
            <p><a href="<?php if ($output['robbuy_info']['is_vr']) { echo urlShop('member_order_vr', 'index');} else { echo urlShop('member_order', 'index');}?>" class="wtbtn wtbtn-grapefruit" target="_blank"><i class="icon-comment-alt"></i>评价商品</a></p>
          </div>
        </div>
        <!-- 商品评价内容部分 -->
        <div id="robbuy_evaluate" class="wtg-evaluate-main"></div>
      </div>
    </div>
  </div>
  <div class="wtg-box-r">
    <?php if (!$output['store_info']['is_own_shop'] || $output['robbuy_info']['is_vr']) { ?>
    <div class="wtg-store">
      <div class="title"><?php echo $lang['store_info'];?></div>
      <div class="content">
        <div class="wtg-store-info">
          <dl class="name">
            <dt>商&#12288;&#12288;家：</dt>
            <dd> <?php echo $output['robbuy_info']['store_name'];?> </dd>
          </dl>
          <?php if (!$output['store_info']['is_own_shop']) { ?>
          <div class="detail-rate">
            <ul>
              <?php  foreach ($output['store_info']['store_credit'] as $value) {?>
              <li>
                <h5><?php echo $value['text'];?></h5>
                <div class="<?php echo $value['percent_class'];?>" title="<?php echo $value['percent_text'];?><?php echo $value['percent'];?>"><?php echo $value['credit'];?><i></i></div>
              </li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <dl class="messenger">
            <dt>在线客服：</dt>
            <dd member_id="<?php echo $output['store_info']['member_id'];?>">
              <?php if(!empty($output['store_info']['store_qq'])){?>
              <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
              <?php }?>
              <?php if(!empty($output['store_info']['store_ww'])){?>
              <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['wt_message_me'];?>" /></a>
              <?php }?>
            </dd>
          </dl>
          <div class="goto"> <a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['robbuy_info']['store_id']));?>" >进入商家店铺</a></div>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="wtg-module-sidebar">
      <div class="title"><?php echo $lang['current_hot'];?></div>
      <div class="content">
        <div class="wtg-group-command">
          <?php $hot_robbuy_count = 1;?>
          <?php if(is_array($output['commended_robbuy_list'])) { ?>
          <?php foreach($output['commended_robbuy_list'] as $hot_robbuy) { ?>
          <dl <?php if($hot_robbuy_count === 1) { echo "style=' border:none'";$hot_robbuy_count++; }?> >
            <dt class="name"><a href="<?php echo $hot_robbuy['robbuy_url'];?>" target="_blank"><?php echo $hot_robbuy['robbuy_name'];?></a></dt>
            <dd class="pic-thumb"><a href="<?php echo $hot_robbuy['robbuy_url'];?>" target="_blank"><img src="<?php echo gthumb($hot_robbuy['robbuy_image1'],'max');?>"></a></dd>
            <dd class="item"><a href="<?php echo $hot_robbuy['robbuy_url'];?>" target="_blank"><?php echo $lang['to_see'];?></a> <span class="price"><?php echo $lang['currency'].wtPriceFormat($hot_robbuy['robbuy_price']);?></span> <span class="buy"><em><?php echo $hot_robbuy['virtual_quantity']+$hot_robbuy['buy_quantity'];?></em><?php echo $lang['text_piece'].$lang['text_buy'];?></span> </dd>
          </dl>
          <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script>
$(function(){
    //浮动导航  waypoints.js
    $('#main-list-bar').waypoint(function(event, direction) {
        $(this).parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
    //首页Tab标签卡滑门切换
    $(".tabs-nav > li > a").live('mouseover', (function(e) {
    	if (e.target == this) {
    		var tabs = $(this).parent().parent().children("li");
    		var panels = $(this).parent().parent().parent().parent().children(".wtg-detail-content");
    		var index = $.inArray(this, $(this).parent().parent().find("a"));
    		if (panels.eq(index)[0]) {
    			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
    			panels.addClass("hide").eq(index).removeClass("hide");
    		}
    	}
    }));

    $("#robbuy_order").load('<?php echo urlShop('robbuy', 'robbuy_order', array('group_id' => $output['robbuy_info']['robbuy_id'],'is_vr'=>$output['robbuy_info']['is_vr']));?>');
    $("#robbuy_evaluate").load('<?php echo urlShop('robbuy', 'robbuy_evaluate', array('commonid' => $output['robbuy_info']['goods_commonid']));?>');
    <?php if ($output['robbuy_info']['is_vr']) { ?>
    $("#wt-store-map").load('index.php?w=show_map&t=index&width=420&height=400&store_id=<?php echo $output['robbuy_info']['store_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    <?php } ?>
});
</script>