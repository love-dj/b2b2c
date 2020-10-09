<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['wt_statgeneral'];?></h3>
        <h5>商城统计最新情报及相关设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-bell-o"></i>
      <h4 title="<?php echo $lang['wt_prompts_title'];?>"><?php echo $lang['wt_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['wt_prompts_span'];?>"></span> </div>
    <ul>
      <li>设置商品价格区间，当对商品价格进行相关统计时按照以下设置的价格区间进行统计和显示</li>
      <li>设置价格区间的几点建议：一、建议设置的第一个价格区间起始额为0；二、价格区间应该设置完整，不要缺少任何一个起始额和结束额；三、价格区间数值应该连贯例如0~100,101~200</li>
    </ul>
  </div>
  <form method="post" action="index.php" name="pricerangeform" id="pricerangeform">
    <input type="hidden" value="ok" name="form_submit">
    <input type="hidden" name="w" value="stat_general" />
    <input type="hidden" name="t" value="setting" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">商品价格区间段设定</dt>
        <dd class="opt" id="pricerang_table">
          <ul class="wtap-ajax-add">
            <?php if (!empty($output['list_setting']['stat_pricerange']) && is_array($output['list_setting']['stat_pricerange'])){?>
            <?php foreach ((array)$output['list_setting']['stat_pricerange'] as $k=>$v){ ?>
            <li id="row_<?php echo $k; ?>">
              <label>起始额：
                <input type="text" class="txt w100 mr5" value="<?php echo $v['s'];?>" name="pricerange[<?php echo $k;?>][s]">元
              </label>
              <label class="ml20 mr10">结束额：
                <input type="text" class="txt w100 mr5" value="<?php echo $v['e'];?>" name="pricerange[<?php echo $k;?>][e]">元
              </label>
              <label><a href="JavaScript:void(0);" onclick="delrow(<?php echo $k;?>);" class="wtap-btn wtap-btn-red"><?php echo $lang['wt_del']; ?></a></label>
            </li>
            <?php } ?>
            <?php } ?>
            
          </ul><a id="addrow" href="javascript:void(0);" class="wtap-btn"><i class="fa fa-plus"></i>增加一行</a>
        </dd>
      </dl>
      <div class="bot"><a id="wtsubmit" class="wtap-btn-big wtap-btn-green" href="JavaScript:void(0);"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
function delrow(i){
	$("#row_"+i).remove();
}
$(function(){
	var i = <?php echo count($output['list_setting']['stat_pricerange']); ?>;
	i += 1;
	var html = '';
	/*新增一行*/
	$('#addrow').click(function(){
		html = '<li id="row_'+i+'">';
		html += '<label>起始额：<input type="text" class="txt w100 mr5" name="pricerange['+i+'][s]" value="0"/>元</label>';
		html += '<label class="ml20 mr10">结束额：<input type="text" class="txt w100 mr5" name="pricerange['+i+'][e]" value="0"/>元</label>';
		html += '<label><a href="JavaScript:void(0);" onclick="delrow('+i+');" class="wtap-btn wtap-btn-red"><?php echo $lang['wt_del']; ?></a></label></li>';
		$('#pricerang_table').find('ul').append(html);
		i += 1;
	});
	
	$('#wtsubmit').click(function(){
		var result = true;
		$("#pricerang_table").find("[name^='pricerange']").each(function(){
			if(!$(this).val()){
				result = false;
			}
		});
		if(result){
			$('#pricerangeform').submit();
		} else {
			showDialog('请将价格区间填写完整');
		}
    });
})
</script>