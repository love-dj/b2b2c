<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script src="<?php echo FENXIAO_STATIC_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">
body { _behavior: url(<?php echo FENXIAO_TEMPLATES_URL;
?>/css/csshover.htc);
}
#main-nav .pagination{ display:none}
#more_select_nav { font-size: 12px; text-align: right; line-height: 30px; height: 30px; margin-top: -11px; margin-bottom: 10px;}
#more_select_nav a{ color: #333;background-color: #EEE;display: inline-block; line-height: 30px; height: 30px; width: 100px; text-align: center;border-bottom: solid 1px #D7D7D7;border-left: solid 1px #D7D7D7;border-right: solid 1px #D7D7D7; }
</style>

 <div id="nav-search" class="wrapper wth-breadcrumb-box">
  <?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
      <?php foreach($output['nav_link_list'] as $nav_link){?>
          <?php if(!empty($nav_link['link'])){?>
		  <?php 
					$model_goods_class = Model('goods_class');
					$nav_info = $model_goods_class->getGoodsClassList(array('gc_id'=>$nav_link['id']));
					$where = array();
					$where['gc_id'] = array('not in',$nav_link['id']);
					$where['gc_parent_id'] = $nav_info[0]['gc_parent_id'];
					$nav_list = $model_goods_class->getGoodsClassList($where);
		  ?>
    <div class="wth-breadcrumb">
     <span class="sort-box"> <a href="<?php echo $nav_link['link'];?>" class="current"><?php echo $nav_link['title'];?><i class="drop-arrow"></i></a>
     <?php if(!empty($nav_list)){?>
      <div class="sort-sub">
        <ul>
         <?php foreach($nav_list as $key => $val){?>
         <li><a href="<?php echo FENXIAO_SITE_URL?>/index.php?w=search&cate_id=<?php echo $val['gc_id'];?><?php if(isset($_GET['keyword'])&&trim($_GET['keyword'])!=''){echo '&keyword='.$_GET['keyword'];}?>"><?php echo $val['gc_name']?></a></li>
         <?php }?> 
        </ul>
      </div>
      <?php }?>  
       </span><span class="arrow">&gt;</span>
       </div>
        <?php }?>
    <?php }?>
     <?php }?>
	 <?php if((isset($output['checked_brand']) && is_array($output['checked_brand'])) || (isset($output['checked_attr']) && is_array($output['checked_attr']))){?>
	 <div class="select-undo"><a href="<?php echo dropParam(array('a_id', 'b_id'));?>" class="delAll">全部撤销</a></div>
	 <div class="selected-box">
		<?php if(isset($output['checked_brand']) && is_array($output['checked_brand'])){?>
		<?php foreach ($output['checked_brand'] as $key=>$val){?>
		<a class="selected"  href="<?php echo removeParam(array('b_id' => $key));?>"><?php echo $lang['goods_class_index_brand'];?>: <em><?php echo $val['brand_name']?></em><i></i></a>
		<?php }?>
		<?php }?>

		 <?php if(isset($output['checked_attr']) && is_array($output['checked_attr'])){?>
		 <?php foreach ($output['checked_attr'] as $val){?>
		 <a class="selected"  href="<?php echo removeParam(array('a_id' => $val['attr_value_id']));?>"><?php echo $val['attr_name'].': <em>'.$val['attr_value_name'].'</em>'?><i></i></a>
		 <?php }?>
		 <?php }?>
		 </div>
	 <?php }?>	 
    <div class="clear"></div>
  </div>




