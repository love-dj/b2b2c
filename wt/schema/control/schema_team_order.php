<?php
/**
 * 分销商管理
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_team_orderControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt(){
		$this->schema_team_orderWt();
	}
	/**
	 * 等级列表
	 * @return schema_distribute 
	 */ 
	public function schema_team_orderWt() {
		$model_order = Model('schema_order');
		$order_list = $model_order->select();
		Tpl::output('order_list', $order_list);
		Tpl::setDirquna('schema');
		Tpl::showpage('schema_team_order');
	}
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('schema_team_order');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'order_sn', 'buyer_id', 'order_amount', 'parent_id', 'commission', 'status');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = $model->where($condition)->page($page)->order($order)->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        
        foreach ($list as $value) {
        	$param = array();
        	$param['order_sn'] = $value['order_sn'];
            $param['buyer_id'] = $value['buyer_id'];
            $buyer = Model('member')->where(array('member_id'=>$value['buyer_id']))->find();
            $param['buyer_name'] = $buyer['member_name'];
            $param['order_amount'] = $value['order_amount'];
            $param['parent_id'] = $value['parent_id'];
            $parent = Model('member')->where(array('member_id'=>$value['parent_id']))->find();
            $param['parent_name'] = $parent['member_name'];
            $commission = '';
            foreach (unserialize($value['commission']) as $k=>$v){
                $commission .= " 第".$v['floor'].'层 / '.$v['name'].' / '.$v['commission']."<br />";
            }
            $param['commission'] = $commission;
            if($value['status'] == 0){
                $param['status'] = "<span style='color: red'>未发放</span>";
            }else{
                $param['status'] = "<span style='color: green'>已发放</span>";
            }
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }
}
