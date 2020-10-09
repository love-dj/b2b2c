<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php foreach($output['goods_list'] as $goods_content) { ?>
<div class="item">
  <div class="scope">
    <dl class="goods">
      <dt class="goods-thumb"> <a title="<?php echo $goods_content['goods_name'];?>" target="_blank" href="<?php echo $goods_content['goods_url'];?>"><img src="<?php echo $goods_content['image_url_240'];?>" /></a> </dt>
      <dd class="goods-name"><span><strong><?php echo $goods_content['xianshi_title'];?></strong></span> <a target="_blank" href="<?php echo $goods_content['goods_url'];?>"><?php echo $goods_content['goods_name'];?></a></dd>
    </dl>
    <div class="goods-price"><span class="sale"><em><?php echo wtPriceFormatForList($goods_content['xianshi_price']);?></em><i><?php echo wtPriceFormatForList($goods_content['goods_price']);?></i></span><span class="depreciate"><i class="icon-long-arrow-down"></i>¥<?php echo $goods_content['down_price'];?></span></div>
    <div class="goods-buy"><a href="javascript:void(0);" wttype="add_cart" data-gid="<?php echo $goods_content['goods_id'];?>" class="btn">加入购物车</a> <span class="raty" data-score="<?php echo $goods_content['evaluation_good_star'];?>" style="width: 100px;"></span> <span class="mt5"><a href="<?php echo urlShop('show_store','index',array('store_id'=>$goods_content['store_id']));?>"><?php echo $goods_content['store_name'];?></a></span> </div>
    <ul class="goodseval">
      <?php if (is_array($output['goodsevallist'][$goods_content['goods_id']])) { ?>
      <?php foreach($output['goodsevallist'][$goods_content['goods_id']] as $k=>$v){ ?>
      <li>
        <div class="user-avatar"> <a <?php if($v['geval_isanonymous'] != 1){?>href="index.php?w=member_snshome&mid=<?php echo $v['geval_frommemberid'];?>" target="_blank" data-param="{'id':<?php echo $v['geval_frommemberid'];?>}" wttype="mcard"<?php }?>> <img src="<?php echo getMemberAvatarForID($v['geval_frommemberid']);?>"> </a> </div>
        <div class="eval"><i class="icon-quote-left"></i><?php echo $v['geval_content'];?><i class="icon-quote-right"></i></div>
      </li>
      <?php } ?>
      <?php } ?>
    </ul>
  </div>
</div>
<?php } ?>
