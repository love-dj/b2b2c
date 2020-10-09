<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<style type="text/css">
.wtsc-form-default dl dt { width: 16%;}
.wtsc-form-default dl dd { width: 82%;}
</style>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form method="post" action="<?php if (empty($output['plate_info'])) { echo urlShop('store_plate', 'plate_add');} else { echo urlShop('store_plate', 'plate_edit');}?>" id="plate_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="p_id" value="<?php echo $output['plate_info']['plate_id'];?>"/>
    
        <dl>
          <dt><i class="required">*</i>版式名称<?php echo $lang['wt_colon'];?></dt>
          <dd>
            <input type="text" class="text w200" name="p_name" value="<?php echo $output['plate_info']['plate_name']?>" id="p_name" />
            <p class="hint">请输入10个字符内的名称，方便商品发布 / 编辑时选择使用。</p>
          </dd>
        </dl>
        <dl>
          <dt><i class="required">*</i>版式位置<?php echo $lang['wt_colon'];?></dt>
          <dd id="gcategory">
            <ul class="wtsc-form-radio-list">
              <li><label><input type="radio" name="p_position" id="p_position" value="1" class="radio" <?php if (empty($output['plate_info']) || $output['plate_info']['plate_position'] == 1) {?>checked="checked"<?php }?> />顶部</label></li>
              <li><label><input type="radio" name="p_position" id="p_position" value="0" class="radio" <?php if (!empty($output['plate_info']) && $output['plate_info']['plate_position'] == 0) {?>checked="checked"<?php }?>/>底部</label></li>
            </ul>
            <p class="hint">选择关联版式插入到页面中的位置，选择“顶部”为商品详情上方内容，“底部”为商品详情下方内容。</p>
          </dd>
        </dl>
        <dl>
          <dt><i class="required">*</i>版式内容<?php echo $lang['wt_colon'];?></dt>
          <dd>
            <?php showEditor('p_content',$output['plate_info']['plate_content'],'100%','480px','visibility:hidden;',"false",$output['editor_multimedia']);?>
            <p class="hr8">
              <a class="wtbtn" wttype="show_desc" href="index.php?w=store_album&t=pic_list&item=des"><i class="icon-picture"></i>插入相册图片</a>
              <a href="javascript:void(0);" wttype="del_desc" class="wtbtn ml5" style="display: none;"><i class=" icon-bbs-arrow-up"></i>关闭相册</a>
            </p>
            <p id="des_demo"></p>
          </dd>
        </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_submit'];?>"/></label>
    </div>
  </form>
</div>
<script>
$(function(){
    $('#plate_form').validate({
        submitHandler:function(form){
            ajaxpost('plate_form', '', '', 'onerror');
        },
        rules : {
            p_name : {
                required : true,
                maxlength: 10
            },
            p_content : {
                required : true
            }
        },
        messages : {
            p_name : {
                required : '<i class="icon-exclamation-sign"></i>请填写版式名称',
                maxlength: '<i class="icon-exclamation-sign"></i>版式名称不能超过10个字符'
            },
            p_content : {
                required : '<i class="icon-exclamation-sign"></i>请填写版式内容'
            }
        }
    });

    // 版式内容使用
    $('a[wttype="show_desc"]').ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_TEMPLATES_URL+"/images/loading.gif",
        target:'#des_demo'
    }).click(function(){
        $(this).hide();
        $('a[wttype="del_desc"]').show();
    });
    $('a[wttype="del_desc"]').click(function(){
        $(this).hide();
        $('a[wttype="show_desc"]').show();
        $('#des_demo').html('');
    });
});
/* 插入编辑器 */
function insert_editor(file_path) {
    KE.appendHtml('p_content', '<img src="'+ file_path + '">');
}
</script> 
