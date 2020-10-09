<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/template.min.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function(){

        // 当前编辑对象，默认为空
        $edit_item = {};

        //现实商品搜索
        $('#btn_show_goods_select').on('click', function() {
            $('#div_goods_select').show();
        });

        //隐藏商品搜索
        $('#btn_hide_goods_select').on('click', function() {
            $('#div_goods_select').hide();
        });

        //搜索商品
        $('#btn_search_goods').on('click', function() {
            var url = "<?php echo urlShop('store_sale_pingou', 'goods_select');?>";
            url += '&' + $.param({goods_name: $('#search_goods_name').val()});
            $('#div_goods_search_result').load(url);
        });
        $('#div_goods_search_result').on('click', 'a.demo', function() {
            $('#div_goods_search_result').load($(this).attr('href'));
            return false;
        });

        //添加商品弹出窗口 
        $('#div_goods_search_result').on('click', '[wttype="btn_add_pingou_goods"]', function() {
            $('#dialog_goods_id').val($(this).attr('data-goods-id'));
            $('#dialog_goods_name').text($(this).attr('data-goods-name'));
            $('#dialog_goods_price').text($(this).attr('data-goods-price'));
            $('#dialog_input_goods_price').val($(this).attr('data-goods-price'));
            $('#dialog_goods_img').attr('src', $(this).attr('data-goods-img'));
            $('#dialog_goods_storage').text($(this).attr('data-storage'));
            $('#dialog_add_pingou_goods').wt_show_dialog({width: 640, title: '商品规则设定'});
            $('#dialog_pingou_price').val('');
			$('#dialog_goods_maxnum').val('0');
            $('#dialog_add_pingou_goods_error').hide();
        });

        //添加商品
        $('#div_goods_search_result').on('click', '#btn_submit', function() {
            var goods_id = $('#dialog_goods_id').val();
            var pingou_id = <?php echo $_GET['pingou_id'];?>;
            var goods_price = Number($('#dialog_input_goods_price').val());
            var pingou_price = Number($('#dialog_pingou_price').val());
			var goods_maxnum = Number($('#dialog_goods_maxnum').val());
            if(!isNaN(pingou_price) && pingou_price > 0 && pingou_price < goods_price) {
                $.post('<?php echo urlShop('store_sale_pingou', 'pingou_goods_add');?>', 
                    {goods_id: goods_id, pingou_id: pingou_id, pingou_price: pingou_price,goods_maxnum:goods_maxnum},
                    function(data) {
                        if(data.result) {
                            $('#dialog_add_pingou_goods').hide();
                            $('#pingou_goods_list').prepend(template.render('pingou_goods_list_template', data.pingou_goods)).hide().fadeIn('slow');
                            $('#pingou_goods_list_norecord').hide();
                            showSucc(data.message);
                        } else {
                            showError(data.message);
                        }
                    }, 
                'json');
            } else {
                $('#dialog_add_pingou_goods_error').show();
            }
        });

        //编辑活动商品
        $('#pingou_goods_list').on('click', '[wttype="btn_edit_pingou_goods"]', function() {
            $edit_item = $(this).parents('tr.bd-line');
            var pingou_goods_id = $(this).attr('data-pingou-goods-id');
            var pingou_price = $edit_item.find('[wttype="pingou_price"]').text();
            var goods_price = $(this).attr('data-goods-price');
			var goods_maxnum = $edit_item.find('[wttype="goods_maxnum"]').text();
            $('#dialog_pingou_goods_id').val(pingou_goods_id);
            $('#dialog_edit_goods_price').text(goods_price);
            $('#dialog_edit_pingou_price').val(pingou_price);
            $('#dialog_edit_goods_maxnum').val(goods_maxnum);
            $('#dialog_edit_pingou_goods').wt_show_dialog({width: 450, title: '修改价格'});
        });

        $('#btn_edit_pingou_goods_submit').on('click', function() {
            var pingou_goods_id = $('#dialog_pingou_goods_id').val();
            var pingou_price = Number($('#dialog_edit_pingou_price').val());
            var goods_price = Number($('#dialog_edit_goods_price').text());
            var goods_maxnum = Number($('#dialog_edit_goods_maxnum').val());
            if(!isNaN(pingou_price) && pingou_price > 0 && pingou_price < goods_price) {
                $.post('<?php echo urlShop('store_sale_pingou', 'pingou_goods_price_edit');?>',
                    {pingou_goods_id: pingou_goods_id, pingou_price: pingou_price,goods_maxnum:goods_maxnum},
                    function(data) {
                        if(data.result) {
                            $edit_item.find('[wttype="pingou_price"]').text(data.pingou_price);
                            $edit_item.find('[wttype="pingou_discount"]').text(data.pingou_discount);
							$edit_item.find('[wttype="goods_maxnum"]').text(data.goods_maxnum);
							if(data.goods_maxnum==0){$edit_item.find('[wttype="goods_maxnum_show"]').text('不限');}
							else{$edit_item.find('[wttype="goods_maxnum_show"]').text(data.goods_maxnum);}
                            $('#dialog_edit_pingou_goods').hide();
                        } else {
                            showError(data.message);
                        }
                    }, 'json'
                ); 
            } else {
                $('#dialog_edit_pingou_goods_error').show();
            }
        });

        //删除活动商品
        $('#pingou_goods_list').on('click', '[wttype="btn_del_pingou_goods"]', function() {
            var $this = $(this);
            if(confirm('确认删除？')) {
                var pingou_goods_id = $(this).attr('data-pingou-goods-id');
                $.post('<?php echo urlShop('store_sale_pingou', 'pingou_goods_delete');?>',
                    {pingou_goods_id: pingou_goods_id},
                    function(data) {
                        if(data.result) {
                            $this.parents('tr').hide('slow', function() {
                                var pingou_goods_count = $('#pingou_goods_list').find('.bd-line:visible').length;
                                if(pingou_goods_count <= 0) {
                                    $('#pingou_goods_list_norecord').show();
                                }
                            });
                        } else {
                            showError(data.message);
                        }
                    }, 'json'
                );
            }
        });
    });
