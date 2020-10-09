<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="wts-message-bar">
  <div class="default">
    <h5><a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>" title="<?php echo $output['setting_config']['site_name']; ?>" class="store_name"><?php echo $output['store_info']['store_name']; ?></a></h5>
    <span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
    <?php if(!empty($output['store_info']['store_qq'])){?>
    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['store_info']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
    <?php }?>
    <?php if(!empty($output['store_info']['store_ww'])){?>
    <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>
&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $output['store_info']['store_ww'];?>
&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
    <?php }?>
  </div>
  <?php if(!empty($output['store_info']['store_presales']) || !empty($output['store_info']['store_aftersales']) || $output['store_info']['store_workingtime'] !=''){?>
  <div class="service-list" store_id="<?php echo $output['store_info']['store_id'];?>" store_name="<?php echo $output['store_info']['store_name'];?>">
    <?php if(!empty($output['store_info']['store_presales'])){?>
    <dl>
      <dt><?php echo $lang['wt_message_presales'];?></dt>
      <?php foreach($output['store_info']['store_presales'] as $val){?>
      <dd><span><?php echo $val['name']?></span><span>
        <?php if($val['type'] == 1){?>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $val['num'];?>&site=qq&menu=yes" title="QQ: <?php echo $val['num'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $val['num'];?>:52" style=" vertical-align: middle;"/></a>
        <?php }elseif($val['type'] == 2){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
        <?php }elseif($val['type'] == 3){?>
        <span c_name="<?php echo $val['name'];?>" member_id="<?php echo $val['num'];?>"></span>
        <?php }?>
        </span></dd>
      <?php }?>
    </dl>
    <?php }?>
    <?php if(!empty($output['store_info']['store_aftersales'])){?>
    <dl>
      <dt><?php echo $lang['wt_message_service'];?></dt>
      <?php foreach($output['store_info']['store_aftersales'] as $val){?>
      <dd><span><?php echo $val['name']?></span><span>
        <?php if($val['type'] == 1){?>
        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $val['num'];?>&site=qq&menu=yes" title="QQ: <?php echo $val['num'];?>" target="_blank"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $val['num'];?>:52" alt="<?php echo $lang['wt_message_me'];?>" style=" vertical-align: middle;"></a>
        <?php }elseif($val['type'] == 2){?>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=<?php echo $val['num'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
        <?php }elseif($val['type'] == 3){?>
        <span c_name="<?php echo $val['name'];?>" member_id="<?php echo $val['num'];?>"></span>
        <?php }?>
        </span></dd>
      <?php }?>
    </dl>
    <?php }?>
    <?php if($output['store_info']['store_workingtime'] !=''){?>
    <dl class="workingtime">
      <dt><?php echo $lang['wt_message_working'];?></dt>
      <dd>
        <p><?php echo html_entity_decode($output['store_info']['store_workingtime']);?></p>
      </dd>
    </dl>
    <?php }?>
  </div>
  <?php }?>
</div>
