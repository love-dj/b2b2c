<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="group warp-all">
  <?php require_once bbs_template('group.top');?>
  <div class="base-box mt20">
    <div class="mainbox">
      <div class="base-tab-menu">
        <ul class="base-tab-nav">
          <li><a href="index.php?w=group&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_theme'];?></a></li>
          <li><a href="index.php?w=group&t=group_member&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['bbs_firend'];?></a></li>
          <li class="selected"><a href="index.php?w=group&t=group_goods&c_id=<?php echo $output['c_id'];?>"><?php echo $lang['wt_goods'];?></a></li>
        </ul>
      </div>
      <div class="group-share-goods">
        <?php if(!empty($output['goods_list'])){?>
        <ul class="share-goods-pinterest" id="groupPinterest">
          <?php foreach($output['goods_list'] as $val){?>
          <li class="item">
            <div class="share-goods-pic thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$val['snsgoods_goodsid']));?>" target="_blank" title="<?php echo $val['snsgoods_goodsname'];?>"><img alt="<?php echo $val['snsgoods_goodsname'];?>" title="<?php echo $val['snsgoods_goodsname'];?>" src="<?php echo cthumb($val['snsgoods_goodsimage'], 240, $val['snsgoods_storeid']);?>"></a></div>
            <dl class="share-describe">
              <dt class="member-avatar-s"><img src="<?php echo getMemberAvatarForID($val['share_memberid']);?>" /></dt>
              <dd class="member-name">
                <h4><a href="javascript:void(0);"><?php echo $val['share_membername'];?></a></h4>
                <h5 class="share-date"><?php echo $lang['wt_at'];?>
                  <?php if($val['share_isshare'] == 1){?>
                  <em><?php echo @date('Y-m-d H:i', $val['share_addtime']);?></em><?php echo $lang['wt_show'];?>
                  <?php }else{?>
                  <em><?php echo @date('Y-m-d H:i', $val['share_likeaddtime']);?></em><?php echo $lang['wt_like'];?>
                  <?php }?>
                </h5>
              </dd>
              <dd class="share-content"><i></i>
                <p>
                  <?php if($val['share_content'] != ''){echo $val['share_content'];}else{echo $lang['wt_share_default_content'];}?>
                </p>
                <?php if(isset($output['pic_list'][$val['share_id']])){?>
                <ul class="ap-pics">
                  <li class="case"></li>
                  <?php foreach($output['pic_list'][$val['share_id']] as $v){?>
                  <li><span class="thumb"><a href="JavaScript:void(0);"><img src="<?php echo showImgUrl($v);?>" class="t-img" /></a></span></li>
                  <?php }?>
                </ul>
                <?php }?>
                <div class="clear">&nbsp;</div>
              </dd>
            </dl>
            <div class="share-ops"> <span class="ops-like" id="likestat_<?php echo $val['share_goodsid'];?>" title="<?php echo $lang['wt_like'];?>"> <a href="javascript:void(0);" wt_type="likebtn" data-param='{"gid":"<?php echo $val['share_goodsid'];?>"}' class="<?php echo $val['snsgoods_havelike']==1?'noaction':''; ?>"><i class="<?php echo $val['snsgoods_havelike']==1?'noaction':''; ?>"></i><?php echo $lang['wt_like'];?></a> <em wt_type="likecount_<?php echo $val['share_goodsid'];?>"><?php echo $val['snsgoods_likenum'];?></em> </span> <span class="ops-comment" title="<?php echo $lang['wt_comment'];?>"><a href="<?php echo BASE_SITE_URL?>/index.php?w=member_snshome&t=goodsinfo&mid=<?php echo $val['share_memberid'];?>&id=<?php echo $val['share_id'];?>" target="_blank"><i></i></a><em><?php echo $val['share_commentcount'];?></em></span></div>
            <div class="clear"></div>
          </li>
          <?php }?>
        </ul>
        <div class="clear"></div>
        <div class="pagination"><?php echo $output['show_page'];?></div>
        <?php }else{?>
        <div class="no-goods"><span><i></i><?php echo $lang['wt_share_goods_null'];?></span></div>
        <?php }?>
      </div>
    </div>
    <?php require_once bbs_template('group.sidebar');?>
  </div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.masonry.js" type="text/javascript"></script> 
<script type="text/javascript">
$(function(){
	var $container = $('#groupPinterest');
		// initialize
		$container.masonry({
  		itemSelector: '.item'
		});
	var msnry = $container.data('masonry');

	//喜欢操作
	$("a[wt_type='likebtn']").live('click',function(){
		var obj = $(this);
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        //ajaxget(SITEURL+'/index.php?w=member_snsindex&t=editlike&inajax=1&id='+data_str.gid);
        ajaxget(BBS_SITE_URL+'/index.php?w=member_snsindex&t=editlike&inajax=1&id='+data_str.gid);
	});

//横高局中比例缩放隐藏显示图片
	$(".ap-pics .t-img").VMiddleImg({"width":30,"height":30});
	
});
</script> 
