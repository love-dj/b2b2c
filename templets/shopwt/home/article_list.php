<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<div class="wth-container wrapper">
  <div class="article-left">
    <div class="wth-module sidebar">
      <div class="title">
        <h3><?php echo $lang['article_article_article_class'];?></h3>
      </div>
	   <?php if(is_array($output['sub_class_list']) && !empty($output['sub_class_list'])){ ?>
      <div class="content">
	   <?php foreach ($output['sub_class_list'] as $k=> $article_class){ ?>
      <?php if(!empty($article_class)){ ?>
      <dl show_id="<?php echo ''.$k+1;?>">
        <dt onclick="show_list('<?php echo ''.$k+1;?>');">
            <i class="hide"></i><?php echo $article_class['ac_name'];?></dt>
		<?php if(is_array($article_class['tlist']) && !empty($article_class['tlist'])){ ?>
		   <dd <?php if($_GET['ac_id']!=$article_class['ac_id']){?>style="display: none;" <?php }?>>
          <ul>
          <?php foreach ($article_class['tlist'] as $article){ ?>
       
		  <li><i></i><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"><?php echo $article['article_title'];?></a></li>
			<?php }?>
			   <li><i></i><a href="<?php echo urlShop('article', 'article', array('ac_id'=>$article_class['ac_id']));?>">更多...</a></li>
			  </ul>
        </dd>
         <?php }?>
		
		  
      </dl>
	  <?php }}?>
      </div>
	<?php }?>
    <div class="title">
      <h3>平台联系方式</h3>
    </div>
    <div class="content">
      <ul>
        <?php
			if(is_array($output['phone_array']) && !empty($output['phone_array'])) {
				foreach($output['phone_array'] as $key => $val) {
			?>
        <li><?php echo '电话'.($key+1).$lang['wt_colon'];?><?php echo $val;?></li>
        <?php
				}
			}
			 ?>
        <li><?php echo '邮箱'.$lang['wt_colon'];?><?php echo C('site_email');?></li>
      </ul>
    </div>
    </div>
  </div>
  <div class="article-right">
    <div class="wth-article-con">
      <div class="title-bar">
        <h3><?php echo $output['class_name'];?></h3>
      </div>
      <?php if(!empty($output['article']) and is_array($output['article'])){?>
      <ul class="wth-article-list">
        <?php foreach ($output['article'] as $article) {?>
        <li><i></i><a <?php if($article['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($article['article_url']!='')echo $article['article_url'];else echo urlShop('article', 'show', array('article_id'=>$article['article_id']));?>"><?php echo $article['article_title'];?></a><time><?php echo date('Y-m-d H:i',$article['article_time']);?></time></li>
        <?php }?>
      </ul>
      <?php }else{?>
      <div><?php echo $lang['article_article_not_found'];?></div>
      <?php }?>
     
    </div> <div class="tc mb20">  <div class="pagination"> <?php echo $output['show_page'];?> </div></div>
  
  </div>
</div>

<script type="text/javascript">
    function show_list(t_id){
        var obj = $(".sidebar dl[show_id='"+t_id+"']");
    	var show_class=obj.find("dt i").attr('class');
    	if(show_class=='hide') {
    		obj.find("dd").show();
    		obj.find("dt i").attr('class','show');
    	}else{
    		obj.find("dd").hide();
    		obj.find("dt i").attr('class','hide');
    	}
    }
</script>
