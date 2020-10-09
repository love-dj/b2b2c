<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
  <form method="get" id="formSearch">
    <table class="search-form">
      <input type="hidden" id='w' name='w' value='store_bill' />
      <input type="hidden" id='t' name='t' value='show_bill' />
      <input type="hidden" name='ob_id' value='<?php echo $_GET['ob_id'];?>' />
      <input type="hidden" name='type' value='<?php echo $_GET['type'];?>' />
      <tr>
        <td>&nbsp;</td>
        <th>订单编号</th>
        <td class="w180"><input type="text" class="text"  value="<?php echo $_GET['query_order_no'];?>" name="query_order_no" /></td>
        <th>成交时间</th>
        <td class="w180">
        	<input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/>
          &#8211;
          <input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/>
        </td>
        <td class="tc w200">
        <label class="submit-border"><input type="button" id="wtsubmit" class="submit" value="<?php echo $lang['wt_search'];?>" /></label>
        <label class="submit-border"><input type="button" id="wtexport" class="submit" value="导出" /></label>
        </td>
    </table>
  </form>
<table class="wtsc-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th>订单编号</th>
        <th>下单时间</th>
        <th>成交时间</th>
        <th>订单金额</th>
        <th>运费</th>
        <th>佣金金额</th>
        <th>平台优惠券</th>
        <th><?php echo $lang['wt_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['order_list']) && !empty($output['order_list'])) { ?>
      <?php foreach($output['order_list'] as $order_info) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="w90"><?php echo $order_info['order_sn'];?></td>
        <td><?php echo date("Y-m-d",$order_info['add_time']);?></td>
        <td><?php echo date("Y-m-d",$order_info['finnshed_time']);?></td>
        <td><?php echo wtPriceFormat($order_info['order_amount']);?></td>
        <td><?php echo wtPriceFormat($order_info['shipping_fee']);?></td>
        <td><?php echo wtPriceFormat($output['commis_list'][$order_info['order_id']]['commis_amount']);?></td>
        <td><?php echo wtPriceFormat($order_info['rpt_amount']);?></td>
        <td>
       	<a target="_blank" href="index.php?w=store_order&t=show_order&order_id=<?php echo $order_info['order_id'];?>"><?php echo $lang['wt_view'];?></a>
        </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php if (is_array($output['order_list']) && !empty($output['order_list'])) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
<script type="text/javascript">
$(function(){
    $('#wtexport').click(function(){
    	$('input[name="t"]').val('export_order');
    	$('#formSearch').submit();
    });
    $('#wtsubmit').click(function(){
    	$('input[name="t"]').val('show_bill');
    	$('#formSearch').submit();
    });
});
</script>