<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_sale_booth'];?></h3>
        <h5><?php echo $lang['wt_sale_booth_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="<?php echo urlAdminShop('sale_booth', 'goods_list');?>">商品列表</a></li>
        <li><a href="<?php echo urlAdminShop('sale_booth', 'booth_quota_list');?>">套餐列表</a></li>
        <li><a href="JavaScript:void(0);" class="current">设置</a></li>
      </ul>
    </div>
  </div>
  <form id="add_form" method="post" action="<?php echo urlAdminShop('sale_booth', 'booth_setting');?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="sale_booth_price"><em>*</em>购买单价（元/月）</label>
        </dt>
        <dd class="opt">
          <input type="text" id="sale_booth_price" name="sale_booth_price" value="<?php echo $output['setting']['sale_booth_price'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买推荐展位活动所需费用，购买后商家可以在所购买周期内推荐商品，在商品列表热卖商品中随机显示</p>
          <p class="notic">相关费用会在店铺的账期结算中扣除</p>
          <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sale_booth_goods_sum"><em>*</em>允许推荐商品最大数量</label>
        </dt>
        <dd class="opt">
          <input type="text" id="sale_booth_goods_sum" name="sale_booth_goods_sum" value="<?php echo $output['setting']['sale_booth_goods_sum'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">每个店铺推荐商品的最大数量。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#submitBtn").click(function(){
 		$("#add_form").submit();
	});
    //页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
		},
		rules : {
		    sale_booth_price: {
				required : true,
				digits : true
			},
			sale_booth_goods_sum: {
				required : true,
				digits : true,
				min : 1
			}
		},
		messages : {
		    sale_booth_price: {
				required : '<i class="fa fa-exclamation-bbs"></i>请填写展位价格',
				digits : '<i class="fa fa-exclamation-bbs"></i>请填写展位价格'
			},
			sale_booth_goods_sum: {
				required : '<i class="fa fa-exclamation-bbs"></i>不能为空，且不小于1的整数',
				digits : '<i class="fa fa-exclamation-bbs"></i>不能为空，且不小于1的整数',
				min : '<i class="fa fa-exclamation-bbs"></i>不能为空，且不小于1的整数'
			}
		}
	});
});
</script>
