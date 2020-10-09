<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_news_article_class'];?></h3>
        <h5><?php echo $lang['wt_news_article_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['news_article_class_list_tip1'];?></li>
    </ul>
  </div>
  <form id="list_form" method='post'>
    <input id="class_id" name="class_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s"><?php echo $lang['wt_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['wt_sort'];?></th>
          <th width="300"><?php echo $lang['class_name'];?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['class_id']; ?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a href="index.php?w=news_article_class&t=news_article_class_drop&class_id=<?php echo $val['class_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a></td>
          <td class="sort"><span wt_type="class_sort" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable "><?php echo $val['class_sort'];?></span></td>
          <td class="name"><span wt_type="class_name" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable "><?php echo $val['class_name'];?></span></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['wt_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '文章分类列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation },
				   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress :  function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?w=news_article_class&t=news_article_class_drop&class_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
                }
               ]
		});

    //行内ajax编辑
    $('span[wt_type="class_sort"]').inline_edit({w: 'news_article_class',t: 'update_class_sort'});
    $('span[wt_type="class_name"]').inline_edit({w: 'news_article_class',t: 'update_class_name'});

    $('a.confirm-del').live('click', function() {
        if (!confirm('确定删除？')) {
            return false;
        }
    });

});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=news_article_class&t=news_article_class_add';
    }
}
</script>