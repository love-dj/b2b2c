<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="w" value="store_goods_offline" />
    <input type="hidden" name="t" value="index" />
    <input type="hidden" name="type" value="lock_up" />
    <tr>
      <td>&nbsp;</td>
      <th>供货商</th>
      <td class="w160"><select name="sup_id" class="w150">
        <option value="0"><?php echo $lang['wt_please_choose'];?></option>
        <?php if (is_array($output['supplier_list'])) {?>
        <?php foreach ($output['supplier_list'] as $val) {?>
        <option value="<?php echo $val['sup_id'];?>" <?php if ($_GET['sup_id'] == $val['sup_id']) {?>selected<?php }?>><?php echo $val['sup_name'];?></option>
        <?php }?>
        <?php }?>
      </select></td>
      <th><?php echo $lang['store_goods_index_store_goods_class'];?></th>
      <td class="w160"><select name="stc_id" class="w150">
          <option value="0"><?php echo $lang['wt_please_choose'];?></option>
          <?php if(is_array($output['store_goods_class']) && !empty($output['store_goods_class'])){?>
          <?php foreach ($output['store_goods_class'] as $val) {?>
          <option value="<?php echo $val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $val['stc_id']){ echo 'selected=selected';}?>><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $child_val['stc_id']){ echo 'selected=selected';}?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
        </select></td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['type'] == 0) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_name'];?></option>
          <option value="1" <?php if ($_GET['type'] == 1) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_no'];?></option>
          <option value="2" <?php if ($_GET['type'] == 2) {?>selected="selected"<?php }?>>SPU</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="wtsc-default-table">
  <thead>
    <tr wt_type="table_header">
      <th class="w30"></th>
      <th class="w50"></th>
      <th><?php echo $lang['store_goods_index_goods_name'];?></th>
      <th class="w180"><?php echo $lang['store_goods_index_close_reason'];?></th>
      <th class="w100"><?php echo $lang['store_goods_index_price'];?></th>
      <th class="w100"><?php echo $lang['store_goods_index_stock'];?></th>
      <th class="w100"><?php echo $lang['wt_handle'];?></th>
    </tr>
    <?php  if (!empty($output['goods_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="10"><label for="all"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0);" class="wtbtn-mini" wt_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'drop_goods');?>" name="commonid" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['goods_list'])) { ?>
    <?php foreach ($output['goods_list'] as $val) { ?>
    <tr>
      <th class="tc"><input type="checkbox" class="checkitem tc" value="<?php echo $val['goods_commonid']; ?>"/></th>
      <th colspan="20">SPU：<?php echo $val['goods_commonid'];?></th>
    </tr>
    <tr>
      <td class="trigger"><i class="icon-plus-sign" wttype="ajaxGoodsList" data-comminid="<?php echo $val['goods_commonid'];?>"></i></td>
      <td><div class="pic-thumb">
        <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt style="max-width: 450px !important;">
            <?php if ($val['is_virtual'] ==1) {?>
            <span class="type-virtual" title="虚拟兑换商品">虚拟</span>
            <?php }?>
            <?php if ($val['is_fcode'] ==1) {?>
            <span class="type-fcode" title="F码优先购买商品">F码</span>
            <?php }?>
            <?php if ($val['is_presell'] ==1) {?>
            <span class="type-presell" title="预先发售商品">预售</span>
            <?php }?>
            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><?php echo $val['goods_name']; ?></a></dt>
          <dd><?php echo $lang['store_goods_index_goods_no'].$lang['wt_colon'];?><?php echo $val['goods_serial'];?></dd>
          <dd class="serve"> <span class="<?php if ($val['goods_commend'] == 1) { echo 'open';}?>" title="店铺推荐商品"><i class="commend">荐</i></span> <span class="<?php if ($val['mobile_body'] != '') { echo 'open';}?>" title="手机端商品详情"><i class="icon-tablet"></i></span> <span class="" title="商品页面二维码"><i class="icon-qrcode"></i>
            <div class="QRcode"><a target="_blank" href="<?php echo goodsQRCode(array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id'], 'store_id' => $_SESSION['store_id']));?>">下载标签</a>
              <p><img src="<?php echo goodsQRCode(array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id'], 'store_id' => $_SESSION['store_id']));?>"/></p>
            </div>
            </span> </dd>
        </dl></td>
      <td><?php echo $val['goods_stateremark'];?></td>
      <td><span><?php echo $lang['currency'].wtPriceFormat($val['goods_price']); ?></span></td>
      <td><span><?php echo $output['storage_array'][$val['goods_commonid']]['sum'].$lang['piece']; ?></span></td>
      <td class="nscs-table-handle"><?php if ($val['goods_lock'] == 0) {?>
        <span><a href="<?php echo urlShop('store_goods_online', 'edit_goods', array('commonid' => $val['goods_commonid']));?>" class="btn-bluejeans"><i class="icon-edit"></i>
        <p><?php echo $lang['wt_edit'];?></p>
        </a></span> <span><a href="javascript:void(0);" onclick="ajax_get_confirm('<?php echo $lang['wt_ensure_del'];?>', '<?php echo urlShop('store_goods_online', 'drop_goods', array('commonid' => $val['goods_commonid']));?>');" class="btn-grapefruit"><i class="icon-trash"></i>
        <p><?php echo $lang['wt_del'];?></p>
        </a></span>
        <?php } else {?>
        <span class="tip" title="该商品参加抢购活动期间不能进行编辑及删除等操作,可以编辑赠品和推荐组合"><a href="<?php if ($val['is_virtual'] ==1 ) {echo 'javascript:void(0);';} else {echo urlShop('store_goods_online', 'add_gift', array('commonid' => $val['goods_commonid']));}?>" class="btn-darkgray"><i class="icon-lock"></i>
        <p>锁定</p>
        </a></span>
        <?php }?></td>
    </tr>
    <tr style="display:none;"><td colspan="20"><div class="wtsc-goods-sku ps-container"></div></td></tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
    <?php  if (!empty($output['goods_list'])) { ?>
  <tfoot>
    <tr>
      <th class="tc"><input type="checkbox" id="all2" class="checkall"/></th>
      <th colspan="10"><label for="all2"><?php echo $lang['wt_select_all'];?></label>
        <a href="javascript:void(0);" class="wtbtn-mini" wt_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'drop_goods');?>" name="commonid" confirm="<?php echo $lang['wt_ensure_del'];?>"><i class="icon-trash"></i><?php echo $lang['wt_del'];?></a> 
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/store_goods_list.js" charset="utf-8"></script> 