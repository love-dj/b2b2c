<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品列表</h3>
        <h5>商城推荐组合促销活动设置与管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <form id="add_form" method="post" action="<?php echo urlAdminShop('sale_combo', 'combo_setting');?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="sale_combo_price"><em>*</em>购买单价（元/月）</label>
        </dt>
        <dd class="opt">
          <input type="text" id="sale_combo_price" name="sale_combo_price" value="<?php echo $output['setting']['sale_combo_price'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买推荐组合活动所需费用，购买后商家可以在所购买周期内使用推荐组合功能，商品订购结束时间不得超过套餐结束时间</p>
          <p class="notic">相关费用会在店铺的账期结算中扣除</p>
          <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
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
            sale_combo_price: {
                required : true,
                digits : true
            }
        },
        messages : {
            sale_combo_price: {
                required : '<i class="fa fa-exclamation-bbs"></i>请填写套餐价格',
                digits : '<i class="fa fa-exclamation-bbs"></i>请填写套餐价格'
                }
        }
    });
});
</script>
