<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo FENXIAO_STATIC_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script src="<?php echo FENXIAO_STATIC_SITE_URL.'/js/search_goods.js';?>"></script>
<link href="<?php echo FENXIAO_TEMPLATES_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<style type="text/css">
body { _behavior: url(<?php echo FENXIAO_TEMPLATES_URL;
?>/css/csshover.htc);
}
#main-nav .pagination{ display:none}
</style>
<div class="i-slides-con">
<?php echo $output['web_html']['index'];?>
</div>
<!--分销推荐-->
<div class="wrapper mt30">
			<div class="wth-container wrapper" >
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
		
</div>
<script src="<?php echo STATIC_SITE_URL;?>/js/waypoints.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/jquery.fly.min.js" charset="utf-8"></script> 
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.masonry.js" type="text/javascript"></script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.infinitescroll.js" type="text/javascript"></script>
<script>
$(function(){	
	//浮动导航  waypoints.js
    $('#main-list-bar').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
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
    window.location.href="<?php echo FENXIAO_SITE_URL?>/index.php?w=search"+params.join('&');
  });
  $('#fx_gcategory select').live('change',function(){
    disgcategoryChange(this);
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
<!--分销推荐 end-->