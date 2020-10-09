<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wtsc-form-default">
  <form id="form_setting" method="post" action="<?php echo urlShop('mb_store_decoration', 'decoration_setting_save');?>">
    <dl>
      <dt>启用手机店铺装修<?php echo $lang['wt_colon'];?></dt>
      <dd>
        <label for="store_decoration_switch_on" class="mr30">
          <input id="store_decoration_switch_on" type="radio" class="radio vm mr5" name="mb_store_decoration_switch" value="1" <?php echo $output['mb_store_decoration_switch'] > 0?'checked':'';?>>
          是</label>
        <label for="store_decoration_switch_off">
          <input id="store_decoration_switch_off" type="radio" class="radio vm mr5" name="mb_store_decoration_switch" value="0" <?php echo $output['mb_store_decoration_switch'] == 0?'checked':'';?>>
          否</label>
        <p class="hint">选择是否使用手机店铺装修模板；<br/>
          如选择“是”，店铺首页背景、头部、导航以及上方区域都将根据店铺装修模板所设置的内容进行显示；<br/>
          如选择“否”，就恢复系统默认的底部菜单。</p>
      </dd>
    </dl>

    <dl>
      <dt>店铺装修<?php echo $lang['wt_colon'];?></dt>
      <dd> <a href="<?php echo urlShop('mb_store_decoration', 'decoration_edit', array('decoration_id' => $output['decoration_id']));?>" class="wtbtn wtbtn-aqua mr5" target="_blank"><i class="icon-puzzle-piece"></i>装修页面</a>
        <p class="hint">点击“装修页面”按钮，在新窗口对店铺首页进行装修设计；<br/>
   </p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input id="btn_submit" type="button" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_submit').on('click', function() {
            ajaxpost('form_setting', '', '', 'onerror');
        });

        $('#btn_build').on('click', function() {
            $.getJSON($(this).attr('href'), function(data) {
                if(typeof data.error == 'undefined') {
                    showSucc(data.message);
                } else {
                    showError(data.error);
                }
            });
            return false;
        });
    });
</script> 
