<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".image_lazy_load").wt_lazyload();
    });
</script>
<div class="warp-all article-box-a">
  <div class="mainbox">
    <div class="sitenav-bar">
        <div class="sitenav"><?php echo $lang['current_location'];?><?php echo $lang['wt_colon'];?> <a href="<?php echo NEWS_SITE_URL;?>"><?php echo $lang['news_site_name'];?></a> > <a href="<?php echo NEWS_SITE_URL.DS.'index.php?w=article&t=article_list';?>"><?php echo $lang['news_article'];?></a><?php echo empty($_GET['class_id'])?'':' > '.$output['article_class_list'][$_GET['class_id']]['class_name'];?></div>
    </div>
    <?php if(!empty($output['article_list']) && is_array($output['article_list'])) {?>
    <ul class="article-list">
      <?php foreach($output['article_list'] as $value) {?>
      <?php $article_url = getNEWSArticleUrl($value['article_id']);?>
      <li>
        <h3 class="article-title"> <a href="<?php echo $article_url;?>" target="_blank"> <?php echo $value['article_title'];?> </a> </h3>
        <div class="article-sub"><span class="PV" title="<?php echo $lang['news_text_view_count'];?>"><i></i><?php echo $value['article_click'];?></span><span class="author"><?php echo $lang['news_text_publisher'];?><?php echo $lang['wt_colon'];?><?php echo empty($value['article_author'])?$lang['news_text_guest']:$value['article_author'];?></span><span class="source"><?php echo $lang['news_text_origin'];?><?php echo $lang['wt_colon'];?> <a href="<?php echo empty($value['article_origin_address'])?NEWS_SITE_URL:$value['article_origin_address'];?>" target="_blank"> <?php echo empty($value['article_origin'])?C('site_name'):$value['article_origin'];?> </a></span><span class="date"><?php echo date('Y-m-d',$value['article_publish_time']);?></span></div>
        <div class="article-preface"><?php echo $value['article_abstract'];?><a href="<?php echo $article_url;?>" target="_blank"><?php echo $lang['news_read_more'];?></a></div>
        <?php if(!empty($value['article_image'])) { ?>
        <div class="article-cover"><a href="<?php echo $article_url;?>" target="_blank"> <img class="image_lazy_load" data-src="<?php echo getNEWSArticleImageUrl($value['article_attachment_path'], $value['article_image'], 'list');?>" src="<?php echo getLoadingImage();?>" alt="" /></a></div>
        <?php } ?>
        <div class="article-tag"><?php echo $lang['news_keyword'];?><?php echo $lang['wt_colon'];?>
          <?php if(!empty($value['article_keyword'])) { ?>
          <?php $article_keyword_array = explode(',', $value['article_keyword']);?>
          <?php foreach ($article_keyword_array as $value) {?>
          <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=article&t=article_search&keyword=<?php echo rawurlencode($value);?>" target="_blank"><?php echo $value;?></a>
          <?php } ?>
          <?php } ?>
        </div>
      </li>
      <?php } ?>
    </ul>
    <div class="pagination"> <?php echo $output['show_page'];?> </div>
    <?php } else { ?>
    <div class="no-content-b"><i class="article"></i><?php echo $lang['no_record'];?></div>
    <?php } ?>
  </div>
  <div class="sidebar">
    <?php require('article_list.sidebar.php');?>
  </div>
</div>
