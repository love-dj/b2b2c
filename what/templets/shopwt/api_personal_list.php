<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!--推荐买心得部分-->
<div class="title">
    <h3><?php echo $lang['what_text_member_commend'];?><em><?php echo $lang['wt_what_personal'];?></em></h3>
</div>
<ul id="indexPersonal" class="jcarousel-skin-personal">
    <?php if(!empty($output['personal_list']) && is_array($output['personal_list'])) {?>
    <?php foreach($output['personal_list'] as $key=>$value) {?>
    <?php $personal_image_array = getwhatPersonalImageUrl($value,'list');?>
    <li>
    <div class="news-thumb"><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=personal&t=detail&personal_id=<?php echo $value['personal_id'];?>">
        <img src="<?php echo $personal_image_array[0];?>" alt="" class="t-img" />
    </a></div>
    <dl>
        <dt class="member-avatar"><img src="<?php echo getMemberAvatar($value['member_avatar']);?>"/></dt>
        <dd class="member-id"><a href="<?php echo WHAT_SITE_URL;?>/index.php?w=home&member_id=<?php echo $value['commend_member_id'];?>"> <?php echo $value['member_name'];?></a></dd>
        <dd class="commend-time"><?php echo date('Y-m-d',$value['commend_time']);?></dd>
        <dd class="commend-message"><?php echo $value['commend_message'];?></dd>
        <dd class="like"><i></i><?php echo $lang['wt_what_like'];?><em><?php echo $value['like_count'];?></em></dd>
    </dl>
    </li>
    <?php } ?>
    <?php } ?>
</ul>
