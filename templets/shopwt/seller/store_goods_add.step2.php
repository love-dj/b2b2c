<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.charCount.js"></script>
<!--[if lt IE 8]>
  <script src="<?php echo STATIC_SITE_URL;?>/js/json2.js"></script>
<![endif]-->
<script src="<?php echo STATIC_SITE_URL;?>/js/store_goods_add.step2.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
#fixedNavBar { filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#CCFFFFFF', endColorstr='#CCFFFFFF');background:rgba(255,255,255,0.8); width: 90px; margin-left: 510px; border-radius: 4px; position: fixed; z-index: 999; top: 172px; left: 50%;}
#fixedNavBar h3 { font-size: 12px; line-height: 24px; text-align: center; margin-top: 4px;}
#fixedNavBar ul { width: 80px; margin: 0 auto 5px auto;}
#fixedNavBar li { margin-top: 5px;}
#fixedNavBar li a { font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 20px; background-color: #F5F5F5; color: #999; text-align: center; display: block;  height: 20px; border-radius: 10px;}
#fixedNavBar li a:hover { color: #FFF; text-decoration: none; background-color: #27a9e3;}
</style>

<div id="fixedNavBar">
<h3>页面导航</h3>
  <ul>
    <li><a id="demo1Btn" href="#demo1" class="demoBtn">基本信息</a></li>
    <li><a id="demo2Btn" href="#demo2" class="demoBtn">详情描述</a></li>
    <?php if ($output['goods_class']['gc_virtual'] == 1) {?>
    <li><a id="demo3Btn" href="#demo3" class="demoBtn">特殊商品</a></li>
    <?php }?>
    <li><a id="demo4Btn" href="#demo4" class="demoBtn">物流运费</a></li>
    <li><a id="demo5Btn" href="#demo5" class="demoBtn">发票信息</a></li>
    <li><a id="demo6Btn" href="#demo6" class="demoBtn">商品分销</a></li>
    <li><a id="demo6Btn" href="#demo7" class="demoBtn">其他信息</a></li>
  </ul>
</div>
<?php if ($output['edit_goods_sign']) {?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<?php } else {?>
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STEP.1</h6>
    <h2>选择商品分类</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STEP.2</h6>
    <h2>填写商品详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-camera-retro "></i>
    <h6>STEP.3</h6>
    <h2>上传商品图片</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-bbs"></i>
    <h6>STEP.4</h6>
    <h2>商品发布成功</h2>
  </li>
</ul>
<?php }?>
<div class="item-publish">
  <form method="post" enctype="multipart/form-data" id="goods_form" action="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'edit_save_goods');} else { echo urlShop('store_goods_add', 'save_goods');}?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $output['goods']['goods_commonid'];?>" />
    <input type="hidden" name="type_id" value="<?php echo $output['goods_class']['type_id'];?>" />
    <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'] ? $_GET['ref_url'] : getReferer();?>" />
    <div class="wtsc-form-goods">
      <h3 id="demo1"><?php echo $lang['store_goods_index_goods_base_info']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_class'].$lang['wt_colon'];?></dt>
        <dd id="gcategory"> <?php echo $output['goods_class']['gc_tag_name'];?> <a class="wtbtn" href="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'edit_class', array('commonid' => $output['goods']['goods_commonid'], 'ref_url' => getReferer())); } else { echo urlShop('store_goods_add', 'add_step_one'); }?>"><?php echo $lang['wt_edit'];?></a>
          <input type="hidden" id="cate_id" name="cate_id" value="<?php echo $output['goods_class']['gc_id'];?>" class="text" />
          <input type="hidden" name="cate_name" value="<?php echo $output['goods_class']['gc_tag_name'];?>" class="text"/>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['store_goods_index_goods_name'].$lang['wt_colon'];?></dt>
        <dd>
          <input name="g_name" type="text" class="text w400" value="<?php echo $output['goods']['goods_name']; ?>" />
          <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_goods_name_help'];?></p>
        </dd>
      </dl>
      <dl>
        <dt>商品卖点<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <textarea name="g_jingle" class="textarea h60 w400"><?php echo $output['goods']['goods_jingle']; ?></textarea>
          <span></span>
          <p class="hint">商品卖点最长不能超过140个汉字</p>
        </dd>
      </dl>
      <dl>
        <dt wt_type="no_spec"><i class="required">*</i><?php echo $lang['store_goods_index_store_price'].$lang['wt_colon'];?></dt>
        <dd wt_type="no_spec">
          <input name="g_price" value="<?php echo wtPriceFormat($output['goods']['goods_price']); ?>" type="text"  class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_store_price_help'];?>，且不能高于市场价。<br>
            此价格为商品实际销售价格，如果商品存在规格，该价格显示最低价格。</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>市场价<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input name="g_marketprice" value="<?php echo wtPriceFormat($output['goods']['goods_marketprice']); ?>" type="text" class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_store_price_help'];?>，此价格仅为市场参考售价，请根据该实际情况认真填写。</p>
        </dd>
      </dl>
      <dl>
        <dt>成本价<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input name="g_costprice" value="<?php echo wtPriceFormat($output['goods']['goods_costprice']); ?>" type="text" class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">价格必须是0.00~9999999之间的数字，此价格为商户对所销售的商品实际成本价格进行备注记录，非必填选项，不会在前台销售页面中显示。</p>
        </dd>
      </dl>
      <dl>
        <dt>折扣<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input name="g_discount" value="<?php echo $output['goods']['goods_discount']; ?>" type="text" class="text w60" readonly style="background:#E7E7E7 none;" /><em class="add-on">%</em>
          <p class="hint">根据销售价与市场价比例自动生成，不需要编辑。</p>
        </dd>
      </dl>
      <dl>
        <dt wt_type="no_spec"><i class="required">*</i><?php echo $lang['store_goods_index_store_step_price'].$lang['wt_colon'];?></dt>
        <dd>
          <ul class="wtsc-form-radio-list">
          
         
          
			<?php 
			if ($output['goods']['is_bat'] == 1)
			 {
			 	
				$kq3="";
				 $kq1="checked";
				 $kq2="";
			 } 
			 else if ($output['goods']['is_bat'] == 2)
			 {
				 $kq1="";
				 $kq3="";
				 $kq2="checked";
			 }
			 else
			 {
				$kq3="checked";
				$kq2="";
				$kq1="";
			 }
			?>
            <li>
              <input type="radio" name="is_bat" id="is_bat_2" value="0"  <?=$kq3?>>
              <label for="is_bat_2">零售及其它规则</label>
            </li>
            <li>
              <input type="radio" name="is_bat" id="is_bat_1" value="1"  <?=$kq1?>>
              <label for="is_bat_1">无规格阶梯价格</label>
            </li>
            <li>
              <input type="radio" name="is_bat" id="is_bat_0" value="2" <?=$kq2?>>
              <label for="is_bat_0">有规格阶梯价格</label>
            </li>
          </ul>
          <p class="hint vital">*此处开启可设置该商品批发阶梯价格，起批数量，从上到下一次递增，起批价格依次递减。（最多设置<?=$output['bat_max_num']?>个阶梯价格）</p>
        </dd>
      </dl>
