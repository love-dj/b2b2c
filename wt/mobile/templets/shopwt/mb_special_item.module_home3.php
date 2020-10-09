<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($item_edit_flag) { ?>

<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
    <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
  </div>
  <ul>
    <li>点击添加新的块内容按钮可以添加新的内容</li>
    <li>鼠标移动到已有的内容上点击出现的删除按钮可以对其进行删除</li>
    <li>操作完成后点击保存编辑按钮进行保存</li>
  </ul>
</div>
<?php } ?>
<div class="index_block home3">
  <?php if($item_edit_flag) { ?>
  <h3>模型版块布局C</h3>
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
    <?php if(!empty($item_data['item']) && is_array($item_data['item'])) {?>
    <?php foreach($item_data['item'] as $item_key => $item_value) {?>
    <div wttype="item_image" class="item"> <img wttype="image" src="<?php echo getMbSpecialImageUrl($item_value['image']);?>" alt="">
      <?php if($item_edit_flag) { ?>
      <input wttype="image_name" name="item_data[item][<?php echo $item_key;?>][image]" type="hidden" value="<?php echo $item_value['image'];?>">
      <input wttype="image_type" name="item_data[item][<?php echo $item_key;?>][type]" type="hidden" value="<?php echo $item_value['type'];?>">
      <input wttype="image_data" name="item_data[item][<?php echo $item_key;?>][data]" type="hidden" value="<?php echo $item_value['data'];?>">
      <a wttype="btn_del_item_image" href="javascript:;"><i class="fa fa-trash-o
"></i>删除</a>
      <?php } ?>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
  <?php if($item_edit_flag) { ?>
  <a wttype="btn_add_item_image" class="wtap-btn" data-desc="320*85" href="javascript:;"><i class="fa fa-plus"></i>添加新的块内容</a>
  <?php } ?>
</div>
