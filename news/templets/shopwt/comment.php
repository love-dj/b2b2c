<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link type="text/css" rel="stylesheet" href="<?php echo NEWS_TEMPLATES_URL;?>/css/login.css">
<script type="text/javascript">
$(document).ready(function(){
    var url_comment_list = "<?php echo NEWS_SITE_URL.DS;?>index.php?w=comment&t=comment_list&type=<?php echo $_GET['w'];?>&comment_object_id=<?php echo $output['detail_object_id'];?>&comment_all=<?php echo $output['comment_all'];?>";
    $("#btn_comment_submit").click(function(){
        if($("#input_comment_message").val() != '') {
        $.post("<?php echo NEWS_SITE_URL.DS.'index.php?w=comment&t=comment_save';?>", $("#add_form").serialize(),
            function(data){
                if(data.result == 'true') {
                    $("#input_comment_message").val("");
                    $("#comment_list").load(url_comment_list);
                    $("#comment_list dl").first().hide().fadeIn("fast");
                } else {
                    showError(data.message);
                }
            }, "json");
        }
    });

    //初始加载评论
    $("#comment_list").load(url_comment_list);

    //评论翻页
    $("#comment_list .demo").live('click',function(e){
        $("#comment_list").load($(this).attr('href'));
        return false;
    });

    //评论删除
    $("[wttype=comment_drop]").live('click',function(){
        if(confirm('<?php echo $lang['wt_ensure_del'];?>')) {
            var item = $(this).parents("dl");
            $.post("index.php?w=comment&t=comment_drop&type=<?php echo $_GET['w'];?>", { comment_id: $(this).attr("comment_id")}, function(json){
                if(json.result == "true") {
                    item.remove();
                } else {
                    showError(json.message);
                }
            },'json');
        }
    });

    <?php if($_SESSION['is_login'] != '1'){?>
    //登陆窗口
    $("#btn_login").wt_login({
        wthash:'<?php echo getWthash();?>',
        formhash:'<?php echo Security::getTokenValue();?>',
        anchor:'news_comment_flag'
    });
    <?php } ?>

    $('#comment_list').on('click', '[wttype="comment_quote"]', function() {
        <?php if($_SESSION['is_login'] != '1'){?>
        //登陆窗口
        $.show_wt_login({
            wthash:'<?php echo getWthash();?>',
            formhash:'<?php echo Security::getTokenValue();?>',
            anchor:'news_comment_flag'
        });
        <?php } else { ?>
        var $comment = $(this).parents('p').next('.comment-quote');
        if($comment.length > 0) {
            $comment.remove();
        } else {
            $(this).parents('p').after('<p class="comment-quote">' + $('#comment_quote').html() + '<input name="comment_id" value="' + $(this).attr('comment_id') + '" type="hidden" />' + '</p>');
        }
        <?php } ?>
    });

    //回复
    $('#comment_list').on('click', '[wttype="btn_comment_quote_publish"]', function() {
        var comment_id = $(this).parents('p').find('input').val();
        var comment_object_id = $('#input_comment_object_id').val();
        var comment_type = $('#input_comment_type').val();
        var comment_message = $(this).parents('p').find('textarea').val();
        $.post("<?php echo NEWS_SITE_URL.DS.'index.php?w=comment&t=comment_save';?>", {comment_id:comment_id, comment_object_id:comment_object_id, comment_type:comment_type, comment_message:comment_message},
            function(data){
                if(data.result == 'true') {
                    $("#input_comment_message").val("");
                    $("#comment_list").load(url_comment_list);
                    $("#comment_list dl").first().hide().fadeIn("fast");
                } else {
                    showError(data.message);
                }
            }, "json");
    });

    $('#comment_list').on('click', '[wttype="btn_comment_quote_cancel"]', function() {
        $(this).parents('p').remove();
    });

    $('#comment_list').on('click', '[wttype="comment_up"]', function() {
        <?php if($_SESSION['is_login'] != '1'){?>
        //登陆窗口
        $.show_wt_login({
            wthash:'<?php echo getWthash();?>',
            formhash:'<?php echo Security::getTokenValue();?>',
            anchor:'news_comment_flag'
        });
        <?php } else { ?>
        var comment_id = $(this).attr('comment_id');
        var $count = $(this).find('em');
        $.post("<?php echo NEWS_SITE_URL.DS.'index.php?w=comment&t=comment_up';?>", {comment_id:comment_id},
            function(data){
                if(data.result == 'true') {
                    var old_count = parseInt($count.text());
                    $count.text(old_count + 1);
                } else {
                    showError(data.message);
                }
         }, "json");
        <?php } ?>
    });

});
</script>

<div id="news_comment_flag" class="article-comment-title">
  <h3>我有说话...</h3>
  <span><?php echo $lang['news_comment1'];?><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=<?php echo $_GET['w'];?>&t=<?php echo $_GET['w'];?>_comment_detail&<?php echo $_GET['w'];?>_id=<?php echo $output['detail_object_id'];?>"><em><?php echo $output[$_GET['w'].'_detail'][$_GET['w'].'_comment_count'];?></em></a><?php echo $lang['news_comment2'];?><em><?php echo $output[$_GET['w'].'_detail'][$_GET['w'].'_click'];?></em><?php echo $lang['news_comment3'];?></span> </div>
<form id="add_form" action="" class="article-comment-form">
  <input id="input_comment_type" name="comment_type" type="hidden" value="<?php echo $_GET['w'];?>" />
  <input id="input_comment_object_id" name="comment_object_id" type="hidden" value="<?php echo $output['detail_object_id'];?>" />
  <textarea id="input_comment_message" name="comment_message" class="article-comment-textarea"></textarea>
  <input id="btn_comment_submit" type="button" class="article-comment-btn" value="发布" />
  <?php if($_SESSION['is_login'] != '1'){?>
  <div class="article-comment-login"><?php echo $lang['news_comment4'];?><a id="btn_login" href="javascript:;">[<?php echo $lang['news_login'];?>]</a><?php echo $lang['news_comment5'];?></div>
  <?php }?>
</form>
<div id="comment_list" class="article-comment-list"></div>
<div id="comment_quote" style="display:none;"><a wttype="btn_comment_quote_cancel" href="JavaScript:;" class="cancel-btn" title="取消"></a>
  <textarea name="comment_quote" rows="3" cols="30"></textarea>
  <a wttype="btn_comment_quote_publish" href="JavaScript:;" class="publish-btn">发布</a></div>
