<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<link href="<?php echo SHOP_TEMPLATES_URL?>/css/shop_custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL?>/css/shop_store_mb.css" rel="stylesheet" type="text/css">
<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
      <?php if (is_array($output['menu'])) {?>
      <ul class="tab-base wt-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
      <?php }?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="icon-lightbulb"></i>
      <h4 title="<?php echo '提示相关设置操作时应注意的要点';?>"><?php echo '操作提示';?></h4>
      <span id="explanationZoom" title="<?php echo '收起提示';?>"></span> </div>
    <ul>
      <li>点击右侧组件的<strong>“添加”</strong>按钮，增加对应类型版块到页面，其中<strong>“幻灯片版块”</strong>只能添加一个。</li>
      <li>鼠标触及左侧页面对应版块，出现操作类链接，可以对该区域块进行<strong>“移动”、“启用/禁用”、“编辑”、“删除”</strong>操作。</li>
      <li>新增加的版块内容默认为<strong>“禁用”</strong>状态，编辑内容并<strong>“启用”</strong>该块后将在手机端即时显示。</li>
    </ul>
  </div>
  
  <!-- 列表 -->
  <div class="mb-special-layout">
    <div class="mb-item-box">
      <div id="item_list" class="item-list">
        <?php if(!empty($output['list']) && is_array($output['list'])) {?>
        <?php foreach($output['list'] as $key => $value) {?>
        <div wttype="special_item" class="special-item <?php echo $value['item_type'];?> <?php echo $value['usable_class'];?>" data-item-id="<?php echo $value['item_id'];?>">
          <div class="item_type"><?php echo $output['module_list'][$value['item_type']]['desc'];?></div>
          <?php $item_data = $value['item_data'];?>
          <?php $item_edit_flag = false;?>
          <div id="item_edit_content">
            <?php require('mb_special_item.module_' . $value['item_type'] . '.php');?>
          </div>
          <div class="handle"><a wttype="btn_move_up" href="javascript:;"><i class="fa fa-arrow-up
"></i>上移</a> <a wttype="btn_move_down" href="javascript:;"><i class="fa fa-arrow-down 
"></i>下移</a> <a wttype="btn_usable" data-item-id="<?php echo $value['item_id'];?>" href="javascript:;"><i class="fa fa-toggle-on 
"></i><?php echo $value['usable_text'];?></a> <a wttype="btn_edit_item" data-item-id="<?php echo $value['item_id'];?>" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a> <a wttype="btn_del_item" data-item-id="<?php echo $value['item_id'];?>" href="javascript:;"><i class="fa fa-trash-o
"></i>删除</a></div>
          </td>
        </div>
        <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="module-list">
      <?php if(!empty($output['module_list']) && is_array($output['module_list'])){ ?>
      <?php foreach($output['module_list'] as $key => $value){ ?>
      <div class="module_<?php echo $key;?>"> <span><?php echo $value['desc'];?></span> <a wttype="btn_add_item" class="add" href="javascript:;" data-module-type="<?php echo $value['name'];?>">添加</a> </div>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/template.min.js" charset="utf-8"></script> 
<!-- 页面模块模板 --> 
<script id="item_template" type="text/html">
</script> 
<script type="text/javascript">
    var special_id = <?php echo $output['special_id'];?>;
    var url_item_add = "<?php echo urlShop('mb_store_decoration', 'special_item_add');?>";
    var url_item_del = "<?php echo urlShop('mb_store_decoration', 'special_item_del');?>";
    var url_item_edit = "<?php echo urlShop('mb_store_decoration', 'special_item_edit');?>";
    $(document).ready(function(){
        //添加模块
        $('[wttype="btn_add_item"]').on('click', function() {
            var data = {
                special_id: special_id,
                item_type: $(this).attr('data-module-type')
            };
            $.post(url_item_add, data, function(data) {
                if(typeof data.error === 'undefined') {
                    location.reload();
                } else {
                    showError(data.error);
                }
            }, "json");
        });

        //删除模块
        $('#item_list').on('click', '[wttype="btn_del_item"]', function() {
            if(!confirm('确认删除？')) {
                return false;
            }
            var $this = $(this);
            var item_id = $this.attr('data-item-id');
            $.post(url_item_del, {item_id: item_id, special_id: special_id} , function(data) {
                if(typeof data.error === 'undefined') {
                    $this.parents('.special-item').remove();
                } else {
                    showError(data.error);
                }
            }, "json");
        });

        //编辑模块
        $('#item_list').on('click', '[wttype="btn_edit_item"]', function() {
            var item_id = $(this).attr('data-item-id');
            go(url_item_edit + '&item_id=' + item_id);
        });

        //上移
        $('#item_list').on('click', '[wttype="btn_move_up"]', function() {
            var $current = $(this).parents('[wttype="special_item"]');
            $prev = $current.prev('[wttype="special_item"]');
            if($prev.length > 0) {
                $prev.before($current);
                update_item_sort();
            } else {
                showError('已经是第一个了');
            }
        });

        //下移
        $('#item_list').on('click', '[wttype="btn_move_down"]', function() {
            var $current = $(this).parents('[wttype="special_item"]');
            $next = $current.next('[wttype="special_item"]');
            if($next.length > 0) {
                $next.after($current);
                update_item_sort();
            } else {
                showError('已经是最后一个了');
            }
        });

        var update_item_sort = function() {
            var item_id_string = '';
            $item_list = $('#item_list').find('[wttype="special_item"]');
            $item_list.each(function(index, item) {
                item_id_string += $(item).attr('data-item-id') + ',';
            });
            $.post("index.php?w=mb_store_decoration&t=update_item_sort", {special_id: special_id, item_id_string: item_id_string}, function(data) {
                if(typeof data.error != 'undefined') {
                    showError(data.message);
                }
            }, 'json');
        };

        //启用/禁用控制
        $('#item_list').on('click', '[wttype="btn_usable"]', function() {
            var $current = $(this).parents('[wttype="special_item"]');
            var item_id = $current.attr('data-item-id');
            var usable = '';
            if($current.hasClass('usable')) {
                $current.removeClass('usable');
                $current.addClass('unusable');
                usable = 'unusable';
                $(this).html('<i class="fa fa-toggle-off"></i>启用');
            } else {
                $current.removeClass('unusable');
                $current.addClass('usable');
                usable = 'usable';
                $(this).html('<i class="fa fa-toggle-on"></i>禁用');
            }

            $.post("index.php?w=mb_store_decoration&t=update_item_usable", {item_id: item_id, usable: usable, special_id: special_id}, function(data) {
                if(typeof data.error != 'undefined') {
                    showError(data.message);
                }
            }, 'json');
        });

    });
</script> 

