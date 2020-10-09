<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form id="rp_list_form" method="get">
    <table class="wtm-search-table">
      <input type="hidden" id='w' name='w' value='member_coupon' />
      <input type="hidden" id='t' name='t' value='rp_list' />
      <tr>
        <td>&nbsp;</td>
        <td class="w100 tr">
          <select name="rp_state_select">
                <option value="" <?php if (!$_GET['rp_state_select']){ echo 'selected=true'; } ?>>优惠券状态</option>
                <?php if (!empty($output['couponstate_arr'])){?>
                <?php foreach ($output['couponstate_arr'] as $k=>$v){?>
                <option value="<?php echo $k;?>" <?php if ($_GET['rp_state_select'] == $k){echo 'selected';}?>><?php echo $v['name'];?></option>
                <?php }?>
                <?php }?>
          </select>
        </td>
        <td class="w70 tc">
            <label class="submit-border">
                <input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" />
            </label>
        </td>
      </tr>
    </table>
  </form>
  <table class="wtm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w70"></th>
        <th class="tl">优惠券编码</th>
        <th class="w80">面额（元）</th>
        <th class="w200">有效期</th>
        <th class="w100">状态</th>
        <th class="w70"><?php echo $lang['wt_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="wtm-goods-thumb"><a href="javascript:void(0);"><img src="<?php echo $val['coupon_customimg_url'];?>" onMouseOver="toolTip('<img src=<?php echo $val['coupon_customimg_url'];?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="tl">
            <dl class="goods-name">
                <dt><?php echo $val['coupon_code'];?></dt>
                <dd>（使用条件：订单满<?php echo $val['coupon_limit'].$lang['currency_zh'];?>）</dd>
            </dl>
        </td>
        <td class="goods-price"><?php echo $val['coupon_price'];?></td>
        <td class="goods-time"><?php echo date("Y-m-d",$val['coupon_start_date']).'~'.date("Y-m-d",$val['coupon_end_date']);?></td>
        <td><?php echo $val['coupon_state_text'];?></td>
        <td class="<?php echo $val['coupon_state_key'] == 'unused' ? 'wtm-table-handle' : null?>">
            <?php if ($val['coupon_state_key'] == 'unused'){?>
                <span><a href="<?php echo urlShop('search', 'index');?>" class="btn-mint" target="_blank"><i class="icon-shopping-cart"></i><p>使用</p></a></span>
            <?php } elseif ($val['coupon_state_key'] == 'used'){?>
                <span><a target="_blank" href="<?php echo urlShop('member_order','index',array('pay_sn'=>$val['coupon_order_id']));?>"><p>查看订单</p></a></span>
            <?php } ?>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php  if (count($output['list'])>0) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>