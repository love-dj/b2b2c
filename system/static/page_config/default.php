<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="i-floor-con wrapper style-<?php echo $output['style_name'];?>">
  <div class="left-floor">
    <div class="title">
      <?php if ($output['code_tit']['code_info']['type'] == 'txt') { ?>
      <div class="module-title">
        <?php if(!empty($output['code_tit']['code_info']['floor'])) { ?>
        <span><?php echo $output['code_tit']['code_info']['floor'];?></span>
        <?php } ?>
        <h2 title="<?php echo $output['code_tit']['code_info']['title'];?>"><?php echo $output['code_tit']['code_info']['title'];?></h2>
      </div>
      <?php }else { ?>
      <div class="pic-type"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>" rel="lazy" /></div>
      <?php } ?>
    </div>
    <div class="left-pic">
      <?php if(!empty($output['code_act']['code_info']['pic'])) { ?>
      <a href="<?php echo $output['code_act']['code_info']['url'];?>" title="<?php echo $output['code_act']['code_info']['title'];?>" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php  echo UPLOAD_SITE_URL.'/'.$output['code_act']['code_info']['pic'];?>" alt="<?php echo $output['code_act']['code_info']['title']; ?>" rel="lazy" /> </a>
      <?php } ?>
    </div>
    <div class="left-class">
      <ul>
        <?php if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { ?>
        <?php foreach ($output['code_category_list']['code_info']['goods_class'] as $k => $v) { ?>
        <li><a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name'];?>" target="_blank"><?php echo $v['gc_name'];?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="middle-box">
	 <div class="right-tabs-nav">
    <ul class="tabs-nav">
      <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
      <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
      <li class="<?php echo $i==1 ? 'tabs-selected':'';?>">
        <h3><?php echo $val['recommend']['name'];?></h3>
      </li>
      <?php } ?>
      <?php } ?>
    </ul>
	</div>
    <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
    <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
    <div class="tabs-panel middle-goods-list <?php echo $i==1 ? '':'tabs-hide';?>">
      <ul>
        <?php foreach($val['goods_list'] as $k => $v){ ?>
        <li>
          <dl>
            <dt class="goods-name"><a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>"> <?php echo $v['goods_name']; ?></a></dt>
            <dd class="goods-thumb"> <a target="_blank" title="<?php echo $v['goods_name']; ?>" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>">
			<?php if (C('oss.open')) { ?>
			<img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:C('oss.img_url')."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" rel="lazy" />
			<?php } else { ?>
			<img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" rel="lazy" />
			<?php } ?>
			</a></dd>
            <dd class="goods-price"><em><?php echo wtPriceFormatForList($v['goods_price']); ?></em></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
    <div class="tabs-panel right-floor-style01 fade-img <?php echo $i==1 ? '':'tabs-hide';?>">
    
      <div class="right-floor">
        <div class="right-side-focus">
          <ul>
            <?php  if (is_array($output['code_show']['code_info']) && !empty($output['code_show']['code_info'])) { ?>
            <?php foreach ($output['code_show']['code_info'] as $key => $val_) { ?>
            <?php if (is_array($val_) && !empty($val_)) { ?>
            <li><a href="<?php echo $val_['pic_url'];?>" title="<?php echo $val_['pic_name'];?>" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val_['pic_img'];?>" alt="<?php echo $val_['pic_name'];?>" rel="lazy" /></a> </li>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
      </div>
      
      <div class="right-floor-list">
        <ul>
          <li>
            <dl>
              <dd class="banner-thumb"><a href="<?php echo $val['pic_list']['11']['pic_url'];?>" title="<?php echo $val['pic_list']['11']['pic_name'];?>" class="a1" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['11']['pic_img'];?>" alt="<?php echo $val['pic_list']['11']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
		  <li>
            <dl>
              <dd class="banner-thumb"><a href="<?php echo $val['pic_list']['31']['pic_url'];?>" title="<?php echo $val['pic_list']['31']['pic_name'];?>" class="a1" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['31']['pic_img'];?>" alt="<?php echo $val['pic_list']['31']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
		  <li>
            <dl>
              <dd class="banner-thumb"> <a href="<?php echo $val['pic_list']['33']['pic_url'];?>" title="<?php echo $val['pic_list']['33']['pic_name'];?>" class="a1" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['33']['pic_img'];?>" alt="<?php echo $val['pic_list']['33']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
          <li>
            <dl>
              <dd class="banner-thumb"> <a href="<?php echo $val['pic_list']['12']['pic_url'];?>" title="<?php echo $val['pic_list']['12']['pic_name'];?>" class="a1" target="_blank"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['12']['pic_img'];?>" alt="<?php echo $val['pic_list']['12']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
          <li>
            <dl>
              <dd class="banner-thumb"> <a href="<?php echo $val['pic_list']['32']['pic_url'];?>" title="<?php echo $val['pic_list']['32']['pic_name'];?>" class="a1" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['32']['pic_img'];?>" alt="<?php echo $val['pic_list']['32']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
          <li>
            <dl>
              <dd class="banner-thumb"><a href="<?php echo $val['pic_list']['34']['pic_url'];?>" title="<?php echo $val['pic_list']['34']['pic_name'];?>" class="a1" target="_blank"> <img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['34']['pic_img'];?>" alt="<?php echo $val['pic_list']['34']['pic_name'];?>" rel="lazy" /></a></dd>
            </dl>
          </li>
        </ul>
      </div>
      
       </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </div>
</div>
