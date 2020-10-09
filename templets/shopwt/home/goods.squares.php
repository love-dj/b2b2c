<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
#box { background: #FFF; width: 238px; height: 410px; margin: -390px 0 0 0; display: block; border: solid 4px #E4393C; position: absolute; z-index: 999; opacity: .5 }
.shopMenu { position: fixed; z-index: 1; right: 25%; top: 0; }
</style>
<div class="squares" wt_type="current_display_mode">
  <input type="hidden" id="lockcompare" value="unlock" />
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['goods_list'] as $value){?>
    <li class="item">
      <div class="goods-warp" wttype_goods=" <?php echo $value['goods_id'];?>" wttype_store="<?php echo $value['store_id'];?>">
        <div class="goods-pic"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_name'];?>"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo cthumb($value['goods_image'], 240,$value['store_id']);?>" rel="lazy" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a></div>
        <?php if (C('robbuy_allow') && $value['goods_sale_type'] == 1) {?>
        <div class="goods-sale"><span>抢购商品</span></div>
        <?php } elseif (C('sale_allow') && $value['goods_sale_type'] == 2)  {?>
        <div class="goods-sale"><span>限时折扣</span></div>
        <?php }?>
        <div class="goods-content">
          <div class="goods-small-pic">
            <ul>
              <?php if(!empty($value['image'])) { array_splice($value['image'], 5);?>
              <?php $i=0;foreach ($value['image'] as $val) {$i++?>
              <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo cthumb($val, 60,$value['store_id']);?>"/></a></li>
              <?php }?>
              <?php } else {?>
              <li class="selected"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo cthumb($value['goods_image'], 60,$value['store_id']);?>" /></a></li>
              <?php }?>
            </ul>
          </div>
          <div class="goods-price"> <em class="sale-price" title="<?php echo $lang['goods_class_index_store_goods_price'].$lang['wt_colon'].$lang['currency'].wtPriceFormat($value['goods_sale_price']);?>"><?php echo wtPriceFormatForList($value['goods_sale_price']);?></em>
            <?php if($value["contractlist"]){?>
            <div class="goods-slogen">
              <?php foreach($value["contractlist"] as $gcitem_k=>$gcitem_v){?>
              <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>">
                <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo $gcitem_v['cti_icon_url_60'];?>"/>
              </span>
              <?php }?>
            </div>
            <?php }?>
            </div>
          <div class="goods-name"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php echo $value['goods_name_highlight'];?><em><?php echo $value['goods_jingle'];?></em></a></div>
          <div class="sell-stat">
            <ul>
              <li>已有<a href="<?php echo urlShop('goods', 'comments_list', array('goods_id' => $value['goods_id']));?>" target="_blank"><?php echo $value['evaluation_count'];?></a>人评价
              </li>
               <li class="right">已销<a href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id']));?>#wtGoodsRate" target="_blank" class="status"><?php echo $value['goods_salenum'];?></a>
              </li>
            </ul>
          </div>
          <div class="store">
          <a href="<?php echo urlShop('show_store','index',array('store_id'=>$value['store_id']), $value['store_domain']);?>" title="<?php echo $value['store_name'];?>" class="name"><?php echo $value['store_name'];?></a>
         <span>
            <?php if(C('node_chat')){ ?>
			  <em member_id="<?php echo $value['member_id'];?>">&nbsp;</em>
			   <?php }?>
			  <?php if ($value['store_qq']) {?>
              <a title="QQ客服:<?php echo $value['store_qq'];?>" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $value['store_qq'];?>&amp;site=qq&amp;menu=yes" target="_blank"><img border="0" style=" vertical-align: middle;" src="http://wpa.qq.com/pa?p=2:<?php echo $value['store_qq'];?>:52"></a>
			   <?php }?>
            <span>
          </div>
          <div class="goods-sub">
          <!--<?php if ($value['is_own_shop'] == 1) {?>
           <span class="own" title="自营店铺">自营</span>
           <?php } ?>-->
            <?php if ($value['is_virtual'] == 1) {?>
            <span class="virtual" title="虚拟兑换商品">虚拟兑换</span>
            <?php }?>
            <?php if ($value['is_fcode'] == 1) {?>
            <span class="fcode" title="F码优先购买商品">F码优先</span>
            <?php }?>
            <?php if ($value['is_book'] == 1) {?>
            <span class="book" title="支付定金预定商品">预定</span>
            <?php }?>
            <?php if ($value['is_presell'] == 1) {?>
            <span class="presell" title="预售购买商品">预售</span>
            <?php }?>
            <?php if ($value['have_gift'] == 1) {?>
            <span class="gift" title="捆绑赠品">赠品</span>
            <?php }?>
            </div>  
          <div class="goods-opt">
            <?php if ($value['goods_storage'] == 0) {?>
            <a class="add-cart" href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){?>login_dialog();<?php }else{?>ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php }?>"><i class="icon-bullhorn"></i>到货通知</a>
            <?php } else {?>
            <?php if ($value['is_virtual'] == 1 || $value['is_fcode'] == 1 || $value['is_presell'] == 1 || $value['is_book'] == 1) {?>
            <a class="add-cart" href="javascript:void(0);" wttype="buy_now" data-param="{goods_id:<?php echo $value['goods_id'];?>}"><i class="icon-shopping-cart"></i>
            <?php if ($value['is_fcode'] == 1) {
                echo 'F码购买';
            } else if ($value['is_book'] == 1) {
                echo '支付定金';
            } else if ($value['is_presell'] == 1) {
                echo '预售购买';
            } else {
                echo '立即购买'; 
            }?>
            </a>
            <?php } else {?>
            <a class="add-cart" href="javascript:void(0);" wttype="add_cart" data-gid="<?php echo $value['goods_id'];?>"><i class="icon-shopping-cart"></i>加入购物车</a>
            <?php }?>
            <?php }?> 
            <span class="goods-compare" wt_type="compare_<?php echo $value['goods_id'];?>" data-param='{"gid":"<?php echo $value['goods_id'];?>"}'><i></i>加入对比</span>
          </div>
        </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <style type="text/css">.wth-container .left{width: 100%;}</style>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
  <?php }?>
</div>
<form id="buynow_form" method="post" action="<?php echo BASE_SITE_URL;?>/index.php" target="_blank">
  <input id="w" name="w" type="hidden" value="buy" />
  <input id="t" name="t" type="hidden" value="buy_step1" />
  <input id="goods_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.raty').raty({
            path: "<?php echo STATIC_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function() {
              return $(this).attr('data-score');
            }
        });
      	//初始化对比按钮
    	initCompare();
    });
</script> 
