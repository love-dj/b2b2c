<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script>
var PURL = '<?php echo $output['purl'];?>';
$(document).ready(function(){
    $('#area_info').wt_region();
	$(".wt-store-list li").hover(function() {
		$(this).find(".fav-store").show();
	},
	function() {
		$(this).find(".fav-store").hide();
	});
});
</script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/store_list.css" rel="stylesheet" type="text/css">
<link href="<?php echo STATIC_SITE_URL;?>/js/jcarousel/skins/tango/skin.css" rel="stylesheet" type="text/css">
<style type="text/css">
.sticky #main-nav { width: 1198px;}
.jcarousel-skin-tango .jcarousel-clip-horizontal { width: 1000px !important; height: 225px !important;}
.jcarousel-skin-tango .jcarousel-item { height: 225px !important;}
.jcarousel-skin-tango .jcarousel-container-horizontal { width: 1000px !important;}
</style>

<script type="text/javascript" src="<?php echo STATIC_SITE_URL.'/js/Jquery.Query.js';?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
/* 替换参数 */
function ss_replaceParam(key, value)
{
    location.assign($.query.set('key', key).set('order', value));
}

/* 替换参数 */
function ss_dropParam(key1, key2)
{
	location.assign($.query.REMOVE(key1).REMOVE(key2));
}

/* 替换参数 */
function ss_dropParam2(key1)
{
	location.assign($.query.REMOVE(key1));
}

/* 替换参数 */
function ss_replaceParam2(key, value)
{
    location.assign($.query.set(key, value, value));
}

$(function (){
    var order = '<?php echo $_GET['order'];?>';
    var arrow = '';
    var class_val = 'sort_desc';

    switch (order){
        case 'store_credit desc' : order = 'store_credit asc';  class_val = 'sort_desc'; break;
        case 'store_credit asc'  : order = 'store_credit desc'; class_val = 'sort_asc' ; break;
        default : order = 'store_credit asc';
    }
    $('#credit_grade').addClass(class_val);
    $('#credit_grade').click(function(){query('order', order);return false;});
}
);

function query(name, value){
    $("input[name='"+name+"']").val(value);
    $('#searchStore').submit();
}

//]]>
</script>
<div class="content">
<div class="wt-store-class">
  <?php if(!empty($output['class_list']) && is_array($output['class_list'])){?>
  <dl>
    <dd>
      <a href="<?php echo urlShop('store','index');?>">所有店铺</a>
      <?php foreach($output['class_list'] as $k=>$v){?>
	      <?php if ($_GET['cate_id'] == $v['sc_parent_id']){?>
	      <a href="<?php echo urlShop('store','index',array('cate_id'=>$k));?>"><?php echo $v['sc_name'];?></a>
	      <?php }elseif (!isset($v['child']) && $output['class_list'][$_GET['cate_id']]['sc_parent_id'] == $v['sc_parent_id']){?>
	      <a href="<?php echo urlShop('store','index',array('cate_id'=>$k));?>"><?php echo $v['sc_name'];?></a>
	      <?php }?>
      <?php }?>
    </dd>
  </dl>
  <?php }?>
