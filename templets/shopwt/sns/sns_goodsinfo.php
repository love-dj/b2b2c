<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="sidebar">
<?php include template('sns/sns_sidebar_sharegoods');?>
</div>
<div class="left-content">
  <input type="hidden" id="rtype" value="<?php echo $_GET['type'];?>"/>
  <div class="goback">
    <a href="index.php?w=member_snshome&t=shareglist&type=<?php if ($_GET['type'] == 'like'){?>like<?php }else { ?>share<?php } ?>&mid=<?php echo $output['sharegoods_content']['share_memberid'];?>">&#8249;&nbsp;<?php echo $lang['sns_goback_all_goods'];?></a>
  </div>
  <div class="snsgoods-content">
    <div class="title">
      <h3><?php echo $output['sharegoods_content']['snsgoods_goodsname'];?></h3>
    </div>
    <div class="gcontainer">
      <div class="pic-module">
      <?php if($output['sharegoods_content']['snsgoods_isfirst'] == false){?>
          <div class="prev <?php echo $output['sharegoods_content']['snsgoods_islast']==true?'whole':'';?>" wt_type="imgflipover" data-param='{"id":"<?php echo $output['sharegoods_content']['snsgoods_previd'];?>"}'></div>
          <?php }?>
          <?php if($output['sharegoods_content']['snsgoods_islast'] == false){?>
          <div class="next <?php echo $output['sharegoods_content']['snsgoods_isfirst']==true?'whole':'';?>" wt_type="imgflipover" data-param='{"id":"<?php echo $output['sharegoods_content']['snsgoods_nextid'];?>"}'></div>
          <?php }?>
        <div class="good-img"> <img src="<?php echo cthumb($output['sharegoods_content']['snsgoods_goodsimage'],1280,$output['sharegoods_content']['snsgoods_storeid']);?>"/> </div>
      </div>
      <div class="handle-module">
        <div class="operate" id="likestat_<?php echo $output['sharegoods_content']['share_goodsid'];?>"><span><i></i>
          <?php if($output['sharegoods_content']['snsgoods_havelike'] == 1){echo $lang['sns_like'];} else{?>
          <a href="javascript:void(0);" wt_type="likebtn" data-param='{"gid":"<?php echo $output['sharegoods_content']['share_goodsid'];?>"}' class="<?php echo $output['sharegoods_content']['snsgoods_havelike']==1?'noaction':''; ?>"><?php echo $lang['sns_like'];?></a>
          <?php }?>
          </span> <em wt_type="likecount_<?php echo $output['sharegoods_content']['share_goodsid'];?>"><?php echo $output['sharegoods_content']['snsgoods_likenum'];?></em></div>
        <?php if ($output['relation'] == 3){//  主人自己?>
        <div class="btn set" wt_type="privacydiv" id="recordone_<?php echo $output['sharegoods_content']['share_id'];?>"> <a href="javascript:void(0)" wt_type="privacybtn"><i></i><?php echo $lang['sns_setting'];?></a>
          <ul wt_type="privacytab" style="display:none;" >
            <li wt_type="privacyoption" data-param='{"sid":"<?php echo $output['sharegoods_content']['share_id'];?>","v":"0"}'><span class="<?php echo $output['sharegoods_content']['share_privacy']==0?'selected':'';?>"><?php echo $lang['sns_open'];?></span></li>
            <li wt_type="privacyoption" data-param='{"sid":"<?php echo $output['sharegoods_content']['share_id'];?>","v":"1"}'><span class="<?php echo $output['sharegoods_content']['share_privacy']==1?'selected':'';?>"><?php echo $lang['sns_friend'];?></span></li>
            <li wt_type="privacyoption" data-param='{"sid":"<?php echo $output['sharegoods_content']['share_id'];?>","v":"2"}'><span class="<?php echo $output['sharegoods_content']['share_privacy']==2?'selected':'';?>"><?php echo $lang['sns_privacy'];?></span></li>
          </ul>
        </div>
        <?php if($_GET['type'] != 'like'){?>
        <div class="btn buyer-show"><a href="javascript:void(0)" wttype="add_share" data-param="{'sid':'<?php echo $output['sharegoods_content']['share_id'];?>', 'gid':'<?php echo $output['sharegoods_content']['share_goodsid'];?>'}"><i></i><?php echo $lang['sns_buyershow'];?></a></div>
        <?php }?>
        <?php } ?>
        <div class="btn"><a href="<?php echo $output['sharegoods_content']['snsgoods_goodsurl'];?>" target="_blank"><strong><?php echo $lang['sns_viewdetails'];?></strong></a></div>
        <div class="price"><?php echo $lang['currency'];?><?php echo $output['sharegoods_content']['snsgoods_goodsprice'];?></div>
      </div>
    </div>
    <?php if ($_GET['type'] != 'like'){?><div class="share-content"><i></i><p><?php echo $output['sharegoods_content']['share_content'];?><i></i></p></div><?php }?>
    <!-- 买家秀图片S -->
    <?php if(!empty($output['pic_list'])){?>
    <div class="ap-pic-module"><div class="top"><span><?php echo $lang['sns_buyershow'];?></span><h3><?php echo $lang['sns_upload_buyer_show'];?></h3></div>
    <div class="picture">
      <?php foreach ($output['pic_list'] as $val){?>
      <img src="<?php echo $val['ap_cover'];?>" />
      <?php }?>
    </div></div>
    <?php }?>
    <!-- 买家秀图片E -->
    <div class="interact-module"><span class="add-time"><?php if ($_GET['type'] == 'like'){echo @date('Y-m-d H:i',$output['sharegoods_content']['share_likeaddtime']); }else {echo @date('Y-m-d H:i',$output['sharegoods_content']['share_addtime']);}?></span>
      <ul>
        <li><a data-param='{"gid":"<?php echo $output['sharegoods_content']['share_goodsid'];?>"}' wt_type="sharegoods" href="javascript:void(0);"><?php echo $lang['wt_snsshare'];?><em>(<?php echo $output['sharegoods_content']['snsgoods_sharenum'];?>)</em></a></li>
        <li><a href="javascript:void(0);" class="comment-btn"><?php echo $lang['sns_comment'];?><em>(<?php echo $output['sharegoods_content']['share_commentcount'];?>)</em></a></li>
      </ul>
    </div>
    <!-- 商品评论 -->
    <div class="comment-module">
      <div class="arrow"></div>
      <div id="tracereply_<?php echo $output['sharegoods_content']['share_id'];?>" class="load-module"></div>
      <div style="clear: both;"></div>
    </div>
  </div>
