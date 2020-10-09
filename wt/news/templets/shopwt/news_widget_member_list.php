<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
<div class="member-select-box">
    <div class="arrow"></div>
    <ul id="member_search_list" class="member-search-list">
        <?php foreach($output['member_list'] as $value){ ?>
        <li>
        <dl class="member-info">
            <dt class="member-name">
            <a href="<?php echo BASE_SITE_URL.DS;?>index.php?w=member_snshome&mid=<?php echo $value['member_id'];?>" target="_blank">
                <?php echo $value['member_name'];?>
            </a>
            </dt>
            <dd class="member-avatar">
            <a href="<?php echo BASE_SITE_URL.DS;?>index.php?w=member_snshome&mid=<?php echo $value['member_id'];?>" target="_blank">
                <img src="<?php echo getMemberAvatar($value['member_avatar']);?>" />
            </a>
            </dd>
            <dd wttype="btn_member_select" class="handle-button" title="<?php echo $lang['news_text_add'];?>"></dd>
        </dl>
        </li>
        <?php } ?>
    </ul>
    <div class="pagination"><?php echo $output['show_page'];?></div>
</div>
<?php }else { ?>
<div class="no-record"><?php echo $lang['no_record'];?></div>
<?php } ?>
