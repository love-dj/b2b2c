<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class full_return_queueControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('full_return_queue.index');
	}

    //执行退款操作
    public function commission_backWt(){
        //后期完善该逻辑
        $data = array();
        $data['msg'] = '退款成功';
        echo Tpl::flexigridXML($data);
    }

    //执行冻结操作
    public function commission_freezeWt(){
        //后期完善该逻辑
        $data = array();
		$id = $_GET['id'];
		if(!$id){
			showDialog('参数错误');
		}
		$condition = array(
			'id' => $id
		);
		
		$update_data = array('status' => -1, 'updatetime' => time());
		Model('full_return')->where($condition)->update($update_data);
		showDialog('冻结成功');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('full_return');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = 'createtime DESC';
		$param = array('id', 'uid', 'limit', 'limit_money', 'return_money', 'return_base_ratio', 'total_commission', 'pay_commission', 'balance_commission', 'last_pay_commission', 'createtime', 'updatetime', 'status');
		
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = Model()->table('full_return')->field('*')->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();        
        //佣金计算增加项目（1：订单实际支付金额；2：商品现价；3：商品成本；4：订单利润；）',
        $commission_types = array(
                1 => '订单实际支付金额',
                2 => '商品现价',
                3 => '商品成本',
                4 => '订单利润'
        );
        //返现状态
        $commission_status = array(  
                '-1' => '已冻结',  
                0 => '待返现',          
                1 => '正在返现',
                2 => '返现完成'
        ); 
        foreach ($list as $value) {
        	$param = array();

            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>"; 
			$operation .= "<li><a href='index.php?w=full_return_queue&t=commission_freeze&id=".$value['id']."'>冻结队列</a></li>";	
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['uid'] = $value['uid'];
            $param['limit'] = $value['limit'];
            $param['limit_money'] = $value['limit_money'];
            $param['commission_type'] = $commission_types[$value['commission_type']];
            $param['return_money'] = $value['return_money'];
            $param['return_base_ratio'] = $value['return_base_ratio'];
            $param['total_commission'] = $value['total_commission'];
            $param['pay_commission'] = $value['pay_commission'];
            $param['balance_commission'] = $value['balance_commission'];
            $param['last_pay_commission'] = $value['last_pay_commission'];
            $param['status'] = $commission_status[$value['status']];
            $param['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
            if($value['updatetime'] > 0){
                $param['updatetime'] = date("Y-m-d H:i:s",$value['updatetime']);
            }else{
                $param['updatetime'] = '';
            }

            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }
}
