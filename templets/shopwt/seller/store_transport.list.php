<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a class="wtbtn wtbtn-mint" href="index.php?w=store_transport&t=add&type=<?php echo $_GET['type'];?>"><?php echo $lang['transport_tpl_add'];?> </a> </div>
<!-----------------list begin------------------------>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>如果某商品选择使用了售卖区域，则该商品只售卖指定地区，运费为指定地区的运费。</li>
  </ul>
</div>
<?php if (is_array($output['list'])){?>
<table class="wtsc-default-table order">
  <thead>
    <tr>
      <th class="w20"></th>
      <th class="cell-area tl"><?php echo $lang['transport_to'];?></th>
              <th class="w120">首(件、重、体积)</th>
              <th class="w80">首费(元)</th>
              <th class="w120">续(件、重、体积)</th>
              <th class="w80">续费(元)</th>
    </tr>
  </thead>
  <?php foreach ($output['list'] as $v){?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20"><?php if ($_GET['type'] == "select"){?>
        <a class="ml5 wtbtn-mini wtbtn-bittersweet" data-param="{name:'<?php echo $v['title'];?>',id:'<?php echo $v['id'];?>',trans_type:'<?php echo intval($v['goods_trans_type']);?>'}" href="javascript:void(0)"><i class="icon-truck"></i><?php echo $lang['transport_applay'];?></span></a>
        <?php }?><h3><?php echo $v['title'];?></h3>
        
        <span class="fr mr5">
        <time title="<?php echo $lang['transport_tpl_edit_time'];?>"><i class="icon-time"></i><?php echo date('Y-m-d H:i:s',$v['update_time']);?></time>
        <a class="J_Clone wtbtn-mini" href="javascript:void(0)" data-id="<?php echo $v['id'];?>"><i class="icon-copy"></i><?php echo $lang['transport_tpl_copy'];?></a> <a class="J_Modify wtbtn-mini" href="javascript:void(0)" data-id="<?php echo $v['id'];?>"><i class="icon-edit"></i><?php echo $lang['transport_tpl_edit'];?></a> <a class="J_Delete wtbtn-mini" href="javascript:void(0)" data-id="<?php echo $v['id'];?>"><i class="icon-trash"></i><?php echo $lang['transport_tpl_del'];?></a></span></th>
    </tr>
    <?php if (is_array($output['extend'][$v['id']]['data'])){
        $_type=$v['goods_trans_type'];?>
    <?php foreach ($output['extend'][$v['id']]['data'] as $value){
	    if ($_type==1) $value['snum']=intval($value['snum']);
	    if ($_type==1) $value['xnum']=intval($value['xnum']);?>
    <tr>
      <td></td>
      <td class="cell-area tl"><?php echo $value['area_name'];?></td>
              <td><?php echo $value['snum'];?></td>
              <td><?php echo $value['sprice'];?></td>
              <td><?php echo $value['xnum'];?></td>
              <td><?php echo $value['xprice'];?></td>
    </tr>
    <?php }?>
    <?php }?>
  </tbody>
  <?php }?>
</table>
<?php } else {?>
<div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div>
<?php } ?>
<?php if (is_array($output['list'])){?>
<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php }?>
<!-----------------list end-----------------------> 

<script>
$(function(){	
	$('a[class="J_Delete wtbtn-mini"]').click(function(){
		var id = $(this).attr('data-id');
		if(typeof(id) == 'undefined') return false;
		get_confirm('<?php echo $lang['transport_del_confirm'];?>','<?php echo BASE_SITE_URL;?>/index.php?w=store_transport&t=delete&type=<?php echo $_GET['type'];?>&id='+id);
//		$(this).attr('href','<?php echo BASE_SITE_URL;?>/index.php?w=transport&t=delete&type=<?php echo $_GET['type'];?>&id='+id);
//		return true;
	});

	$('a[class="J_Modify wtbtn-mini"]').click(function(){
		var id = $(this).attr('data-id');
		if(typeof(id) == 'undefined') return false;
		$(this).attr('href','<?php echo BASE_SITE_URL;?>/index.php?w=store_transport&t=edit&type=<?php echo $_GET['type'];?>&id='+id);
		return true;
	});
	
	$('a[class="J_Clone wtbtn-mini"]').click(function(){
		var id = $(this).attr('data-id');
		if(typeof(id) == 'undefined') return false;
		$(this).attr('href','<?php echo BASE_SITE_URL;?>/index.php?w=store_transport&t=clone&type=<?php echo $_GET['type'];?>&id='+id);
		return true;
	});
	$('a[class="ml5 wtbtn-mini wtbtn-bittersweet"]').click(function(){
		var data_str = '';
		eval('data_str = ' + $(this).attr('data-param'));
		if(data_str.trans_type >1 ) {
    		var _str = '';
    		_str = '<span id="goods_trans_v">&nbsp;&nbsp;&nbsp;&nbsp;商品(重量/体积)：<input name="goods_trans_v" value="1.00" type="text" class="w30" />(kg/m³)</span>';
    		if($("#goods_trans_v", opener.document).size()==0) $("#postageName", opener.document).after(_str);
		} else {
		    $("#goods_trans_v", opener.document).remove();
		}
		
		$("#postageName", opener.document).css('display','inline-block').html(data_str.name);
		$("#transport_title", opener.document).val(data_str.name);
		$("#transport_id", opener.document).val(data_str.id);
		$("#g_freight", opener.document).val(0);
		window.close();
	});	

});
</script>