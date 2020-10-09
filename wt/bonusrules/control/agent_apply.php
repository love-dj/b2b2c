<?php
/**
 * 区域代理申请记录
 * 2019/06/12
 * auth hyz
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class agent_applyControl extends SystemControl{
	public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('agent_apply.index');
	}
	
	public function check_agentWt()
	{
		$id = $_GET['id'];
		$type = $_GET['type'];
		if(!$id || !$type){
			showDialog('参数错误');
		}
		
		$condition = array('id' => $id);
		
		$agent_apply = Model('agent_apply_log')->where($condition)->find();
		if(!$agent_apply){
			showDialog('参数错误');
		}
		
		
		$update_data = array('status' => $type, 'updatetime' => time());
		Model('agent_apply_log')->where($condition)->update($update_data);
		
		if($type == 1){
			$member_chain_data = array(
				'agent_check' => 1,
				'agent_check_time' => time(),
			);
			Model('member_chain')->where('member_id = ' . $agent_apply['member_id'])->update($member_chain_data);
		}
		
		showDialog('操作成功');
	}
	
	/**
     * 输出XML数据
     */
    public function get_xmlWt()
	{
		$model = Model('agent_apply_log');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'member_id', 'agent_level', 'area_info', 'member_mobile', 'remark', 'createtime', 'updatetime', 'status');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
		
		$list = Model('agent_apply_log')->where('status <> -1')->page($page)->order($order)->select();
		
		$data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

		$agent_level_types = array(
			1 => '省级代理',
			2 => '市级代理',
			3 => '区县代理'
		);
		
		$apply_status = array(
			0 => '待审核',
			1 => '已审核',
			2 => '已拒绝',
			3 => '已驳回'
		);
		
		foreach ($list as $value) {
        	$param = array();

            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
			if($value['status'] == 0 || $value['status'] == 3 ){//如果订单发生退款，要执行收回返现操作，这个简单，查退款金额，计算该退返现，扣用户余额，更新记录状态
                $operation .= "<li><a href='index.php?w=agent_apply&t=check_agent&id=".$value['id']."&type=1'>通过审核</a></li>";
				$operation .= "<li><a href='index.php?w=agent_apply&t=check_agent&id=".$value['id']."&type=2'>拒绝审核</a></li>";
				$operation .= "<li><a href='index.php?w=agent_apply&t=check_agent&id=".$value['id']."&type=3'>驳回审核</a></li>";
            } 
			$operation .= "<li><a href='index.php?w=agent_apply&t=check_agent&id=".$value['id']."&type=-1'>删除审核</a></li>";	

			
            $operation .= "</ul></span>";
            $param['operation']     = $operation;
        	$param['member_id'] = $value['member_id'];
            $param['agent_level'] = $agent_level_types[$value['agent_level']];
			$area_info = Model('area')->getTopAreaName($value['area_info']);   
            $param['area_info'] = $area_info;
            $param['member_mobile'] = $value['member_mobile'];
            $param['remark'] = $value['remark'];
            $param['status'] = $apply_status[$value['status']];
            $param['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
            if($value['updatetime'] > 0){
                $param['updatetime'] = date("Y-m-d H:i:s",$value['updatetime']);
            }else{
                $param['updatetime'] = $apply_status[0];
            }

            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
	}
}