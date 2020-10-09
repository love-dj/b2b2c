<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($item_edit_flag) { ?>

<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="icon-lightbulb"></i>
    <h4 title="<?php echo '操作说明';?>"><?php echo '操作说明';?></h4>
  </div>
  <ul>
    <li>鼠标移动到内容上出现编辑按钮可以对内容进行修改</li>
    <li>操作完成后点击保存编辑按钮进行保存</li>
  </ul>
</div>
<?php } ?>
<div class="index_block home1">
  <?php if($item_edit_flag) { ?>
  <h3>模型版块布局A</h3>
  <?php } ?>
  <div class="title">
    <?php if($item_edit_flag) { ?>
    <h5>标题：</h5>
    <input id="home1_title" type="text" class="txt w200" name="item_data[title]" value="<?php echo $item_data['title'];?>">
    <?php } else { ?>
    <span><?php echo $item_data['title'];?></span>
    <?php } ?>
  </div>
  <div wttype="item_content" class="content">
    <?php if($item_edit_flag) { ?>
    <h5>内容：</h5>
    <?php } ?>
    <div wttype="item_image" class="item"> <img wttype="image" src="<?php echo getMbSpecialImageUrl($item_data['image']);?>" alt="">
      <?php if($item_edit_flag) { ?>
      <input wttype="image_name" name="item_data[image]" type="hidden" value="<?php echo $item_data['image'];?>">
      <input wttype="image_type" name="item_data[type]" type="hidden" value="<?php echo $item_data['type'];?>">
      <input wttype="image_data" name="item_data[data]" type="hidden" value="<?php echo $item_data['data'];?>">
      <a wttype="btn_edit_item_image" data-desc="640*260" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
      <?php } ?>
    </div>
  </div>
</div>
