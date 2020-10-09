<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class buy_return_logsControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('buy_return_logs.index');
	}

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
		$model = Model('buy_return_log');
		$id = $_POST['id'];
		
		if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = 'createtime desc';
		$param = array('id', 'buy_return_id', 'uid', 'return_type', 'return_commission', 'remark', 'createtime', 'status');
		
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
		$where = array(
			'1' => 1,
		);
		if($id){
			$where['buy_return_id'] = $id;
		}
		$log_list = Model('buy_return_log')->field('*')->where($where)->page($page)->order($order)->select();
		
		$data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();    
		
		//返现方式
        $return_status = array(   
                0 => '递减返现',          
                1 => '等额返现',
        );
		
		//返现状态
        $commission_status = array(  
                0 => '未返现',          
                1 => '已返现',
        ); 
		
		foreach($log_list as $value){
			$param = array();

            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "</ul></span>";
			
            $param['operation'] = $operation;
        	$param['buy_return_id'] = $value['buy_return_id'];
            $param['uid'] = $value['uid'];
            $param['return_type'] = $return_status[$value['return_type']];
            $param['return_commission'] = $value['return_commission'];
            $param['remark'] = $value['remark'];
            $param['status'] = $commission_status[$value['status']];
            $param['createtime'] = date("Y-m-d H:i:s",$value['createtime']);

            $data['list'][$value['id']] = $param;
		}
		
        echo Tpl::flexigridXML($data);
    }
}
