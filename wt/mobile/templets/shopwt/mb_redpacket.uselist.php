<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/main.css" rel="stylesheet" type="text/css">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?w=mb_redpacket"><span>管理</span></a></li>
        <li><a href="index.php?w=mb_redpacket&t=new" ><span>新增</span></a></li>
		<li><a href="JavaScript:void(0);" class="current"><span>已领取红包</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <!--form method="get" name="formSearch">
    <input type="hidden" name="w" value="mb_redpacket">
    <input type="hidden" name="t" value="uselist">
	<input type="hidden" name="id" value="<?php echo $output['id']; ?>">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
		  <th>红包状态</th>
		  <td>
		     <select name="state">
			    <option value="">请选择</option>
				<option value="1" <?php if($_GET['state']==1){echo 'selected';} ?>>已使用</option>
				<option value="2" <?php if($_GET['state']==2){echo 'selected';} ?>>未使用</option>
				<option value="3" <?php if($_GET['state']==3){echo 'selected';} ?>>已过期</option>
			 </select>
		  </td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['wt_query']; ?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form-->
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title">
            <h5><?php echo $lang['wt_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>  共计：<?php echo $output['number']; ?>个红包</li>
			<li>总金额：<?php echo $output['total']; ?> 元</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form id="listform" action="index.php" method='post'>
    <input type="hidden" name="w" value="activity" />
    <input type="hidden" id="listop" name="t" value="del" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="align-center">序号</th>
		  <th class="align-center">会员</th>
          <th class="align-center">红包金额</th>
		  <th class="align-center">红包状态</th>
		  <!--th class="align-center">使用时间</th-->
		  <th class="align-center">领取时间</th>
        </tr>
      </thead>
      <tbody id="treet1">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $v){ ?>
        <tr class="hover edit row">
          <td class="align-center"><?php echo $v['id'];?></td>
		  <td class="align-center"><?php echo $v['member_name'];?></td>
		  <td class="align-center"><?php echo $v['packet_price'];?></td>
		  <td class="align-center">已领取<?php //if($v['is_use']==1){echo '已使用';}elseif($v['is_use']==2){echo '未使用';}elseif($v['is_use']==3){echo '已过期';};?></td>
		  <td class="align-center"><?php echo date('Y-m-d H:i:s',$v['add_time']);?></td>
		  <!--td class="align-center"><?php if(!empty($v['use_time'])){echo date('Y-m-d H:i:s',$v['use_time']);}?></td-->
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['wt_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
          <td colspan="16">
            <div class="pagination"> <?php echo $output['show_page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>