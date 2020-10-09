<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<style type="text/css">
.wts-info .btns .chat_online, .wts-info .btns .chat_offline {padding-left:10px;  width:73px;  line-height:21px !important;background: url("<?php echo SHOP_TEMPLATES_URL;?>/images/chat_ico.png") no-repeat;}
.wts-info .btns .chat_online { background-position: 8px 2px;}
.wts-info .btns .chat_offline { background-position: 8px -19px;}
.wts-info .btns span a:hover { background: url("<?php echo SHOP_TEMPLATES_URL;?>/images/chat_ico.png") no-repeat 8px 2px;;}
</style>
<!--店铺基本信息 S-->
<div class="wts-info wts-infobox">
  <div class="content">
<div class="store-logo"><a title="<?php echo $output['store_info']['store_name'];?>" target="_blank" href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" ><img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name'];?>"></a></div>
     <div class="title">
    <h4><a class="name" title="<?php echo $output['store_info']['store_name'];?>" target="_blank" href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" ><?php echo $output['store_info']['store_name']; ?></a><?php if ($output['store_info']['is_own_shop']) { ?><em>自营</em><?php } ?></h4>
  </div>
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <div class="store-infos">
    <dl>
      <dt>公司名称：</dt>
      <dd><?php echo $output['store_info']['store_company_name'];?></dd>
    </dl>
     <dl>
      <dt>所 在 地：</dt>
      <dd><?php echo $output['store_info']['area_info'];?></dd>
    </dl>
    <?php if(!empty($output['store_info']['store_phone'])){?>
    <dl>
      <dt>客服电话：</dt>
      <dd><?php echo $output['store_info']['store_phone'];?></dd>
    </dl>
    <?php } ?>
        <?php if($output['store_info']['store_workingtime'] !=''){?>
   
        <dl>
      <dt>工作时间：</dt>
      <dd><?php echo html_entity_decode($output['store_info']['store_workingtime']);?></dd>
    </dl> <?php }?>
    </div>
    <?php } ?>
    
    <?php if (!$output['store_info']['is_own_shop']) { ?>
	<div class="score-infor">
	 <div class="score-sum"><span class="number <?php echo $value['percent_class'];?>"><?php echo $output['store_info']['store_credit_average'];?></span></div>
    <div class="wts-detail-rate">
      <ul>
        <?php  foreach ($output['store_info']['store_credit'] as $value) {?>
        <li>
          <h5><?php echo $value['text'];?></h5>
          <div class="<?php echo $value['percent_class'];?>" title="<?php echo $value['percent_text'];?><?php echo $value['percent'];?>"><?php echo $value['credit'];?><i></i></div>
        </li>
        <?php } ?>
      </ul>
      </div>
    </div>
<?php } ?>
    
    <?php if(!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])){?>
    <?php } ?>
    <div class="btns clearfix"><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['store_info']['store_id']), $output['store_info']['store_domain']);?>" class="goto" >进店逛逛</a><a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" >收藏店铺<span>(<em wttype="store_collect"><?php echo $output['store_info']['store_collect']?></em>)</span></a>
   <?php if(!empty($output['store_info']['store_qq'])){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:8" style=" vertical-align: middle;"/></a>
        <?php }?>
        <?php if(C('node_chat')){ ?>
        <span member_id="<?php echo $output['store_info']['member_id'];?>" ></span>
        <?php }else{ ?>
        <?php if(!empty($output['store_info']['store_ww'])){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /> 旺旺</a>
        <?php }?>
	<?php }?>
     </div>
  </div>
</div>
 
<script>
$(function(){
	var store_id = "<?php echo $output['store_info']['store_id']; ?>";
	var goods_id = "<?php echo $_GET['goods_id']; ?>";
	var w = "<?php echo trim($_GET['w']); ?>";
	var t  = "<?php echo trim($_GET['t']) != ''?trim($_GET['t']):'index'; ?>";
	$.getJSON("index.php?w=show_store&t=ajax_flowstat_record",{store_id:store_id,goods_id:goods_id,w_param:w,t_param:t});
});
</script> 
