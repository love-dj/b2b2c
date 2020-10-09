<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtc-receipt-info"><div class="wtc-receipt-info-title">
  <h3>发票信息</h3>
  <a href="javascript:void(0)" wt_type="buy_edit" id="edit_invoice">[修改]</a></div>
  <div id="invoice_list" class="wtc-candidate-items">
    <ul>
      <li><?php echo $output['inv_info']['content'];?></li>
    </ul>
  </div>
</div>
<script type="text/javascript">
//隐藏发票列表
function hideInvList(content) {
    $('#edit_invoice').show();
	$("#invoice_list").html('<ul><li>'+content+'</li></ul>');
	$('.current_box').removeClass('current_box');
	ableOtherEdit();
	//重新定位到顶部
	$("html, body").animate({ scrollTop: 0 }, 0);
}
//加载发票列表
$('#edit_invoice').on('click',function(){
    $(this).hide();
    disableOtherEdit('如需修改，请先保存发票信息');
    $(this).parent().parent().addClass('current_box');
    $('#invoice_list').load(SITEURL+'/index.php?w=buy&t=load_inv&vat_hash=<?php echo $output['vat_hash'];?>');
});
</script>