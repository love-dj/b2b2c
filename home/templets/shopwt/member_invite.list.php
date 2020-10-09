<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu'); ?>
		<a class="wtbtn wtbtn-bittersweet" title="我要推广" href="index.php?w=member_invite&t=view" style="right: 0px;"><i class="icon-shield"></i>我要推广</a>
    </div>
    <div class="alert alert-block">
  	<h4>返利规则</h4>
	<ul>
	<li>邀请新用户注册，得到<em><?php echo C('points_invite'); ?></em>积分。</li>	
	<li>一级邀请会员：被邀请的会员购买商品时，可获得消费金额的<em><?php echo C('invite_points_one'); ?>%</em>的整数积分返利,确认收货后自动结算。</li>
	<li>二级邀请会员：被邀请的会员购买商品时，可获得消费金额的<em><?php echo C('invite_points_two'); ?>%</em>的整数积分返利,确认收货后自动结算。</li>
	<li>三级邀请会员：被邀请的会员购买商品时，可获得消费金额的<em><?php echo C('invite_points_three'); ?>%</em>的整数积分返利,确认收货后自动结算。</li>
	<li>被邀请会员购买商品时给邀请人返的积分数(例如设为10%，被邀请人购买100元商品，返给邀请人10积分)</li>	
		</ul>
 </div>
    <table class="wtm-default-table">
        <thead>
            <tr>
                <th class="w10"></th>
                <th class="tl"><?php echo $lang['member_name']; ?></th>
                <th class="tl"><?php echo $lang['buy_count']; ?></th>
                <th class="tl"><?php echo $lang['refund_amount']; ?></th>
                <th class="tl"><?php echo $lang['signup_time']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($output['invite_list'])>0) { ?>
                <?php foreach($output['invite_list'] as $v) { ?>
                    <tr class="bd-line">
                        <td></td>
                        <td class="tl"><?php echo $v['member_name'] ;?></td>
                        <td class="tl"><?php echo $v['buy_count'] ;?></td>
                        <td class="tl"><?php echo $v['refund_amount'] ;?></td>
                        <td class="tl"><?php echo date('Y-m-d H:i:s', $v['member_time']) ;?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <?php  if (count($output['invite_list'])>0) { ?>
                <tr>
                    <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>