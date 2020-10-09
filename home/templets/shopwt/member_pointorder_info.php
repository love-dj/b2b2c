<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wtm-oredr-show">
  <div class="wtm-order-info">
    <div class="wtm-order-details">
      <div class="title"><?php echo $lang['member_pointorder_info_ordersimple'];?></div>
      <div class="content">
        <dl>
          <dt><?php echo $lang['member_pointorder_info_shipinfo'].$lang['wt_colon'];?></dt>
          <dd> <span><?php echo $output['orderaddress_info']['point_truename']; ?></span> <span><?php echo $output['orderaddress_info']['point_mobphone']; ?></span> <span><?php echo $output['orderaddress_info']['point_telphone']; ?></span> <span><?php echo $output['orderaddress_info']['point_areainfo']; ?></span> <span><?php echo $output['orderaddress_info']['point_address']; ?></span>
        </dl>
        <dl>
          <dt><?php echo $lang['member_pointorder_info_ordermessage'].$lang['wt_colon'];?></dt>
          <dd>
            <?php if ($output['order_info']['point_ordermessage']){ ?>
            <?php echo $output['order_info']['point_ordermessage']; ?>
            <?php } ?>
          </dd>
        </dl>
        <dl class="line">
          <dt><?php echo $lang['member_pointorder_ordersn'].$lang['wt_colon'];?></dt>
          <dd><?php echo $output['order_info']['point_ordersn']; ?><a href="javascript:void(0);">更多<i class="icon-angle-down"></i>
            <div class="more"><span class="arrow"></span>
              <ul>
                <li><?php echo $lang['member_pointorder_addtime'].$lang['wt_colon'];?> <span><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></span></li>
                <?php if ($output['order_info']['point_shippingtime'] != ''){?>
                <li><?php echo $lang['member_pointorder_shipping_time'].$lang['wt_colon'];?> <span> <?php echo @date('Y-m-d H:i:s',$output['order_info']['point_shippingtime']);?> </span></li>
                <?php }?>
              </ul>
            </div>
            </a></dd>
        </dl>
      </div>
    </div>
    <div class="wtm-order-condition">
      <?php if ($output['order_info']['point_orderstate'] == $output['pointorderstate_arr']['canceled'][0]){ ?>
      <dl>
        <dt><i class="icon-off orange"></i>兑换订单状态：</dt>
        <dd><?php echo $output['pointorderstate_arr']['canceled'][1];?></dd>
      </dl>
      <ul>
        <li>已取消了该兑换订单，<a href="<?php echo urlShop('pointprod', 'plist');?>">马上去看看其他兑换礼品</a></li>
      </ul>
      <?php } else { ?>
      <div class="wtm-order-step">
        <dl class="step-first current">
          <dt>提交兑换</dt>
          <dd class="bg"></dd>
          <dd class="date" title="<?php echo $lang['member_pointorder_addtime'];?>"><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></dd>
        </dl>
        <dl class="<?php if ($output['order_info']['point_shippingtime'] != ''){?>current<?php } ?>">
          <dt>礼品发货</dt>
          <dd class="bg"> </dd>
          <dd class="date" title="<?php echo $lang['member_pointorder_shipping_time'];?> "><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_shippingtime']);?></dd>
        </dl>
        <dl class="<?php if($output['order_info']['point_finnshedtime'] != '') { ?>current<?php } ?>">
          <dt>确认收货</dt>
          <dd class="bg"> </dd>
          <dd class="date" title=""><?php echo date("Y-m-d H:i:s",$output['order_info']['point_finnshedtime']); ?></dd>
        </dl>
      </div>
      <?php } ?>
    </div>
  </div>
  <div class="wtm-order-contnet">
    <table class="wtm-default-table order">
      <thead>
        <tr>
          <th class="w10"></th>
          <th colspan="2"><?php echo $lang['member_pointorder_info_prodinfo'];?></th>
          <th class="w120"><?php echo $lang['member_pointorder_exchangepoints'];?></th>
          <th class="w100"><?php echo $lang['member_pointorder_info_prodinfo_exnum'];?></th>
          <th class="w100"><?php echo $lang['member_pointorder_orderstate'];?></th>
          <th class="w150">兑换单操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($output['prod_list']) and !empty($output['prod_list'])) {?>
        <?php if ($output['order_info']['point_shippingtime'] != '') {?>
        <tr>
          <th colspan="20"> <div class="order-deliver"><span>物流公司： <a target="_blank" href="<?php echo $output['express_info']['e_url'];?>"><?php echo $output['express_info']['e_name'];?></a></span><span> <?php echo $lang['member_pointorder_shipping_code'].$lang['wt_colon'];?> <?php echo $output['order_info']['point_shippingcode'];?> </span><span><a href="javascript:void(0);" id="show_shipping">物流跟踪<i class="icon-angle-down"></i>
              <div class="more"><span class="arrow"></span>
                <ul id="shipping_ul">
                  <li>加载中...</li>
                </ul>
              </div>
              </a></span></div></th>
        </tr>
        <?php }?>
        <?php foreach($output['prod_list'] as $k => $val) { ?>
        <tr>
          <td></td>
          <td class="w50"><div class="pic-thumb"><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $val['point_goodsid']));?>" target="_blank"> <img src="<?php echo $val['point_goodsimage_small'];?>" onMouseOver="toolTip('<img src=<?php echo $val['point_goodsimage'];?> />')" onMouseOut="toolTip()" /></a></div></td>
          <td class="tl"><dl class="goods-name">
              <dt><a href="<?php echo urlShop('pointprod', 'pinfo', array('id' => $val['point_goodsid']));?>" target="_blank"><?php echo $val['point_goodsname']; ?></a></dt>
            </dl></td>
          <td><?php echo $val['point_goodspoints']; ?></td>
          <td><?php echo $val['point_goodsnum']; ?></td>
          <?php if ((count($output['prod_list']) > 1 && $k ==0) || (count($output['prod_list']) == 1)){?>
          <td class="bdl" rowspan="<?php echo count($output['prod_list']);?>"><?php echo $output['order_info']['point_orderstatetext']; ?></td>
          <td class="bdl" rowspan="<?php echo count($output['prod_list']);?>"><?php if ($output['order_info']['point_orderallowcancel']) { ?>
            <p><a href="javascript:void(0)" class="wtbtn wtbtn-bittersweet" onclick="ajax_confirm('<?php echo $lang['member_pointorder_cancel_confirmtip']; ?>','<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=cancel_order&order_id=<?php echo $output['order_info']['point_orderid']; ?>');"><?php echo $lang['member_pointorder_cancel_title']; ?></a></p>
            <?php } ?>
            <?php if ($output['order_info']['point_orderallowreceiving']) { ?>
            <p><a href="javascript:void(0)" class="wtbtn" onclick="ajax_confirm('<?php echo $lang['member_pointorder_confirmreceivingtip']; ?>','<?php echo MEMBER_SITE_URL;?>/index.php?w=member_pointorder&t=receiving_order&order_id=<?php echo $output['order_info']['point_orderid']; ?>');"><?php echo $lang['member_pointorder_confirmreceiving']; ?></a></p>
            <?php } ?></td>
          <?php } ?>
        </tr>
        <?php } }?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><dl class="sum">
              <dt>兑换单所需：</dt>
              <dd><em><?php echo $output['order_info']['point_allpoint'];?></em>分</dd>
            </dl></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#show_shipping').on('hover',function(){
        var_send = '<?php echo date("Y-m-d H:i:s",$output['order_info']['point_shippingtime']); ?>&nbsp;&nbsp;平台已发货<br/>';
    	$.getJSON('index.php?w=member_pointorder&t=get_express&e_code=<?php echo $output['express_info']['e_code']?>&shipping_code=<?php echo $output['order_info']['point_shippingcode']?>&c=<?php echo random(7);?>',function(data){
    		if(data){
    			data = var_send+data.join('<br/>');
    			$('#shipping_ul').html('<li>'+data+'</li>');
    			$('#show_shipping').unbind('hover');
    		}else{
    			$('#shipping_ul').html(var_send);
    			$('#show_shipping').unbind('hover');
    		}
    	});
    });
});
</script> 
