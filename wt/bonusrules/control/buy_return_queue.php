<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class buy_return_queueControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('buy_return_queue.index');
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
        $data = array();
		$id = $_GET['id'];
		if(!$id){
			showDialog('参数错误');
		}
		$condition = array(
			'id' => $id
		);
		
		$update_data = array('status' => -1, 'updatetime' => time());
		Model('buy_return')->where($condition)->update($update_data);
		showDialog('冻结成功');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('buy_return');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
		$param = array('id', 'ordersn', 'uid', 'order_money', 'commission_type', 'total_commission', 'return_type', 'each_return_rate', 'pay_commission', 'balance_commission', 'order_state', 'refund_state', 'refund_state', 'status', 'createtime', 'updatetime');
		
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $condition['order_state'] = array('gt', '0');//过滤掉已取消的订单
        //$list = $model->where($condition)->page($page)->order($order)->select();
        $list = Model()->table('buy_return,orders')->field('buy_return.*,orders.order_sn,orders.order_amount,orders.order_state,orders.refund_state')->join('left')->on('buy_return.order_id=orders.order_id')->where($condition)->page($page)->order($order)->select();

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
        //返现方式
        $return_status = array(   
                0 => '递减返现',          
                1 => '等额返现',
        );
        //订单状态 ：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货;
        $order_status = array(  
                0 => '已取消',          
                10 => '未付款',
                20 => '已付款',
                30 => '已发货',
                40 => '已收货'
        ); 
        //退款状态:0是无退款,1是部分退款,2是全部退款
        $refund_status = array(  
                0 => '无退款',          
                1 => '部分退款',
                2 => '全部退款'
        ); 
        foreach ($list as $value) {
        	$param = array();

            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            if($value['refund_state'] > 0){//如果订单发生退款，要执行收回返现操作，这个简单，查退款金额，计算该退返现，扣用户余额，更新记录状态
                $operation .= "<li><a href='index.php?w=buy_return_queue&t=commission_back&id=".$value['id']."'>收回返现</a></li>";
            } 
			$operation .= "<li><a href='index.php?w=buy_return_queue&t=commission_freeze&id=".$value['id']."'>冻结队列</a></li>";	
            $operation .= "</ul></span>";
			
            $param['operation'] = $operation;
        	$param['ordersn'] = $value['ordersn'];
            $param['uid'] = $value['uid'];
            $param['order_money'] = $value['order_money'];
            $param['commission_type'] = $commission_types[$value['commission_type']];
            $param['total_commission'] = $value['total_commission'];
            $param['return_type'] = $return_status[$value['return_type']];
            $param['each_return_rate'] = $value['each_return_rate'];
            $param['pay_commission'] = $value['pay_commission'];
            $param['balance_commission'] = $value['balance_commission'];
            $param['order_state'] = $order_status[$value['order_state']];
            $param['refund_state'] = $refund_status[$value['refund_state']];
            $param['status'] = $commission_status[$value['status']];
			
			if($value['createtime'] > 0){
                $param['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
            }else{
                $param['createtime'] = '临时队列';
            }
			
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
