<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wts-sidebar-container wts-class-bar">
  <div class="title">
    <h4><?php echo $lang['wt_goods_class'];?></h4>
  </div>
  <div class="content">
    <p><span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '1', 'order' => '2'));?>"><?php echo $lang['wt_by_new'];?></a></span><span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '2', 'order' => '2'));?>"><?php echo $lang['wt_by_price'];?></a></span><span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '3', 'order' => '2'));?>"><?php echo $lang['wt_by_sale'];?></a></span><span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '5', 'order' => '2'));?>"><?php echo $lang['wt_by_click'];?></a></span></p>
    <ul class="wts-submenu">
      <li><span class="ico-none"><em>-</em></span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id']));?>"><?php echo $lang['wt_whole_goods'];?></a></li>
      <?php if(!empty($output['goods_class_list']) && is_array($output['goods_class_list'])){?>
      <?php foreach($output['goods_class_list'] as $value){?>
      <?php if(!empty($value['children']) && is_array($value['children'])){?>
      <li><span class="ico-none" onclick="class_list(this);" span_id="<?php echo $value['stc_id'];?>" style="cursor: pointer;"><em>-</em></span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value['stc_id']));?>"><?php echo $value['stc_name'];?></a>
        <ul id="stc_<?php echo $value['stc_id'];?>">
          <?php foreach($value['children'] as $value1){?>
          <li><span class="ico-sub">&nbsp;</span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value1['stc_id']));?>"><?php echo $value1['stc_name'];?></a></li>
          <?php }?>
        </ul>
      </li>
      <?php }else {?>
      <li> <span class="ico-none"><em>-</em></span><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value['stc_id']));?>"><?php echo $value['stc_name'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>
    
  </div>
</div>

            
 <?php if(!empty($output['goods_rand_list']) && is_array($output['goods_rand_list']) && count($output['goods_rand_list'])>1){?>
    <div class="wts-sidebar-container wt-look-look">
      <div class="title">
        <h4>看了又看</h4>
      </div>
      <div class="content">
        <ul>
<?php foreach ((array) $output['goods_rand_list'] as $g) { ?>
          <li>
            <dl>
              <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'], )); ?>" target="_blank" title="<?php echo $g['goods_name']; ?>"><?php echo $g['goods_name']; ?><em><?php echo $g['goods_jingle'];?></em></a></dt>
              <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'], )); ?>" target="_blank" title="<?php echo $g['goods_name']; ?>"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif"  rel="lazy" data-url="<?php echo cthumb($g['goods_image'], 240); ?>"/></a></dd>
              <dd class="goods-price"><?php echo $lang['currency'];?><?php echo wtPriceFormat($g['goods_sale_price']); ?></dd>
            </dl>
          </li>
          <?php }?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
    <?php }?>
<div class="wts-sidebar-container wts-top-bar">
  <div class="title">
    <h4><?php echo $lang['wt_goods_rankings'];?></h4>
  </div>
  <div class="content">
    <ul class="wts-top-tab pngFix">
      <li id="hot_sales_tab" class="current"><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '3', 'order' => '2'));?>"><?php echo $lang['wt_hot_goods_rankings'];?></a></li>
      <li id="hot_collect_tab"><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id'], 'key' => '4', 'order' => '2'));?>"><?php echo $lang['wt_hot_collect_rankings'];?></a></li>
    </ul>
    <div id="hot_sales_list" class="wts-top-panel">
      <?php if(is_array($output['hot_sales']) && !empty($output['hot_sales'])){?>
      <ol>
        <?php foreach($output['hot_sales'] as $val){?>
        <li>
          <dl>
            <dt><a title="<?php echo $val['goods_name']?>" href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><?php echo $val['goods_name']?></a></dt>
            <dd class="goods-pic"><a title="<?php echo $val['goods_name']?>" href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['goods_id']));?>"><span class="thumb size60"><i></i><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo thumb($val, 60);?>"  onload="javascript:DrawImage(this,60,60);"></span></a>
              <p><span class="thumb size100"><i></i><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $val['goods_name']?>"><big></big><small></small></span></p>
            </dd>
            <dd class="price pngFix"><?php echo wtPriceFormat($val['goods_sale_price']);?></dd>
            <dd class="selled pngFix"><?php echo $lang['wt_sell_out'];?><strong><?php echo $val['goods_salenum'];?></strong><?php echo $lang['wt_bi'];?></dd>
          </dl>
        </li>
        <?php }?>
      </ol>
      <?php }?>
    </div>
    <div id="hot_collect_list" class="wts-top-panel hide">
      <?php if(is_array($output['hot_collect']) && !empty($output['hot_collect'])){?>
      <ol>
        <?php foreach($output['hot_collect'] as $val){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['goods_id']));?>"><?php echo $val['goods_name']?></a></dt>
            <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['goods_id']));?>" title=""><span class="thumb size60"><i></i> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif"  rel="lazy" data-url="<?php echo thumb($val, 60);?>" onload="javascript:DrawImage(this,60,60);"></span></a>
              <p><span class="thumb size100"><i></i><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif"  rel="lazy" data-url="<?php echo thumb($val, 240);?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $val['goods_name']?>"><big></big><small></small></span></p>
            </dd>
            <dd class="price pngFix"><?php echo wtPriceFormat($val['goods_sale_price']);?></dd>
            <dd class="collection pngFix"><?php echo $lang['wt_collection_popularity'].$lang['wt_colon'];?><strong><?php echo $val['goods_collect'];?></strong></dd>
          </dl>
        </li>
        <?php }?>
      </ol>
      <?php }?>
    </div>
    <p><a href="<?php echo urlShop('show_store', 'goods_all', array('store_id' => $output['store_info']['store_id']));?>"><?php echo $lang['wt_look_more_store_goods'];?></a></p>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //热销排行切换
        $('#hot_sales_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_collect_tab').removeClass('current');
            $('#hot_sales_list').removeClass('hide');
            $('#hot_collect_list').addClass('hide');
        });
        $('#hot_collect_tab').on('mouseenter', function() {
            $(this).addClass('current');
            $('#hot_sales_tab').removeClass('current');
            $('#hot_sales_list').addClass('hide');
            $('#hot_collect_list').removeClass('hide');
        });
    });
    /** left.php **/
    // 商品分类
    function class_list(obj){
    	var stc_id=$(obj).attr('span_id');
    	var span_class=$(obj).attr('class');
    	if(span_class=='ico-block') {
    		$("#stc_"+stc_id).show();
    		$(obj).html('<em>-</em>');
    		$(obj).attr('class','ico-none');
    	}else{
    		$("#stc_"+stc_id).hide();
    		$(obj).html('<em>+</em>');
    		$(obj).attr('class','ico-block');
    	}
    }
</script> 
