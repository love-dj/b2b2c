<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_news_article_manage'];?></h3>
        <h5><?php echo $lang['wt_news_article_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" <?php if($menu['target']=='_blank') echo 'target="_blank"';?> ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['news_article_class_list_tip1'];?></li>
      <li><?php echo $lang['news_article_class_list_tip2'];?></li>
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
              <dt>标题</dt>
              <dd>
                <input type="text" name="article_title" class="s-input-txt" placeholder="请输入标题关键字" />
              </dd>
            </dl>
            <dl>
              <dt>作者</dt>
              <dd>
                <input type="text" name="article_publisher_name" class="s-input-txt" placeholder="请输入作者" />
              </dd>
            </dl>

            <dl>
              <dt>推荐文章</dt>
              <dd>
                <select name="article_commend_flag" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>

            <dl>
              <dt>推荐图文</dt>
              <dd>
                <select name="article_commend_image_flag" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>

            <dl>
              <dt>评论</dt>
              <dd>
                <select name="article_comment_flag" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">开启</option>
                    <option value="0">关闭</option>
                </select>
              </dd>
            </dl>

            <dl>
              <dt>心情</dt>
              <dd>
                <select name="article_attitude_flag" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">开启</option>
                    <option value="0">关闭</option>
                </select>
              </dd>
            </dl>

<?php if (!$output['currentState']) { ?>
            <dl>
              <dt>状态</dt>
              <dd>
                <select name="article_state" class="s-select">
                    <option value="">-请选择-</option>
                    <?php foreach ((array) $output['states'] as $k => $v) { ?>
                    <option value="<?php echo $k; ?>"><?php echo $v['text']; ?></option>
                    <?php } ?>
                </select>
              </dd>
            </dl>
<?php } ?>

          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="wtsubmit" class="wtap-btn wtap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="wtreset" class="wtap-btn wtap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['wt_cancel_search'];?></a>
        </div>
      </form>
    </div>

</div>
<div id="dialog_verify" style="display:none;">
  <form id="verify_form" method='post' action="index.php?w=news_article&t=news_article_verify">
    <input id="verify_article_id" name="article_id" type="hidden" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>审核通过</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label title="是" class="cb-enable selected" for="rewrite_enabled"><span>是</span></label>
            <label title="否" class="cb-disable" for="rewrite_disabled"><span>否</span></label>
            <input type="radio" value="1" checked="checked" name="verify_state" id="rewrite_enabled">
            <input type="radio" value="0" name="verify_state" id="rewrite_disabled">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" style="display: none;" wttype="reason">
        <dt class="tit">
          <label for="verify_reason">未通过理由</label>
        </dt>
        <dd class="opt">
          <textarea id="verify_reason" name="verify_reason" cols="60" class="tarea w250" rows="6"></textarea>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="btn_verify_submit" class="wtap-btn-big wtap-btn-green" href="javascript:void(0);"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){

    //审核
    $('input[name="verify_state"]').click(function() {
        if ($(this).val() == 1) {
            $('dl[wttype="reason"]').hide();
        } else {
            $('dl[wttype="reason"]').show();
        }
    });
    $('#btn_verify_submit').on('click', function() {
        $('#verify_form').submit();
    });

    var flexUrl = 'index.php?w=news_article&t=news_article_list_xml&article_state=<?php echo $output['currentState']; ?>';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '排序', name: 'article_sort', width: 60, sortable: false, align: 'left'},
            {display: '标题', name: 'article_title', width: 300, sortable: false, align: 'left'},
            {display: '图片', name: 'img', width: 40, sortable: false, align: 'left'},
            {display: '作者', name: 'article_publisher_name', width: 80, sortable: false, align: 'left'},
            {display: '点击数', name: 'article_click', width: 60, sortable: false, align: 'left'},
            {display: '推荐文章', name: 'article_commend_flag', width: 50, sortable: false, align: 'center'},
            {display: '推荐图文', name: 'article_commend_image_flag', width: 50, sortable: false, align: 'center'},
            {display: '评论', name: 'article_comment_flag', width: 50, sortable: false, align: 'center'},
            {display: '心情', name: 'article_attitude_flag', width: 50, sortable: false, align: 'center'},
            {display: '状态', name: 'article_state', width: 50, sortable: false, align: 'center'}
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
                    location.href = 'index.php?w=news_article&t=news_article_drop&article_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ],
        searchitems: [
            {display: '标题', name: 'article_title', isdefault: true},
            {display: '作者', name: 'article_publisher_name'}
        ],
        sortname: "article_id",
        sortorder: "desc",
        title: '文章列表'
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

});

$("a[data-j='drop']").live('click', function() {
    if (!confirm('确定删除?')) {
        return false;
    }
    var id = $(this).parents('tr[data-id]').attr('data-id');
    location.href = 'index.php?w=news_article&t=news_article_drop&article_id='+id;
});

$("a[data-j='audit']").live('click', function() {
    var id = $(this).parents('tr[data-id]').attr('data-id');
    $('#verify_article_id').val(id);
    $('#dialog_verify').wt_show_dialog({title:'审核'});
});

$("a[data-j='callback']").live('click', function() {
    if (!confirm('确定收回?')) {
        return false;
    }
    var id = $(this).parents('tr[data-id]').attr('data-id');
    location.href = 'index.php?w=news_article&t=news_article_callback&article_id=' + id;
});

$("a[data-j='article_commend_flag'],a[data-j='article_commend_image_flag'],a[data-j='article_comment_flag'],a[data-j='article_attitude_flag']").live('click', function() {
    var column = $(this).attr('data-j');
    var value = $(this).attr('data-val');
    var id = $(this).parents('tr[data-id]').attr('data-id');
    $.get('index.php?w=news_article&t=ajax', {
        column: column,
        id: id,
        value: value
    }, function(d) {
        if (d == 'true') {
            $("#flexigrid").flexReload();
        } else {
            alert('操作失败！');
        }
    });
});

$("span[data-live-inline-edit]").live('click', function() {
    var $this = $(this);
    var column = $this.attr('data-live-inline-edit');
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.getJSON('index.php?w=news_article&t=update_' + column, {
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
        });
    });
});

</script>
