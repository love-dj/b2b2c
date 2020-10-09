<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php echo getChat($layout);?>
<div class="slogen">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
      <?php foreach($output['contract_list'] as $k=>$v){?>
        <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
        <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo $v['cti_icon_url_60']; ?>" rel="lazy" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
        <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div class="faq">
  <div class="wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
    <ul>
      <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
      <?php if(!empty($article_class)){ ?>
      <li>
        <dl class="s<?php echo ''.$k+1;?>">
          <dt>
            <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
          </dt>
          <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
          <?php foreach ($article_class['list'] as $article){ ?>
          <dd><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd>
          <?php }?>
          <?php }?>
        </dl>
      </li>
      <?php }}?>
      <li class="kefu-con">
      <dl>
      	<dt>联系我们</dt>
        <dd>
        <i class="icon-tel"></i>
        <div><strong><?php echo $output['setting_config']['wt_phone']; ?></strong>
         <p><?php echo $output['setting_config']['wt_time']; ?></p>
         </div>
          </dd>
          <dd>
          <i class="icon-chat"></i>
          <div>
          <strong>在线客服</strong>
           <p>E-mail：<?php echo $output['setting_config']['wt_mail']; ?></p>
           </div>
        </dd>
       </dl>
      </li>
      <li class="box-qr">
        <dl>
          <dt>关注我们<a title="关注<?php echo $output['setting_config']['site_name']; ?>微信公众号" class="weixin">
                        <span class="weixin-qr"></span>
                    </a>
                    <a title="关注<?php echo $output['setting_config']['site_name']; ?>微博" class="weibo">
                        <span class="weibo-qr"></span>
                    </a>
          </dt>
          <?php if (C('mobile_isuse') && C('mobile_app')){?>
          <!-- <dd>
            <p><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" rel="lazy" /></p>
            <p>下载APP手机端</p>
          </dd> -->
          <?php } ?>
          <?php if (C('mobile_wx')) { ?>
          <dd>
            <p><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/loading.gif" data-url="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.C('mobile_wx');?>" rel="lazy" /></p>
            <p>关注微信公众号</p>
          </dd>
          <?php } ?>
        </dl>
      </li>
    </ul>
    <?php }?>
  </div>
</div>
<div class="footer">
  <p><a href="<?php echo BASE_SITE_URL;?>"><?php echo $lang['wt_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>  <a href="http://www.miibeian.gov.cn" rel="nofollow" target="_blank"><?php echo $output['setting_config']['icp_number']; ?></a></div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['wt_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<?php if($output['index_sign'] != 'index' && $output['index_sign'] != '0'){?>
<div id="task"></div>
<script type="text/javascript">
document.getElementById('task').innerHTML='<iframe src="<?php echo BASE_SITE_URL;?>/system/task/cj_index.php?w=task&run=js" width="0" height="0" frameborder="0" style="display:none;"></iframe>';
</script>
<?php }?>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo STATIC_SITE_URL;?>/js/compare.js"></script> 
<script type="text/javascript">
$(function(){$('[wttype="mcard"]').membershipCard({type:'shop'});});
</script>