</div>
<div class="clear"></div>
<script type="text/javascript">
//评论隐藏/显示
$(document).ready(function(){
  	$(".comment-btn").click(function(){
  	$(".comment-module").slideToggle(300);
  	});
});
document.onclick = function(){ $("#smilies_div").html(''); $("#smilies_div").hide();};
$(function(){
	//图片翻页
	$("[wt_type='imgflipover']").bind('click',function(){
	    var data = $(this).attr('data-param');
	    eval( "data = "+data);
	    var type = $("#rtype").val();
	    window.location.href="index.php?w=member_snshome&t=goodsinfo&type="+type+"&mid=<?php echo $output['sharegoods_content']['share_memberid'];?>&id="+data.id;
	});
	//评论加载
	$("#tracereply_<?php echo $output['sharegoods_content']['share_id'];?>").load('index.php?w=member_snshome&t=commentlist&type=1&mid=<?php echo $output['sharegoods_content']['share_memberid'];?>&id=<?php echo $output['sharegoods_content']['share_id'];?>');


	// 追加
	$('a[wttype="add_share"]').click(function(){
	    eval( "data_str = "+$(this).attr('data-param'));
		ajax_form('add_share', '<?php echo $lang['sns_upload_treasure_buyer_show'];?>', SITEURL+'/index.php?w=member_snshome&t=add_share&type=refresh&sid='+data_str.sid+'&gid='+data_str.gid, 580);
	});
});
</script>