<dl class="special-02" wttype="fbat_valid" <?php if ($output['goods']['is_bat'] == 0 || $output['goods']['is_bat'] == 2) {?>style="display:none;"<?php }?>>
        <dt wt_type="no_spec"><i class="required">*</i><?php echo "无规格起批设置".$lang['nc_colon'];?></dt>
        <dd wt_type="no_spec">
			<div id="step_bat">
			<?php
			$sp = 0;
			if(count($output['step_prices'])<=0)
			{
				$sp++;
			?>
				  <div style="margin-bottom:5px">数量：<input name="g_step_num_<?php echo $sp;?>" id="g_step_num_<?php echo $sp;?>" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_<?php echo $sp;?>" id="g_step_price_<?php echo $sp;?>" value="" type="text"  class="text w60" onblur="checkStep(this,2)"/><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
				  </div>
			<?php
			}
			else
			{
				if($output['goods']['is_bat']== 1)
				{
					foreach($output['step_prices'] as $step_price)
					{
						$sp++;
				?>
					  <div style="margin-bottom:5px">数量：<input name="g_step_num_<?php echo $sp;?>" id="g_step_num_<?php echo $sp;?>" value="<?php echo $step_price['step_l_num'];?>" type="text"  class="text w60" onblur="checkStep(this,1)"/><span></span> 价格：<input name="g_step_price_<?php echo $sp;?>" id="g_step_price_<?php echo $sp;?>" value="<?php echo $step_price['step_price'];?>" type="text"  class="text w60" onblur="checkStep(this,2)"/><em class="add-on"><i class="icon-renminbi"></i></em><?php if($sp>=2){?> <a name="remStep" id="remStep" href="#">删除</a><span></span><?php }?>
					  </div>
				<?php
					}
				}
				else{
					?>
					  <div style="margin-bottom:5px">数量：<input name="g_step_num_<?php echo $sp;?>" id="g_step_num_<?php echo $sp;?>" value="<?php echo $step_price['step_l_num'];?>" type="text"  class="text w60" onblur="checkStep(this,1)"/><span></span> 价格：<input name="g_step_price_<?php echo $sp;?>" id="g_step_price_<?php echo $sp;?>" value="<?php echo $step_price['step_price'];?>" type="text"  class="text w60" onblur="checkStep(this,2)"/><em class="add-on"><i class="icon-renminbi"></i></em><?php if($sp>=2){?> <a name="remStep" id="remStep" href="#">删除</a><span></span><?php }?>
					  </div>
				<?php
				}
			}
			?>
		    </div>
		     <div><a href="javascript:void(0);" id="addStepNoSpec" class="wtbtn">+新增区间
		       <input name="noSpecsnum" type="hidden" id="noSpecsnum" value="<?php echo $sp;?>" />
		     </a></div>
        </dd>
      </dl>
      <?php if(is_array($output['spec_list']) && !empty($output['spec_list'])){?>
      <?php $i = '0';?>
      <?php foreach ($output['spec_list'] as $k=>$val){?>
      <dl wt_type="spec_group_dl_<?php echo $i;?>" wttype="spec_group_dl" <?php if ($output['goods']['is_bat'] == 1) {?>style="display:none;"<?php }?> class="spec-bg" <?php if($k == '1'){?>spec_img="t"<?php }?>>
        <dt>
          <input name="sp_name[<?php echo $k;?>]" type="text" class="text w60 tip2 tr" title="自定义规格类型名称，规格值名称最多不超过4个字" value="<?php if (isset($output['goods']['spec_name'][$k])) { echo $output['goods']['spec_name'][$k];} else {echo $val['sp_name'];}?>" maxlength="4" wttype="spec_name" data-param="{id:<?php echo $k;?>,name:'<?php echo $val['sp_name'];?>'}"/>
          <?php echo $lang['wt_colon']?></dt>
        <dd <?php if($k == '1'){?>wttype="sp_group_val"<?php }?>>
          <ul class="spec">
            <?php if(is_array($val['value'])){?>
            <?php foreach ($val['value'] as $v) {?>
            <li><span wttype="input_checkbox">
              <input type="checkbox" value="<?php echo $v['sp_value_name'];?>" wt_type="<?php echo $v['sp_value_id'];?>" <?php if($k == '1'){?>class="sp_val"<?php }?> name="sp_val[<?php echo $k;?>][<?php echo $v['sp_value_id']?>]">
              </span><span wttype="pv_name"><?php echo $v['sp_value_name'];?></span></li>
            <?php }?>
            <?php }?>
            <li data-param="{gc_id:<?php echo $output['goods_class']['gc_id'];?>,sp_id:<?php echo $k;?>,url:'<?php echo urlShop('store_goods_add', 'ajax_add_spec');?>'}">
              <div wttype="specAdd1"><a href="javascript:void(0);" class="wtbtn" wttype="specAdd"><i class="icon-plus"></i>添加规格值</a></div>
              <div wttype="specAdd2" style="display:none;">
                <input class="text w60" type="text" placeholder="规格值名称" maxlength="40">
                <a href="javascript:void(0);" wttype="specAddSubmit" class="wtbtn wtbtn-aqua ml5 mr5">确认</a><a href="javascript:void(0);" wttype="specAddCancel" class="wtbtn wtbtn-bittersweet">取消</a></div>
            </li>
          </ul>
          <?php if($output['edit_goods_sign'] && $k == '1'){?>
          <p class="hint">添加或取消颜色规格时，提交后请编辑图片以确保商品图片能够准确显示。</p>
          <?php }?>
        </dd>
      </dl>
      <?php $i++;?>
      <?php }?>
      <?php }?>
      <dl wt_type="spec_dl" class="spec-bg" style="display:none; overflow: visible;">
        <dt><?php echo $lang['srore_goods_index_goods_stock_set'].$lang['wt_colon'];?></dt>
        <dd class="spec-dd">
          <div wttype="spec_div">
          <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <?php if(is_array($output['spec_list']) && !empty($output['spec_list'])){?>
              <?php foreach ($output['spec_list'] as $k=>$val){?>
            <th wttype="spec_name_<?php echo $k;?>"><?php if (isset($output['goods']['spec_name'][$k])) { echo $output['goods']['spec_name'][$k];} else {echo $val['sp_name'];}?></th>
              <?php }?>
              <?php }?>
              <th class="w90"><span class="red">*</span>市场价
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="wtbtn-mini" data-type="marketprice">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w90"><span class="red">*</span><?php echo $lang['store_goods_index_price'];?>
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="wtbtn-mini" data-type="price">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w60"><span class="red">*</span><?php echo $lang['store_goods_index_stock'];?>
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置库存：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="wtbtn-mini" data-type="stock">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w70">预警值
                <div class="batch"><i class="icon-edit" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置预警值：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="wtbtn-mini" data-type="alarm">设置</a><span class="arrow"></span></div>
                </div></th>
              <th class="w100"><?php echo $lang['store_goods_index_goods_no'];?></th>
              <th class="w100">商品条形码</th>
                </thead>
            <tbody wt_type="spec_table">
            </tbody>
          </table>
          </div>
          <p class="hint">点击<i class="icon-edit"></i>可批量修改所在列的值。<br>当规格值较多时，可在操作区域通过滑动滚动条查看超出隐藏区域。</p>
        </dd>
      </dl>
      <dl>
        <dt wt_type="no_spec"><i class="required">*</i><?php echo $lang['store_goods_index_goods_stock'].$lang['wt_colon'];?></dt>
        <dd wt_type="no_spec">
          <input name="g_storage" value="<?php echo $output['goods']['g_storage'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_goods_stock_help'];?></p>
        </dd>
      </dl>
      <dl>
        <dt>库存预警值<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input name="g_alarm" value="<?php echo $output['goods']['goods_storage_alarm'];?>" type="text" class="text w60" />
          <span></span>
          <p class="hint">设置最低库存预警值。当库存低于预警值时商家中心商品列表页库存列红字提醒。<br>
            请填写0~255的数字，0为不预警。</p>
        </dd>
      </dl>
      <dl>
        <dt wt_type="no_spec"><?php echo $lang['store_goods_index_goods_no'].$lang['wt_colon'];?></dt>
        <dd wt_type="no_spec">
          <p>
            <input name="g_serial" value="<?php echo $output['goods']['goods_serial'];?>" type="text" class="text" />
          </p>
          <p class="hint"><?php echo $lang['store_goods_index_goods_no_help'];?></p>
        </dd>
      </dl>
      <dl>
        <dt wt_type="no_spec">商品条形码：</dt>
        <dd wt_type="no_spec">
          <p>
            <input name="g_barcode" value="<?php echo $output['goods']['goods_barcode'];?>" type="text" class="text" />
          </p>
          <p class="hint">请填写商品条形码下方数字。</p>
        </dd>
      </dl>
      <dl>
        <dt>商品视频：</dt>
        <dd>
          <div class="upload-thumb">
          <?php if(!empty($output['goods']['goods_video'])) { ?>
          <video src="<?php echo goodsVideoPath($output['goods']['goods_video'],$output['goods']['store_id']);  ?>" width="100" height="100">
            <img width="240" height="240" src="<?php echo upload_site_url.'/'.ATTACH_COMMON.'/'.'default_video.gif';?>">
          </video>
          <?php } ?>
          </div>
          <input id="file" name="goods_video_file" type="file" class="type-file-file">
          <input type="hidden" name="video_file" value="<?php echo $output['goods']['goods_video']; ?>">
          <p class="hint">上传商品视频；支持mp4格式上传，建议使用
            <font color="red">大小不超过10M的视频</font>，上传后的视频将会自动保存在视频空间的默认分类中。</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['store_goods_album_goods_pic'].$lang['wt_colon'];?></dt>
        <dd>
          <div class="wtsc-goods-default-pic">
            <div class="goodspic-uplaod">
              <div class="upload-thumb"> <img wttype="goods_image" src="<?php echo thumb($output['goods'], 240);?>"/> </div>
              <input type="hidden" name="image_path" id="image_path" wttype="goods_image" value="<?php echo $output['goods']['goods_image']?>" />
              <span></span>
              <p class="hint"><?php echo $lang['store_goods_step2_description_one'];?><?php printf($lang['store_goods_step2_description_two'],intval(C('image_max_filesize'))/1024);?></p>
              <div class="handle">
                <div class="wtsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="goods_image" id="goods_image">
                  </span>
                  <p><i class="icon-upload-alt"></i>图片上传</p>
                  </a> </div>
                <a class="wtbtn mt5" wttype="show_image" href="<?php echo urlShop('store_album', 'pic_list', array('item'=>'goods'));?>"><i class="icon-picture"></i>从图片空间选择</a> <a href="javascript:void(0);" wttype="del_goods_demo" class="wtbtn mt5" style="display: none;"><i class="icon-bbs-arrow-up"></i>关闭相册</a></div>
            </div>
          </div>
          <div id="demo"></div>
        </dd>
      </dl>
      <h3 id="demo2"><?php echo $lang['store_goods_index_goods_detail_info']?></h3>
      <dl style="overflow: visible;">
        <dt><?php echo $lang['store_goods_index_goods_brand'].$lang['wt_colon'];?></dt>
        <dd>
          <div class="wtsc-brand-select">
            <div class="selection">
              <input name="b_name" id="b_name" value="<?php echo $output['goods']['brand_name'];?>" type="text" class="text w180" readonly /><input type="hidden" name="b_id" id="b_id" value="<?php echo $output['goods']['brand_id'];?>" /><em class="add-on"><i class="icon-collapse"></i></em></div>
            <div class="wtsc-brand-select-container">
              <div class="brand-index" data-tid="<?php echo $output['goods_class']['type_id'];?>" data-url="<?php echo urlShop('store_goods_add', 'ajax_get_brand');?>">
                <div class="letter" wttype="letter">
                  <ul>
                    <li><a href="javascript:void(0);" data-letter="all">全部</a></li>
                    <li><a href="javascript:void(0);" data-letter="A">A</a></li>
                    <li><a href="javascript:void(0);" data-letter="B">B</a></li>
                    <li><a href="javascript:void(0);" data-letter="C">C</a></li>
                    <li><a href="javascript:void(0);" data-letter="D">D</a></li>
                    <li><a href="javascript:void(0);" data-letter="E">E</a></li>
                    <li><a href="javascript:void(0);" data-letter="F">F</a></li>
                    <li><a href="javascript:void(0);" data-letter="G">G</a></li>
                    <li><a href="javascript:void(0);" data-letter="H">H</a></li>
                    <li><a href="javascript:void(0);" data-letter="I">I</a></li>
                    <li><a href="javascript:void(0);" data-letter="J">J</a></li>
                    <li><a href="javascript:void(0);" data-letter="K">K</a></li>
                    <li><a href="javascript:void(0);" data-letter="L">L</a></li>
                    <li><a href="javascript:void(0);" data-letter="M">M</a></li>
                    <li><a href="javascript:void(0);" data-letter="N">N</a></li>
                    <li><a href="javascript:void(0);" data-letter="O">O</a></li>
                    <li><a href="javascript:void(0);" data-letter="P">P</a></li>
                    <li><a href="javascript:void(0);" data-letter="Q">Q</a></li>
                    <li><a href="javascript:void(0);" data-letter="R">R</a></li>
                    <li><a href="javascript:void(0);" data-letter="S">S</a></li>
                    <li><a href="javascript:void(0);" data-letter="T">T</a></li>
                    <li><a href="javascript:void(0);" data-letter="U">U</a></li>
                    <li><a href="javascript:void(0);" data-letter="V">V</a></li>
                    <li><a href="javascript:void(0);" data-letter="W">W</a></li>
                    <li><a href="javascript:void(0);" data-letter="X">X</a></li>
                    <li><a href="javascript:void(0);" data-letter="Y">Y</a></li>
                    <li><a href="javascript:void(0);" data-letter="Z">Z</a></li>
                    <li><a href="javascript:void(0);" data-letter="0-9">其他</a></li>
                    <li><a href="javascript:void(0);" data-empty="0">清空</a></li>
                  </ul>
                </div>
                <div class="search" wttype="search">
                  <input name="search_brand_keyword" id="search_brand_keyword" type="text" class="text" placeholder="品牌名称关键字查找"/><a href="javascript:void(0);" class="wtbtn-mini" style="vertical-align: top;">Go</a></div>
              </div>
              <div class="brand-list" wttype="brandList">
                <ul wttype="brand_list">
                  <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])){?>
                  <?php foreach($output['brand_list'] as $val) { ?>
                  <li data-id='<?php echo $val['brand_id'];?>'data-name='<?php echo $val['brand_name'];?>'><em><?php echo $val['brand_initial'];?></em><?php echo $val['brand_name'];?></li>
                  <?php } ?>
                  <?php }?>
                </ul>
              </div>
              <div class="no-result" wttype="noBrandList" style="display: none;">没有符合"<strong>搜索关键字</strong>"条件的品牌</div>
              <div class="tc"><a href="javascript:void(0);" class="wtbtn-mini" onclick="$(this).parents('.wtsc-brand-select-container:first').hide();">关闭品牌列表</a></div>
            </div>
            
          </div>
        </dd>
      </dl>
      <?php if(is_array($output['attr_list']) && !empty($output['attr_list'])){?>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_attr'].$lang['wt_colon']; ?></dt>
        <dd>
          <?php foreach ($output['attr_list'] as $k=>$val){?>
          <span class="property">
          <label class="mr5"><?php echo $val['attr_name']?></label>
          <input type="hidden" name="attr[<?php echo $k;?>][name]" value="<?php echo $val['attr_name']?>" />
          <?php if(is_array($val) && !empty($val)){?>
          <select name="" attr="attr[<?php echo $k;?>][__WT__]" wt_type="attr_select">
            <option value='不限' wt_type='0'>不限</option>
            <?php foreach ($val['value'] as $v){?>
            <option value="<?php echo $v['attr_value_name']?>" <?php if(isset($output['attr_checked']) && in_array($v['attr_value_id'], $output['attr_checked'])){?>selected="selected"<?php }?> wt_type="<?php echo $v['attr_value_id'];?>"><?php echo $v['attr_value_name'];?></option>
            <?php }?>
          </select>
          <?php }?>
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <?php if (!empty($output['custom_list'])) {?>
      <dl>
        <dt>自定义属性：</dt>
        <dd>
          <?php foreach ($output['custom_list'] as $val) {?>
          <span class="property">
            <label class="mr5"><?php echo $val['custom_name'];?></label>
            <input type="hidden" name="custom[<?php echo $val['custom_id'];?>][name]" value="<?php echo $val['custom_name'];?>" />
            <input class="text w60" type="text" name="custom[<?php echo $val['custom_id'];?>][value]" value="<?php if ($output['goods']['goods_custom'][$val['custom_id']]['value'] != '') {echo $output['goods']['goods_custom'][$val['custom_id']]['value'];}?>" />
          </span>
          <?php }?>
        </dd>
      </dl>
      <?php }?>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_desc'].$lang['wt_colon'];?></dt>
        <dd id="wtProductDetails">
          <div class="tabs">
            <ul class="ui-tabs-nav">
              <li class="ui-tabs-selected"><a href="#panel-1"><i class="icon-desktop"></i> 电脑端</a></li>
              <li class="selected"><a href="#panel-2"><i class="icon-mobile-phone"></i>手机端</a></li>
            </ul>
            <div id="panel-1" class="ui-tabs-panel">
              <?php showEditor('g_body',$output['goods']['goods_body'],'100%','480px','visibility:hidden;',"false",$output['editor_multimedia']);?>
              <div class="hr8">
                <div class="wtsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="add_album" multiple>
                  </span>
                  <p><i class="icon-upload-alt" data_type="0" wttype="add_album_i"></i>图片上传</p>
                  </a> </div>
                <a class="wtbtn mt5" wttype="show_desc" href="index.php?w=store_album&t=pic_list&item=des"><i class="icon-picture"></i><?php echo $lang['store_goods_album_insert_users_photo'];?></a> <a href="javascript:void(0);" wttype="del_desc" class="wtbtn mt5" style="display: none;"><i class=" icon-bbs-arrow-up"></i>关闭相册</a> </div>
              <p id="des_demo"></p>
            </div>
            <div id="panel-2" class="ui-tabs-panel ui-tabs-hide">
              <div class="wtsc-mobile-editor">
                <div class="pannel">
                  <div class="size-tip"><span wttype="img_count_tip">图片总数不得超过<em>20</em>张</span><i>|</i><span wttype="txt_count_tip">文字不得超过<em>5000</em>字</span></div>
                  <div class="control-panel" wttype="mobile_pannel">
                    <?php if (!empty($output['goods']['mb_body'])) {?>
                    <?php foreach ($output['goods']['mb_body'] as $val) {?>
                    <?php if ($val['type'] == 'text') {?>
                    <div class="module m-text">
                      <div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_edit" href="javascript:void(0);">编辑</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="text-div"><?php echo $val['value'];?></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php if ($val['type'] == 'image') {?>
                    <div class="module m-image">
                      <div class="tools"><a wttype="mp_up" href="javascript:void(0);">上移</a><a wttype="mp_down" href="javascript:void(0);">下移</a><a wttype="mp_rpl" href="javascript:void(0);">替换</a><a wttype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="image-div"><img src="<?php echo $val['value'];?>"></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <?php }?>
                  </div>
                  <div class="add-btn">
                    <ul class="btn-wrap">
                      <li><a href="javascript:void(0);" wttype="mb_add_img"><i class="icon-picture"></i>
                        <p>图片</p>
                        </a></li>
                      <li><a href="javascript:void(0);" wttype="mb_add_txt"><i class="icon-font"></i>
                        <p>文字</p>
                        </a></li>
                    </ul>
                  </div>
                </div>
                <div class="explain">
                  <dl>
                    <dt>1、基本要求：</dt>
                    <dd>（1）手机详情总体大小：图片+文字，图片不超过20张，文字不超过5000字；</dd>
                    <dd>建议：所有图片都是本宝贝相关的图片。</dd>
                  </dl><dl>
                    <dt>2、图片大小要求：</dt>
                    <dd>（1）建议使用宽度480 ~ 620像素、高度小于等于960像素的图片；</dd>
                    <dd>（2）格式为：JPG\JEPG\GIF\PNG；</dd>
                    <dd>举例：可以上传一张宽度为480，高度为960像素，格式为JPG的图片。</dd>
                  </dl><dl>
                    <dt>3、文字要求：</dt>
                    <dd>（1）每次插入文字不能超过500个字，标点、特殊字符按照一个字计算；</dd>
                    <dd>（2）请手动输入文字，不要复制粘贴网页上的文字，防止出现乱码；</dd>
                    <dd>（3）以下特殊字符“<”、“>”、“"”、“'”、“\”会被替换为空。</dd>
                    <dd>建议：不要添加太多的文字，这样看起来更清晰。</dd>
                  </dl>
                </div>
              </div>
              <div class="wtsc-mobile-edit-area" wttype="mobile_editor_area">
                <div wttype="mea_img" class="wtsc-mea-img" style="display: none;"></div>
                <div class="wtsc-mea-text" wttype="mea_txt" style="display: none;">
                  <p id="meat_content_count" class="text-tip"></p>
                  <textarea class="textarea valid" wttype="meat_content"></textarea>
                  <div class="button"><a class="wtbtn wtbtn-bluejeansjeansjeans" wttype="meat_submit" href="javascript:void(0);">确认</a><a class="wtbtn ml10" wttype="meat_cancel" href="javascript:void(0);">取消</a></div>
                  <a class="text-close" wttype="meat_cancel" href="javascript:void(0);">X</a>
                </div>
              </div>
              <input name="m_body" autocomplete="off" type="hidden" value='<?php echo $output['goods']['mobile_body'];?>'>
            </div>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>关联版式：</dt>
        <dd> <span class="mr50">
          <label>顶部版式</label>
          <select name="plate_top">
            <option>请选择</option>
            <?php if (!empty($output['plate_list'][1])) {?>
            <?php foreach ($output['plate_list'][1] as $val) {?>
            <option value="<?php echo $val['plate_id']?>" <?php if ($output['goods']['plateid_top'] == $val['plate_id']) {?>selected="selected"<?php }?>><?php echo $val['plate_name'];?></option>
            <?php }?>
            <?php }?>
          </select>
          </span> <span class="mr50">
          <label>底部版式</label>
          <select name="plate_bottom">
            <option>请选择</option>
            <?php if (!empty($output['plate_list'][0])) {?>
            <?php foreach ($output['plate_list'][0] as $val) {?>
            <option value="<?php echo $val['plate_id']?>" <?php if ($output['goods']['plateid_bottom'] == $val['plate_id']) {?>selected="selected"<?php }?>><?php echo $val['plate_name'];?></option>
            <?php }?>
            <?php }?>
          </select>
          </span> </dd>
      </dl>
      <!-- 只有可发布虚拟商品才会显示 S -->
      <?php if ($output['goods_class']['gc_virtual'] == 1) {?>
      <h3 id="demo3">特殊商品</h3>
      <dl class="special-01">
        <dt>虚拟商品<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <?php if ($output['edit_goods_sign']) {?>
            <input type="hidden" name="is_gv" value="<?php echo $output['goods']['is_virtual'];?>">
          <?php }?>
          <ul class="wtsc-form-radio-list">
            <li>
              <input type="radio" name="is_gv" value="1" id="is_gv_1" <?php if ($output['goods']['is_virtual'] == 1) {?>checked<?php }?> <?php if ($output['edit_goods_sign']) {?>disabled<?php }?>>
              <label for="is_gv_1">是</label>
            </li>
            <li>
              <input type="radio" name="is_gv" value="0" id="is_gv_0" <?php if ($output['goods']['is_virtual'] == 0) {?>checked<?php }?> <?php if ($output['edit_goods_sign']) {?>disabled<?php }?>>
              <label for="is_gv_0">否</label>
            </li>
          </ul>
          <p class="hint vital">*虚拟商品不能参加限时折扣和组合销售两种促销活动。也不能赠送赠品和推荐搭配。</p>
        </dd>
      </dl>
      <dl class="special-01" wttype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt><i class="required">*</i>虚拟商品有效期至<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input type="text" name="g_vindate" id="g_vindate" class="w80 text" value="<?php if($output['goods']['is_virtual'] == 1 && !empty($output['goods']['virtual_indate'])) { echo date('Y-m-d', $output['goods']['virtual_indate']);}?>"><em class="add-on"><i class="icon-calendar"></i></em>
          <span></span>
          <p class="hint">虚拟商品可兑换的有效期，过期后商品不能购买，电子兑换码不能使用。</p>
        </dd>
      </dl>
      <dl class="special-01" wttype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt><i class="required">*</i>虚拟商品购买上限<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <input type="text" name="g_vlimit" id="g_vlimit" class="w80 text" value="<?php if ($output['goods']['is_virtual'] == 1) {echo $output['goods']['virtual_limit'];}?>">
          <span></span>
          <p class="hint">请填写1~10之间的数字，虚拟商品最高购买数量不能超过10个。</p>
        </dd>
      </dl>
      <dl class="special-01" wttype="virtual_valid" <?php if ($output['goods']['is_virtual'] == 0) {?>style="display:none;"<?php }?>>
        <dt>支持过期退款<?php echo $lang['wt_colon'];?></dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_1" value="1" <?php if ($output['goods']['virtual_invalid_refund'] ==1) {?>checked<?php }?>>
              <label for="g_vinvalidrefund_1">是</label>
            </li>
            <li>
              <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_0" value="0" <?php if ($output['goods']['virtual_invalid_refund'] == 0) {?>checked<?php }?>>
              <label for="g_vinvalidrefund_0">否</label>
            </li>
          </ul>
          <p class="hint">兑换码过期后是否可以申请退款。</p>
        </dd>
      </dl>
      <?php }?>
      <!-- 只有可发布虚拟商品才会显示 E --> 
      <!-- 商品物流信息 S -->
      <h3 id="demo4"><?php echo $lang['store_goods_index_goods_transport']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_szd'].$lang['wt_colon']?></dt>
        <dd>
           <input type="hidden" value="<?php echo $output['goods']['areaid_3'] ? $output['goods']['areaid_3'] : $output['goods']['areaid_2'];?>" name="region" id="region">
           <input type="hidden" value="<?php echo $output['goods']['areaid_1'];?>" name="province_id" id="_area_1">
           <input type="hidden" value="<?php echo $output['goods']['areaid_2'];?>" name="city_id" id="_area_2">
           <input type="hidden" value="<?php echo $output['goods']['areaid_3'];?>" name="area_id" id="_area_3">
          </p>
        </dd>
      </dl>
      <dl wttype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>
        <dt><?php echo $lang['store_goods_index_goods_transfee_charge'].$lang['wt_colon']; ?></dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <input id="freight_0" wttype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['goods']['transport_id']) == 0) {?>checked="checked"<?php }?> value="0">
              <label for="freight_0">固定运费</label>
              <div wttype="div_freight" <?php if (intval($output['goods']['transport_id']) != 0) {?>style="display: none;"<?php }?>>
                <input id="g_freight" class="w50 text" wt_type='transport' type="text" value="<?php printf('%.2f', floatval($output['goods']['goods_freight']));?>" name="g_freight"><em class="add-on"><i class="icon-renminbi"></i></em> </div>
            </li>
            <li>
              <input id="freight_1" wttype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['goods']['transport_id']) != 0) {?>checked="checked"<?php }?> value="1">
              <label for="freight_1"><?php echo $lang['store_goods_index_use_tpl'];?></label>
              <div wttype="div_freight" <?php if (intval($output['goods']['transport_id']) == 0) {?>style="display: none;"<?php }?>>
                <input id="transport_id" type="hidden" value="<?php echo $output['goods']['transport_id'];?>" name="transport_id">
                <input id="transport_title" type="hidden" value="<?php echo $output['goods']['transport_title'];?>" name="transport_title">
                <span id="postageName" class="transport-name" <?php if ($output['goods']['transport_title'] != '' && intval($output['goods']['transport_id'])) {?>style="display: inline-block;"<?php }?>>
                    <?php echo $output['goods']['transport_title'];?>
                    </span>
                    <?php if (intval($output['transport']['goods_trans_type']) > 1) {?>
                    <span id="goods_trans_v">&nbsp;&nbsp;&nbsp;&nbsp;商品(重量/体积)：<input name="goods_trans_v" value="<?php echo $output['goods']['goods_trans_v'];?>" type="text" class="w30" />(kg/m³)</span>
                    <?php }?>
                
                <a href="JavaScript:void(0);" onclick="window.open('index.php?w=store_transport&type=select')" class="wtbtn" id="postageButton"><i class="icon-truck"></i><?php echo $lang['store_goods_index_select_tpl'];?></a> </div>
            </li>
          </ul>
          <p class="hint">运费设置为 0 元，前台商品将显示为免运费。</p>
        </dd>
      </dl>
      <!-- 商品物流信息 E -->
      <h3 id="demo5" wttype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>发票信息</h3>
      <dl wttype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>
        <dt>是否开增值税发票：</dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <label>
                <input name="g_vat" value="1" <?php if (!empty($output['goods']) && $output['goods']['goods_vat'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['wt_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_vat" value="0" <?php if (empty($output['goods']) || $output['goods']['goods_vat'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['wt_no'];?></label>
            </li>
          </ul>
          <p class="hint"></p>
        </dd>
      </dl>
	  
	  <h3 id="demo5" wttype="virtual_null" <?php if ($output['buy_return_isuse'] == 1) {?>style="display:none;"<?php }?>>单品消费返现设置</h3>
      <dl wttype="virtual_null" <?php if ($output['buy_return_isuse'] == 1) {?>style="display:none;"<?php }?>>
        <dt>开启单品消费返现：</dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <label>
                <input name="g_vat" value="1" <?php if (!empty($output['goods']) && $output['goods']['is_buy_return'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['wt_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_vat" value="0" <?php if (empty($output['goods']) || $output['goods']['is_buy_return'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['wt_no'];?></label>
            </li>
          </ul>
          <p class="hint"></p>
        </dd>
      </dl>

      <h3 id="demo6" <?php if ($output['distribution_isuse'] == 1) {?>style="display:none;"<?php }?>>商品分销设置</h3>
      <dl>
        <dt>开启独立分销佣金：</dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <label><input name="is_independent_bonus" value="1" <?php if (!empty($output['goods']) && $output['goods']['is_independent_bonus'] == 1) { ?>checked="checked" <?php } ?> type="radio" />开启</label>
            </li>
            <li>
              <label><input name="is_independent_bonus" value="0" <?php if (empty($output['goods']) || $output['goods']['is_independent_bonus'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>关闭</label>
            </li>
          </ul>
          <p class="hint">启用独立佣金设置，此商品拥有独自的佣金比例,不受分销商等级比例及默认设置限制</p>
        </dd>
      </dl>
      <dl id="set_commission" <?php if ($output['goods']['is_independent_bonus'] != 1) {?>style="display:none;"<?php }?>>
        <dt>设置分销佣金：</dt>
        <dd>
          <table class="table table-hover" style="width:100%;">
              <thead>
              <tr>
                  <th style="width: 15%">等级名称</th>
                  <th style="text-align: center;">一级分销佣金</th> 
                  <th style="text-align: center;">二级分销佣金</th>
                  <th style="text-align: center;">三级分销佣金</th>                                
              </tr>
              </thead>
              <tbody>
              <?php foreach($output['level_list'] as $value){ ?>
              <tr style="margin-bottom: 5px;border-collapse: inherit;border-spacing: 15px;">
                  <td><?php echo $value['level_name']?></td>
                  <td style="text-align: center;">
                      <div class="input-group">
                          <input type="text" name="level_commission[<?php echo $value['id']?>][first_level_rate]" class="text w40" value="<?php echo $output['levelcommission'][$value['id']]['first_level_rate']>0?$output['levelcommission'][$value['id']]['first_level_rate']:0;?>"> % 固定 <input type="text" name="level_commission[<?php echo $value['id']?>][first_level_money]" class="text w40" value="<?php echo $output['levelcommission'][$value['id']]['first_level_money']>0?$output['levelcommission'][$value['id']]['first_level_money']:0;?>"> 元
                      </div>
                  </td>
                  <td style="text-align: center;">
                      <div class="input-group">
                          <input type="text" name="level_commission[<?php echo $value['id']?>][second_level_rate]" class="text w40" value="<?php echo $output['levelcommission'][$value['id']]['second_level_rate']>0?$output['levelcommission'][$value['id']]['second_level_rate']:0;?>"> % 固定 <input type="text" name="level_commission[<?php echo $value['id']?>][second_level_money]" class="text w40 " value="<?php echo $output['levelcommission'][$value['id']]['second_level_money']>0?$output['levelcommission'][$value['id']]['second_level_money']:0;?>"> 元
                      </div>
                  </td>
                  <td style="text-align: center;">
                    <div class="input-group">
                        <input type="text" name="level_commission[<?php echo $value['id']?>][third_level_rate]" class="text w40" value="<?php echo $output['levelcommission'][$value['id']]['third_level_rate']>0?$output['levelcommission'][$value['id']]['third_level_rate']:0;?>"> % 固定 <input type="text" name="level_commission[<?php echo $value['id']?>][third_level_money]" class="text w40" value="<?php echo $output['levelcommission'][$value['id']]['third_level_money']>0?$output['levelcommission'][$value['id']]['third_level_money']:0;?>"> 元
                    </div>
                  </td>
              </tr>
              <?php }?>
              </tbody>
          </table>
          <p class="hint">如果比例为空或等于0，则使用固定规则，如果都为空或等于0则无分销佣金</p>
        </dd>
      </dl>

      <h3 id="demo7"><?php echo $lang['store_goods_index_goods_other_info']?></h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_store_goods_class'].$lang['wt_colon'];?></dt>
        <dd><span class="new_add"><a href="javascript:void(0)" id="add_sgcategory" class="wtbtn"><?php echo $lang['store_goods_index_new_class'];?></a> </span>
          <?php if (!empty($output['store_class_goods'])) { ?>
          <?php foreach ($output['store_class_goods'] as $v) { ?>
          <select name="sgcate_id[]" class="sgcategory">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if ($v==$val['stc_id']) { ?>selected="selected"<?php } ?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($v==$child_val['stc_id']) { ?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }?>
            <?php }?>
            <?php } ?>
          </select>
          <?php } ?>
          <?php } else { ?>
          <select name="sgcate_id[]" class="sgcategory">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>"><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }?>
            <?php }?>
            <?php } ?>
            <?php } ?>
          </select>
          <?php } ?>
          <p class="hint"><?php echo $lang['store_goods_index_belong_multiple_store_class'];?></p>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_show'].$lang['wt_colon'];?></dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <label>
                <input name="g_state" value="1" type="radio" <?php if (empty($output['goods']) || $output['goods']['goods_state'] == 1 || $output['goods']['goods_state'] == 10) {?>checked="checked"<?php }?> />
                <?php echo $lang['store_goods_index_immediately_sales'];?> </label>
            </li>
            <li>
              <label>
                <input name="g_state" value="0" type="radio" wttype="auto" />
                <?php echo $lang['store_goods_step2_start_time'];?> </label>
              <input type="text" class="w80 text" name="starttime" disabled="disabled" style="background:#E7E7E7 none;" id="starttime" value="<?php echo date('Y-m-d');?>" />
              <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_H" id="starttime_H">
                <?php foreach ($output['hour_array'] as $val){?>
                <option value="<?php echo $val;?>" <?php $sign_H = 0;if($val>=date('H') && $sign_H != 1){?>selected="selected"<?php $sign_H = 1;}?>><?php echo $val;?></option>
                <?php }?>
              </select>
              <?php echo $lang['store_goods_step2_hour'];?>
              <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_i" id="starttime_i">
                <?php foreach ($output['minute_array'] as $val){?>
                <option value="<?php echo $val;?>" <?php $sign_i = 0;if($val>=date('i') && $sign_i != 1){?>selected="selected"<?php $sign_i = 1;}?>><?php echo $val;?></option>
                <?php }?>
              </select>
              <?php echo $lang['store_goods_step2_minute'];?> </li>
            <li>
              <label>
                <input name="g_state" value="0" type="radio" <?php if (!empty($output['goods']) && $output['goods']['goods_state'] == 0) {?>checked="checked"<?php }?> />
                <?php echo $lang['store_goods_index_in_warehouse'];?> </label>
            </li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_recommend'].$lang['wt_colon'];?></dt>
        <dd>
          <ul class="wtsc-form-radio-list">
            <li>
              <label>
                <input name="g_commend" value="1" <?php if (empty($output['goods']) || $output['goods']['goods_commend'] == 1) { ?>checked="checked" <?php } ?> type="radio" />
                <?php echo $lang['wt_yes'];?></label>
            </li>
            <li>
              <label>
                <input name="g_commend" value="0" <?php if (!empty($output['goods']) && $output['goods']['goods_commend'] == 0) { ?>checked="checked" <?php } ?> type="radio"/>
                <?php echo $lang['wt_no'];?></label>
            </li>
          </ul>
          <p class="hint"><?php echo $lang['store_goods_index_recommend_tip'];?></p>
        </dd>
      </dl>
      <?php if (is_array($output['supplier_list'])) {?>
      <dl>
        <dt>供货商：</dt>
        <dd>
          <select name="sup_id">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php foreach ($output['supplier_list'] as $val) {?>
            <option value="<?php echo $val['sup_id'];?>" <?php if ($output['goods']['sup_id'] == $val['sup_id']) {?>selected<?php }?>><?php echo $val['sup_name']?></option>
            <?php }?>
          </select>
          <p class="hint">可以选择商品的供货商。</p>
        </dd>
      </dl>
      <?php }?>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" wttype="formSubmit" class="submit" value="<?php if ($output['edit_goods_sign']) {echo '提交';} else {?><?php echo $lang['store_goods_add_next'];?>，上传商品图片<?php }?>" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">
var SITEURL = "<?php echo BASE_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
var SHOP_TEMPLATES_URL = "<?php echo SHOP_TEMPLATES_URL;?>";
//批发价v6.5
function checkStep(obj,t)
{
	var sValue = obj.value;
	if(sValue &&sValue!=null&&typeof(sValue)!="undefined" && sValue!=0)
	{
		if(t==1)
		{
			var reg = /^[1-9]\d*$/;
			if (!reg.test(sValue)) {
				alert('请正确输入起批数量');
				return false;
			}
		}
		else
		{
			var isMoney = /^(([1-9][0-9]*)|(([0]\.\d{1,2}|[1-9][0-9]*\.\d{1,2})))$/;
			if (!isMoney.test(sValue)) {
				alert('请正确输入批发价格');
				return false;
			}
		}
	}
}

$(function(){

    // 防止重复提交 by 33h ao.co m
    var __formSubmit = false;
    $('input[wttype="formSubmit"]').click(function(){
        if (__formSubmit) {
            return false;
        }
        if($('#goods_form').valid()){
            __formSubmit = true;
        }
    });
	
//动态增加阶梯价格 v6.5	
	var stepDiv = $('#step_bat');
	<?php
	if (count($output['step_prices'])>0){
	?>
	var st = <?=$sp+1?>;
	<?php }
	else{
	?>
	var st = $('#step_bat').size() + 1;
	<?php }	?>
	$('#addStepNoSpec').live('click', function() {
		if(st<=<?=$output['bat_max_num']?>)//最多设置3个阶梯价格
		{
			var aN = st -1;
			var _add_step_num   =  $("#g_step_num_"+aN).val();
			var _add_step_price =  $("#g_step_price_"+aN).val();
			if((_add_step_num &&_add_step_num!=null&& typeof(_add_step_num)!="undefined" && _add_step_num!=0)&&(_add_step_price&&_add_step_price!=null && typeof(_add_step_price)!="undefined" && _add_step_price!=0))
			{
				if(st>=3)
				{
					var aaN = aN-1;
					if(parseInt($("#g_step_num_"+aaN).val())>parseInt(_add_step_num)||parseInt($("#g_step_price_"+aaN).val())<parseInt(_add_step_price))
					{
						alert("注意：\n1、起批数量依次递增\n2、批发价格应依次递减");
						return false;//直接返回
					}
				}
				$('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+st+'"  id="g_step_num_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+st+'" id="g_step_price_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,2)" /><em class="add-on"><i class="icon-renminbi"></i></em> <a name="remStep" id="remStep" href="#">删除</a> <span></span> </div>').appendTo(stepDiv);
				$("#noSpecsnum").val(st);
				st++;
				return false;
			}
			else
			{
				alert('必须设置起批量和价格');
			}
		}
		else
		{
			alert('已达到最大阶梯价格设置数量');
		}
	});

	$("#remStep").live('click', function() {
			if( st > 2 ) {
				$(this).parent('div').remove();
				st--;
			}
			return false;
	});	
    $.validator.addMethod('checkPrice', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if (_g_marketprice <= 0) {
            return true;
        }
        if (_g_price > _g_marketprice) {
            return false;
        }else {
            return true;
        }
    }, '');
    $('#goods_form').validate({
        errorPlacement: function(error, element){
            __formSubmit = false;
            $(element).nextAll('span').append(error);
        },
        <?php if ($output['edit_goods_sign']) {?>
        submitHandler:function(form){
            ajaxpost('goods_form', '', '', 'onerror');
        },
        <?php }?>
        rules : {
            g_name : {
                required    : true,
                minlength   : 3,
                maxlength   : 50
            },
            g_jingle : {
                maxlength   : 140
            },
            g_price : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_marketprice : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_costprice : {
                number      : true,
                min         : 0.00,
                max         : 9999999
            },
            g_storage  : {
                required    : true,
                digits      : true,
                min         : 0,
                max         : 999999999
            },
            image_path : {
                required    : true
            },
            g_vindate : {
                required    : function() {if ($("#is_gv_1").prop("checked")) {return true;} else {return false;}}
            },
			g_vlimit : {
				required	: function() {if ($("#is_gv_1").prop("checked")) {return true;} else {return false;}},
				range		: [1,10]
			},
			g_deliverdate : {
				required	: function () {if ($('#is_presell_1').prop("checked")) {return true;} else {return false;}}
			},
			g_step_num_1 : {
				number      : true,
                min         : 1,
                max         : 9999,
                required    : function() {if ($("#is_bat_1").prop("checked")) {return true;} else {return false;}}
            },
        },
        messages : {
            g_name  : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_null'];?>',
                minlength   : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_help'];?>',
                maxlength   : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_name_help'];?>'
            },
            g_jingle : {
                maxlength   : '<i class="icon-exclamation-sign"></i>商品卖点不能超过140个字符'
            },
            g_price : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_null'];?>',
                number      : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_error'];?>',
                min         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_interval'];?>',
                max         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_store_price_interval'];?>',
                checkPrice  : '<i class="icon-exclamation-sign"></i>商品价格不能高于市场价格'
            },
            g_marketprice : {
                required    : '<i class="icon-exclamation-sign"></i>请填写市场价',
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                checkPrice  : '<i class="icon-exclamation-sign"></i>市场价格不能低于商品价格'
            },
            g_costprice : {
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.00~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>请填写0.00~9999999之间的数字'
            },
            g_storage : {
                required    : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_null'];?>',
                digits      : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_error'];?>',
                min         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>',
                max         : '<i class="icon-exclamation-sign"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>'
            },
            image_path : {
                required    : '<i class="icon-exclamation-sign"></i>请设置商品主图'
            },
            g_vindate : {
                required    : '<i class="icon-exclamation-sign"></i>请选择有效期'
            },
			g_vlimit : {
				required	: '<i class="icon-exclamation-sign"></i>请填写1~10之间的数字',
				range		: '<i class="icon-exclamation-sign"></i>请填写1~10之间的数字'
			},
			g_deliverdate : {
				required	: '<i class="icon-exclamation-sign"></i>请选择有效期'
			},
			g_step_num_1 : {
				number      : '',
                min         : '',
				max         : '',
				required	: ''
			}
        }
    });
    <?php if (isset($output['goods'])) {?>
	setTimeout("setArea(<?php echo $output['goods']['areaid_1'];?>, <?php echo $output['goods']['areaid_2'];?>)", 1000);
	<?php }?>

  // 上传类型
  $('input[class="type-file-file"]').change(function(){
    var filepath=$(this).val();
    var extStart=filepath.lastIndexOf(".");
    var ext=filepath.substring(extStart,filepath.length).toUpperCase();
    if(ext!=".mp4"&&ext!=".MP4"){
      alert("视频限于mp4格式");
      $(this).attr('value','');
      return false;
    }
  });
});
// 按规格存储规格值数据
var spec_group_checked = [<?php for ($i=0; $i<$output['sign_i']; $i++){if($i+1 == $output['sign_i']){echo "''";}else{echo "'',";}}?>];
var str = '';
var V = new Array();

