<?php
/**
 * 分销商管理
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_team_manageControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

//	public function indexWt(){
//		$this->schema_team_manageWt();
//	}
	/**
	 * 等级列表
	 * @return schema_distribute 
	 */ 
	public function indexWt() {
//		$model_manage = Model('schema_manage');
//		$manage_list = $model_manage->select();
//
//		Tpl::output('manage_list', $manage_list);
		Tpl::setDirquna('schema');
		Tpl::showpage('schema_team_manage');
	}
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('schema_team_manage');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'mid', 'parent_id', 'level_name', 'nickname', 'true_name', 'level_name', 'distribute_total');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
//        var_dump($condition,$page,$order);die;

        $list = $model->where($condition)->page($page)->order($order)->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $value) {
        	$param = array();
        	$param['mid'] = $value['mid'];
            $param['nickname'] = $value['nickname'];
            $param['true_name'] = $value['true_name'];
            $parent = Model('member')->where(array('member_id'=>$value['parent_id']))->find();
            $param['parent_id'] = $value['parent_id'];
            $param['parent_name'] = $parent['member_name'];
            $param['level_name'] = $value['level_name'];
            $param['distribute_total'] = $value['distribute_total'];

            $data['list'][$value['id']] = $param;
        }
        var_dump($data);die;
        echo Tpl::flexigridXML($data);
    }
}
