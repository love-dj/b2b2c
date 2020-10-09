<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="channel-standard-box wrapper style-<?php echo $output['web_id'];?>">
  <div class="middle-box">
	<div class="title">
      <div class="txt-type">
        <h3 title="<?php echo $output['code_channel_tit']['code_info']['title'];?>"><?php echo $output['code_channel_tit']['code_info']['title'];?></h3>
      </div>
    </div>
	<div class="left-side-focus">
     <ul>
		  <?php if(!empty($output['code_show_a']['code_info']['pic'])) { ?>
		<li>
		  <a href="<?php echo $output['code_show_a']['code_info']['pic_url'];?>" title="<?php echo $output['code_show_a']['code_info']['pic_name'];?>" target="_blank">
		  <img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_show_a']['code_info']['pic'];?>"/>
		  </a>
		</li>
		 <?php } ?>
		 <?php if(!empty($output['code_show_b']['code_info']['pic'])) { ?>
	    <li>
		  <a href="<?php echo $output['code_show_b']['code_info']['pic_url'];?>" title="<?php echo $output['code_show_b']['code_info']['pic_name'];?>" target="_blank">
		  <img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_show_b']['code_info']['pic'];?>"/>
		  </a>
		</li>
		  <?php } ?>
		<?php if(!empty($output['code_show_c']['code_info']['pic'])) { ?>
	     <li>
		  <a href="<?php echo $output['code_show_c']['code_info']['pic_url'];?>" title="<?php echo $output['code_show_c']['code_info']['pic_name'];?>" target="_blank">
		  <img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_show_c']['code_info']['pic'];?>"/>
		  </a>
		</li>
		  <?php } ?>
    </div>
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
            <dd class="goods-thumb">
             <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $output['code_show_c']['code_info']['pic_name'];?>">
              <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" /> </a>
            </dd>
            <dd class="goods-price"><em><?php echo wtPriceFormatForList($v['goods_price']); ?></em></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </div>
</div>