<?php for ($i=0; $i<$output['sign_i']; $i++){?>
var spec_group_checked_<?php echo $i;?> = new Array();
<?php }?>

$(function(){
  //独立佣金
  $('input[name="is_independent_bonus"]').click(function(){
    if(parseInt(this.value) == 1){
      $("#set_commission").css("display","block");
    }else{
      $("#set_commission").css("display","none");
    }
  });



	$('dl[wttype="spec_group_dl"]').on('click', 'span[wttype="input_checkbox"] > input[type="checkbox"]',function(){
		into_array();
		goods_stock_set();
		var spv_is_bat = $("input[name='is_bat']:checked").val();
		if(spv_is_bat==2){
			$('[wttype="batedit"]').show();
		}
		else{
			$('[wttype="batedit"]').hide();
		}
	});

	// 提交后不没有填写的价格或库存的库存配置设为默认价格和0
	// 库存配置隐藏式 里面的input加上disable属性
	$('input[type="submit"]').click(function(){
		$('input[data_type="price"]').each(function(){
			if($(this).val() == ''){
				$(this).val($('input[name="g_price"]').val());
			}
		});
		$('input[data_type="stock"]').each(function(){
			if($(this).val() == ''){
				$(this).val('0');
			}
		});
		$('input[data_type="alarm"]').each(function(){
			if($(this).val() == ''){
				$(this).val('0');
			}
		});
		if($('dl[wt_type="spec_dl"]').css('display') == 'none'){
			$('dl[wt_type="spec_dl"]').find('input').attr('disabled','disabled');
		}
	});
	
});

