<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['list']) && is_array($output['list'])) {?>
<script type="text/javascript">
$(document).ready(function(){
    $("[wt_type=what_like]").what_like({type:'store'});
});
</script>
<?php foreach($output['list'] as $key=>$value) {?>

<div class="what-store-list">
  <?php if($output['owner_flag'] === TRUE){ ?>
  <?php if($_GET['t'] == 'like_list') { ?>
  <!-- 喜欢删除按钮 -->
  <div class="del"><a wt_type="like_drop" like_id="<?php echo $output['like_store_list'][$value['what_store_id']]['like_id'];?>" href="javascript:void(0)" title="<?php echo $lang['wt_delete'];?>">&nbsp;</a></div>
  <?php } ?>
  <?php } ?>
  <div class="top"><span class="goods-count"><strong><?php echo $value['goods_count'];?></strong><?php echo $lang['what_text_jian'].$lang['what_text_goods'];?></span>
    <h2><a href="<?php echo WHAT_SITE_URL.'/index.php?w=store&t=detail&store_id='.$value['what_store_id'];?>"><?php echo $value['store_name'];?></a></h2>
  </div>
  <div style="zoom:1;">
    <div class="what-store-info">
      <dl>
        <dt><?php echo $lang['what_text_store_member_name'];?><?php echo $lang['wt_colon'];?></dt>
        <dd><?php echo $value['member_name'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['what_text_store_area'];?><?php echo $lang['wt_colon'];?></dt>
        <dd><?php echo $value['area_info'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['what_text_store_zy'];?><?php echo $lang['wt_colon'];?></dt>
        <dd><?php echo $value['store_zy'];?></dd>
      </dl>
      <dl>
        <dt><?php echo $lang['what_text_store_favorites'];?><?php echo $lang['wt_colon'];?></dt>
        <dd><strong wttype="store_collect"><?php echo $value['store_collect']?></strong><?php echo $lang['wt_person'];?><?php echo $lang['wt_collect'];?></dd>
      </dl>
      <div class="handle"><span class="like-btn"><a wt_type="what_like" like_id="<?php echo $value['what_store_id'];?>" href="javascript:void(0)"><i class="pngFix"></i><span><?php echo $lang['what_text_like'];?></span><em><?php echo $value['like_count']<=999?$value['like_count']:'999+';?></em></a></span> <span class="comment"><a href="<?php echo WHAT_SITE_URL.'/index.php?w=store&t=detail&store_id='.$value['what_store_id'];?>"><i class="pngFix" title="<?php echo $lang['what_text_comment'];?>">&nbsp;</i><em><?php echo $value['comment_count']<=999?$value['comment_count']:'999+';?></em></a></span> </div>
    </div>
    <?php if(!empty($value['hot_sales_list']) && is_array($value['hot_sales_list'])) { ?>
    <div class="what-store-info-image">
      <ul>
        <?php $i = 1;?>
        <?php foreach($value['hot_sales_list'] as $k=>$v){?>
        <li style="background-image: url(<?php echo thumb($v, 240);?>)" title="<?php echo $v['goods_name'];?>"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>" target="_blank">&nbsp;</a> <em><?php echo $v['goods_store_price'];?></em> </li>
        <?php if($i >=5) break; ?>
        <?php $i++; ?>
        <?php }?>
      </ul>
    </div>
    <?php } else {?>
    <div class="no-content">
        <p><?php echo $lang['what_store_commend_goods_none'];?></p>
    </div>
    <?php }?>
</div>
</div>
<?php } ?>
<div class="pagination"> <?php echo $output['show_page'];?> </div>
<?php } else { ?>
<?php if($_GET['t'] == 'like_list') { ?>
<div class="no-content">
<i class="store">&nbsp;</i>
<?php if($output['owner_flag'] === TRUE) { ?>
<p><?php echo $lang['what_store_like_list_none_owner'];?></p>
<?php } else { ?>
<p><?php echo $lang['wt_quote1'];?><?php echo $output['member_info']['member_name'];?><?php echo $lang['wt_quote2'];?><?php echo $lang['what_store_like_list_none'];?></p>
<?php } ?>
<?php } else { ?>
<div class="no-content">
<i class="store">&nbsp;</i>
<p><?php echo $lang['what_store_list_none'];?></p>
<?php } ?>
<?php } ?>
