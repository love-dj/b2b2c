<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=coupon&t=rptlist" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3>平台优惠券 - 优惠券详情</h3>
        <h5>平台优惠券新增与管理</h5>
      </div>
    </div>
  </div>
  
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">优惠券名称：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_title'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">领取方式：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_gettype_text'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">有效期：</dt>
        <dd class="opt">
            <?php echo @date('Y-m-d',$output['t_info']['coupon_t_start_date']);?> 至 
            <?php echo @date('Y-m-d',$output['t_info']['coupon_t_end_date']);?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">面额：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_price'];?>&nbsp;&nbsp;<?php echo $lang['currency_zh'];?></dd>
      </dl>
      <?php if($output['t_info']['coupon_t_gettype_key']=='points'){?>
      <dl class="row" id="points_dl">
        <dt class="tit">兑换所需积分：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_points'];?></dd>
      </dl>
      <?php }?>
      <dl class="row">
        <dt class="tit">可发放总数：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_total'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">每人限领：</dt>
        <dd class="opt"><?php echo ($t=$output['t_info']['coupon_t_eachlimit']) > 0?$t:'不限';?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">消费限额：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_limit'];?>&nbsp;&nbsp;<?php echo $lang['currency_zh'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">会员级别：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_mgradelimittext'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">优惠券描述：</dt>
        <dd class="opt">
            <textarea id="rpt_desc" name="rpt_desc" class="w300" readonly ><?php echo $output['t_info']['coupon_t_desc'];?></textarea>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">优惠券图片：</dt>
        <dd class="opt">
            <?php if ($output['t_info']['coupon_t_customimg_url']){?>
              <img onload="javascript:DrawImage(this,220,95);" src="<?php echo $output['t_info']['coupon_t_customimg_url'];?>"/>
            <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">最近修改时间：</dt>
        <dd class="opt"><?php echo @date('Y-m-d H:i:s',$output['t_info']['coupon_t_updatetime']);?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">最近修改人：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_creator_name'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">已领取：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_giveout'];?>  张</dd>
      </dl>
      <dl class="row">
        <dt class="tit">已使用：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_used'];?>  张</dd>
      </dl>
      <dl class="row">
        <dt class="tit">状态：</dt>
        <dd class="opt"><?php echo $output['t_info']['coupon_t_state_text'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">是否推荐：</dt>
        <dd class="opt">
            <?php if($output['t_info']['coupon_t_recommend'] == '1'){ echo $lang['wt_yes']; }else{ echo $lang['wt_no']; } ?>
        </dd>
      </dl>
      <?php if($output['t_info']['coupon_t_gettype_key'] == 'free'){ ?>
      <dl class="row">
        <dt class="tit">推广链接：</dt>
        <dd class="opt">
            <input type="text" onclick="oCopy(this)" value="<?php echo BASE_SITE_URL.DS;?>index.php?w=coupon&t=getcoupon&tid=<?php echo $output['t_info']['coupon_t_id'];?>" readonly class='w350'/>
<script>
	function oCopy(obj){
		obj.select();
        if (!!window.ActiveXObject || "ActiveXObject" in window) {
            js=obj.createTextRange();
            js.execCommand("Copy")
            alert("复制成功!");
        }else{
            alert('在“推广链接”文本框上右击鼠标，选择“复制”将推广链接复制到剪切板');
        }
	}
</script>
            <span class="err"></span>
            <p class="notic">可以复制该链接对免费领取的优惠券进行推广</p>
        </dd>
      </dl>
      <?php }?>
      <?php if($output['t_info']['coupon_t_gettype_key'] == 'pwd' && $output['t_info']['coupon_t_isbuild']==0){ ?>
      <dl class="row">
        <dt class="tit">卡密生成状态：</dt>
        <dd class="opt">未生成
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="build_rp">点击生成优惠券卡密</a>
        </dd>
      </dl>
      <?php }?>
    </div>
    
    <div id="flexigrid"></div>
    <div class="wtap-form-default">
        <div style="text-align: center;"><a href="index.php?w=coupon&t=rptlist" class="wtap-btn-big wtap-btn-green" id="submitBtn">返回列表</a></div>
    </div>
</div>
<script>
$(function(){
    var flexUrl = 'index.php?w=coupon&t=rplist_xml&tid=<?php echo $output['t_info']['coupon_t_id'];?>&gtype=<?php echo $output['t_info']['coupon_t_gettype_key'];?>';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '优惠券编码', name: 'coupon_code', width: 250, sortable: false, align: 'center'},
            <?php if ($output['t_info']['coupon_t_gettype_key'] == 'pwd'){ ?>
            {display: '卡密', name: 'coupon_pwd', width: 250, sortable: false, align: 'center'},
            <?php } ?>
            {display: '使用状态', name: 'coupon_statetext', width: 150, sortable: false, align: 'center'},
            {display: '所属会员', name: 'coupon_owner_name', width: 150, sortable: false, align: 'left'},
            {display: '领取时间', name: 'coupon_active_datetext', width: 200, sortable: false, align: 'center'}
        ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出excel文件,如果不选中行，将导出列表所有数据', onpress : fg_operate }
        ],
        sortname: "coupon_id",
        sortorder: "desc",
        title: '优惠券列表'
    });

    $("#build_rp").click(function(){
    	ajaxget("<?php echo urlAdminShop('coupon','rpbulidpwd',array('tid'=>$output['t_info']['coupon_t_id']));?>");
    });
});
function fg_operate(name, grid) {
	if (name == 'csv') {
    	var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = 'index.php?w=coupon&t=export_step1&tid=<?php echo $output['t_info']['coupon_t_id'];?>&rid=' + id;
}
</script>