<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="wrap">
  <form id="voucher_list_form" method="get">
    <table class="wtm-search-table">
      <input type="hidden" id='w' name='w' value='fx_goods' />
      <input type="hidden" id='t' name='t' value='index' />
      <tr>
        <td>分销商品管理</td>
        <td class="w100 tr">
          <input type="text" name="goods_name" class="txt w100" placeholder="商品名称">
        </td>
        <td class="w70 tc"><label class="submit-border">
            <input type="submit" class="submit" value="<?php echo $lang['wt_search'];?>" />
          </label></td>
      </tr>
    </table>
  </form>
  <table class="wtm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w70"></th>
        <th class="tl">商品名称</th>
        <th class="w80">商品价格</th>        
        <th class="w100">店铺名称</th>
        <th class="w100">认领时间</th>
        <th class="w100"><?php echo $lang['wt_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['goods_list'])>0) { ?>
      <?php foreach($output['goods_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="wtm-goods-thumb"><a href="javascript:void(0);"><img src="<?php echo cthumb($val['goods_image'], 60,$val['store_id']);?>" onMouseOver="toolTip('<img src=<?php echo cthumb($val['goods_image'], 360,$val['store_id']);?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="tl">
          <dl class="goods-name">
            <a href="<?php echo urlShop('fx_goods','index',array('goods_id'=>$val['fx_id']));?>" title="<?php echo $val['goods_name'];?>"><?php echo $val['goods_name'];?></a>
          </dl>
        </td>
        <td class="goods-price">
          <?php echo wtPriceFormatForList($val['goods_price']);?>
        </td>
        <td class="goods-name">
          <a href="<?php echo urlFenxiao('store','index',array('store_id'=>$val['store_id']));?>" title="<?php echo $val['store_name'];?>"><?php echo $val['store_name'];?></a>
        </td>
        <td class="goods-time"><?php echo date("Y-m-d",$val['fx_time']);?></td>
       
        <td class="goods-time" style="padding-left:12px">
          <span><a href="javascript:void(0);" data-gid="<?php echo $val['goods_commonid']?>" class="cd-popup-trigger1" title="立即推广"><p class="text-left-word" style="color:#444"><i class="icon-share-alt"></i>&nbsp;&nbsp;立即推广</p></a><span>
          <span><a href="javascript:void(0);" data-gid="<?php echo $val['goods_commonid']?>" class="cd-popup-trigger2" title="获取二维码"><p class="text-left-word" style="color:#444"><i class="icon-qrcode"></i>&nbsp;&nbsp;获取二维码</p></a><span>
          <span><a href="javascript:void(0);" onclick="ajax_get_confirm('确认要删除吗？','<?php echo urlFenxiao('fx_goods', 'drop_goods', array('fx_id' => $val['fx_id']))?>')" title="删除商品"><p class="text-left-word" style="color:#e71d34"><i class="icon-trash"></i>&nbsp;&nbsp;删除商品</p></a><span>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php  if (count($output['goods_list'])>0) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>


<!-- 立即推广-->
<div class="cd-popup1 cd-popup-box">
    <div class="cd-popup-container1">
        <div id="fx_info"></div>        
        <div class="clear"></div>
        <div class="share">
            <div class="sharebord">
                <div class="item">
                    <input type="text" id="link1" readonly="readonly">
                    <button class="clip_button">复制链接</button>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="bdsharebuttonbox">
            <a href="javascript:void(0)">分享至：</a>
            <a href="javascript:void(0)" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
            <a href="javascript:void(0)" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
            <a href="javascript:void(0)" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
            <a href="javascript:void(0)" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a>
        </div>        
        <a href="#0" class="cd-popup-close"><i class="icon-close" aria-hidden="true"></i>&nbsp;关闭</a>
    </div>
</div>


<!-- 获取二维码-->
<div class="cd-popup2 cd-popup-box">
    <div class="cd-popup-container2">
        <img src="<?php echo FENXIAO_TEMPLATES_URL?>/images/fx-share-code.jpg" class="share-qrcode"/>
        <div id="fx_info2"></div>
        <div class="clear"></div>

        <div class="qrcodeTable">
            <div id="qrcodeTable" class="fl"></div>
            <h4 class="fl w-45">打开微信扫描二维码后可将商品分享至微信好友及朋友圈</h4>
        </div>
        <a href="#0" class="cd-popup-close"><i class="icon-close" aria-hidden="true"></i>&nbsp;关闭</a>
    </div>
</div>

<script type="text/javascript" src="<?php echo FENXIAO_STATIC_SITE_URL;?>/js/jquery.qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FENXIAO_STATIC_SITE_URL;?>/js/qrcode.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FENXIAO_STATIC_SITE_URL;?>/js/zclip/ZeroClipboard.min.js" charset="utf-8"></script>

<!--弹出js-->
<script type="text/javascript">
    $(function(){
        //打开窗口
        $('.cd-popup-trigger1').on('click', function(event){
            var g_id = $(this).attr('data-gid');
            $.ajax({
                type:'get',
                url:'index.php?w=search&t=fx_add&id='+g_id,
                dataType:"json",
                success:function(res){
                    if(res.stat == 'succ'){
                        var datas = res.data;
                        var html = '<img src="'+datas.goods_image+'" class="fl"/>';
                        html += '<h4>'+datas.goods_name+'<b>&yen;'+datas.goods_price+'</b></h4>';
                        $('#fx_info').html(html);
                        $('#link1').val("<?php echo urlShop('fx_goods','index')?>&goods_id="+datas.fx_id);
                        window._bd_share_config={
                            "common":{
                                "bdText":datas.goods_name,
                                "bdPic":datas.goods_image,
                                "bdUrl":"<?php echo urlShop('fx_goods','index')?>&goods_id="+datas.fx_id
                            },
                            "share":{
                                "bdSize":"24"
                            },
                            "selectShare":{
                                "bdSelectMiniList":["tsina","qzone","weixin","sqq"]
                            }
                        };
                        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
                        event.preventDefault();
                        $('.cd-popup1').addClass('is-visible1');
                    }else{
                        //alert(res.msg);
                        showDialog(res.msg, 'alert', '', function(){ 
                            if(res.url != ''){
                                window.location = res.url;
                            }                            
                        });
                    }
                },
                error:function(error){
                    showError('操作失败');
                }
            });
        });
        //关闭窗口
        $('.cd-popup1').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup1') ) {
                event.preventDefault();
                $(this).removeClass('is-visible1');
            }
        });
        //ESC关闭
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup1').removeClass('is-visible1');
            }
        });
        
        //打开窗口
        $('.cd-popup-trigger2').on('click', function(event){
            var g_id = $(this).attr('data-gid');
            $.ajax({
                type:'get',
                url:'index.php?w=search&t=fx_add&id='+g_id,
                dataType:"json",
                success:function(res){
                    if(res.stat == 'succ'){
                        var datas = res.data;
                        var html = '<img src="'+datas.goods_image+'" class="fl"/>';
                        html += '<h4 class="w-55">'+datas.goods_name+'<b>&yen;'+datas.goods_price+'</b></h4>';
                        $('#fx_info2').html(html);
                        $('#qrcodeTable').html('');
                        var fx_uri = "<?php echo urlShop('fx_goods','index')?>&goods_id="+datas.fx_id;
                        jQuery('#qrcodeTable').qrcode({render: "canvas",text:fx_uri,width:"124",height:"124"});
                        event.preventDefault();
                        $('.cd-popup2').addClass('is-visible2');
                    }else{
                        //alert(res.msg);
                        showDialog(res.msg, 'alert', '', function(){ 
                            if(res.url != ''){
                                window.location = res.url;
                            }                            
                        });
                    }
                },
                error:function(error){
                    showError('操作失败');
                }
            }); 
        });
        //关闭窗口
        $('.cd-popup2').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup2') ) {
                event.preventDefault();
                $(this).removeClass('is-visible2');
            }
        });
        //ESC关闭
        $(document).keyup(function(event){
            if(event.which=='27'){
                $('.cd-popup2').removeClass('is-visible2');
            }
        });
        //复制
        var client = new ZeroClipboard($('.clip_button'));
        client.on( 'ready', function(event) {

            client.on('copy', function(event) {
              event.clipboardData.setData('text/plain',$('#link1').val());
            });

            client.on('aftercopy', function(event) {
              alert('你已经成功复制本地址，请直接粘贴推荐给你的朋友!');
            });
        });
    });
</script>
<!--弹出js结束-->