// 将选中的规格放入数组
function into_array(){
<?php for ($i=0; $i<$output['sign_i']; $i++){?>
		
		spec_group_checked_<?php echo $i;?> = new Array();
		$('dl[wt_type="spec_group_dl_<?php echo $i;?>"]').find('input[type="checkbox"]:checked').each(function(){
			i = $(this).attr('wt_type');
			v = $(this).val();
			c = null;
			if ($(this).parents('dl:first').attr('spec_img') == 't') {
				c = 1;
			}
			spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v,i,c];
		});

		spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;

<?php }?>
}
//批发价
$("#remStep2").live('click', function() {
		if( st > 2 ) {
			$(this).parent('div').remove();
			st--;
		}
		return false;
});	
// 生成库存配置
var st;	

function goods_stock_set(){
	var stepDivNew; //批发价
    //  店铺价格 商品库存改为只读
    $('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    $('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');

    $('dl[wt_type="spec_dl"]').show();
    str = '<tr>';
    <?php recursionSpec(0,$output['sign_i']);?>
    if(str == '<tr>'){
        //  店铺价格 商品库存取消只读
        $('input[name="g_price"]').removeAttr('readonly').css('background','');
        $('input[name="g_storage"]').removeAttr('readonly').css('background','');
        $('dl[wt_type="spec_dl"]').hide();
    }else{
        $('tbody[wt_type="spec_table"]').empty().html(str)
            .find('input[wt_type]').each(function(){
                s = $(this).attr('wt_type');
                try{$(this).val(V[s]);}catch(ex){$(this).val('');};
                if ($(this).attr('data_type') == 'marketprice' && $(this).val() == '') {
                    $(this).val($('input[name="g_marketprice"]').val());
                }
                if ($(this).attr('data_type') == 'price' && $(this).val() == ''){
                    $(this).val($('input[name="g_price"]').val());
                }
                if ($(this).attr('data_type') == 'stock' && $(this).val() == ''){
                    $(this).val('0');
                }
                if ($(this).attr('data_type') == 'alarm' && $(this).val() == ''){
                    $(this).val('0');
                }
            }).end()
            .find('input[data_type="stock"]').change(function(){
                computeStock();    // 库存计算
            }).end()
            .find('input[data_type="price"]').change(function(){
                computePrice();     // 价格计算
            }).end()
            .find('input[wt_type]').change(function(){
                s = $(this).attr('wt_type');
                V[s] = $(this).val();
			}).end()
			 .find('i[id="newbatprice"]').click(function(){
                $('.batch > .batch-input').hide();
                $(this).next().show();				
					var spec_id = $(this).attr('lang');
					var snc = spec_id+'|id';
				    var good_spec_id = $('input[wt_type=\"'+snc+'\"]').val();
					stepDivNew = $('#step_bat_spec_'+spec_id);
					stepDivNew.empty();
					var sn;
					var sn_o;
					$.ajax({   
						type: "POST",  
						dataType: "text",  
						url: 'index.php?w=store_goods_spec_step&t=TempGetSpecStepPrice',    
						data: "spec_id="+spec_id+"&good_id="+good_spec_id,                     
						success: function(msg) {
							if(eval(msg).length>0)
							{
								for(sn=0;sn<eval(msg).length;sn++)
								{
									sn_o = sn+1;
									$('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+spec_id+'_'+sn_o+'"  id="g_step_num_'+spec_id+'_'+sn_o+'" value="'+eval(msg)[sn]['step_l_num']+'" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+spec_id+'_'+sn_o+'" id="g_step_price_'+spec_id+'_'+sn_o+'" value="'+eval(msg)[sn]['step_price']+'" type="text"  class="text w60" onblur="checkStep(this,2)" /><em><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>').appendTo(stepDivNew);
								}
								st = sn+1;
							}
							else
							{
									stepDivNew.html('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+spec_id+'_1"  id="g_step_num_'+spec_id+'_1" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+spec_id+'_1" id="g_step_price_'+spec_id+'_1" value="" type="text"  class="text w60" onblur="checkStep(this,2)" /><em><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>');	
									st = 2;
							}
						}  
					});
            }).end()
			 .find('a[id="x"]').click(function(){
                $(this).parent().hide();
            }).end()
			 .find('div[id="SaveStepPrice"]').click(function(){
			   var sprc_s = $(this).attr('title');
			   var r=1;
			   for(var ssp=1;ssp<=<?=$output['bat_max_num']?>;ssp++)
			   {
			        var _s_add_step_num   =  $("#g_step_num_"+sprc_s+"_"+ssp).val();
					var _s_add_step_price =  $("#g_step_price_"+sprc_s+"_"+ssp).val();
					if((_s_add_step_num &&_s_add_step_num!=null&& typeof(_s_add_step_num)!="undefined" && _s_add_step_num!=0)&&(_s_add_step_price&&_s_add_step_price!=null && typeof(_s_add_step_price)!="undefined" && _s_add_step_price!=0))
					{
						var aaN = ssp+1;
		if(parseInt($("#g_step_num_"+sprc_s+"_"+aaN).val())<parseInt(_s_add_step_num)||parseInt($("#g_step_price_"+sprc_s+"_"+aaN).val())>parseInt(_s_add_step_price))
						{
							alert("注意：\n1、起批数量依次递增\n2、批发价格应依次递减");
							return false;//直接返回
						}
					}
			   }
			   var urldata ="spec="+sprc_s;
               for(var ssp_=1;ssp_<=<?=$output['bat_max_num']?>;ssp_++)
			   {
			   		var _s_add_step_num   =  $("#g_step_num_"+sprc_s+"_"+ssp_).val();
					var _s_add_step_price =  $("#g_step_price_"+sprc_s+"_"+ssp_).val();
					if((_s_add_step_num &&_s_add_step_num!=null&& typeof(_s_add_step_num)!="undefined" && _s_add_step_num!=0)&&(_s_add_step_price&&_s_add_step_price!=null && typeof(_s_add_step_price)!="undefined" && _s_add_step_price!=0))
					{
						urldata =urldata+"&step_num"+ssp_+"=" + _s_add_step_num + "&step_price"+ssp_+"=" + _s_add_step_price;
					}
			   }
			   
				$.ajax({   
					type: "POST",  
					dataType: "text",  
					url: 'index.php?w=store_goods_spec_step&t=TempSaveSpecStepPrice',      //提交到一般处理程序请求数据   
					data: urldata,                          
					success: function(msg) {
						if(msg<1)
						{
							r=0;
						}
					}  
				});
			   if(r==1)
			   {
			   		alert("保存完毕");
					$(this).parent().hide();
			   }
			   else
			   {
			   		alert("保存失败");
			   }
            }).end()
			 .find('div[id="addStep"]').click(function(){	
					var sprc_a = $(this).attr('title');
					if(st<=<?=$output['bat_max_num']?>)
					{
						var aN = st -1;
						var _add_step_num   =  $("#g_step_num_"+sprc_a+"_"+aN).val();
						var _add_step_price =  $("#g_step_price_"+sprc_a+"_"+aN).val();
						if((_add_step_num &&_add_step_num!=null&& typeof(_add_step_num)!="undefined" && _add_step_num!=0)&&(_add_step_price&&_add_step_price!=null && typeof(_add_step_price)!="undefined" && _add_step_price!=0))
						{
							if(st>=3)
							{
								var aaN = aN-1;
								if(parseInt($("#g_step_num_"+sprc_a+"_"+aaN).val())>parseInt(_add_step_num)||parseInt($("#g_step_price_"+sprc_a+"_"+aaN).val())<parseInt(_add_step_price))
								{
									alert("注意：\n1、起批数量依次递增\n2、批发价格应依次递减");
									return false;//直接返回
								}
							}
							$('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+sprc_a+'_'+st+'"  id="g_step_num_'+sprc_a+'_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+sprc_a+'_'+st+'" id="g_step_price_'+sprc_a+'_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,2)" /><em><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>').appendTo(stepDivNew);
							$("#snum").val(st);
							st++;
							return false;
						}
						else
						{
							alert('必须设置起批量和价格');
						}
					}
					else
					{
						alert('已达到最大阶梯价格设置数量');
					}
					//end
            });
    }
    $('div[wttype="spec_div"]').perfectScrollbar('update');
}
//删除阶梯价格个数
function dRemove(obj)
{
	if( st > 2 ) {
		$(obj).parent('div').remove();
		st--;
	}
	return false;
}

<?php 
/**
 * 
 * 
 *  生成需要的js循环。递归调用	PHP
 * 
 *  形式参考 （ 2个规格）
 *  $('input[type="checkbox"]').click(function(){
 *      str = '';
 *      for (var i=0; i<spec_group_checked[0].length; i++ ){
 *      td_1 = spec_group_checked[0][i];
 *          for (var j=0; j<spec_group_checked[1].length; j++){
 *              td_2 = spec_group_checked[1][j];
 *              str += '<tr><td>'+td_1[0]+'</td><td>'+td_2[0]+'</td><td><input type="text" /></td><td><input type="text" /></td><td><input type="text" /></td>';
 *          }
 *      }
 *      $('table[class="spec_table"] > tbody').empty().html(str);
 *  });
 */
function recursionSpec($len,$sign) {
    if($len < $sign){
        echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
        $len++;
        recursionSpec($len,$sign);
    }else{
        echo "var tmp_spec_td = new Array();\n";
        for($i=0; $i< $len; $i++){
            echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
        }
        echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
        echo "var spec_bunch = 'i_';\n";
        for($i=0; $i< $len; $i++){
            echo "spec_bunch += tmp_spec_td[".($i)."];\n";
        }
        echo "str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][goods_id]\" wt_type=\"'+spec_bunch+'|id\" value=\"\" />';";
		$xs="";
        for($i=0; $i< $len; $i++){
			$styleno="display:none";
			if($output['goods']['is_bat']==2)
			{
				$styleno="display:block";
			}
			$xs ="<i id=\"newbatprice\" wttype=\"batedit\" class=\"icon-edit\" title=\"设置批发\" lang='+spec_bunch+' style=\"margin-right:10px;".$styleno."\"></i>";
			
            echo "if (td_".($i+1)."[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_".($i+1)."[1]+'\" />';}";
			echo "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value=\"'+td_".($i+1)."[0]+'\" />'+td_".($i+1)."[0]+'&nbsp;&nbsp;<div class=\"batch\" style=\"z-index: inherit;\">  ".$xs."<div class=\"batch-input\" style=\"display:none;\"><h6>阶梯价格设置：</h6><a href=\"javascript:void(0)\" class=\"close\" id=\"x\" style=\"font-size: 11px; line-height: 12px; color: #000; text-decoration: none; background-color: #FFF; text-align: center; display: block; width: 12px; height: 12px; border-radius: 7px; border: solid 1px #000; top: -7px; right: -7px; position: absolute; z-index: 2;\">X</a><div id=\"step_bat_spec_'+spec_bunch+'\"  name=\"step_bat_spec_'+spec_bunch+'\"><div style=\"margin-bottom:5px\">数量：<input name=\"g_step_num_'+spec_bunch+'_1\" id=\"g_step_num_'+spec_bunch+'_1\" value=\"\" type=\"text\"  class=\"text w60\" onblur=\"checkStep(this,1)\" /><span></span> 价格：<input name=\"g_step_price_'+spec_bunch+'_1\" id=\"g_step_price_'+spec_bunch+'_1\" value=\"\" type=\"text\"  class=\"text w60\" onblur=\"checkStep(this,2)\"/><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em> <span></span></div></div><div id=\"addStep\" name=\"addStep\" style=\"cursor:pointer;margin-left:6px;margin-bottom:10px;width:60px;height:30px; border:0px;line-height:30px;text-align:center;cursor:default;background-color:#ccd0d9;color:#fff; padding:0 6px;float: left;border-radius: 3px;margin-right:5px\" title='+spec_bunch+'>+新增区间<input name=\"snum\" type=\"hidden\" id=\"snum\" value=\"1\" /></div><div id=\"SaveStepPrice\" name=\"SaveStepPrice\" style=\"margin-bottom:10px;width:60px;height:30px; border:0;line-height:30px;text-align:center;cursor:default;background-color:#48cfae;cursor: pointer;color:#fff;border-radius:3px;margin-right:5px;float: right;padding:0 6px;\" title='+spec_bunch+'>保存</div><div style=\"margin-bottom:5px\"> <span class=\"arrow\"></span></div></div></td>';\n";
        }
        echo "str +='<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][marketprice]\" data_type=\"marketprice\" wt_type=\"'+spec_bunch+'|marketprice\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                    '<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" wt_type=\"'+spec_bunch+'|price\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                    '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][stock]\" data_type=\"stock\" wt_type=\"'+spec_bunch+'|stock\" value=\"\" /></td>' +
                    '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][alarm]\" data_type=\"alarm\" wt_type=\"'+spec_bunch+'|alarm\" value=\"\" /></td>' +
                    '<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][sku]\" wt_type=\"'+spec_bunch+'|sku\" value=\"\" /></td>' +
                    '<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][barcode]\" wt_type=\"'+spec_bunch+'|barcode\" value=\"\" /></td>' +
                    '</tr>';\n";
        for($i=0; $i< $len; $i++){
            echo "}\n";
        }
    }
}

?>


<?php if (!empty($output['goods']) && $_GET['class_id'] <= 0 && !empty($output['sp_value']) && !empty($output['spec_checked']) && !empty($output['spec_list'])){?>
//  编辑商品时处理JS
$(function(){
	var E_SP = new Array();
	var E_SPV = new Array();
	
	var stepDivEdit;
	var sn_o;
	<?php
	$string = '';
	foreach ($output['spec_checked'] as $v) {
		$string .= "E_SP[".$v['id']."] = '".$v['name']."';";
	}
	echo $string;
	echo "\n";
	$string = '';
	foreach ($output['sp_value'] as $k=>$v) {
		$string .= "E_SPV['{$k}'] = '{$v}';";
	}
	echo $string;
	?>
	V = E_SPV;
	$('dl[wt_type="spec_dl"]').show();
	$('dl[wttype="spec_group_dl"]').find('input[type="checkbox"]').each(function(){
		//  店铺价格 商品库存改为只读
		$('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
		$('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');
		s = $(this).attr('wt_type');
		if (!(typeof(E_SP[s]) == 'undefined')){
			$(this).attr('checked',true);
			v = $(this).parents('li').find('span[wttype="pv_name"]');
			if(E_SP[s] != ''){
				$(this).val(E_SP[s]);
				v.html('<input type="text" maxlength="20" value="'+E_SP[s]+'" />');
			}else{
				v.html('<input type="text" maxlength="20" value="'+v.html()+'" />');
			}
			change_img_name($(this));			// 修改相关的颜色名称
		}
	});

    into_array();	// 将选中的规格放入数组
    str = '<tr>';
    <?php recursionSpec(0,$output['sign_i']);?>
    if(str == '<tr>'){
        $('dl[wt_type="spec_dl"]').hide();
        $('input[name="g_price"]').removeAttr('readonly').css('background','');
        $('input[name="g_storage"]').removeAttr('readonly').css('background','');
    }else{
        $('tbody[wt_type="spec_table"]').empty().html(str)
            .find('input[wt_type]').each(function(){
                s = $(this).attr('wt_type');
                try{$(this).val(E_SPV[s]);}catch(ex){$(this).val('');};
            }).end()
            .find('input[data_type="stock"]').change(function(){
                computeStock();    // 库存计算
            }).end()
            .find('input[data_type="price"]').change(function(){
                computePrice();     // 价格计算
            }).end()
            .find('input[type="text"]').change(function(){
                s = $(this).attr('wt_type');
                V[s] = $(this).val(); //v5.3 批发价处理
				}).end()
			 .find('i[id="newbatprice"]').click(function(){
					$('.batch > .batch-input').hide();
					$(this).next().show();
					var spec_id = $(this).attr('lang');
					stepDivEdit = $('#step_bat_spec_'+spec_id);
					stepDivEdit.empty();
					var snc = spec_id+'|id';
				    var good_spec_id = $('input[wt_type=\"'+snc+'\"]').val();
					var sn;
					$.ajax({   
						type: "POST",  
						dataType: "text",  
						url: 'index.php?w=store_goods_spec_step&t=TempGetSpecStepPrice',    
						data: "good_id="+good_spec_id+"&spec_id="+spec_id,                     
						success: function(msg) {							
						if(eval(msg).length>0)
							{
								for(sn=0;sn<eval(msg).length;sn++)
								{
									sn_o = sn+1;
									$('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+spec_id+'_'+sn_o+'"  id="g_step_num_'+spec_id+'_'+sn_o+'" value="'+eval(msg)[sn]['step_l_num']+'" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+spec_id+'_'+sn_o+'" id="g_step_price_'+spec_id+'_'+sn_o+'" value="'+eval(msg)[sn]['step_price']+'" type="text"  class="text w60" onblur="checkStep(this,2)" /><em><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>').appendTo(stepDivEdit);
								}
								st = sn+1;
							}
							else
							{
									stepDivEdit.html('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+spec_id+'_1"  id="g_step_num_'+spec_id+'_1" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+spec_id+'_1" id="g_step_price_'+spec_id+'_1" value="" type="text"  class="text w60" onblur="checkStep(this,2)" /><em><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>');	
									st = 2;
							}
						}  
					});
            }).end()
			 .find('a[id="x"]').click(function(){
                $(this).parent().hide();
            }).end()
			.find('div[id="SaveStepPrice"]').click(function(){
			   var sprc_s = $(this).attr('title');
			   var r=1;
			   for(var ssp=1;ssp<=<?=$output['bat_max_num']?>;ssp++)
			   {
			        var _s_add_step_num   =  $("#g_step_num_"+sprc_s+"_"+ssp).val();
					var _s_add_step_price =  $("#g_step_price_"+sprc_s+"_"+ssp).val();
					if((_s_add_step_num &&_s_add_step_num!=null&& typeof(_s_add_step_num)!="undefined" && _s_add_step_num!=0)&&(_s_add_step_price&&_s_add_step_price!=null && typeof(_s_add_step_price)!="undefined" && _s_add_step_price!=0))
					{
						var aaN = ssp+1;
		if(parseInt($("#g_step_num_"+sprc_s+"_"+aaN).val())<parseInt(_s_add_step_num)||parseInt($("#g_step_price_"+sprc_s+"_"+aaN).val())>parseInt(_s_add_step_price))
						{
							alert("注意：\n1、起批数量依次递增\n2、批发价格应依次递减");
							return false;//直接返回
						}
					}
			   }
			   var urldata ="spec="+sprc_s;
               for(var ssp_=1;ssp_<=<?=$output['bat_max_num']?>;ssp_++)
			   {
			   		var _s_add_step_num   =  $("#g_step_num_"+sprc_s+"_"+ssp_).val();
					var _s_add_step_price =  $("#g_step_price_"+sprc_s+"_"+ssp_).val();
					if((_s_add_step_num &&_s_add_step_num!=null&& typeof(_s_add_step_num)!="undefined" && _s_add_step_num!=0)&&(_s_add_step_price&&_s_add_step_price!=null && typeof(_s_add_step_price)!="undefined" && _s_add_step_price!=0))
					{
						urldata =urldata+"&step_num"+ssp_+"=" + _s_add_step_num + "&step_price"+ssp_+"=" + _s_add_step_price;
					}
			   }
			   
				$.ajax({   
					type: "POST",  
					dataType: "text",  
					url: 'index.php?w=store_goods_spec_step&t=TempSaveSpecStepPrice',      //提交到一般处理程序请求数据   
					data: urldata,                          
					success: function(msg) {
						if(msg<1)
						{
							r=0;
						}
					}  
				});
						
			   if(r==1)
			   {
			   		alert("保存完毕");
					$(this).parent().hide();
			   }
			   else
			   {
			   		alert("保存失败");
			   }
            }).end()
			 .find('div[id="addStep"]').click(function(){	
					var sprc_a = $(this).attr('title');
					if(st<=<?=$output['bat_max_num']?>)
					{
						var aN = st -1;
						var _add_step_num   =  $("#g_step_num_"+sprc_a+"_"+aN).val();
						
						var _add_step_price =  $("#g_step_price_"+sprc_a+"_"+aN).val();
						if((_add_step_num &&_add_step_num!=null&& typeof(_add_step_num)!="undefined" && _add_step_num!=0)&&(_add_step_price&&_add_step_price!=null && typeof(_add_step_price)!="undefined" && _add_step_price!=0))
						{
							if(st>=3)
							{
								var aaN = aN-1;
								if(parseInt($("#g_step_num_"+sprc_a+"_"+aaN).val())>parseInt(_add_step_num)||parseInt($("#g_step_price_"+sprc_a+"_"+aaN).val())<parseInt(_add_step_price))
								{
									alert("注意：\n1、起批数量依次递增\n2、批发价格应依次递减");
									return false;//直接返回
								}
							}
							$('<div style="margin-bottom:5px">数量：<input name="g_step_num_'+sprc_a+'_'+st+'"  id="g_step_num_'+sprc_a+'_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,1)" /><span></span> 价格：<input name="g_step_price_'+sprc_a+'_'+st+'" id="g_step_price_'+sprc_a+'_'+st+'" value="" type="text"  class="text w60" onblur="checkStep(this,2)" /><em ><i class="icon-renminbi"></i></em>&nbsp;&nbsp;&nbsp;<a name="remStep" id="remStep" href="javascript:void(0)" onclick="dRemove(this)">删除</a> <span></span> </div>').appendTo(stepDivEdit);
							$("#snum").val(st);
							st++;
							return false;
						}
						else
						{
							alert('必须设置起批量和价格');
						}
					}
					else
					{
						alert('已达到最大阶梯价格设置数量');
					}
					//end
				
            });
    }
    $('div[wttype="spec_div"]').perfectScrollbar('update');
});
<?php }?>
</script> 
<script src="<?php echo STATIC_SITE_URL;?>/js/scrolld.js"></script>
<script type="text/javascript">$("[id*='Btn']").stop(true).on('click', function (e) {e.preventDefault();$(this).scrolld();})</script>
