<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">body { background-color: #F5F5F5;}</style>
<div class="warp-all">
   <?php if(!empty($output['special_list']) && is_array($output['special_list'])) {?>
   <div class="topicBox">
      <div class="topic_main">
	  <?php foreach($output['special_list'] as $value) {?>
	     <div class="col">
	        <i class="location"></i>
		    <div class="col_l">
		       <p class="year"><?php echo date('Y',$value['special_stime']); ?></p>
		       <p class="time_range"><?php echo date('y.m.d',$value['special_stime']); ?> ~ <?php echo date('y.m.d',$value['special_etime']); ?></p>
		       <?php if(time()>$value['special_stime'] && time()<$value['special_etime']){ ?>
			   <span class="status">进行中</span>
			   <?php }elseif(time()<$value['special_stime']){ ?>
			   <span class="status will">未开始</span>
			   <?php }elseif(time()>$value['special_etime']){ ?>
			   <span class="status over">已结束</span>
			   <?php } ?>
		    </div>
		    <div class="col_con">
		       <h2><a href="<?php echo $value['special_link']; ?>" title="<?php echo $value['special_title']; ?>" target="_blank"><?php echo $value['special_title']; ?></a><i class="line"></i></h2>
		       <p class="activity"><?php echo $value['special_stitle'];?></p>
		       <a href="<?php echo urlShop('special','special_detail', array('special_id'=>$value['special_id']));?>" class="topic_pic" title="<?php echo $value['special_title'];?>" target="_blank"><img src="<?php echo getNEWSSpecialImageUrl($value['special_image']);?>" alt="<?php echo $value['special_title']; ?>" height="260" width="800"></a>
		    </div>
		  </div>
	   <?php } ?>
	   </div>
   </div>
   <div class="pagination"> <?php echo $output['show_page'];?> </div>
   <?php } else { ?>
      <div class="no-content">暂无专题内容</div>
   <?php } ?>

</div>
