<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if($item_edit_flag) { ?>

<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
    <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
  </div>
  <ul>
    <li>鼠标移动到内容上出现编辑按钮可以对内容进行修改</li>
    <li>操作完成后点击保存编辑按钮进行保存</li>
  </ul>
</div>
<?php } ?>
<div class="index_block home6">
  <?php if($item_edit_flag) { ?>
  <h3>模型版块布局F</h3>
  <?php } ?>
  <div class="title">
    <?php if($item_edit_flag) { ?>
    <h5>标题：</h5>
    <input id="home6_title" type="text" class="txt w200" name="item_data[title]" value="<?php echo $item_data['title'];?>">
    <?php } else { ?>
    <span><?php echo $item_data['title'];?></span>
    <?php } ?>
  </div>
  <div wttype="item_content" class="content">
    <?php if($item_edit_flag) { ?>
    <h5>内容：</h5>
    <?php } ?>
     <div class="home6_1" >
      <div wttype="item_image" class="item"> <img wttype="image" src="<?php echo getMbSpecialImageUrl($item_data['square_image']);?>" alt="">
        <?php if($item_edit_flag) { ?>
        <input wttype="image_name" name="item_data[square_image]" type="hidden" value="<?php echo $item_data['square_image'];?>">
        <input wttype="image_type" name="item_data[square_type]" type="hidden" value="<?php echo $item_data['square_type'];?>">
        <input wttype="image_data" name="item_data[square_data]" type="hidden" value="<?php echo $item_data['square_data'];?>">
        <a wttype="btn_edit_item_image" data-desc="213*260" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
        <?php } ?>
      </div>
	   <div wttype="item_image" class="item"> <img wttype="image" src="<?php echo getMbSpecialImageUrl($item_data['rectangle1_image']);?>" alt="">
          <?php if($item_edit_flag) { ?>
          <input wttype="image_name" name="item_data[rectangle1_image]" type="hidden" value="<?php echo $item_data['rectangle1_image'];?>">
          <input wttype="image_type" name="item_data[rectangle1_type]" type="hidden" value="<?php echo $item_data['rectangle1_type'];?>">
          <input wttype="image_data" name="item_data[rectangle1_data]" type="hidden" value="<?php echo $item_data['rectangle1_data'];?>">
          <a wttype="btn_edit_item_image" data-desc="213*260" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
          <?php } ?>
        </div>
		<div wttype="item_image" class="item"> <img wttype="image" src="<?php echo getMbSpecialImageUrl($item_data['rectangle2_image']);?>" alt="">
          <?php if($item_edit_flag) { ?>
          <input wttype="image_name" name="item_data[rectangle2_image]" type="hidden" value="<?php echo $item_data['rectangle2_image'];?>">
          <input wttype="image_type" name="item_data[rectangle2_type]" type="hidden" value="<?php echo $item_data['rectangle2_type'];?>">
          <input wttype="image_data" name="item_data[rectangle2_data]" type="hidden" value="<?php echo $item_data['rectangle2_data'];?>">
          <a wttype="btn_edit_item_image" data-desc="213*130" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
          <?php } ?>
        </div>
    </div>
    
  </div>
</div>
