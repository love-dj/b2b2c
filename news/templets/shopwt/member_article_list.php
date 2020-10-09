<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="news-member-box">
  <div class="news-member-sidebar">
    <?php require('member_sidebar.php');?>
    <ul class="news-member-menu">
      <li <?php echo $_GET['t']=='article_list'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_list"><i class="a"></i><?php echo $lang['news_my_article'];?></a></li>
      <li <?php echo $_GET['t']=='draft_list'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=draft_list"><i class="b"></i><?php echo $lang['news_text_draft'];?></a></li>
      <li <?php echo $_GET['t']=='recycle_list'?' class="current"':'';?>><a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=recycle_list"><i class="c"></i><?php echo $lang['news_text_recycle'];?></a></li>
    </ul>
  </div>
  <div class="news-member-main">
    <table class="news-member-table">
      <thead>
        <tr>
          <th class="w30"><?php echo $lang['news_text_id'];?></th>
          <th><?php echo $lang['news_text_article'];?></th>
          <th class="w90"><?php echo $lang['news_text_date'];?></th>
          <th class="w90"><?php echo $lang['news_text_state'];?></th>
          <th class="w60"><?php echo $lang['news_text_click_count'];?></th>
          <th class="w90"><?php echo $lang['news_text_op'];?></th>
        </tr>
        <tr>
          <td colspan="10"><form action="" method="get">
              <input name="w" type="hidden" value="member_article" />
              <input name="t" type="hidden" value="<?php echo $_GET['t'];?>" />
              <?php if($_GET['t'] == 'article_list') { ?>
              <select name="article_state">
                <option value=""><?php echo $lang['news_text_all'];?></option>
                <option value="3" <?php echo $_GET['article_state'] == '3'?'selected':'';?>><?php echo $lang['news_text_published'];?></option>
                <option value="2" <?php echo $_GET['article_state'] == '2'?'selected':'';?>><?php echo $lang['news_text_verify'];?></option>
              </select>
              <?php } ?>
              <input name="keyword" type="text" value="<?php echo $_GET['keyword'];?>" placeholder="<?php echo $lang['news_article_keyword'];?>" class="text" />
              <input type="submit" class="search-btn" value="<?php echo $lang['news_text_search'];?>" />
            </form></td>
        </tr>
      </thead>
      <?php if(!empty($output['article_list']) && is_array($output['article_list'])) {?>
      <tbody>
        <?php foreach($output['article_list'] as $value) {?>
        <?php $article_url = getNEWSArticleUrl($value['article_id']);?>
        <tr>
          <td><?php echo $value['article_id'];?></td>
          <td><dl class="article-info">
              <dt class="title" title="<?php echo $value['article_title'];?>"><a href="<?php echo $article_url;?>" target="_blank"><?php echo $value['article_title'];?></a></dt>
              <dd class="thumb" title="<?php echo $lang['news_cover'];?>"><a href="<?php echo $article_url;?>" target="_blank"> <img src="<?php echo getNEWSArticleImageUrl($value['article_attachment_path'], $value['article_image']);?>" alt="" class="t-img"/></a></dd>
              <dd class="abstract" title="<?php echo $lang['news_article_abstract'];?><?php echo $lang['wt_colon'];?><?php echo $value['article_abstract'];?>"><?php echo $value['article_abstract'];?></dd>
            </dl></td>
          <td><?php echo date('Y-m-d',$value['article_publish_time']);?></td>
          <td><?php echo $output['article_state_list'][$value['article_state']];?></td>
          <td><?php echo $value['article_click'];?></td>
          <td class="handle">
            <?php if($value['article_state'] != '3') {?>
              <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=article&t=article_detail&article_id=<?php echo $value['article_id'];?>" target="_blank" style="color:#06C;"><?php echo $lang['news_text_view'];?></a>
              <?php } else { ?>
              <a href="<?php echo $article_url;?>" target="_blank" style="color:#06C;"><?php echo $lang['news_text_view'];?></a>
              <?php } ?>
            <?php if($_GET['t'] == 'draft_list') {?>
            <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_publish&article_id=<?php echo $value['article_id'];?>" onclick="javascript:return confirm('<?php echo $lang['news_publish_confirm'];?>')" style="color:#390;"><?php echo $lang['news_text_publish'];?></a> <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_edit&article_id=<?php echo $value['article_id'];?>" style="color:#F90;" ><?php echo $lang['news_text_edit'];?></a> <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_recycle&article_id=<?php echo $value['article_id'];?>" style="color:#F36;"><?php echo $lang['news_text_remove'];?></a>
            <?php } ?>
            <?php if($_GET['t'] == 'recycle_list') {?>
            <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_draft&article_id=<?php echo $value['article_id'];?>" style="color:#639;"><?php echo $lang['news_text_back'];?></a> <a href="<?php echo NEWS_SITE_URL.DS;?>index.php?w=member_article&t=article_drop&article_id=<?php echo $value['article_id'];?>" onclick="javascript:return confirm('<?php echo $lang['news_delete_confirm'];?>')" style="color:#F00;"><?php echo $lang['news_delete'];?></a>
            <?php } ?></td>
        </tr>
            <?php if($_GET['t'] == 'draft_list') {?>
            <?php if(!empty($value['article_verify_reason'])) { ?>
            <tr>
                <td colspan="20"><strong class="fl" style="color: #F00;">审核结果：未通过平台审核，<?php echo $value['article_verify_reason'];?>。请根据审核意见进行修改编辑后再次提交。</strong></td>
            </tr>
            <?php } ?>
            <?php } ?>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
      </tfoot>
      <?php } else { ?>
      <tbody>
        <tr>
          <td colspan="20"><div class="no-record"><i></i><?php echo $lang['no_record'];?></div></td>
        </tr>
      </tbody>
      <?php } ?>
    </table>
  </div>
</div>
<script type="text/javascript">
$(window).load(function() {
	$(".article-info .t-img").VMiddleImg({"width":40,"height":32});
});
</script> 
