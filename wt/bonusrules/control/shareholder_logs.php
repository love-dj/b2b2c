<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class shareholder_logsControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('shareholder_logs.index');
	}

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
		$model = Model('shareholder_return_log');
		
		if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = 'createtime desc';
		$param = array('id', 'shareholder_return_id', 'uid', 'return_money', 'createtime');
		
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
		$where = array(
			'1' => 1,
		);
		
		$log_list = $model->field('*')->where($where)->page($page)->order($order)->select();
		
		$data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();    
		
		foreach($log_list as $value){
			$param = array();

            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "</ul></span>";
			
            $param['operation'] = $operation;
        	$param['shareholder_return_id'] = $value['shareholder_return_id'];
            $param['uid'] = $value['uid'];
            $param['return_money'] = $value['return_money'];
            $param['createtime'] = date("Y-m-d H:i:s",$value['createtime']);

            $data['list'][$value['id']] = $param;
		}
		
        echo Tpl::flexigridXML($data);
    }
}
