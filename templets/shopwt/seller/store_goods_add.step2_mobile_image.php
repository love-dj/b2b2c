<a href="javascript:void(0);" wttype="meai_cancel" class="wtbtn mt5"><i class=" icon-bbs-arrow-up"></i>关闭相册</a>
<div class="goods-gallery add-step2"><a class='sample_demo' id="select_submit" href="index.php?w=store_album&t=pic_list&item=goods" style="display:none;"><?php echo $lang['wt_submit'];?></a>
  <div class="nav"><span class="l"><?php echo $lang['store_goods_album_users'];?> >
    <?php if(isset($output['class_name']) && $output['class_name'] != ''){echo $output['class_name'];}else{?>
    <?php echo $lang['store_goods_album_all_photo'];?>
    <?php }?>
    </span><span class="r">
    <select name="jumpMenu" id="jumpMenu" style="width:100px;">
      <option value="0" style="width:80px;"><?php echo $lang['wt_please_choose'];?></option>
      <?php foreach($output['class_list'] as $val) { ?>
      <option style="width:80px;" value="<?php echo $val['aclass_id']; ?>" <?php if($val['aclass_id']==$_GET['id']){echo 'selected';}?>><?php echo $val['aclass_name']; ?></option>
      <?php } ?>
    </select>
    </span></div>
  <?php if(!empty($output['pic_list'])){?>
  <ul class="list">
    <?php foreach ($output['pic_list'] as $v){?>
    <li onclick="<?php if ($output['type'] == 'replace') {?>replace<?php } else {?>insert<?php }?>_mobile_img('<?php echo thumb($v, 1280);?>');"><a href="JavaScript:void(0);"><img src="<?php echo thumb($v, 240);?>" title='<?php echo $v['apic_name']?>'/></a></li>
    <?php }?>
  </ul>
  <?php }else{?>
  <div class="warning-option"><i class="icon-warning-sign"></i><span>相册中暂无图片</span></div>
  <?php }?>
  <div class="pagination"><?php echo $output['show_page']; ?></div>
</div>
<script type="text/javascript">
$(function(){
    $('[wttype="mea_img"]').find('a[class="demo"]').click(function(){
        $('[wttype="mea_img"]').load($(this).attr('href'));
        return false;
    });
    $('#jumpMenu').change(function(){
        $('[wttype="mea_img"]').load('index.php?w=store_album&t=pic_list&item=mobile<?php if ($output['type'] == 'replace') {?>&type=replace<?php }?>&id=' + $(this).val());
    });
});
</script>