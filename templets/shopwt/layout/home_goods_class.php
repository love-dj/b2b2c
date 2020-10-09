<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="title"><i></i>
  <h3><a href="<?php echo urlShop('category', 'index');?>"><?php echo $lang['wt_all_goods_class'];?></a></h3>
</div>
<div class="category">
  <ul class="menu">
    <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
    <?php foreach ($output['show_goods_class'] as $key => $val) { ?>

	<?php if($val['is_show']=='1'){ continue;} $i++; ?>
    <li cat_id="<?php echo $val['gc_id'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($output['index_sign'] == 'index' && $i>11){?>style="display:none;"<?php }?>>

      <div class="class"><span class="arrow"></span>
        <?php if($val['cn_pic'] != '') { ?>
        <span class="ico" style="background-image:url(<?php echo $val['cn_pic'];?>)"></span>
        <?php } ?>
              <?php if (!empty($val['channel_id'])) {?>
              <h4><a href="<?php echo urlShop('channel','index',array('id'=> $val['channel_id']));?>"><?php echo $val['gc_name'];?></a></h4>
              <?php }else{?>
              <h4><a href="<?php echo urlShop('search','index',array('cate_id'=> $val['gc_id']));?>"><?php echo $val['gc_name'];?></a></h4>
	      <?php } ?>
         </div>
      <div class="sub-class" cat_menu_id="<?php echo $val['gc_id'];?>">
        <div class="sub-class-content">
          <div class="recommend-class">
            <?php if (!empty($val['cn_classs']) && is_array($val['cn_classs'])) { ?>
            <?php foreach ($val['cn_classs'] as $k => $v) { ?>
            <span><a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name']; ?>"><?php echo $v['gc_name'];?></a></span>
            <?php } ?>
            <?php } ?>
          </div>
          <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
          <?php foreach ($val['class2'] as $k => $v) { ?>
		<?php if($v['is_show']=='1'){ continue;}?>
          <dl>
            <dt>
              <?php if (!empty($v['channel_id'])) {?>
              <h3><a href="<?php echo urlShop('channel','index',array('id'=> $v['channel_id']));?>"><?php echo $v['gc_name'];?></a></h3>
              <?php }else{?>
             <h3><a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>"><?php echo $v['gc_name'];?></a></h3>
              <?php } ?>
            </dt>
            <dd class="goods-class">
              <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
              <?php foreach ($v['class3'] as $k3 => $v3) { ?>
				<?php if($v3['is_show']=='1'){ continue;}?>
              <a href="<?php echo urlShop('search','index',array('cate_id'=> $v3['gc_id']));?>"><?php echo $v3['gc_name'];?></a>
              <?php } ?>
              <?php } ?>
            </dd>
          </dl>
          <?php } ?>
          <?php } ?>
        </div>
        <div class="sub-class-right">
          <?php if (!empty($val['cn_brands'])) {?>
          <div class="brands-list">
            <ul>
              <?php foreach ($val['cn_brands'] as $brand) {?>
              <li> <a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>" title="<?php echo $brand['brand_name'];?>"><?php if ($brand['brand_pic'] != '') {?><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo brandImage($brand['brand_pic']);?>" rel="lazy" /><?php }?>
                <span><?php echo $brand['brand_name'];?></span>
                </a></li>
              <?php }?>
            </ul>
          </div>
          <?php }?>
          
          <div class="show-sales">
          <?php if($val['cn_show1'] != '') { ?>
          <a <?php echo $val['cn_show1_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="'.$val['cn_show1_link'].'"';?>><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo $val['cn_show1'];?>" rel="lazy" /></a>
          <?php } ?>
          <?php if($val['cn_show2'] != '') { ?>
          <a <?php echo $val['cn_show2_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="'.$val['cn_show2_link'].'"';?>><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo $val['cn_show2'];?>" rel="lazy" /></a>
          <?php } ?></div>
        </div>
      </div>
    </li>
    <?php } ?>
    <?php } ?>
  </ul>
</div>
