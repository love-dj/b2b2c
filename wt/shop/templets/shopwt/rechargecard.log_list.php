<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台充值卡</h3>
        <h5>商城充值卡设置生成及用户充值使用明细</h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="<?php echo urlAdminShop('rechargecard', 'index'); ?>">列表</a></li>
        <li><a href="javascript:void(0);" class="current">明细</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>此处展示了会员的充值卡使用明细</li>
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
              <dt>会员名称</dt>
              <dd>
                <input type="text" name="member_name" class="s-input-txt" placeholder="请输入会员名称" />
              </dd>
            </dl>
            <dl>
              <dt>变更时间</dt>
              <dd>
                <label>
                    <input type="text" name="sdate" data-dp="1" class="s-input-txt" placeholder="请输入起始时间" />
                </label>
                <label>
                    <input type="text" name="edate" data-dp="1" class="s-input-txt" placeholder="请输入终止时间" />
                </label>
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
$(function() {
    var flexUrl = 'index.php?w=rechargecard&t=log_list_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '会员名称', name: 'member_name', width: 120, sortable: false, align: 'left'},
            {display: '变更时间', name: 'add_time', width: 150, sortable: 1, align: 'left'},
            {display: '可用金额(元)', name: 'available_amount', width: 90, sortable: false, align: 'left'},
            {display: '冻结金额(元)', name: 'freeze_amount', width: 90, sortable: false, align: 'left'},
            {display: '描述', name: 'description', width: 400, sortable: false, align: 'left'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-file-excel-o"></i>导出数据',
                name: 'csv',
                bclass: 'csv',
                title: '将选定行数据导出Excel文件',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length == 0 && !confirm('您确定要下载本次搜索的全部数据吗？')) {
                        return false;
                    }
                    var qs = $("#flexigrid").flexSimpleSearchQueryString();
                    location.href = qs+'&w=rechargecard&t=log_export_step1&ids=' + ids.join(',');
                }
            }
        ],
        searchitems: [
            {display: '会员名称', name: 'member_name', isdefault: true}
        ],
        sortname: "id",
        sortorder: "desc",
        title: '平台充值卡使用明细列表'
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

    $("input[data-dp='1']").datepicker({dateFormat: 'yy-mm-dd'});

});
</script>