</div>
<div class="sort-bar">
  <div class="shop_con_list" id="main-list-bar">
    <nav class="wt-gl-sort-bar" id="main-nav">
      <form id="store_list" method="GET" action="index.php">
        <input type="hidden" name="order" value="<?php echo $_GET['order'];?>"/>
        <input type="hidden" name="w" value="store"/>
        <input type="hidden" name="cate_id" value="<?php echo $_GET['cate_id'];?>"/>
        <div class="sort-bar"><!-- 排序方式S -->
          <ul class="array">
          <!-- 默认 -->
            <li <?php if(!$_get['key']){?>class="selected"<?php }?>><a href="javascript:void(0)" class="nobg" onclick="javascript:ss_dropParam('key','order');" title="<?php echo $lang['goods_class_index_default_sort'];?>">默认</a></li>
            <!-- 销量 -->
            <li <?php if($_get['key'] == 'store_sales'){?>class="selected"<?php }?>><a href="javascript:void(0)" <?php if($_get['key'] == 'store_sales'){?>class="<?php echo $_GET['order'];?>"<?php }?> onclick="javascript:ss_replaceParam('store_sales','<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'store_sales')?'asc':'desc' ?>');" title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'store_sales')?$lang['store_class_index_sold_asc']:$lang['store_class_index_sold_desc']; ?>">销量</a></li>
            <!-- 信用 -->
            <li <?php if($_get['key'] == 'store_credit'){?>class="selected"<?php }?>><a href="javascript:void(0)" <?php if($_get['key'] == 'store_credit'){?>class="<?php echo $_GET['order'];?>"<?php }?> onclick="javascript:ss_replaceParam('store_credit','<?php  echo ($_GET['order'] == 'desc' && $_GET['key'] == 'store_credit')?'asc':'desc' ?>');" title="<?php  echo ($_GET['order'] == 'desc' && $_GET['key'] == 'store_credit')?$lang['store_class_index_credit_asc']:$lang['store_class_index_credit_desc']; ?>">信用</a></li>
          </ul>
          <!-- 排序方式E -->
          
          <div class="sidebox">
            <h5 class="title">店铺名称<?php echo $lang['wt_colon'];?></h5>
            <div class="selectbox">
              <input class="text" type="text" name="keyword" value="<?php echo $_GET['keyword'];?>" style=" width:150px;"/>
            </div>
          </div>
          <div class="sidebox">
            <h5><label for="area_info">店铺所在地<?php echo $lang['wt_colon'];?></label></h5>
            <div class="selectbox">
              <input id="area_info" name="area_info" type="hidden" value=""/>
            </div>
          </div>
          <div class="sidebox width5" style=" background-image: none">
            <div class="selectbox">
              <input class="btn" type="submit" value="<?php echo $lang['wt_search'];?>" />
            </div>
          </div>
          
        </div>
      </form>
    </nav>
  </div>
