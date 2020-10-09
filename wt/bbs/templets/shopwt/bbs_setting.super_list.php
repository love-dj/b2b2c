<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_bbs_setting'];?></h3>
        <h5><?php echo $lang['wt_bbs_setting_subhead'];?></h5>
      </div>
      <ul class="tab-base wt-row">
        <li><a href="<?php echo urlAdminbbs('bbs_setting', 'index');?>"><?php echo $lang['wt_bbs_setting'];?></a></li>
        <li><a href="<?php echo urlAdminbbs('bbs_setting', 'seo');?>"><?php echo $lang['bbs_setting_seo'];?></a></li>
        <li><a href="<?php echo urlAdminbbs('bbs_setting', 'sec');?>"><?php echo $lang['bbs_setting_sec'];?></a></li>
        <li><a href="<?php echo urlAdminbbs('bbs_setting', 'exp');?>"><?php echo $lang['bbs_setting_exp'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current">超级管理员</a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="clmdForm" id="clmdForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="60" align="center" class="handle-s"><?php echo $lang['wt_handle'];?></th>
          <th width="150" align="left">会员名称</th>
          <th width="150" align="left">添加时间</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['cm_list'])){?>
        <?php foreach($output['cm_list'] as $val){?>
        <tr>
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle-s"><a href="javascript:void(0);" onclick="if(confirm('<?php echo $lang['wt_ensure_del'];?>')){location.href='<?php echo urlAdminbbs('bbs_setting', 'del_super', array('member_id' => $val['member_id']));?>'}" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['wt_del'];?></a></td>
          <td><?php echo $val['member_name'];?></td>
          <td><?php echo date('Y-m-d H:i:s', $val['cm_jointime']);?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['wt_no_record'];?></td>
        </tr>
        <?php }?>
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
		title: '社区“超级管理员”列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>设置超管', name : 'add', bclass : 'add', title : '添加超级管理员', onpress : fg_operation }				   
               ]
		});

});
function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?w=bbs_setting&t=superadd';
    }
}
</script>