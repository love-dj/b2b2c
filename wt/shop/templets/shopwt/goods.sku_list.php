<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtap-goods-sku" >
  <div class="title">
    <h4>SKU编号</h4>
    <h4>SKU图片</h4>
    <h4>SKU库存</h4>
    <h4>SKU价格(元)</h4>
    <h4>操作</h4>
  </div>
  <div class="content">
    <ul>
      <?php foreach ($output['goods_list'] as $val) {?>
      <li> <span><?php echo $val['goods_id'];?></span> <span><img src="<?php echo $val['goods_image'];?>" onMouseOver="toolTip('<img src=<?php echo $val['goods_image'];?>>')" onMouseOut="toolTip()"></span> <span><?php echo $val['goods_storage'];?></span> <span><?php echo $val['goods_price'];?></span> <span><a href="index.php?w=goods&t=add&gid=<?php echo $val['goods_id'];?>">评论添加</a></span></li>
      <?php }?>
    </ul>
  </div>
</div>
<script type="text/javascript">
$(function(){
//自动加载滚动条
    $('.content').perfectScrollbar();
});
</script> 