<div class="wth-container wrapper" >
    <!-- 分类下的推荐商品 -->
    <div id="gc_goods_recommend_div"></div>
    <?php $dl=1; $num_dl = 1; //dl标记?>
    <?php if((!empty($output['brand_array']) && is_array($output['brand_array'])) || (!empty($output['attr_array']) && is_array($output['attr_array']))){?>
    <div class="wth-module wth-module-style01">
      <div class="title">
        <h3>
          <?php if (!empty($output['show_keyword'])) {?>
          <em><?php echo $output['show_keyword'];?></em> -
          <?php }?>
          商品筛选</h3>
      </div>
      <div class="content">
        <div class="wth-module-filter">
          <?php if((isset($output['checked_brand']) && is_array($output['checked_brand'])) || (isset($output['checked_attr']) && is_array($output['checked_attr']))){?>
          <dl wt_type="ul_filter">
            <dt><?php echo $lang['goods_class_index_selected'].$lang['wt_colon'];?></dt>
            <dd class="list">
              <?php if(isset($output['checked_brand']) && is_array($output['checked_brand'])){?>
              <?php foreach ($output['checked_brand'] as $key=>$val){?>
              <span class="selected" wttype="span_filter"><?php echo $lang['goods_class_index_brand'];?>:<em><?php echo $val['brand_name']?></em><i data-uri="<?php echo removeParam(array('b_id' => $key));?>">X</i></span>
              <?php }?>
              <?php }?>
              <?php if(isset($output['checked_attr']) && is_array($output['checked_attr'])){?>
              <?php foreach ($output['checked_attr'] as $val){?>
              <span class="selected" wttype="span_filter"><?php echo $val['attr_name'].':<em>'.$val['attr_value_name'].'</em>'?><i data-uri="<?php echo removeParam(array('a_id' => $val['attr_value_id']));?>">X</i></span>
              <?php }?>
              <?php }?>
            </dd>
          </dl>
          <?php }?>
          <?php if (!isset($output['checked_brand']) || empty($output['checked_brand'])){?>
          <?php if(!empty($output['brand_array']) && is_array($output['brand_array'])){?>
          <dl>
            <dt><?php echo $lang['goods_class_index_brand'].$lang['wt_colon'];?></dt>
            <dd class="list">
              <ul class="wth-brand-tab" wttype="ul_initial" style="display:none;">
                <li data-initial="all"><a href="javascript:void(0);">所有品牌<i class="arrow"></i></a></li>
                <?php if (!empty($output['initial_array'])) {?>
                <?php foreach ($output['initial_array'] as $val) {?>
                <li data-initial="<?php echo $val;?>"><a href="javascript:void(0);"><?php echo $val;?><i class="arrow"></i></a></li>
                <?php }?>
                <?php }?>
              </ul>
              <div id="wtBrandlist">
                <ul class="wth-brand-con" wttype="ul_brand">
                  <?php $i = 0;foreach ($output['brand_array'] as $k=>$v){$i++;?>
                  <li data-initial="<?php echo $v['brand_initial']?>" <?php if ($i > 14) {?>style="display:none;"<?php }?>><a href="<?php $b_id = (($_GET['b_id'] != '' && intval($_GET['b_id']) != 0)?$_GET['b_id'].'_'.$k:$k); echo replaceParam(array('b_id' => $b_id));?>">
                    <?php if ($v['show_type'] == 0) {?>
                    <img src="<?php echo brandImage($v['brand_pic']);?>" alt="<?php echo $v['brand_name'];?>" /> <span>
                    <?php  echo $v['brand_name'];?>
                    </span>
                    <?php } else { echo $v['brand_name'];?>
                    <?php }?>
                    </a></li>
                  <?php }?>
                </ul>
              </div>
            </dd>
            <?php if (count($output['brand_array']) > 16){?>
            <dd class="all"><span wttype="brand_show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $dl++;$num_dl++;}?>
          <?php }?>
          <?php if(!empty($output['attr_array']) && is_array($output['attr_array'])){?>
          <?php $j = 0;foreach ($output['attr_array'] as $key=>$val){$j++;?>
          <?php if(!isset($output['checked_attr'][$key]) && !empty($val['value']) && is_array($val['value'])){?>
          <dl <?php if($num_dl > 3){?>class="hide_dl" style="display: none;"<?php }?>>
            <dt><?php echo $val['name'].$lang['wt_colon'];?></dt>
            <dd class="list">
              <ul>
                <?php $i = 0;foreach ($val['value'] as $k=>$v){$i++;?>
                <li <?php if ($i>10){?>style="display:none" wt_type="none"<?php }?>><a href="<?php $a_id = (($_GET['a_id'] != '' && $_GET['a_id'] != 0)?$_GET['a_id'].'_'.$k:$k); echo replaceParam(array('a_id' => $a_id));?>"><?php echo $v['attr_value_name'];?></a></li>
                <?php }?>
              </ul>
            </dd>
            <?php if (count($val['value']) > 10){?>
            <dd class="all"><span wt_type="show"><i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?></span></dd>
            <?php }?>
          </dl>
          <?php $num_dl++;}?>
          <?php $dl++;} ?>
          <?php }?>
        </div>
      </div>
    </div>
    <?php if($num_dl > 4){?>
      <div id="more_select_nav"><a href="javascript:void(0);" class="down"><span>更多选项&nbsp;</span><i class="icon-angle-down"></i></a></div>
    <?php }?>
    <?php }?>
     <div class="shop_con_list" id="main-list-bar">
      <nav class="sort-bar" id="main-nav">
        <div class="wth-category-nav">
          <div class="category-nav">
            <?php require template('layout/home_goods_class');?>
          </div>
        </div>
        <div class="wth-sortbar-array">
          <ul class="screen">
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>"  title="<?php echo $lang['goods_class_index_default_sort'];?>"><?php echo $lang['goods_class_index_default_sort'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')?$lang['goods_class_index_sold_asc']:$lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold'];?><i></i></a></li>
            </li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == '2' && $_GET['key'] == '2')?$lang['goods_class_index_click_asc']:$lang['goods_class_index_click_desc']; ?>"><?php echo $lang['goods_class_index_click']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3')?$lang['goods_class_index_price_asc']:$lang['goods_class_index_price_desc']; ?>"><?php echo $lang['goods_class_index_price'];?><i></i></a></li>
          </ul>
        </div>
        <form class="d_index_filtrate fr" id="">
        	<input id="button" value="搜索" class="fr" type="button">          
          <dd id="fx_gcategory" style="display:inline-block;float:left;">
            <input type="hidden" id="fx_goods_class" value="<?php echo intval($_GET['cate_id']);?>">
          	<select name="">
              <option value="0">请选择</option>
              <?php if(!empty($output['f_gc_list']) && is_array($output['f_gc_list']) ) {?>
              <?php foreach ($output['f_gc_list'] as $gc) {?>
              <option value="<?php echo $gc['gc_id'];?>" <?php if($output['s_parent_id'] == $gc['gc_id'] || $gc['gc_id'] == $_GET['cate_id']){?>selected="selected" <?php }?>><?php echo $gc['gc_name'];?></option>
              <?php }?>
              <?php }?>
            </select>
            <?php if(!empty($output['s_gc_list']) && is_array($output['s_gc_list']) ) {?>
              <select class="class-select">
              <option value="0">请选择</option>
              <?php foreach ($output['s_gc_list'] as $gc) {?>
              <option value="<?php echo $gc['gc_id'];?>" <?php if($output['t_parent_id'] == $gc['gc_id'] || $gc['gc_id'] == $_GET['cate_id']){?>selected="selected" <?php }?>><?php echo $gc['gc_name'];?></option>
              <?php }?>
            </select>
            <?php }?>
            <?php if(!empty($output['t_gc_list']) && is_array($output['t_gc_list']) ) {?>
              <select class="class-select">
              <option value="0">请选择</option>
              <?php foreach ($output['t_gc_list'] as $gc) {?>
              <option value="<?php echo $gc['gc_id'];?>" <?php if($gc['gc_id'] == $_GET['cate_id']){?>selected="selected" <?php }?>><?php echo $gc['gc_name'];?></option>
              <?php }?>
            </select>
            <?php }?> 
          </dd>
        </form>
      </nav>
      <!-- 商品列表循环  -->
      <div>
        <?php require_once (BASE_TPL_PATH.'/home/goods.squares.php');?>
      </div>
     <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
  <div class="clear"></div>
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->

<script type="text/javascript">
var defaultSmallGoodsImage = '<?php echo defaultGoodsImage(240);?>';
var defaultTinyGoodsImage = '<?php echo defaultGoodsImage(60);?>';

$(function(){
  //gcategoryDisTriInit("fx_gcategory");
	//品牌索引过长滚条
	$('#wtBrandlist').perfectScrollbar({suppressScrollX:true});
    //浮动导航  waypoints.js
    $('#main-list-bar').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
	// 单行显示更多
	$('span[wt_type="show"]').click(function(){
		s = $(this).parents('dd').prev().find('li[wt_type="none"]');
		if(s.css('display') == 'none'){
			s.show();
			$(this).html('<i class="icon-angle-up"></i><?php echo $lang['goods_class_index_retract'];?>');
		}else{
			s.hide();
			$(this).html('<i class="icon-angle-down"></i><?php echo $lang['goods_class_index_more'];?>');
		}
	});

  $('form.d_index_filtrate #button').click(function(){
    var cate_id_val = $('#fx_goods_class').val();
    var url = window.location.search;
    var params  = url.substr(1).split('&');
    var fx_cate_id = false;
    for(var j=0; j < params.length; j++)
    {
        var param = params[j];
        var arr   = param.split('=');
        if(arr[0] == 'cate_id')
        {
          params[j] = "cate_id="+cate_id_val;
          fx_cate_id = true;
        }
    }
    if(!fx_cate_id){
      params[params.length] = "cate_id="+cate_id_val;
    }
    window.location.href="<?php echo FENXIAO_SITE_URL?>/index.php?"+params.join('&');
  });
  $('#fx_gcategory select').live('change',function(){
    disgcategoryChange(this);
  });

  //获取更多
  $('#more_select_nav a').click(function(){
    var attr = $(this).attr('class');
    if(attr == 'down'){
      $(this).attr('class','up');
      $(this).find('i').removeClass('icon-angle-down').addClass('icon-angle-up');
      $(this).find('span').html('收起选项&nbsp;');
      $('.wth-module-filter .hide_dl').show();
    }else{
      $(this).attr('class','down');
      $(this).find('i').removeClass('icon-angle-up').addClass('icon-angle-down');
      $(this).find('span').html('更多选项&nbsp;');
      $('.wth-module-filter .hide_dl').hide();
    }
  });
	
});

function disgcategoryChange(obj){
  // 删除后面的select
    $(obj).nextAll("select").remove();

    // 计算当前选中到id和拼起来的name
    var selects = $(obj).siblings("select").andSelf();
    var id = 0;
    var names = new Array();
    for (i = 0; i < selects.length; i++)
    {
        sel = selects[i];
        if (sel.value > 0)
        {
            id = sel.value;
            name = sel.options[sel.selectedIndex].text;
            names.push(name);
        }
    }
    $(obj).parent().find(".mls_id").val(id);
    $(obj).parent().find(".mls_name").val(name);
    $(obj).parent().find(".mls_names").val(names.join("\t"));
    $(obj).parent().find("#fx_goods_class").val(id);

    // ajax请求下级分类
    if (obj.value > 0)
    {
        var _self = obj;
        var url = SITEURL + '/index.php?w=index&t=josn_class&callback=?';
        $.getJSON(url, {'gc_id':obj.value}, function(data){
            if (data)
            {
                if (data.length > 0)
                {
                    $("<select class='class-select'><option value=''>-请选择-</option></select>").change(disgcategoryChange(this)).insertAfter(_self);
                    var data  = data;
                    for (i = 0; i < data.length; i++)
                    {
                        $(_self).next("select").append("<option data-explain='" + data[i].commis_rate + "' value='" + data[i].gc_id + "'>" + data[i].gc_name + "</option>");
                    }
                }
                else
                {
                  $(_self).attr('end','1');
                }
            }
        });
    }
}

</script> 
