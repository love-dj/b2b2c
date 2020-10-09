<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/point.css" rel="stylesheet" type="text/css">

<div class="wtp-container">
  <div class="wtp-base-box">
    <div class="wtp-member-left">
      <?php include_once BASE_TPL_PATH.'/home/points.minfo.php'; ?>
    </div>
    <div class="wtp-member-right">
      <div class="wtp-grade">
        <div class="title">
          <h3>我的升级进度</h3>
        </div>
        <div class="wtp-gradeall-bar">
          <?php if ($output['membergrade_arr']){ ?>
          <?php foreach ($output['membergrade_arr'] as $k=>$v){ ?>
          <div class="itemlevel exp-lv<?php echo $v['level'];?>" >
            <?php if ($v['is_cur']){?>
            <div class="bar" title='<?php echo $v['is_cur']?"经验值：{$output['member_info']['member_exppoints']}":'';?>'><i></i> <span class="arrow"></span>
              <div class="tips">
                <p class="p1"> 我的当前等级： <strong><?php echo $v['level_name'];?></strong> 当前经验值： <em><?php echo $output['member_info']['member_exppoints'];?></em> </p>
                <p class="p2">
                  <?php if ($k >= count($output['membergrade_arr'])-1){?>
                  已达到最高会员级别，继续加油保持这份荣誉哦！
                  <?php } else {?>
                  在有效期前再累积 <strong><?php echo $output['membergrade_arr'][$k+1]['exppoints']-$output['member_info']['member_exppoints'];?></strong> 经验值即可升级 <em><?php echo $output['membergrade_arr'][$k+1]['level_name'];?></em>
                  <?php } ?>
                </p>
              </div>
            </div>
            <?php }?>
            <div class="gradelabel"> <strong><?php echo $v['level_name'];?></strong> <i>(<?php echo $v['exppoints'];?>)</i> </div>
          </div>
          <?php } ?>
          <?php } else { ?>
          暂无等级
          <?php }?>
        </div>
      </div>
    </div>
  </div>
  <div class="wtp-grade-box mt20">
    <div class="title">
      <h3>经验值结构</h3>
    </div>
    <dl>
      <dt><i class="icon-01"></i>
        <p>如何计算经验值</p>
      </dt>
      <dd>
        <?php if ($output['ruleexplain_arr']){ ?>
        <ul>
          <?php foreach ($output['ruleexplain_arr'] as $v){ ?>
          <li><?php echo $v; ?></li>
          <?php } ?>
        </ul>
        <?php } ?>
      </dd>
    </dl>
  </div>
  <div class="wtp-grade-box">
    <div class="title">
      <h3>有效购物金额</h3>
    </div>
    <dl>
      <dt><i class="icon-02"></i>
        <p>有效范围</p>
      </dt>
      <dd>
        <?php if ($output['ruleexplain_arr']['exp_order']){ ?>
        <ul>
          <li>实物交易订单的在<strong>【确认完成】</strong>后，该笔订单金额计入有效购物金额；在您收货后，请到<strong>【实物交易订单】</strong>中，点击<strong>【确认收货】</strong>，经验值会更快地发放；</li>
          <li>虚拟兑换订单的在<strong>【已完成】</strong>后，该笔订单金额计入有效购物金额；</li>
        </ul>
        <?php } ?>
      </dd>
    </dl>
  </div>
</div>
</div>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/home.js" id="wt_dialog" charset="utf-8"></script> 
