<?php
/**
 * 分销商管理
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_orderControl extends SystemControl{
	public function __construct(){
        parent::__construct();
		Language::read('schema');
	}

	public function indexWt() {
		$this->schema_orderWt();
	}
	/**
	 * 等级列表
	 * @return schema_distribute 
	 */ 
	public function schema_orderWt() {
		$model_order = Model('schema_order');
		$order_list = $model_order->select();
		Tpl::output('order_list', $order_list);
		Tpl::setDirquna('schema');
		Tpl::showpage('schema_order');
	}
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('schema_order');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'order_sn', 'buyer_id', 'order_amount', 'formula', 'parent_id', 'commission_rate', 'commission', 'status');
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
            $param['order_amount'] = $value['order_amount'];
            $param['parent_id'] = $value['parent_id'];
            $param['parent_name'] = $value['parent_name'];
            $param['commission_rate'] = $value['commission_rate'];
            $param['commission_ratio'] = $value['commission_ratio'];
            $param['commission'] = $value['commission'];

            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }
}