</div>
<ul class="wt-store-list">
<?php if(!empty($output['store_list']) && is_array($output['store_list'])){?>
<?php foreach($output['store_list'] as $skey => $store){?>
    <li class="item">
		<span class="fav-store"> <a href="javascript:collect_store('<?php echo $store['store_id'];?>','count','store_collect')" wt_data="<?php echo $store['store_id'];?>"> <i class="icon fa icon-star-empty"></i> 收藏店铺</a> </span>
      <dl class="shop-info">
        <dt class="shop-name"><a href="<?php echo urlShop('show_store','', array('store_id'=>$store['store_id']),$store['store_domain']);?>" target="_blank"><?php echo $store['store_name'];?></a> <img src="<?php echo SHOP_TEMPLATES_URL;?><?php echo '/images/store_grade/'.$store['grade_id'].'.png'; ?>" class="pngFix"></dt>
        <dd class="shop-pic"><a href="<?php echo urlShop('show_store','', array('store_id'=>$store['store_id']),$store['store_domain']);?>" title="" target="_blank"><span class="size90"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" rel="lazy" data-url="<?php echo getStoreLogo($store['store_avatar']);?>"  alt="<?php echo $store['store_name'];?>" title="<?php echo $store['store_name'];?>" class="size90" /></span></a></dd>
        
        <dd class="shopkeeper"><?php echo $lang['store_class_index_owner'].$lang['wt_colon'];?><?php echo $store['member_name'];?><a target="_blank" class="message" href="index.php?w=member_message&t=sendmsg&member_id=<?php echo $store['member_id'];?>"></a><dd>
	<dd class="main-runs" title="<?php echo $store['store_zy']?>">主营：<?php echo $store['store_zy']?></dd>
	</dd>
      </dl>

      <dl class="w150">
        <dd><?php echo ($tmp = $store['goods_count']) ? $lang['store_class_index_goods_amount'].$tmp.$lang['piece'] : $lang['wt_common_goods_null'];?></dd>
        <dd><?php echo ($tmp = $store['num_sales_jq']) ? $lang['store_class_index_deal'].$tmp.$lang['store_class_index_jian'] : $lang['wt_common_sell_null'];?></dd>
        <?php if (!empty($store['search_list_goods'])){?>
        <dd class="more-on" attr='morep' wt_type='<?php echo $skey;?>'><span><?php echo $lang['store_class_index_goods_hiden'];?></span><i></i></dd>
        <?php }?>
      </dl>
      <dl class="w200">
      	<!-- 店铺信用度 -->
        <dd><?php if (empty($store['store_credit_average'])){ echo $lang['wt_common_credit_null']; }else {?>
          <?php echo $lang['store_class_index_credit_value'].$lang['wt_colon'];?>
          <span class="seller-heart level-<?php echo floor($store['store_credit_average']) ?>"></span>
          <?php }?>
        </dd>
        <!-- 店铺好评率 -->
        <dd>
        <?php if (empty($store['store_credit_percent'])){?>
        	<?php echo $lang['wt_common_rate_null'];?>
        <?php }else{?>	
        	<?php echo $lang['store_class_index_praise_rate'].$lang['wt_colon'].$store['store_credit_percent'];?>%
        <?php }?>
        </dd>
        <!-- 店铺动态评分 -->
        <dd class="shop-rate" wt_type="shop-rate" store_id='<?php echo $store['store_id'];?>'><?php echo $lang['store_class_index_shop_rate'].$lang['wt_colon'];?><span><i></i></span>
          <div class="shop-rate-con">
              <div class="arrow"></div>
              <dl class="rate">
                <?php  foreach ($store['store_credit'] as $key=>$value) {?>
                  <dt><?php echo $value['text'].$lang['wt_colon'];?></dt>
                  <dd class="rate-star"><em><i style=" width: <?php echo @round($value['credit']/5*100,2);?>%;"></i></em><span><?php echo $value['credit'];?><?php echo $lang['store_class_index_grade'];?></span></dd>
                <?php } ?>
              </dl>
          </div>
          </dd>
      </dl>
      <dl class="w250">
       
	<?php if(!empty($store['store_phone'])){?>
	电话：<?php echo $store['store_phone']?>
	<?php }?>
	</dd>
	<dd>
	客服：
        <?php if(!empty($store['store_qq'])){?>
          <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $store['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $store['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $store['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(!empty($store['store_ww'])){?>
         <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $store['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $store['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
        <?php }?>
	</span>
	<span member_id="<?php echo $store['member_id'];?>"></span>
	
	</dd>
	 <dd>
	 <?php if(!empty($store['area_info'])){?>
	 地址：<?php echo $store['area_info'];?>
	 <?php }?>
	 </dd>
        <dd>
       </dl>
      
      <?php if(!empty($store['search_list_goods']) && is_array($store['search_list_goods'])){?>
      <div class="wt-shop-goodslist" wt_type='goods_<?php echo $skey;?>'>
        <div class="arrow"></div>
        <ul class="jcarousel-skin-tango" wt_type="jcarousel" >
        <?php foreach($store['search_list_goods'] as $k=>$v){?>
        <li>
            <dl>
              <dt class="goods-pic"><span class="thumb size160"> <i></i> <a href="index.php?w=goods&goods_id=<?php echo $v['goods_id'];?>" target="_blank"> <img  onload="javascript:DrawImage(this,160,160);" alt="<?php echo $v['goods_name'];?>" width="160" height="160"  src="<?php echo thumb($v,'small');?>"></a></span></dt>
              <dd class="goods-name"><a href="<?php echo urlShop('goods','',array('goods_id'=>$v['goods_id']));?>" title="<?php echo $v['goods_name'];?>" target="_blank"><?php echo $v['goods_name'];?></a></dd>
              <dd class="goods-price"><em><?php echo $v['goods_price'];?></em></dd>
              <dd class="goods-sales"><?php echo $lang['store_class_index_deal'];?><?php echo $v['goods_salenum'];?><?php echo $lang['store_class_index_jian'];?></dd>
            </dl>
          </li>
         <?php }?>
        </ul>
      </div>
      <?php }?>
    </li>

<?php }?>

<?php }else{?>
<div id="no_results"><?php echo $lang['store_class_index_no_record'];?></div>
<?php }?>
</ul>
<div class="pagination"> <?php echo $output['show_page'];?> </div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js"></script> 

<script type="text/javascript">
$(function(){
	//图片轮换
    $('[wt_type="jcarousel"]').jcarousel({visible: 4});
    $('[attr="morep"]').click(function(){
    	var id = $(this).attr('wt_type');
    	if($(this).attr('class')=='more-off'){
    		$(this).addClass('more-on').removeClass('more-off').html('<?php echo $lang['store_class_index_goods_hiden'];?><i></i>');
    		$('div[wt_type="goods_'+id+'"]').show();
    	}else{
    		$(this).addClass('more-off').removeClass('more-on').html('<?php echo $lang['store_class_index_goods_show'];?><i></i>');
    		$('div[wt_type="goods_'+id+'"]').hide();
    	}
    });
   
});
</script>
