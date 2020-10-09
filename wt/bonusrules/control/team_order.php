<?php
/**
 * 团队无限级管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class team_orderControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bonusrules');
    }

	public function indexWt() {
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('team_order.index');
	}

    //执行退款操作
    public function commission_back(){
        //后期完善该逻辑
        $data = array();
        $data['msg'] = '退款成功';
        echo Tpl::flexigridXML($data);
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('team_order');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'order_sn', 'buyer_id', 'order_amount', 'commission_amount', 'commission_type', 'calculation_type', 'commission_list', 'order_time', 'settle_time', 'status', 'order_state', 'refund_state');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $condition['order_state'] = array('gt', '0');//过滤掉已取消的订单
        $list = Model()->table('team_order,orders')->field('team_order.*,orders.order_sn,orders.order_amount,orders.order_state,orders.refund_state')->join('left')->on('team_order.order_id=orders.order_id')->where($condition)->page($page)->order($order)->select();

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
        //佣金状态（0：未发放；1：已发放；2：已扣除）
        $commission_status = array(  
                0 => '未发放',          
                1 => '已发放',
                2 => '已扣除'
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
            if($value['refund_status'] > 0){//如果订单发生付款，要执行收回佣金操作，这个简单，查退款金额，计算该退佣金，扣用户余额，更新记录状态
                $operation .= "<li><a href='index.php?w=team_order&t=commission_back&id=".$value['id']."'>收回佣金</a></li>";
            }            
            $operation .= "</ul></span>";
            $param['operation']     = $operation;
        	$param['order_sn'] = $value['order_sn'];
            $param['buyer_id'] = $value['buyer_id'];
            $param['order_amount'] = $value['order_amount'];
            $param['commission_amount'] = $value['commission_amount'];
            $param['commission_type'] = $commission_types[$value['commission_type']];
            $param['calculation_type'] = $value['calculation_type']==1?'下级团队奖励为基数计算':'独立计算';
            $commission_list = unserialize($value['commission_list']);
			$comm_str = '';
			if(!empty($commission_list)){
				foreach($commission_list as $k=>$v){
					/*Array
					(
						"team_layer_count"=>6,
						"team_commission"=>13.16,
						"commission_layers"=>10,
						"same_team_commission"=>5.64,
						"same_layers"=>3,
						"team_level"=>2
					)*/
					if($v['team_commission']>0){
						$comm_str .= '会员【'.$k.'】 团队奖：'.$v['team_commission'].'元<br>';
					}else{						
						$comm_str .= '会员【'.$k.'】 安慰奖：'.$v['same_team_commission'].'元<br>';
					}
				
				}
			}
			$param['commission_list'] = $comm_str;
            $param['order_state'] = $order_status[$value['order_state']];
            $param['refund_status'] = $refund_status[$value['refund_status']];
            $param['status'] = $commission_status[$value['status']];
            $param['order_time'] = date("Y-m-d H:i:s",$value['order_time']);
            if($value['settle_time'] > 0){
                $param['settle_time'] = date("Y-m-d H:i:s",$value['settle_time']);
            }else{
                $param['settle_time'] = '';
            }

            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }
}
