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
class single_consume_give_recordControl extends SystemControl{
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
            $where .= ' and (order_single_consume_record.member_id='."'".$member_kw."'".' or order_single_consume_record.buyer_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }

        if($_POST['start_time']&&$_POST['end_time']){  //时间范围

            if($_POST['start_time']>$_POST['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_single_consume_record.add_time >='."'".strtotime($_POST['start_time'].' 00:00:00')."'".' and order_single_consume_record.add_time <='."'".strtotime($_POST['end_time'].' 23:59:59')."'";

        }

        $count = $model->table('order_single_consume_record')->where($where)->count();
        $on = 'order_single_consume_record.member_id=member.member_id';
        $list = $model->table('order_single_consume_record,member')->join('left')->on($on)->field('order_single_consume_record.*,member_mobile')->where($where)->page(10,$count)->select();


//        var_dump($list);die;
        Tpl::output('list',$list);

        Tpl::output('count',$count);

        Tpl::output('page',$model->showpage());
        Tpl::setDirquna('schema');
        Tpl::showpage('single_consume_give_record');


    }




    //导出Excel
    public function export_single_consume_recordWt(){

        $model = Model();



        $where = 'common=0';
        if($member_kw = $_GET['member_keyword']){
            $where .= ' and (order_single_consume_record.member_id='."'".$member_kw."'".' or order_single_consume_record.buyer_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }

        if($_GET['start_time']&&$_GET['end_time']){  //时间范围

            if($_GET['start_time']>$_GET['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_single_consume_record.add_time >='."'".strtotime($_GET['start_time'].' 00:00:00')."'".' and order_single_consume_record.add_time <='."'".strtotime($_GET['end_time'].' 23:59:59')."'";

        }


        $on = 'order_single_consume_record.member_id=member.member_id';
        $list = $model->table('order_single_consume_record,member')->join('left')->on($on)->field('order_single_consume_record.*,member_mobile')->where($where)->select();


        foreach ($list as $k=>$v){
            $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);

            $list[$k]['give_type'] = $v['give_type']==1?'递减返现':'等比返现';
            $list[$k]['give_money'] = $v['give_money'].'元';

        }

        $this->exportExcel($list);

    }


    function exportExcel($data, $isDown = false)
    {
        $filename = '满额返现队列' . date('YmdHis');
        $header = array('会员ID','时间', '会员', '返现方式', '本期赠送金额');
        $index = array('member_id','add_time','buyer_name', 'give_type', 'give_money');
        createtable($data, $filename, $header, $index);
    }







}
