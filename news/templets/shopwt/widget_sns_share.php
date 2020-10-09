<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($_SESSION['member_id'])) { ?>
<script type="text/javascript">
$(document).ready(function(){
    //分享
    $("#btn_sns_share").click(function(){
        $("#commend_message").val("");
        $("#_share_title").html($(this).attr("data-title"));
        $("#_share_image").attr("src", $(this).attr("data-image"));
        $("#_share_publisher").html("<?php echo $lang['news_text_publisher'];?><?php echo $lang['wt_colon'];?>" + $(this).attr("data-publisher"));
        $("#_share_origin").html("<?php echo $lang['news_text_origin'];?><?php echo $lang['wt_colon'];?>" + $(this).attr("data-origin"));
        $("#_share_publish_time").html($(this).attr("data-publish_time"));
        $("#_share_abstract").html($(this).attr("data-abstract"));
        $("#div_sns_share").show_dialog({title:'<?php echo $lang['news_text_share'];?>', width:480});
    });
    $('#commend_message').wt_text_count();
    $("#btn_share_publish").click(function(){
        if($('#commend_message').val().length <= 140) {
            $("#div_sns_share").hide();
            $("#btn_sns_share > em > b").wt_count();
            ajaxpost('share_form', '', '', 'onerror'); 
        }
    });
});
</script>
<!-- 弹出层开始 -->

<div id="div_sns_share" style="display:none;" class="news-share">
<form action="<?php echo NEWS_SITE_URL;?>/index.php?w=share&t=share_save&type=<?php echo $_GET['w'];?>" method="post" id="share_form" class="feededitor">
  <input type="hidden" value="<?php echo $output['detail_object_id'];?>" name="share_id">
  </input>
  <input type="hidden" value="<?php echo $output[$_GET['w'].'_detail'][$_GET['w'].'_title'];?>" name="share_title">
  </input>
  <input type="hidden" value="<?php echo getNEWSArticleImageUrl($output[$_GET['w'].'_detail'][$_GET['w'].'_attachment_path'], $output[$_GET['w'].'_detail'][$_GET['w'].'_image']);?>" name="share_image">
  </input>
  <dl class="share-target">
    <dt id="_share_title" class="title"></dt>    
    <dd class="cover"><a href="JavaScript: void(0);"><img id="_share_image" src="" alt="" /></a></dd>
    <dd class="sub"><span id="_share_publisher"></span><span id="_share_origin"></span><!--<span id="_share_publish_time"></span>--></dd>
    <dd class="abstract"><span id="_share_abstract"></span></dd>
  </dl>
  <div class="share-content">
    <textarea name="commend_message" id="commend_message" placeholder="<?php echo $lang['news_sns_share_title'];?>"></textarea>
  </div>
  <div class="handle">
    <input id="btn_share_publish" type="button" value="<?php echo $lang['news_text_share'];?>" class="button" />
    <!-- 站外分享 -->
    <?php require('widget_share.php');?>
  </div>
</form>
</div>
<?php } else { ?>
<script type="text/javascript">
$(document).ready(function(){
    //登陆窗口
    $("#btn_sns_share").wt_login({
        wthash:'<?php echo getWthash();?>',
        formhash:'<?php echo Security::getTokenValue();?>',
        anchor:'news_comment_flag'
    });
});
</script>
<?php } ?>

