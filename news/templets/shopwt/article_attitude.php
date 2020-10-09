<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script type="text/javascript">
    <?php if($_SESSION['is_login'] != '1'){?>
    $(document).ready(function(){
    //登陆窗口
    $("[wttype='article_attitude']").wt_login({
        wthash:'<?php echo getWthash();?>',
        formhash:'<?php echo Security::getTokenValue();?>',
        anchor:'news_attitude_flag'
    });
    show_article_attitude();
    });
    <?php } else { ?>
$(document).ready(function(){
    $("[wttype='article_attitude']").click(function() {
        var article_id = <?php echo $_GET['article_id'];?>;
        var article_attitude = $(this).attr("attitude");
        var attitude_item = $(this);
        $.getJSON("<?php echo NEWS_SITE_URL.DS;?>index.php?w=attitude&t=article_attitude", { article_id: article_id, article_attitude: article_attitude }, function(json){
            if(json.result == "true") {
                var item = attitude_item.find("[wttype='article_attitude_count']");
                var current = item.text();
                item.text(Number(current)+1);
                show_article_attitude();
            } else {
                showError(json.message);
            }
        });
    });
    show_article_attitude();
});
<?php } ?>
function show_article_attitude() {
    var attitude_count_sum = 0;
    $("[wttype='article_attitude_count']").each(function() {
        attitude_count_sum += Number($(this).text());
    });
    if(attitude_count_sum > 0) {
        $("[wttype='article_attitude']").each(function() {
            var attitude_count = $(this).find("[wttype='article_attitude_count']").text();
            var attitude_percent = parseInt(Number(attitude_count) / attitude_count_sum * 100);
            $(this).find("[wttype='article_attitude_percent']").css("height", attitude_percent + "%");
        });
    }
}

</script>

<h3><?php echo $lang['attitude_title'];?></h3>
<ul id="news_attitude_flag">
  <?php for ($i = 1; $i <= 6; $i++) { ?>
  <li wttype="article_attitude" attitude="<?php echo $i;?>" title="<?php echo $output['article_attitude_list'][$i];?>">
      <div class="article-attitude-percent-box"> <em wttype="article_attitude_count"><?php echo $output['article_detail']['article_attitude_'.$i];?></em>
        <p class="article-attitude-percent-<?php echo $i;?>" wttype="article_attitude_percent" ></p>
      </div>
      <img src="<?php echo NEWS_TEMPLATES_URL;?>/images/attitude/<?php echo $i;?>.png" alt="" /> <span><?php echo $output['article_attitude_list'][$i];?></span>
  </li>
  <?php } ?>
</ul>
<div class="clear"></div>
