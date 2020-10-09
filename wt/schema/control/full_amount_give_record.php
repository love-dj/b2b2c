<?php
/**
 * schema管理
 * 2018/10/22
 * auth feng
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class full_amount_give_recordControl extends SystemControl{
    /*private $links = array(
        array('url'=>'act=schema_config&op=settlement', 'lang'=>'settlement'),
        array('url'=>'act=schema_config&op=active', 'lang'=>'active'),
        array('url'=>'act=schema_config&op=center', 'lang'=>'center'),
    );*/

	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt() {
        $model = Model();

        $where = 'common=0';
        if($member_kw = $_POST['member_keyword']){
            $where .= ' and (order_full_give_record.member_id='."'".$member_kw."'".' or member.member_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }

        if($_POST['start_time']&&$_POST['end_time']){  //时间范围

            if($_POST['start_time']>$_POST['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_full_give_record.add_time >='."'".strtotime($_POST['start_time'].' 00:00:00')."'".' and order_full_give_record.add_time <='."'".strtotime($_POST['end_time'].' 23:59:59')."'";
        }

        $count = $model->table('order_full_give_record')->count();
        $on = 'order_full_give_record.member_id=member.member_id';
        $list = $model->table('order_full_give_record,member')->join('left')->on($on)->field('order_full_give_record.*,member.member_name,member_mobile')->where($where)->page(10,$count)->select();

//var_dump($list);die;
        Tpl::output('list',$list);
        Tpl::setDirquna('schema');
        Tpl::output('count',$count);
        Tpl::output('page',$model->showpage());

        Tpl::showpage('full_amount_give_record');
	}


//导出Excel
	public function export_full_give_recordWt(){
        $model = Model();

        $where = 'common=0';
        if($member_kw = $_GET['member_keyword']){
            $where .= ' and (order_full_give_record.member_id='."'".$member_kw."'".' or member.member_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }

        if($_GET['start_time']&&$_GET['end_time']){  //时间范围

            if($_GET['start_time']>$_GET['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_full_give_record.recent_time >='."'".strtotime($_GET['start_time'].' 00:00:00')."'".' and order_full_give_record.recent_time <='."'".strtotime($_GET['end_time'].' 23:59:59')."'";

        }

        $on = 'order_full_give_record.member_id=member.member_id';
        $list = $model->table('order_full_give_record,member')->join('left')->on($on)->field('order_full_give_record.*,member.member_name,member_mobile')->where($where)->select();

        foreach ($list as $k=>$v){
            $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);  //时间
            $list[$k]['total_give'] = $v['interest_dot']*$v['unit_price'];     //权益单价
            $list[$k]['recent_money'] = $v['recent_money'].'元';               //返现金额
            $list[$k]['platform_money'] = $v['platform_money'].'元';           //营业额/利润
            $list[$k]['platform_give_rate'] = $v['platform_give_rate'].'%';              //分配比例
            $list[$k]['total_money'] = $v['total_money'].'元';                   //总权益

            $list[$k]['unit_price'] = $v['unit_price'].'元';                   //权益单价
            $list[$k]['rest_money'] = $v['rest_money'].'元';                   //剩余权益
            $list[$k]['total_dot'] = $v['total_dot'].'个';                     //权益总个数


        }

        $this->exportExcel($list);


    }


    function exportExcel($data, $isDown = false)
    {
        $filename = '满额返现记录' . date('YmdHis');
        $header = array('会员ID', '时间','会员','返现金额','营业额/利润','分配比例','总权益','权益单价','剩余权益','权益总个数' );
        $index = array('member_id','add_time','member_name', 'recent_money', 'platform_money', 'platform_give_rate', 'total_money', 'unit_price','rest_money', 'total_dot');
        createtable($data, $filename, $header, $index);
    }
    
}
