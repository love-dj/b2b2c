<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"> <a class="back" href="javascript:history.go(-1);" title="返回"> <i class="fa fa-arrow-bbs-o-left"></i> </a>
      <div class="subject">
        <h3>虚拟抢购 - 查看/编辑虚拟抢购区域“<?php echo $output['parent_area']['area_name']; ?>”下级区域</h3>
        <h5>商家可设置其虚拟抢购活动的区域以便于会员检索</h5>
      </div>
    </div>
  </div>
  <form id="list_form" method='post'>
    <input id="area_id" name="area_id" type="hidden" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="left" class="handle"><?php echo $lang['wt_handle']; ?></th>
          <th width="200" align="left">区域名称</th>
          <th width="200" align="left">所属城市</th>
          <th width="100" align="center">添加时间</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $val){ ?>
        <tr data-id="<?php echo $val['area_id'] ;?>">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle"><a href="index.php?w=vr_robbuy&t=area_street&parent_area_id=<?php echo $val['area_id']; ?>" class="btn green"><i class="fa fa-list-alt"></i>查看</a> <span class="btn"> <em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
            <ul>
              <li><a href="index.php?w=vr_robbuy&t=area_edit&area_id=<?php echo $val['area_id']; ?>">编辑商区</a></li>
              <li><a href="javascript:;" onclick="submit_delete(<?php echo $val['area_id']; ?>)">删除商区</a></li>
            </ul>
            </span></td>
          <td><?php echo $val['area_name']; ?></td>
          <td><?php echo $output['parent_area']['area_name']; ?></td>
          <td><?php echo date("Y-m-d",$val['add_time']); ?></td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-bbs"></i><?php echo $lang['wt_no_record']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function() {

    $('.flex-table').flexigrid({
        height: 'auto', // 高度自动
        usepager: false, // 不翻页
        striped: false, // 不使用斑马线
        resizable: false, // 不调节大小
        title: '虚拟抢购商区列表', // 表格标题
        reload: false, // 不使用刷新
        columnControl: false, // 不使用列控制
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增商区',
                name: 'add',
                bclass: 'add',
                title: '新增商区',
                onpress: function() {
                    location.href = 'index.php?w=vr_robbuy&t=area_add&area_id=<?php echo $_GET['parent_area_id']; ?>';
                }
            },
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
                    if (ids.length < 1) {
                        return false;
                    }
                    submit_delete(ids.join(','));
                }
            }
        ]
    });

});

function submit_delete(id){
    if (confirm('<?php echo $lang['wt_ensure_del'];?>')) {
        $('#list_form').attr('method','post');
        $('#list_form').attr('action','index.php?w=vr_robbuy&t=area_drop');
        $('#area_id').val(id);
        $('#list_form').submit();
    }
}

</script>