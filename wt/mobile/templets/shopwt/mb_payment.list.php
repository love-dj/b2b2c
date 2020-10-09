<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>手机支付</h3>
        <h5>手机客户端可使用支付方式/接口设置</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>此处列出了手机支持的支付方式，点击编辑可以设置支付参数及开关状态</li>
	  <li>WAP手机版/封装APP方式使用：支付宝、微信支付JSAPI、微信H5支付；原生APP使用：微信支付、支付宝移动支付</li>
    </ul>
  </div>
  <table class="flex-table">
    <thead>
      <tr>
        <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
        <th width="60" align="center" class="handle-s"><?php echo $lang['wt_handle'];?></th>
        <th width="200" align="left">支付方式</th>
        <th width="80" align="center">启用</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['mb_payment_list']) && is_array($output['mb_payment_list'])){ ?>
      <?php foreach($output['mb_payment_list'] as $k => $v) { ?>
      <tr>
        <td class="sign"><i class="ico-check"></i></td>
        <td class="handle-s"><a href="<?php echo urlAdminMobile('mb_payment', 'payment_edit', array('payment_id' => $v['payment_id']));?>" class="btn purple"><i class="fa fa-cog"></i><?php echo $lang['wt_set']?></a></td>
        <td><?php echo $v['payment_name'];?></td>
        <td><?php echo $v['payment_state_text'];?></td>
        <td></td>
      </tr>
      <?php } } ?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '手机支付方式列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
    });
});        
        
	function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'javascript:;';

    }
}
</script> 
