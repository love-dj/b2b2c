<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品管理</h3>
        <h5>商城所有分销商品索引及管理</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>商品终止分销后将移出商家及分销员的分销商品列表，商家可在商家中心重新分销该商品</li>
      <li>终止分销后通过原分销链接进行购买产生的订单按普通订单处理，不记为分销订单也不产生分佣；终止分销前产生的订单按原定的佣金和结算时间进行结算</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <div class="wtap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="wtap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>商品名称</dt>
            <dd>
              <label>
                <input type="text" value="" name="goods_name" id="goods_name" class="s-input-txt" placeholder="输入商品全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>SPU</dt>
            <dd>
              <label>
                <input type="text" value="" name="goods_commonid" id="goods_commonid" class="s-input-txt" placeholder="输入商品平台货号">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>所属店铺</dt>
            <dd>
              <label>
                <input type="text" value="" name="store_name" id="store_name" class="s-input-txt" placeholder="输入商品所属店铺名称">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>所属品牌</dt>
            <dd>
              <label>
                <input type="text" value="" name="brand_name" id="brand_name" class="s-input-txt" placeholder="输入商品关联品牌关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>商品分类</dt>
            <dd id="gcategory">
              <input type="hidden" id="cate_id" name="cate_id" value="" class="mls_id" />
              <select class="class-select">
                <option value="0"><?php echo $lang['wt_please_choose'];?></option>
                <?php if(!empty($output['gc_list'])){ ?>
                <?php foreach($output['gc_list'] as $k => $v){ ?>
                <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </dd>
          </dl>
          <dl>
            <dt>商品状态</dt>
            <dd>
              <label>
                <select name="goods_state" class="s-select">
                  <option value=""><?php echo $lang['wt_please_choose'];?></option>
                  <option value="1">出售中</option>
                  <option value="0">仓库中</option>
                  <option value="10">违规下架</option>
                </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>审核状态</dt>
            <dd>
              <label>
                <select name="goods_verify" class="s-select">
                  <option value=""><?php echo $lang['wt_please_choose'];?></option>
                  <option value="1">通过</option>
                  <option value="0">未通过</option>
                  <option value="10">审核中</option>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="wtreset" class="wtap-btn wtap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['wt_cancel_search'];?></a></div>
    </form>
  </div>
  <script src="<?php echo STATIC_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
  <script type="text/javascript">
    gcategoryInit('gcategory');
    </script>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?w=fx_goods&t=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center'},
            {display: 'SPU', name : 'goods_commonid', width : 60, sortable : true, align: 'center'},
            {display: '商品名称', name : 'goods_name', width : 150, sortable : false, align: 'left'},
            {display: '商品价格(元)', name : 'goods_price', width : 100, sortable : true, align: 'center'},
            {display: '商品状态', name : 'goods_state', width : 60, sortable : true, align: 'center'},
            {display: '审核状态', name : 'goods_verify', width : 60, sortable : false, align: 'center'},
            {display: '商品图片', name : 'goods_image', width : 60, sortable : true, align: 'center'},
            {display: '分类名称', name : 'gc_name', width : 180, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 80, sortable : true, align: 'left'},
            {display: '店铺类型', name : 'is_own_shop', width : 80, sortable : true, align: 'center'},
            {display: '品牌名称', name : 'brand_name', width : 80, sortable : true, align: 'left'},
            {display: '分销发布时间', name : 'fx_add_time', width : 100, sortable : true, align: 'center'},
            {display: '分销佣金比例', name : 'fx_commis_rate', width : 80, sortable : true, align: 'center'},
            {display: '销量', name : 'sale_count', width : 80, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }
            ],
        searchitems : [
            {display: 'SPU', name : 'goods_commonid'},
            {display: '商品名称', name : 'goods_name'},
            {display: '店铺名称', name : 'store_name'},
            {display: '品牌名称', name : 'brand_name'}
            ],
        sortname: "goods_commonid",
        sortorder: "desc",
        title: '商品列表'
    });


    // 高级搜索提交
    $('#wtsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?w=fx_goods&t=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#wtreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?w=fx_goods&t=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
});

function fg_operation(name, bDiv) {
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&t=export_csv&type=<?php echo $output['type'];?>&id=' + id;
}


//商品取消分销
function fg_del(id) {
    if(confirm('终止分销后将不能恢复且所有分销员对该商品的分销都将失效，确认终止分销这项吗？')){
        $.getJSON('index.php?w=fx_goods&t=del_fx', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}
</script> 
