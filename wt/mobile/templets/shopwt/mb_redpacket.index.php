<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/main.css" rel="stylesheet" type="text/css">
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>红包管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="index.php?w=mb_redpacket&t=new" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch">
    <input type="hidden" name="w" value="mb_redpacket">
    <input type="hidden" name="t" value="index">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th>红包名称</th>
          <td><input type="text" name="packet_name" class="txt" value='<?php echo $_GET['packet_name'];?>'></td>
		  <th>状态</th>
		  <td>
		     <select name="state">
			    <option value="">请选择</option>
				<option value="1" <?php if($_GET['state']==1){echo 'selected';} ?>>开启</option>
				<option value="2" <?php if($_GET['state']==2){echo 'selected';} ?>>关闭</option>
			 </select>
		  </td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo $lang['wt_query']; ?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title">
            <h5><?php echo $lang['wt_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>1、只有开启状态且在抢购时间段内的红包才会显示</li>
			<li>2、不允许删除正在进行的活动，如果要删除，请先关闭活动</li>
			<li>3、只支持手机版，建议在手机版首页添加红包活动入口，链接地址即是：红包推广URL</li>
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
          <th class="w150">红包名称</th>
		  <th class="align-center">红包推广URL</th>
          <th class="align-center">红包金额</th>
		  <th class="align-center">红包总量</th>
		  <th class="align-center">已抢数量</th>
		  <!--th class="align-center">已使用数量</th>
		  <th class="align-center">已过期数量</th-->
		  <th class="align-center">中奖机率</th>
		  <!--th class="align-center">红包有效期</th-->
          <th class="align-center">开始时间</th>
          <th class="align-center">结束时间</th>
		  <th class="align-center">状态</th>
          <th class="w150 align-center">操作</th>
        </tr>
      </thead>
      <tbody id="treet1">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover edit row">
		  <?php
             //$used = Model()->table('mb_redpacket_rec')->where(array('packet_id'=>$v['id'],'is_use'=>1))->select();
			 //$no_use = Model()->table('mb_redpacket_rec')->where(array('packet_id'=>$v['id'],'is_use'=>3))->select();
		  ?>
          <td><a href="<?php echo WAP_SITE_URL; ?>/html/red_packet.html?id=<?php echo $v['id']; ?>" target="_blank"><?php echo $v['packet_name'];?></a></td>
		<td><a href="<?php echo WAP_SITE_URL; ?>/html/red_packet.html?id=<?php echo $v['id']; ?>" target="_blank"><?php echo WAP_SITE_URL; ?>/html/red_packet.html?id=<?php echo $v['id']; ?></a></td>
          <td class="align-center"><?php echo $v['packet_amount'];?></td>
		  <td class="align-center"><?php echo $v['packet_number'];?></td>
		  <td class="align-center"><a href="index.php?w=mb_redpacket&t=uselist&id=<?php echo $v['id']; ?>"><?php echo $v['packet_numbered'];?></a></td>
		  <!--td class="align-center"><a href="index.php?w=mb_redpacket&t=uselist&id=<?php echo $v['id']; ?>"><?php echo count($used);?></a></td>
		  <td class="align-center"><a href="index.php?w=mb_redpacket&t=uselist&id=<?php echo $v['id']; ?>"><?php echo count($no_use);?></a></td-->
		  <td class="align-center"><?php echo $v['win_rate'];?>%</td>
		  <!--td class="align-center"><?php if(!empty($v['valid_date'])){echo date('Y-m-d H:i:s',$v['valid_date']);}else{if($v['valid_date2']==1){echo '自领取后当天内有效';}else{echo '自领取后'.$v['valid_date2'].'天内有效';}}?></td-->
		  <td class="align-center"><?php echo date('Y-m-d H:i:s',$v['start_time']);?></td>
		  <td class="align-center"><?php echo date('Y-m-d H:i:s',$v['end_time']);?></td>
          <td class="align-center"><?php if($v['state']==1 && $v['start_time']<time() && $v['end_time']>time()){echo '正在进行';}elseif($v['state']==2 && $v['start_time']<time() && $v['end_time']>time()){echo '已关闭';}elseif($v['start_time']>time()){echo '未开始';}else{echo '已结束';} ?></td>
          <td class="align-center">
		    <a href="index.php?w=mb_redpacket&t=list&id=<?php echo $v['id']; ?>">查看</a> | 
          	<a href="index.php?w=mb_redpacket&t=edit&id=<?php echo $v['id']; ?>">编辑</a> |
			<?php if($v['state']==1){ ?>
			<a href="javascript:if(confirm('确定关闭?'))window.location.href='index.php?w=mb_redpacket&t=state&id=<?php echo $v['id']; ?>&type=close'">关闭</a>
			<?php }else{ ?>
			<a href="javascript:if(confirm('确定开启?'))window.location.href='index.php?w=mb_redpacket&t=state&id=<?php echo $v['id']; ?>&type=open'">开启</a>
			<?php } ?>
			|
			<?php if($v['state']==1 && $v['start_time']<time() && $v['end_time']>time()){ ?>
			<a href="javascript:;"><span style="color:#666;">删除</span></a>
			<?php }else{ ?>
			<a href="javascript:if(confirm('确定删除?'))window.location.href='index.php?w=mb_redpacket&t=del&id=<?php echo $v['id']; ?>'">删除</a>
			<?php } ?>
          </td>
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