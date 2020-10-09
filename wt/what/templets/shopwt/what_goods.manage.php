<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_what_goods_manage'];?></h3>
        <h5><?php echo $lang['wt_what_goods_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['what_goods_tip1'];?></li>
      <li><?php echo $lang['what_goods_tip2'];?></li>
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
              <dt>编号</dt>
              <dd>
                <input type="text" name="commend_id" class="s-input-txt" placeholder="请输入编号" />
              </dd>
            </dl>
            <dl>
              <dt>用户</dt>
              <dd>
                <input type="text" name="member_name" class="s-input-txt" placeholder="请输入用户" />
              </dd>
            </dl>
            <dl>
              <dt>商品名称</dt>
              <dd>
                <input type="text" name="commend_goods_name" class="s-input-txt" placeholder="请输入商品名称关键词" />
              </dd>
            </dl>
            <dl>
              <dt>推荐</dt>
              <dd>
                <select name="what_commend" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>推荐时间</dt>
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
    var flexUrl = 'index.php?w=goods&t=goods_manage_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '编号', name: 'commend_id', width: 60, sortable: false, align: 'center'},
            {display: '排序', name: 'what_sort', width: 60, sortable: false, align: 'left'},
            {display: '推荐', name: 'commend_state', width: 60, sortable: false, align: 'center'},
            {display: '用户', name: 'member_name', width: 100, sortable: false, align: 'center'},
            {display: '商品图片', name: 'commend_goods_image', width: 60, sortable: false, align: 'center'},
            {display: '商品名称', name: 'commend_goods_name', width: 350, sortable: false, align: 'left'},
            {display: '推荐说明', name: 'commend_message', width: 350, sortable: false, align: 'left'},
            {display: '推荐时间', name: 'commend_time_text', width: 80, sortable: false, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?w=goods&t=goods_drop&commend_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ],
        searchitems: [
            {display: '编号', name: 'commend_id', isdefault: true},
            {display: '用户', name: 'member_name'},
            {display: '商品名称', name: 'commend_goods_name'}
        ],
        sortname: "commend_id",
        sortorder: "desc",
        title: '说说看列表'
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

$('a.confirm-del-on-click').live('click', function() {
    return confirm('确定删除?');
});

$('a[data-ie-column]').live('click', function() {
    $.get('index.php?w=goods&t=ajax&branch=goods_commend', {
        column: $(this).attr('data-ie-column'),
        value: $(this).attr('data-ie-value'),
        id: $(this).parents('tr').attr('data-id')
    }, function(d) {
        if (d != 'true') {
            alert('操作失败！');
            return false;
        }
        $("#flexigrid").flexReload();
    });
});

$("span[data-live-inline-edit='what_sort']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.getJSON('index.php?w=goods&t=goods_sort_update', {
            id: $this.parents('tr').attr('data-id'),
            value: v2
        }, function(d) {
            if (d.result) {
                $this.html(v2);
            } else {
                alert(d.message);
            }
            $input.remove();
            $this.show();
            // $("#flexigrid").flexReload();
        });
    });
});

</script>
