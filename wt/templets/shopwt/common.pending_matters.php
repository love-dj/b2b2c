<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<ul class="cp-toast-list">
  <?php if ($output['statistics']['cashlist'] > 0) {?>
  <li>
    <span>[商城-会员]</span>
    <a target="workspace" href="<?php echo urlAdminshop('predeposit','pd_cash_list');?>" onclick="openItem('shop|predeposit')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['cashlist'];?></strong>条预存款提现申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['store_joinin'] > 0) {?>
  <li>
    <span>[商城-店铺]</span>
    <a target="workspace" href="<?php echo urlAdminshop('store', 'store_joinin');?>" onclick="openItem('shop|store')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['store_joinin'];?></strong>条开店申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['store_reopen_applay'] > 0) {?>
  <li>
    <span>[商城-店铺]</span>
    <a target="workspace" href="<?php echo urlAdminshop('store', 'reopen_list');?>" onclick="openItem('shop|store')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['store_reopen_applay'];?></strong>条开店续签申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['store_bind_class_applay']) {?>
  <li>
    <span>[商城-店铺]</span>
    <a target="workspace" href="<?php echo urlAdminshop('store', 'store_bind_class_applay_list');?>" onclick="openItem('shop|store')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['store_bind_class_applay'];?></strong>条经营类目申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['store_expire']) {?>
  <li>
    <span>[商城-店铺]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|store')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['store_expire'];?></strong>家店铺即将到期。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['store_expired']) {?>
  <li>
    <span>[商城-店铺]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|store')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['store_expired'];?></strong>家店铺已经到期。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['product_verify']) {?>
  <li>
    <span>[商城-商品]</span>
    <a target="workspace" href="<?php echo urlAdminshop('goods', 'goods', array('type' => 'waitverify'));?>" onclick="openItem('shop|goods')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['product_verify'];?></strong>个商品需要审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['inform']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|inform')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['inform'];?></strong>条举报需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['brand_apply']) {?>
  <li>
    <span>[商城-商品]</span>
    <a target="workspace" href="<?php echo urlAdminshop('brand', 'brand_apply');?>" onclick="openItem('shop|brand')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['brand_apply'];?></strong>个新增品牌需要审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['refund']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|refund')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['refund'];?></strong>个实物退款申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['return']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|return')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['return'];?></strong>个实物退货申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['vr_refund']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|vr_refund')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['vr_refund'];?></strong>个虚拟退款申请需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['complain_new']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|complain')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['complain_new'];?></strong>条投诉需要处理。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['complain_handle']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="<?php echo urlAdminshop('complain', 'complain_handle_list')?>" onclick="openItem('shop|complain')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['complain_handle'];?></strong>条投诉等待仲裁。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['robbuy_verify']) {?>
  <li>
    <span>[商城-促销]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|robbuy')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['robbuy_verify'];?></strong>个抢购申请需要审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['points_order']) {?>
  <li>
    <span>[商城-交易]</span>
    <a target="workspace" href="<?php echo urlAdminshop('pointprod', 'pointorder_list');?>" onclick="openItem('shop|pointprod')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['points_order'];?></strong>个积分订单需要发货。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['check_billno']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="<?php echo urlAdminshop('bill', 'show_statis');?>" onclick="openItem('shop|bill')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['check_billno'];?></strong>个实物账单等待审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['check_vr_billno']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="<?php echo urlAdminshop('bill', 'show_statis')?>" onclick="openItem('shop|bill')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['check_vr_billno'];?></strong>个虚拟订单等待审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['pay_billno']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="<?php echo urlAdminshop('bill', 'show_statis')?>" onclick="openItem('shop|bill')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['pay_billno'];?></strong>个实物账单等待支付。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['pay_vr_billno']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="<?php echo urlAdminshop('bill', 'show_statis')?>" onclick="openItem('shop|bill')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['pay_vr_billno'];?></strong>个虚拟账单等待支付。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['mall_consult']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="javascript:void(0);" onclick="openItem('shop|mall_consult')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['mall_consult'];?></strong>个客户提问需要回复。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['delivery_point']) {?>
  <li>
    <span>[商城-运营]</span>
    <a target="workspace" href="<?php echo urlAdminshop('delivery', 'index', array('sign' => 'verify'));?>" onclick="openItem('shop|delivery')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['delivery_point'];?></strong>个物流自提服务站申请需要处理。</a>
  </li>
  <?php }?>
  <?php if (C('news_isuse')) {?>
  <?php if ($output['statistics']['news_article_verify']) {?>
  <li>
    <span>[资讯-文章]</span>
    <a target="workspace" href="<?php echo urlAdminCms('news_article', 'news_article_list_verify');?>" onclick="openItem('news|news_article')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['news_article_verify'];?></strong>个文章需要审核。</a>
  </li>
  <?php }?>
  <?php if ($output['statistics']['news_picture_verify']) {?>
  <li>
    <span>[资讯-图刊]</span>
    <a target="workspace" href="<?php echo urlAdminCms('news_picture', 'news_picture_list_verify');?>" onclick="openItem('news|news_picture')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['news_picture_verify'];?></strong>个图刊需要审核。</a>
  </li>
  <?php }?>
  <?php }?>
  <?php if (C('bbs_isuse')) {?>
  <?php if ($output['statistics']['bbs_verify']) {?>
  <li>
    <span>[社区-圈组]</span>
    <a target="workspace" href="<?php echo urlAdminbbs('bbs_manage', 'bbs_verify');?>" onclick="openItem('bbs|bbs_manage')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['bbs_verify'];?></strong>个社区需要审核。</a>
  </li>
  <?php }?>
<?php }?>
  <?php if (C('fenxiao_isuse')) {?>
      <?php if ($output['statistics']['fx_verify']) {?>
          <li>
              <span>[分销-分销认证]</span>
              <a target="workspace" href="<?php echo urlAdminfenxiao('fx_member', 'auth_up');?>" onclick="openItem('fenxiao|fx_member')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['fx_verify'];?></strong>个分销认证需要审核。</a>
          </li>
      <?php }?>
      <?php if ($output['statistics']['fx_cash']) {?>
		  <li>
			<span>[分销-佣金提现]</span>
			<a target="workspace" href="<?php echo urlAdminfenxiao('fx_cash', 'index');?>" onclick="openItem('fenxiao|fx_cash')"><i class="fa fa-bell-o"></i>有<strong><?php echo $output['statistics']['fx_cash'];?></strong>个佣金提现需要处理。</a>
		  </li>
      <?php }?>
  <?php }?>
</ul>
