<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_web_index'];?></h3>
        <h5><?php echo $lang['wt_web_index_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo '板块区';?></a></li>
        <li><a href="index.php?w=page_config&t=focus_edit"><?php echo '焦点区';?></a></li>
        <li><a href="index.php?w=page_config&t=sale_edit"><?php echo '促销区';?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['page_config_index_help1'];?></li>
      <li><?php echo $lang['page_config_index_help2'];?></li>
      <li><?php echo $lang['page_config_index_help3'];?></li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="150" align="center" class="handle"><?php echo $lang['wt_handle'];?></th>
        <th width="60" align="center"><?php echo $lang['wt_sort'];?></th>
        <th width="150" align="left"><?php echo $lang['page_config_web_name'];?></th>
        <th width="150" align="left"><?php echo $lang['page_config_style_name'];?></th>
        <th width="150" align="left"><?php echo $lang['page_config_update_time'];?></th>
        <th width="60" align="center"><?php echo $lang['wt_display'];?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['web_list']) && is_array($output['web_list'])){ ?>
      <?php foreach($output['web_list'] as $k => $v){ ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle"><a class="btn purple" href="index.php?w=page_config&t=web_edit&web_id=<?php echo $v['web_id'];?>"><i class="fa fa-cog"></i><?php echo $lang['page_config_web_edit'];?></a><a class="btn orange" href="index.php?w=page_config&t=code_edit&web_id=<?php echo $v['web_id'];?>"><i class="fa fa-steam"></i><?php echo $lang['page_config_code_edit'];?></a></td>
        <td><?php echo $v['web_sort'];?></td>
        <td><?php echo $v['web_name'];?></td>
        <td><?php echo $output['style_array'][$v['style_name']];?></td>
        <td><?php echo date('Y-m-d H:i:s',$v['update_time']);?></td>
        <td><?php echo $v['web_show']==1 ? '<span class="yes"><i class="fa fa-check-bbs"></i>'.$lang['wt_yes'].'</span>' : '<span class="no"><i class="fa fa-ban"></i>'.$lang['wt_no'].'</span>';?></td>
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
</div>
<script>
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped: true,// 使用斑马线
		resizable: false,// 不调节大小
		title: '商城首页板块列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制      
		});
});
</script> 
