<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtm-flow-box">
  <div id="wtmInformFlow" class="wtm-flow-container">
    <div class="title">
      <h3><?php echo $lang['inform_page_title']; ?></h3>
    </div>
    <div class="wtm-flow-step">
      <dl class="step-first current">
        <dt>填写举报内容</dt>
        <dd class="bg"></dd>
      </dl>
      <dl class="current">
        <dt>平台审核处理</dt>
        <dd class="bg"> </dd>
      </dl>
      <dl class="<?php if ($output['inform_info']['inform_state'] == 2) {?>current<?php }?>">
        <dt>举报完成</dt>
        <dd class="bg"> </dd>
      </dl>
    </div>
    <div class="wtm-default-form">
      <div id="warning"></div>
      <dl>
        <dt>被举报商家：</dt>
        <dd><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['goods_content']['goods_id']));?>" ><?php echo $output['goods_content']['store_name'];?></a></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['inform_goods_name'].$lang['wt_colon'];?></dt>
        <dd>
          <div class="wtm-inform-item">
            <div class="wtm-goods-thumb-mini"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$output['goods_content']['goods_id']));?>" target="_blank"><img src="<?php echo thumb($output['goods_content'],60); ?>" onMouseOver="toolTip('<img src=<?php echo thumb($output['goods_content'],240); ?>>')" onMouseOut="toolTip()" /></a></div>
            <a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$output['goods_content']['goods_id']));?>" target="_blank"> <?php echo $output['goods_content']['goods_name']; ?> </a></div>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['inform_type'].$lang['wt_colon'];?></dt>
        <dd> <?php echo $output['subject_info']['inform_subject_content'];?> </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['inform_subject'].$lang['wt_colon'];?></dt>
        <dd><?php echo $output['subject_info']['inform_subject_type_name'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['inform_content'].$lang['wt_colon'];?></dt>
        <dd>
          <?php echo $output['inform_info']['inform_content'];?>
        </dd>
      </dl>
      <dl class="noborder">
        <dt><?php echo $lang['inform_pic'].$lang['wt_colon'];?></dt>
        <dd>
        <ul class="wtm-evidence-pic">
        <?php if (!empty($output['inform_info']['inform_pic1'])) {?>
        <li><a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic1'];?>" wttype="nyroModal" rel="gal"><img class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic1'];?>"></a></li>
        <?php }?>
        <?php if (!empty($output['inform_info']['inform_pic2'])) {?>
        <li><a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic2'];?>" wttype="nyroModal" rel="gal"><img class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic2'];?>"></a></li>
        <?php }?>
        <?php if (!empty($output['inform_info']['inform_pic3'])) {?>
        <li><a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic3'];?>" wttype="nyroModal" rel="gal"><img class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/inform/'.$output['inform_info']['inform_pic3'];?>"></a></li>
        <?php }?>
        </ul>
        </dd>
      </dl>
      <div class="bottom"><a href="javascript:history.go(-1);" class="wtbtn"><i class="icon-reply"></i>返回列表</a></div>
    </div>
  </div>
  <div class="wtm-flow-item">
    <div class="title">违规举报须知</div>
    <div class="content">
      <div class="alert">
        <ul>
          <li> 1.请提供充分的证据以确保举报成功，请珍惜您的会员权利，帮助商城更好地管理网站；</li>
          <li> 2.被举报待处理的商品不能反复进行违规提交，处理下架后的商品不能再次举报，商家如重新上架后仍存在违规现象，可再次对该商品进行违规举报；</li>
          <li> 3.举报仅针对商品或商家本身，如需处理交易中产生的纠纷，请选择投诉；</li>
          <li> 4.举报时依次选择举报类型及举报主题（必填），填写违规描述（必填，不超过200字），上传3张以内的举证图片（选填），详细的举报内容有助于平台对该条举报的准确处理。</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" ></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script>
$(document).ready(function(){
   $('a[wttype="nyroModal"]').nyroModal();
});
</script>