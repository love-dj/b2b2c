<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
.wth-activity { width: 1200px; margin: 10px auto; overflow: hidden;}
#banner_box .pic { width:100%; height: 380px;}
.sale { width: 100%; overflow: hidden;}
.sale .list_pic { font-size: 0; *word-spacing:-1px/*IE6、7*/; width: 1199px; margin: 0 auto; border: solid #E6E6E6; border-width: 1px 0 0 1px;}
.sale .list_pic li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display:inline/*IE7*/; width: 240px; margin: 0 -1px 0 0; *zoom:1;}
.sale .list_pic li dl { width: 200px; padding: 21px 20px 21px 21px; border-style: solid; border-width: 0 1px 1px 0; border-color: transparent #E6E6E6 #E6E6E6 transparent; position: relative;}
.sale .list_pic li dl { transition: border-color 0.4s ease-in-out 0s;}
.sale .list_pic li dl:hover { padding: 19px; border: solid 2px #F30; box-shadow: 0 0 3px rgba(204,204,204,0.9);}
.sale .list_pic li dl dt { width: 200px; height: 200px; margin-bottom: 10px;}
.sale .list_pic li dl dt a { line-height: 0; background-color: #FFF; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 200px; height: 200px; overflow: hidden;}
.sale .list_pic li dl dt img  {max-width: 200px; max-height: 200px; margin-top:expression(200-this.height/2); *margin-top:expression(100-this.height/2)/*IE6,7*/;}
.sale .list_pic li dl dd { font-size: 12px; font-weight: normal; line-height: 16px; color: #555; display: block; width: 200px; clear: both; padding: 0; margin: 0 10px;}
.sale .list_pic li dl dd.goodsname { line-height: 18px; height: 36px; padding-bottom: 5px; overflow: hidden;}
.sale .list_pic li dl dd.cost { line-height: 20px; color: #999; width: 132px; display: block; padding-left: 28px;}
.sale .list_pic li dl dd.cost h4 { font-size: 12px; font-weight: normal; text-decoration: line-through; color: #999; display: inline;}
.sale .list_pic li dl dd.price { line-height: 33px; color: #FFF; background: #FF0606; text-align: center; display: block; width: 160px; height: 33px; padding: 0; margin: 5px 10px;}
.sale .list_pic li dl dd.price h4 { font-family: Arial, Helvetica, sans-serif; display: inline-block; *display: inline; font-size: 15px; line-height: 33px; color: #FFF; height: 33px; margin-left: 4px; vertical-align: middle; *zoom: 1;}
</style>
<script type="text/javascript" >
	$(document).ready(function(){
		$('#sale').children('ul').children('li').bind('mouseenter',function(){
			$('#sale').children('ul').children('li').attr('class','c1');
			$(this).attr('class','c2');
		});
	
		$('#sale').children('ul').children('li').bind('mouseleave',function(){
			$('#sale').children('ul').children('li').attr('class','c1');
		});
})
</script>
  <div id="banner_box">
    <div class="pic" style="background: #f795cc url(<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$output['activity']['activity_banner'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$output['activity']['activity_banner'];}else{echo SHOP_TEMPLATES_URL."/images/sale_banner.jpg";}?>) no-repeat scroll 0px center;"></div>
  </div>
<div class="wth-activity">
  <div class="sale" id="sale">
    <ul class="list_pic">
      <?php if(is_array($output['list']) and !empty($output['list'])){?>
      <?php foreach ($output['list'] as $v) {?>
      <li class="c1">
        <dl>
          <dt class="goodspic"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>" target="_blank"><img src="<?php echo thumb($v, 240);?>"/></a></dt>
          <dd class="goodsname"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>" target="_blank" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a></dd>
          <dd class="price">活动价：
            <h4><?php echo wtPriceFormatForList($v['goods_sale_price']);?></h4>
          </dd>
        </dl>
      </li>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
