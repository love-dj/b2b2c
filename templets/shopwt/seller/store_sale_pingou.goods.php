<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>

<ul class="goods-list">
  <?php foreach($output['goods_list'] as $key=>$val){?>
  <li>
    <div class="goods-thumb"> <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val, 240);?>"/></a></div>
    <dl class="goods-content">
      <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a> </dt>
      <dd>销售价格：<?php echo $lang['currency'].wtPriceFormat($val['goods_price']);?>
    </dl>
    <a wttype="btn_add_pingou_goods" data-storage="<?php echo $val['goods_storage'];?>" data-goods-id="<?php echo $val['goods_id'];?>" data-goods-name="<?php echo $val['goods_name'];?>" data-goods-img="<?php echo thumb($val, 240);?>" data-goods-price="<?php echo $val['goods_price'];?>" href="javascript:void(0);" class="wtbtn-mini">选择商品/修改拼团价</a> </li>
  <?php } ?>
</ul>
<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } else { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>
<div id="dialog_add_pingou_goods" style="display:none;">
  <input id="dialog_goods_id" type="hidden">
  <input id="dialog_input_goods_price" type="hidden">
  <div class="eject_con">
    <div id="dialog_add_pingou_goods_error" class="alert alert-error">
      <label for="dialog_pingou_price" class="error" ><i class='icon-exclamation-sign'></i>拼团价格不能为空，且必须小于商品价格</label>
    </div>
    <div class="selected-goods-content">
      <div class="goods-thumb"><img id="dialog_goods_img" src="" alt=""></div>
      <dl class="goods-content">
        <dt id="dialog_goods_name"></dt>
        <dd>销售价格：<strong class="red"><?php echo $lang['currency']; ?><font id="dialog_goods_price"></font></strong></dd>
        <dd>库存：<span id="dialog_goods_storage"></span> 件</dd>
      </dl>
    </div>
    <dl>
      <dt>拼团价格：</dt>
      <dd>
        <input id="dialog_pingou_price" type="text" class="text w70">
        <em class="add-on"><i class="icon-renminbi"></i></em>
        <p class="hint">拼团价应低于正常商品售价。</p>
      </dd>
    </dl>
	    <dl>
      <dt>限购数量：</dt>
      <dd>
        <input id="dialog_goods_maxnum" type="text" class="text w70">
       
        <p class="hint">
    每个买家ID可拼团的最大数量，不限数量请填 "0"。</p>
      </dd>
    </dl>
    <div class="eject_con">
      <div class="bottom">
        <label class="submit-border"><a id="btn_submit" class="submit" href="javascript:void(0);">提交</a></label>
      </div>
    </div>
  </div>
</div>
