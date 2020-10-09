<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/point.css" rel="stylesheet" type="text/css">
<div class="wtp-container">
  <?php if ($_SESSION['is_login'] == '1'){ ?>
  <div class="wtp-member-top">
    <?php include_once BASE_TPL_PATH.'/home/points.minfo.php'; ?>
  </div>
  <?php } ?>
  <div class="wtp-main-box">
    <?php if (intval($_GET['store_id']) <= 0) {?>
    <div class="wtp-category">
      <!-- 高级搜索start -->
      <dl class="searchbox">
        <dt>排序方式：</dt>
        <dd>
          <ul>
            <input type="hidden" id="orderby" name="orderby" value="<?php echo $_GET['orderby']?$_GET['orderby']:'default';?>"/>
            <!-- 默认排序s -->
            <?php if (!$_GET['orderby'] || $_GET['orderby'] == 'default'){ ?>
            <li class="selected">默认排序</li>
            <?php } else { ?>
            <li wt_type="search_orderby" data-param='{"orderval":"default"}'>默认排序</li>
            <?php }?>
            <!-- 默认排序e -->

            <!-- 兑换量s -->
            <?php if ($_GET['orderby'] == 'exchangenumdesc'){//降序选中 ?>
            <li class="selected" wt_type="search_orderby" data-param='{"orderval":"exchangenumasc"}'>兑换量<em class="desc"></em></li>
            <?php } elseif ($_GET['orderby'] == 'exchangenumasc') {//升序选中 ?>
            <li class="selected" wt_type="search_orderby" data-param='{"orderval":"exchangenumdesc"}'>兑换量<em class="asc"></em></li>
            <?php } else {//未选中?>
            <li wt_type="search_orderby" data-param='{"orderval":"exchangenumdesc"}'>兑换量<em class="desc"></em></li>
            <?php } ?>
            <!-- 兑换量e -->

            <!-- 积分值s -->
            <?php if ($_GET['orderby'] == 'pointsdesc'){//降序选中 ?>
            <li class="selected" wt_type="search_orderby" data-param='{"orderval":"pointsasc"}'>积分值<em class="desc"></em></li>
            <?php } elseif ($_GET['orderby'] == 'pointsasc') {//升序选中 ?>
            <li class="selected" wt_type="search_orderby" data-param='{"orderval":"pointsdesc"}'>积分值<em class="asc"></em></li>
            <?php } else {//未选中?>
            <li wt_type="search_orderby" data-param='{"orderval":"pointsdesc"}'>积分值<em class="desc"></em></li>
            <?php } ?>
            <!-- 积分值e -->
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <!-- 所需积分s -->
            <li>所需积分：
              <input type="text" id="points_min" class="text w50" value="<?php echo $_GET['points_min'];?>"/>
              ~
              <input type="text" id="points_max" class="text w50" value="<?php echo $_GET['points_max'];?>" />
              <a href="javascript:searchrpt();" class="wtbtn">搜索</a> </li>
            <!-- 所需积分e -->
            <?php if($_SESSION['is_login'] == '1'){ ?>
            <li>
              <label for="isable"><input type="checkbox" id="isable" <?php echo intval($_GET['isable'])==1?'checked="checked"':'';?> onclick="javascript:searchrpt();">
              &nbsp;只看我能兑换 </label></li>
            <?php } ?>
          </ul>
        </dd>
      </dl>
      <!-- 高级搜索end --></div>
      <?php }?>
    <?php if (!empty($output['rptlist'])){?>
    <ul class="wtp-voucher-list">
      <?php foreach ($output['rptlist'] as $k=>$v){?>
      <li>
        <div class="wtp-voucher">
          <div class="cut"></div>
          <div class="info">
            <div class="pic"><img src="<?php echo $v['coupon_t_customimg_url'];?>" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.defaultGoodsImage(240);?>'"/></div>
          </div>
          <dl class="value">
            <dt><?php echo $lang['currency'];?><em><?php echo $v['coupon_t_price'];?></em></dt>
            <dd><?php if ($v['coupon_t_limit'] > 0){?>购物满<?php echo $v['coupon_t_limit'];?>元可用<?php } else { ?>无限额优惠券<?php } ?></dd>
            <dd class="time">有效期至<?php echo @date('Y-m-d',$v['coupon_t_end_date']);?></dd>
          </dl>
          <div class="point">
            <p class="required">需<em><?php echo $v['coupon_t_points'];?></em>积分</p>
            <p><em><?php echo $v['coupon_t_giveout'];?></em>人兑换</p>
            <?php if ($v['coupon_t_mgradelimit'] > 0){ ?>
            <span style="background-color: #e8e8e8;clear: left;display: block;float: right;font-family: Georgia,Arial;font-size: 18px;height: 53px;line-height: 53px;text-align: center;width: 40px;">
                <?php echo $v['coupon_t_mgradelimittext'];?>
            </span>
            <?php } ?>
          </div>
          <div class="button"><a target="_blank" href="javascript:;" wt_type="rptexchangebtn" data-param='{"tid":"<?php echo $v['coupon_t_id'];?>"}' class="wtbtn wtbtn-grapefruit">立即兑换</a></div>
        </div>
      </li>
      <?php }?>
    </ul>
    <div class="tc mt20 mb20">
      <div class="pagination"><?php echo $output['show_page'];?></div>
    </div>
    <?php }else{?>
    <div class="norecord">暂无优惠券</div>
    <?php }?>
  </div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/home.js" id="wt_dialog" charset="utf-8"></script>
<script>
$(function () {
	$("[wt_type='search_orderby']").click(function(){
		var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    $("#orderby").val(data_str.orderval);
	    searchrpt();
	});
	$("[wt_type='search_cate']").click(function(){
		var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    $("#sc_id").val(data_str.sc_id);
	    searchrpt();
	});
});
function searchrpt(){
	var url = 'index.php?w=pointcoupon&t=index';
	var orderby = $("#orderby").val();
	if(orderby){
		url += ('&orderby='+orderby);
	}
	var points_min = $("#points_min").val();
	if(points_min){
		url += ('&points_min='+points_min);
	}
	var points_max = $("#points_max").val();
	if(points_max){
		url += ('&points_max='+points_max);
	}
	if($("#isable").attr("checked") == 'checked'){
		url += '&isable=1';
	}
	go(url);
}
</script>
