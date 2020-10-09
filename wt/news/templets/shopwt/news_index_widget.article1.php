<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="news-index-module-article1 module-style-<?php echo $value['module_style'];?>">
  <div class="news-index-module-article-title"> 
    <!-- 标题 -->
    <div class="news-index-module-article-title-left">
      <h2 id="article1_title" wttype="object_module_edit"><?php echo $module_content['article1_title'];?></h2>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_title_edit" href="JavaScript:void(0);" class="tip-r" title="<?php echo $lang['news_index_module_title_edit'];?>"><?php echo $lang['news_index_module_title_edit'];?></a></div>
      <?php } ?>
    </div>
    <!-- 标签 -->
    <div class="news-index-module-article-title-right">
      <ul id="article1_tag" wttype="object_module_edit">
        <?php echo html_entity_decode($module_content['article1_tag']);?>
      </ul>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a id="btn_module_tag_edit" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_tag_edit'];?>"><?php echo $lang['news_index_module_tag_edit'];?></a></div>
      <?php } ?>
    </div>
  </div>
  <div class="clear"></div>
  <div class="news-index-module-article1-1"> 
    <!-- 封面图 -->
    <div class="article1-1-1-contnet">
      <ul id="article1_image" wttype="object_module_edit">
        <?php echo html_entity_decode($module_content['article1_image']);?>
      </ul>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_image_edit" image_count="1" href="JavaScript:void(0);" class="tip-r" data-title="<?php echo $lang['news_index_module_image_edit_title'];?>" title="<?php echo $lang['news_index_module_image_edit_title'];?>"><?php echo $lang['news_index_module_image_edit'];?></a></div>
      <?php } ?>
    </div>
    <!-- 文章　-->
    <div class="news-index-module-article1-1-2">
      <div class="title-bar">
        <h3 id="article1_1_2_title" wttype="object_module_edit"><?php echo $module_content['article1_1_2_title'];?></h3>
        <?php if($output['edit_flag']) { ?>
        <div class="news-index-module-handle"><a wttype="btn_module_title_edit" href="JavaScript:void(0);" class="tip-r" title="<?php echo $lang['news_index_module_title_edit'];?>"><?php echo $lang['news_index_module_title_edit'];?></a></div>
        <?php } ?>
      </div>
      <div class="content-box">
        <ul id="article1_1_2_content" wttype="object_module_edit">
          <?php echo html_entity_decode($module_content['article1_1_2_content']);?>
        </ul>
        <?php if($output['edit_flag']) { ?>
        <div class="news-index-module-handle"><a wttype="btn_module_article_edit" save_function="article_type_1_save" limit_count="5" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_article_edit'];?>"><?php echo $lang['wt_edit'];?></a></div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- 文章　-->
  <div class="news-index-module-article1-2">
    <div class="title-bar">
      <h3 id="article1_2_title" wttype="object_module_edit"><?php echo $module_content['article1_2_title'];?></h3>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_title_edit" href="JavaScript:void(0);" class="tip-r" title="<?php echo $lang['news_index_module_title_edit'];?>"><?php echo $lang['news_index_module_title_edit'];?></a></div>
      <?php } ?>
    </div>
    <div class="content-box">
      <ul id="article1_2_1_content" wttype="object_module_edit">
        <?php echo html_entity_decode($module_content['article1_2_1_content']);?>
      </ul>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_article_edit" save_function="article_type_2_save" limit_count="6" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_article_edit'];?>"><?php echo $lang['wt_edit'];?></a></div>
      <?php } ?>
    </div>
    <div class="content-box">
      <ul id="article1_2_2_content" wttype="object_module_edit">
        <?php echo html_entity_decode($module_content['article1_2_2_content']);?>
      </ul>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_article_edit" save_function="article_type_2_save" limit_count="6" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_article_edit'];?>"><?php echo $lang['wt_edit'];?></a></div>
      <?php } ?>
    </div>
  </div>
  <!-- 商品　-->
  <div class="news-index-module-article1-3">
    <div class="title-bar">
      <h3 id="article1_3_title" wttype="object_module_edit"><?php echo $module_content['article1_3_title'];?></h3>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_title_edit" href="JavaScript:void(0);" class="tip-r" title="<?php echo $lang['news_index_module_title_edit'];?>"><?php echo $lang['news_index_module_title_edit'];?></a></div>
      <?php } ?>
    </div>
    <div class="content-box">
      <ul id="article1_3_content" wttype="object_module_edit">
        <?php echo html_entity_decode($module_content['article1_3_content']);?>
      </ul>
      <?php if($output['edit_flag']) { ?>
      <div class="news-index-module-handle"><a wttype="btn_module_goods_edit" limit_count="6" href="JavaScript:void(0);" class="tip-l" title="<?php echo $lang['news_index_module_goods_edit'];?>"><?php echo $lang['news_index_module_goods_edit'];?></a></div>
      <?php } ?>
    </div>
  </div>
  <div class="clear"></div>
</div>