</script>
<div class="tabmenu">
    <?php include template('layout/submenu');?>
    <a id="btn_show_goods_select" class="wtbtn wtbtn-mint" href="javascript:;"><i></i>添加商品</a> 
</div>
<table class="wtsc-default-table">
  <tbody>
    <tr>
      <td class="w90 tr"><strong>活动名称<?php echo $lang['wt_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['pingou_info']['pingou_name'];?></td>
      <td class="w90 tr"><strong>开始时间<?php echo $lang['wt_colon'];?></strong></td>
      <td class="w120 tl"><?php echo date('Y-m-d H:i',$output['pingou_info']['start_time']);?></td>
      <td class="w90 tr"><strong>结束时间<?php echo $lang['wt_colon'];?></strong></td>
      <td class="w120 tl"><?php echo date('Y-m-d H:i',$output['pingou_info']['end_time']);?></td>
      <td class="w90 tr"><strong>参团人数<?php echo $lang['wt_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['pingou_info']['min_num'];?></td>
      <td class="w90 tr"><strong><?php echo '状态'.$lang['wt_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['pingou_info']['end_time'] > TIMESTAMP ? $output['state_array'][$output['pingou_info']['state']]:'已结束'; ?></td>
    </tr>
</table>
<!-- 商品搜索 -->
<div id="div_goods_select" class="div-goods-select" style="display: none;">
    <table class="search-form">
      <tr><th class="w150"><strong>第一步：搜索店内商品</strong></th><td class="w160"><input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/></td>
        <td class="w70 tc"><a href="javascript:void(0);" id="btn_search_goods" class="wtbtn"/><i class="icon-search"></i><?php echo $lang['wt_search'];?></a></td><td class="w10"></td><td><p class="hint">不输入名称直接搜索将显示店内所有普通商品，特殊商品不能参加。</p></td>
      </tr>
    </table>
  <div id="div_goods_search_result" class="search-result"></div>
  <a id="btn_hide_goods_select" class="close" href="javascript:void(0);">X</a> </div>
<table class="wtsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w50"></th>
      <th class="tl">商品名称</th>
      <th class="w90">商品价格</th>
      <th class="w120">拼团价格</th>
      <th class="w50">限购数量</th>
      <th class="w120"><?php echo $lang['wt_handle'];?></th>
    </tr>
  </thead>
  <tbody id="pingou_goods_list">
    <?php if (!empty($output['pingou_goods_list'])) {?>
    <?php foreach ($output['pingou_goods_list'] as $val) {?>
    <tr class="bd-line">
        <td></td>
        <td><div class="pic-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val, 240);?>" alt=""></a></div></td>
        <td class="tl"><dl class="goods-name"><dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a></dt></dl></td>
        <td><?php echo $lang['currency']; ?><?php echo $val['goods_price'];?></td>
        <td><?php echo $lang['currency']; ?><span wttype="pingou_price"><?php echo $val['pingou_price'];?></span></td>
		<td><span wttype="goods_maxnum" style="display:none"><?php echo $val['goods_maxnum'];?></span><span wttype="goods_maxnum_show"><?php echo $val['goods_maxnum']==0?'不限':$val['goods_maxnum'];?></span></td>
        <td class="nscs-table-handle">
        <span><a wttype="btn_edit_pingou_goods" class="btn-bluejeans" data-pingou-goods-id="<?php echo $val['pingou_goods_id']?>" data-goods-price="<?php echo $val['goods_price'];?>"  href="javascript:void(0);"><i class="icon-edit"></i><p><?php echo $lang['wt_edit'];?></p></a></span>
            <span><a wttype="btn_del_pingou_goods" class="btn-grapefruit" data-pingou-goods-id="<?php echo $val['pingou_goods_id']?>" href="javascript:void(0);"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a></span>
        
        </td>
    </tr>
    <?php }?>
    <?php }?>
    <tr id="pingou_goods_list_norecord" style="display:none">
      <td class="norecord" colspan="20"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
  </tbody>
  <tfoot>
    <?php if(!empty($output['pingou_goods_list'])){?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<div class="bottom">
  <label class="submit-border"><input type="submit" class="submit" id="submit_back" value="<?php echo $lang['wt_back'].$lang['pingou_index'];?>" onclick="window.location='index.php?w=store_sale_pingou&t=pingou_list'"></label>
</div>
<div id="dialog_edit_pingou_goods" class="eject_con" style="display:none;">
    <input id="dialog_pingou_goods_id" type="hidden">
    <dl><dt>商品价格：</dt><dd><span id="dialog_edit_goods_price"></dd>
    </dl>
    <dl><dt>价格：</dt><dd><input id="dialog_edit_pingou_price" type="text" class="text w70"><em class="add-on"><i class="icon-renminbi"></i></em>
    <p id="dialog_edit_pingou_goods_error" style="display:none;"><label for="dialog_edit_pingou_goods_error" class="error"><i class='icon-exclamation-sign'></i>价格不能为空，且必须小于商品价格</label></p>
    </dl>   
	<dl>
      <dt>限购数量：</dt>
      <dd>
        <input id="dialog_edit_goods_maxnum" type="text" class="text w70">
       
        <p class="hint">
    每个买家ID可拼团的最大数量，不限数量请填 "0"。</p>
      </dd>
    </dl>	
    <div class="eject_con">
        <div class="bottom"><a id="btn_edit_pingou_goods_submit" class="submit" href="javascript:void(0);">提交</a></div>
    </div>
</div>
<script id="pingou_goods_list_template" type="text/html">
<tr class="bd-line">
    <td></td>
    <td><div class="pic-thumb"><a href="<%=goods_url%>" target="_blank"><img src="<%=image_url%>" alt=""></a></div></td>
    <td class="tl"><dl class="goods-name"><dt><a href="<%=goods_url%>" target="_blank"><%=goods_name%></a></dt></dl></td>
    <td><?php echo $lang['currency']; ?><%=goods_price%></td>
    <td><?php echo $lang['currency']; ?><span wttype="pingou_price"><%=pingou_price%></span></td>
	<td><span wttype="goods_maxnum" style="display:none"><%=goods_maxnum%></span><span wttype="goods_maxnum_show"><% if(goods_maxnum==0){ %>不限<% }else{ %> <%=goods_maxnum%><% } %></span></td>
        
    <td class="nscs-table-handle">
    <span><a wttype="btn_edit_pingou_goods" class="btn-bluejeans" data-pingou-goods-id="<%=pingou_goods_id%>" data-goods-price="<%=goods_price%>" href="javascript:void(0);"><i class="icon-edit"></i><p><?php echo $lang['wt_edit'];?></p></a></span>
        <span><a wttype="btn_del_pingou_goods" class="btn-grapefruit" data-pingou-goods-id="<%=pingou_goods_id%>" href="javascript:void(0);"><i class="icon-trash"></i><p><?php echo $lang['wt_del'];?></p></a></span>
    
    </td>
</tr>
</script> 
