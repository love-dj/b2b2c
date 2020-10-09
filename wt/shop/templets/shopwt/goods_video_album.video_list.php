<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!--shopwt-->
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=goods_video_album&t=list" title="返回视频列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>视频空间 - <?php echo $output['title'];?></h3>
        <h5>商品视频及商家店铺视频管理</h5>
      </div>
    </div>
  </div>
  <form method='post' action="index.php" name="videoForm" id="videoForm">
    <input type="hidden" name="w" value="goods_video_album" />
    <input type="hidden" name="t" value="del_more_video" />
    <div class="wtap-store-album">
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <ul class="wtap-thumb-list">
        <?php foreach($output['list'] as $k => $v){ ?>
        <li class="picture">
          <input class="checkitem" type="checkbox" name="delbox[]" value="<?php echo $v['video_id'];?>">
          <div class="thumb-list-pics">
            <?php if($v['video_cover'] != ''){ ?>
              <video width="100" height="100" src="<?php echo goodsVideoPath($v['video_cover'], $v['store_id']);?>"></video>
            <?php }else{?>
            <a href="javascript:void(0);"><img src="<?php echo ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/member/default_video.gif';?>"></a>
            <?php }?>
          </div>
          <a href="javascript:void(0);" wt_type="delete" wt_key="<?php echo $v['video_id'].'|'.$v['video_cover'];?>" class="del" title="<?php echo $lang['wt_del'];?>">X</a>
          <p><?php echo date('Y-m-d',$v['upload_time']) . '<br/>' .  number_format($v['video_size']/1024/1024,2) . 'MB';?> </p>
        </li>
        <?php } ?>
      </ul>
      <?php }else { ?>
      <div class="no-data"><i class="fa fa-exclamation-circle"></i><?php echo $lang['wt_no_record'];?></div>
      <?php } ?>
    </div>
    <div class="bot">
      <input id="checkallBottom" class="checkall" type="checkbox" />
      <label for="checkallBottom"><?php echo $lang['wt_select_all'];?></label>
      <a class="wtap-btn-mini wtap-btn-red" href="javascript:void(0);" onclick="if(confirm('确认删除')){$('#videoForm').submit();}"><span><?php echo $lang['wt_del'];?></span></a>
      <div class="pagination"><?php echo $output['page'];?> </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script>
$(function(){
	$('a[wttype="nyroModal"]').nyroModal();
	$('a[wt_type="delete"]').bind('click',function(){
		if(!confirm('确认删除')) return false;
		cur_note = this;
		$.get("index.php?w=goods_video_album&t=del_album_video", {'key':$(this).attr('wt_key')}, function(data){
            if (data == 1) {
            	$(cur_note).parents('li:first').remove();
            } else {
            	alert('删除失败');
            }
        });
	});

});
</script> 
