<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>消费者保障服务</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
        <ul class="tab-base wt-row">
            <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
                <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
            <?php }  else { ?>
                <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
            <?php  } }  ?>
            <li><a href="<?php echo urlAdminShop('operating', 'setting');?>">设置</a></li>
        </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
        <li>列表为店铺申请加入各项消费者保障服务记录</li>
        <li>当店铺提出的申请记录状态为“等待审核”、“保证金待审核”的时候，可以编辑申请；否则只能查看申请详情。</li>
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
            <input type="hidden" name="showanced" value="1" />
            <div id="searchCon" class="content">
                <div class="layout-box">
                    <dl>
                        <dt>店铺名称</dt>
                        <dd>
                            <input type="text" name="search_storename" class="s-input-txt" placeholder="请输入店铺名称关键字" />
                        </dd>
                    </dl>
                    <dl>
                        <dt>保障服务</dt>
                        <dd>
                            <select name="search_itemid" class="s-select">
                                <option value="0" selected>全部</option>
                                <?php if ($output['item_list']){ ?>
                                    <?php foreach ($output['item_list'] as $k=>$v){ ?>
                                        <option value="<?php echo $v['cti_id'];?>"><?php echo $v['cti_name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                        <dt>添加时间</dt>
                        <dd>
                            <label>
                                <input type="text" name="sdate" data-dp="1" class="s-input-txt" placeholder="请选择筛选时间段起点" />
                            </label>
                            <label>
                                <input type="text" name="edate" data-dp="1" class="s-input-txt" placeholder="请选择筛选时间段终点" />
                            </label>
                        </dd>
                    </dl>
                    <dl>
                        <dt>审核状态</dt>
                        <dd>
                            <select name="search_state" class="s-select">
                                <option value="" selected>全部</option>
                                <?php if ($output['join_auditstate_arr']){ ?>
                                    <?php foreach ($output['join_auditstate_arr'] as $k=>$v){ ?>
                                        <option value="<?php echo $k;?>"><?php echo $v['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bottom">
                <a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green">提交查询</a>
                <a href="javascript:void(0);" id="wtreset" class="wtap-btn wtap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['wt_cancel_search'];?></a>
            </div>
        </form>
    </div>
</div>

<script>
$(function(){
    var flexUrl = 'index.php?w=contract&t=applylist_xml';
    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 80, sortable: false, align: 'center', className: 'handle'},
            {display: '店铺名称', name: 'cta_storename', width: 200, sortable: false, align: 'left'},
            {display: '保障服务', name: 'itemname', width: 300, sortable: false, align: 'left'},
            {display: '添加时间', name: 'cta_addtime', width: 120, sortable: false, align: 'center'},
            {display: '状态', name: 'cta_auditstate_text', width: 150, sortable: true, align: 'center'}
        ],
        searchitems: [
            {display: '店铺名称', name: 'cta_storename'}
        ],
        sortname: "cta_id",
        sortorder: "desc",
        title: '加入申请列表'
    });

    // 高级搜索提交
    $('#wtsubmit').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#wtreset').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
        $("#formSearch")[0].reset();
    });

    $('[data-dp]').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>