<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_what_goods_class'];?></h3>
        <h5><?php echo $lang['wt_what_goods_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['what_goods_class_tip1'];?></li>
      <li><?php echo $lang['what_goods_class_tip2'];?></li>
      <li><?php echo $lang['what_goods_class_tip3'];?></li>
      <li><?php echo $lang['what_goods_class_tip4'];?></li>
      <li><?php echo $lang['what_goods_class_tip5'];?></li>
    </ul>
  </div>
  <form id="list_form" method='post'>
    <input id="class_id" name="class_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['wt_handle'];?></th>
          <th width="60" align="center"><?php echo $lang['wt_sort'];?></th>
          <th width="350"><?php echo $lang['what_class_name'];?></th>
          <th width="80">分类图片</th>
          <th width="100" align="center"><?php echo $lang['what_commend'];?></th>
          <th width="80">默认</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <?php if(empty($val['class_parent_id'])) { ?>
        <tr data-id="<?php echo $val['class_id']; ?>">
          <td class="sign"><img class="class_parent" class_id="<?php echo 'class_id'.$val['class_id'];?>" status="open" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif"></td>
          <td class="handle">
          <a href="index.php?w=goods_class&t=goodsclass_drop&class_id=<?php echo $val['class_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a>
          <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?w=goods_class&t=goodsclass_edit&class_id=<?php echo $val['class_id'];?>">编辑分类信息</a></li>
              <li><a href="index.php?w=goods_class&t=goodsclass_add&class_parent_id=<?php echo $val['class_id'];?>">新增下级分类</a></li>
            </ul>
            </span></td>
          <td class="sort"><span wt_type="class_sort" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable"><?php echo $val['class_sort'];?></span></td>
          <td class="name"><span wt_type="class_name" column_id="<?php echo $val['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable"><?php echo $val['class_name'];?></span></td>
          <td>
            <a href="javascript:;" class="pic-thumb-tip" onmouseout="toolTip()" onmouseover="toolTip('<img src=\'<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS. (empty($val['class_image']) ? 'what_goods_default.gif' : $val['class_image']); ?>\'>')">
              <i class='fa fa-picture-o'></i>
            </a>
          </td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" <?php echo $val['class_commend']? 'enabled':'disabled'?>" ajax_branch='class_commend'  wt_type="inline_edit" fieldname="class_commend" fieldid="<?php echo $val['class_id']?>" fieldvalue="<?php echo $val['class_commend']?'1':'0'?>" title="<?php echo $lang['editable'];?>"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/transparent.gif"></a></td>
          <td></td>
          <td></td>
        </tr>
        <?php foreach($output['list'] as $val1){ ?>
        <?php if($val1['class_parent_id'] == $val['class_id']) { ?>
        <tr class="edit <?php echo 'class_id'.$val['class_id'];?> " style="display:none;" data-id="<?php echo $val['class_id']; ?>">
          <td></td>
          <td class="handle">
          <a href="index.php?w=goods_class&t=goodsclass_drop&class_id=<?php echo $val1['class_id'];?>" class="btn red confirm-del"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a>
          <span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul><li><a href="index.php?w=goods_class&t=goodsclass_edit&class_id=<?php echo $val1['class_id'];?>">编辑分类信息</a></li>
              <li><a href="index.php?w=goods_class&t=goodsclass_binding&class_id=<?php echo $val1['class_id'];?>">绑定商品分类</a></li>
              <?php if(empty($val1['class_default'])) { ?><li><a href="index.php?w=goods_class&t=goodsclass_default&class_id=<?php echo $val1['class_id'];?>">设为默认分类</a></li><?php } ?>
            </ul>
            </span></td>
          <td class="sort"><span wt_type="class_sort" column_id="<?php echo $val1['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable "><?php echo $val1['class_sort'];?></span></td>
          <td class="name"><img src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item1.gif"> <span wt_type="class_name" column_id="<?php echo $val1['class_id'];?>" title="<?php echo $lang['wt_editable'];?>" class="editable "><?php echo $val1['class_name'];?></span>
          </td>
          <td>
            <a href="javascript:;" class="pic-thumb-tip" onmouseout="toolTip()" onmouseover="toolTip('<img src=\'<?php echo UPLOAD_SITE_URL.DS.ATTACH_WHAT.DS. (empty($val1['class_image']) ? 'what_goods_default.gif' : $val1['class_image']); ?>\'>')">
              <i class='fa fa-picture-o'></i>
            </a>
          </td>
          <td></td>
          <td>
          <?php if (empty($val1['class_default'])) { ?>
            <span class="no"><i class="fa fa-ban"></i>否</span>
          <?php } else { ?>
            <span class="yes"><i class="fa fa-check-bbs"></i>是</span>
          <?php } ?>
          </td>
          <td></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } ?>
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
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '说说看分类列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation }
/*
        ,
				   {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?w=goods_class&t=goodsclass_drop&class_id=__IDS__'.replace('__IDS__', ids.join(','));
                    } }
*/
               ]
		});

        $(".class_parent").click(function(){
            if($(this).attr("status") == "open") {
                $(this).attr("status","close");
                $(this).attr("src","<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-collapsable.gif");
                $("."+$(this).attr("class_id")).show();
            } else {
                $(this).attr("status","open");
                $(this).attr("src","<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif");
                $("."+$(this).attr("class_id")).hide();
            }
        });

        //行内ajax编辑
        $('span[wt_type="class_sort"]').inline_edit({w: 'goods_class',t: 'goodsclass_sort_update'});
        $('span[wt_type="class_name"]').inline_edit({w: 'goods_class',t: 'goodsclass_name_update'});

    $('a.confirm-del').live('click', function() {
        if (!confirm('确定删除？')) {
            return false;
        }
    });

    });
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=goods_class&t=goodsclass_add';
    }
}
</script>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.edit.js" charset="utf-8"></script>