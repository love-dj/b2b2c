<div class="wtsc-upload-btn"> <a href="javascript:void(0);"><span>
  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="mobile_add_album" multiple>
  </span>
  <p><i class="icon-upload-alt" data_type="0" wttype="mobile_add_album_i"></i>图片上传</p>
  </a> </div>
<a href="javascript:void(0);" wttype="meai_cancel" class="wtbtn mt5"><i class=" icon-circle-arrow-up"></i>关闭相册</a>
<div class="goods-gallery add-step2">
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
    $('#mobile_add_album').fileupload({
        dataType: 'json',
        url: ADMIN_SITE_URL+'/index.php?w=lib_goods&t=image_upload',
        formData: {name:'add_album'},
        add: function (e,data) {
            $('i[wttype="mobile_add_album_i"]').removeClass('icon-upload-alt').addClass('icon-spinner icon-spin icon-large').attr('data_type', parseInt($('i[wttype="mobile_add_album_i"]').attr('data_type'))+1);
            data.submit();
        },
        done: function (e,data) {
            var _counter = parseInt($('i[wttype="mobile_add_album_i"]').attr('data_type'));
            _counter -= 1;
            if (_counter == 0) {
                $('i[wttype="mobile_add_album_i"]').removeClass('icon-spinner icon-spin icon-large').addClass('icon-upload-alt');
                $('div[wttype="mea_img"]').show().load(ADMIN_SITE_URL+'/index.php?w=lib_goods&t=pic_list&item=mobile');
            }
            $('i[wttype="mobile_add_album_i"]').attr('data_type', _counter);
        }
    });
});
</script>