<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台优惠券</h3>
        <h5>平台优惠券新增与管理</h5>
      </div>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>优惠券模板失效后,用户将不能领取,但是已经领取的优惠券仍然可以使用</li>
      <li>已兑换优惠券后则相应优惠券模板不可删除</li>
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
              <dt>优惠券名称</dt>
              <dd>
                <input type="text" name="rpt_title" class="s-input-txt" placeholder="请输入优惠券名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>最近修改时间</dt>
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
              <dt>领取方式</dt>
              <dd>
                <select name="rpt_gettype" class="s-select">
                    <option value="0" selected>全部</option>
                    <?php if ($output['gettype_arr']){ ?>
                    <?php foreach ($output['gettype_arr'] as $k=>$v){ ?>
                    <option value="<?php echo $v['sign'];?>"><?php echo $v['name'];?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="rpt_state" class="s-select">
                    <option value="0" selected>全部</option>
                    <?php if ($output['templateState']){ ?>
                    <?php foreach ($output['templateState'] as $k=>$v){ ?>
                    <option value="<?php echo $v['sign'];?>"><?php echo $v['name'];?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>推荐</dt>
              <dd>
                <select name="rpt_recommend" class="s-select">
                    <option value="" selected>全部</option>
                    <option value="1" >是</option>
                    <option value="0" >否</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>有效期时期筛选</dt>
              <dd>
                <label>
                    <input type="text" name="pdate1" data-dp="1" class="s-input-txt" placeholder="结束时间不晚于" />
                </label>
                <label>
                    <input type="text" name="pdate2" data-dp="1" class="s-input-txt" placeholder="开始时间不早于" />
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
$(function(){
    var flexUrl = 'index.php?w=coupon&t=rptlist_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '优惠券名称', name: 'coupon_t_title', width: 200, sortable: false, align: 'left'},
            {display: '面额(<?php echo $lang['currency_zh'];?>)', name: 'coupon_t_price', width: 80, sortable: true, align: 'left'},
            {display: '消费限额(<?php echo $lang['currency_zh'];?>)', name: 'coupon_t_limit', width: 80, sortable: true, align: 'left'},
            {display: '会员级别', name: 'coupon_t_mgradelimittext', width: 80, sortable: true, align: 'center'},
            {display: '最近修改时间', name: 'coupon_t_updatetimetext', width: 120, sortable: true, align: 'center'},
            {display: '开始时间', name: 'coupon_t_start_datetext', width: 120, sortable: true, align: 'center'},
            {display: '结束时间', name: 'coupon_t_end_datetext', width: 120, sortable: true, align: 'center'},
            {display: '领取方式', name: 'coupon_t_gettype_text', width: 80, sortable: false, align: 'center'},
            {display: '状态', name: 'coupon_t_statetext', width: 80, sortable: false, align: 'center'},
            {display: '推荐', name: 'coupon_t_recommend', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '优惠券名称', name: 'rpt_title', isdefault: true}
        ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operate }
        ],
        sortname: "coupon_t_id",
        sortorder: "desc",
        title: '优惠券模板列表'
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

function fg_operate(name, bDiv) {
	if (name == 'add') {
		window.location.href = 'index.php?w=coupon&t=rptadd';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除吗？')){
        $.getJSON('index.php?w=coupon&t=rptdel', {tid:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}
</script>
