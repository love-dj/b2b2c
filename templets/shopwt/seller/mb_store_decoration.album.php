<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style>
.mb-sliders li { width: 225px; height: 168px; display: inline-block; padding: 3px; margin: 3px; border: 1px solid #ccc; }
.mb-sliders img { max-width: 100%; max-height: 100%; display: block; margin: 3px auto; }
.img-wrapper { width: 220px; height: 80px; overflow: hidden; }
</style>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul>
    <li>1、可以在此处对手机店铺导航设置，修改后的设置需要点击“保存修改”按钮进行保存</li>
    <li>2、必须填入导航名称、图片</li>
    <li>3、选择开启为显示此处设置，关闭为默认显示</li>
    <li>4、导航图片上传必须是60*60分辨率大小</li>
  </ul>
</div>
<div class="wtsc-form-default">
  <form method="post" action="index.php?w=mb_store_decoration&t=decoration_album" enctype="multipart/form-data" id="my_store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>启用手机店铺导航装修<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <label for="store_decoration_switch_on" class="mr30">
          <input id="store_decoration_switch_on" type="radio" class="radio vm mr5" name="mb_store_navigation" value="1" <?php echo $output['mb_store_navigation'] > 0?'checked':'';?>>
          是</label>
        <label for="store_decoration_switch_off">
          <input id="store_decoration_switch_off" type="radio" class="radio vm mr5" name="mb_store_navigation" value="0" <?php echo $output['mb_store_navigation'] == 0?'checked':'';?>>
          否</label>
        <p class="hint">选择是否使用手机店铺导航装修模板；<br/>
          如选择“是”，店铺首页导航区域将根此处设置的内容进行显示；<br/>
          如选择“否”，就恢复系统默认的底部菜单。</p>
      </dd>
    </dl>
    <ul class="wtsc-store-slider sortable">
    	<?php $mbSliders = $output['mbSliders'];?>
<?php 
for ($k = 1; $k <= $output['max_mb_sliders']; $k++) { $v = $mbSliders[$k]; ?>
      <li>
        <input type="hidden" name="mb_sliders_sort[]" value="<?php echo $k; ?>" />
        <div class="picture" wttype="file_<?php echo $k; ?>">
          <?php if ($v['img']) { ?>
          <img wttype="file_<?php echo $k; ?>" alt="" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$v['img']; ?>" />
          <?php } else { ?>
          <i class="icon-picture"></i>
          <?php } ?>
          <a href="javascript:;" data-slider-drop="<?php echo $k; ?>" class="del" title="移除">&#215;</a> </div>
        <div class="url">

         <input  placeholder="导航名称" type="text" class="text w150" name="mb_sliders_names[<?php echo $k; ?>]" value="<?php echo isset($v['name'])?$v['name']:''; ?>" />
                  <input  placeholder="导航地址" type="text" class="text w150" name="mb_sliders_links[<?php echo $k; ?>]" value="<?php echo isset($v['links'])?$v['links']:''; ?>" />
        </div>
        <div class="wtsc-upload-btn"> <a href="javascript:;"> <span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="file_<?php echo $k; ?>" id="file_<?php echo $k; ?>" />
          </span>
          <p> <i class="icon-upload-alt"></i> 图片上传 </p>
          </a> </div>
      </li>
      <?php } ?>
    </ul>

    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存修改" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.flexslider.min.js"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script> 
<script>
$(function() {
    $('.flexslider').flexslider();

    $(".sortable").sortable();

    var DEFAULT_GOODS_IMAGE = '<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON; ?>/default_goods_image.gif';
    var LOADING_IMAGE = '<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif';

    $('input.input-file').change(function() {
        var id = this.id;

        $('div[wttype="'+id+'"]').find('i,img').remove().end()
            .prepend('<img wttype="'+id+'" src="'+LOADING_IMAGE+'">');

        $.ajaxFileUpload({
            url: 'index.php?w=mb_store_decoration&t=store_mb_sliders',
            secureuri: false,
            fileElementId: id,
            dataType: 'json',
            data: {id: id},
            success: function(data, status) {
                if (data.error) {
                    alert(data.error);
                    $('img[wttype="'+id+'"]').attr('src', DEFAULT_GOODS_IMAGE);
                    return;
                }
                $('img[wttype="'+id+'"]').attr('src', data.uploadedUrl);
            },
            error: function(data, status, e) {
                alert(e);
            }
        });
    });

    $("a[data-slider-drop]").click(function() {
        var id = $(this).attr('data-slider-drop');
        var $this = $(this);

        $.getJSON('index.php?w=mb_store_decoration&t=store_mb_sliders_drop', {id: id}, function(d) {
            if (!d.success) {
                alert(d.error);
                return;
            }
            $this.parents('div.picture').find('img,i').remove().end()
                .prepend('<i class="icon-picture"></i>');
        });
    });


});
</script> 
