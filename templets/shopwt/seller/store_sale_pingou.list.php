<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <?php if ($output['isOwnShop']) { ?>
  <a class="wtbtn wtbtn-mint" href="<?php echo urlShop('store_sale_pingou', 'pingou_add');?>"><i class="icon-plus-sign"></i>添加活动</a>
  <?php } else { ?>
  <?php if(!empty($output['current_quota'])) { ?>
  <a class="wtbtn wtbtn-mint" style="right:100px" href="<?php echo urlShop('store_sale_pingou', 'pingou_add');?>"><i class="icon-plus-sign"></i>添加活动</a> <a class="wtbtn wtbtn-aqua" href="<?php echo urlShop('store_sale_pingou', 'quota_add');?>" title=""><i class="icon-money"></i>套餐续费</a>
  <?php } else { ?>
  <a class="wtbtn wtbtn-aqua" href="<?php echo urlShop('store_sale_pingou', 'quota_add');?>" title=""><i class="icon-money"></i>购买套餐</a>
  <?php } ?>
  <?php } ?>
</div>
<?php if ($output['isOwnShop']) { ?>
<div class="alert alert-block mt10">
  <ul>
    <li>1、点击添加活动按钮可以添加拼团活动，点击管理按钮可以对拼团活动内的商品进行管理</li>
    <li>2、已经开始的活动不可编辑信息。</li>
  </ul>
</div>
<?php } else { ?>
<div class="alert alert-block mt10">
  <?php if(!empty($output['current_quota'])) { ?>
  <strong>套餐过期时间<?php echo $lang['wt_colon'];?></strong><strong style="color:#F00;"><?php echo date('Y-m-d H:i:s', $output['current_quota']['end_time']);?></strong>
  <?php } else { ?>
  <strong>当前没有可用套餐，请先购买套餐</strong>
  <?php } ?>
  <ul>
    <li>1、点击购买套餐和套餐续费按钮可以购买或续费套餐</li>
    <li>2、点击添加活动按钮可以添加拼团活动，点击管理按钮可以对拼团活动内的商品进行管理</li>
    <li>3、已经开始的活动不可编辑信息。</li>
    <li>4、<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>。</li>
  </ul>
</div>
<?php } ?>
<form method="get">
  <table class="search-form">
    <input type="hidden" name="w" value="store_sale_pingou" />
    <input type="hidden" name="t" value="list" />
    <tr>
      <td>&nbsp;</td>
      <th class="w110">活动名称</th>
      <td class="w160"><input type="text" class="text w150" name="name" value="<?php echo $_GET['name'];?>"/></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" />
        </label></td>
    </tr>
  </table>
</form>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="tl">活动名称</th>
      <th class="w180">开始时间</th>
      <th class="w180">结束时间</th>
      <th class="w80">参团人数</th>
      <th class="w80">状态</th>
      <th class="w160"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <?php foreach($output['list'] as $key=>$val){?>
  <tbody id="list">
    <tr class="bd-line">
      <td></td>
      <td class="tl"><dl class="goods-name">
          <dt><?php echo $val['pingou_name'];?></dt>
        </dl></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['start_time']);?></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['end_time']);?></td>
      <td><?php echo $val['min_num'];?></td>
      <td><?php echo $val['end_time'] > TIMESTAMP ? $output['state_array'][$val['state']]:'已结束'; ?></td>
      <td class="nscs-table-handle tr"><?php if($val['state'] && $val['start_time'] > TIMESTAMP) { ?>
        <span> <a href="index.php?w=store_sale_pingou&t=pingou_edit&pingou_id=<?php echo $val['pingou_id'];?>" class="btn-bluejeans"> <i class="icon-edit"></i>
        <p><?php echo $lang['wt_edit'];?></p>
        </a> </span>
        <?php } ?>
        <?php if($val['state'] && $val['end_time'] > TIMESTAMP) { ?>
        <span> <a href="index.php?w=store_sale_pingou&t=pingou_manage&pingou_id=<?php echo $val['pingou_id'];?>" class="btn-mint"> <i class="icon-cog"></i>
        <p><?php echo $lang['wt_manage'];?></p>
        </a> </span> 
        <?php } ?>
        <span> <a href="javascript:;" wttype="btn_del_pingou" data-id=<?php echo $val['pingou_id'];?> class="btn-grapefruit"> <i class="icon-trash"></i>
        <p><?php echo $lang['wt_delete'];?></p>
        </a> </span></td>
    </tr>
    <?php }?>
    <?php }else{?>
    <tr id="list_norecord">
      <td class="norecord" colspan="20"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['list']) && is_array($output['list'])){?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<form id="submit_form" action="" method="post" >
  <input type="hidden" id="id" name="pingou_id" value="">
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[wttype="btn_del_pingou"]').on('click', function() {
            if(confirm('<?php echo $lang['wt_ensure_del'];?>')) {
                var action = "<?php echo urlShop('store_sale_pingou', 'pingou_del');?>";
                var id = $(this).attr('data-id');
                $('#submit_form').attr('action', action);
                $('#id').val(id);
                ajaxpost('submit_form', '', '', 'onerror');
            }
        });
    });
</script> 
