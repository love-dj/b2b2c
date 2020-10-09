<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php require NEWS_BASE_TPL_PATH.'/layout/top.php';?>
<style type="text/css">
.search-news,.news-write { display: none !important;}
#topHeader .warp-all { height: 80px !important;}
#topHeader .news-logo { top: 8px !important;}
</style>


<div class="news-member-nav-bar"> 
  <!-- NEWS用户中心导航 -->
  <ul class="news-member-nav">
    <li <?php echo $_GET['w']=='member_article'&&$_GET['t']!='article_edit'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_list" ><i class="a"></i><?php echo $lang['news_article_list'];?></a></li>
    <li <?php echo $_GET['t']=='publish_article'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=publish&t=publish_article"><i class="b"></i><?php echo $lang['news_article_publish'];?></a></li>
    <li <?php echo $_GET['w']=='member_picture'&&$_GET['t']!='picture_edit'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_picture&t=picture_list"><i class="c"></i><?php echo $lang['news_picture_list'];?></a></li>
    <li <?php echo $_GET['t']=='publish_picture'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=publish&t=publish_picture"><i class="d"></i><?php echo $lang['news_picture_publish'];?></a></li>
    <li><a href="<?php echo urlLogin('login', 'logout');?>"><i class="e"></i><?php echo $lang['news_loginout'];?></a></li>
  </ul></div>
  <?php require_once($tpl_file);?>

<?php require NEWS_BASE_TPL_PATH.'/layout/footer.php';?>
