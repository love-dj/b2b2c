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
class single_consume_give_queueControl extends SystemControl{
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
        if($_POST['back_state']){
            $where = ' order_single_consume.status='."'".$_POST['back_state']."'";
        }else{
            $where = 'common=0';
        }

        if($member_kw = $_POST['member_keyword']){
            $where .= ' and (order_single_consume.member_id='."'".$member_kw."'".' or order_single_consume.buyer_name='."'".$member_kw."'".' or order_single_consume.member_mobile='."'".$member_kw."')";
        }

        if($_POST['start_time']&&$_POST['end_time']){  //时间范围

            if($_POST['start_time']>$_POST['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_single_consume.add_time >='."'".strtotime($_POST['start_time'].' 00:00:00')."'".' and order_single_consume.add_time <='."'".strtotime($_POST['end_time'].' 23:59:59')."'";
        }

	    $count = $model->table('order_single_consume')->where($where)->count();
        $on = "member.member_id=order_single_consume.member_id";
        $list = $model->table('order_single_consume,member')->join('left')->on($on)->field('member.member_mobile,order_single_consume.*')->where($where)->page(10,$count)->order('add_time desc')->select();

//        var_dump($list);die;
        Tpl::output('list',$list);
        Tpl::output('count',$count);

        Tpl::output('page',$model->showpage());
        Tpl::setDirquna('schema');
        Tpl::showpage('single_consume_give_queue');

	}


//导出Excel
    public function export_single_consume_returnWt(){

        $model = Model();


        if($_GET['back_state']){
            $where = ' order_single_consume.status='."'".$_GET['back_state']."'";
        }else{
            $where = 'common=0';
        }

        if($member_kw = $_GET['member_keyword']){
            $where .= ' and (order_single_consume.member_id='."'".$member_kw."'".' or order_single_consume.buyer_name='."'".$member_kw."'".' or order_single_consume.member_mobile='."'".$member_kw."')";
        }

        if($_GET['start_time']&&$_GET['end_time']){  //时间范围

            if($_GET['start_time']>$_GET['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_single_consume.add_time >='."'".strtotime($_GET['start_time'].' 00:00:00')."'".' and order_single_consume.add_time <='."'".strtotime($_GET['end_time'].' 23:59:59')."'";
        }


        $on = "member.member_id=order_single_consume.member_id";
        $list = $model->table('order_single_consume,member')->join('left')->on($on)->field('order_single_consume.*,member.member_mobile')->where($where)->order('add_time desc')->select();


        foreach ($list as $k=>$v){

            $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            $list[$k]['recent_time'] = isset($v['recent_time'])&&!empty($v['recent_time'])?date('Y-m-d H:i:s',$v['recent_time']):'';
            $list[$k]['gived_money'] = $v['gived_money'].'元';
            $list[$k]['no_gived_money'] = $v['no_gived_money'].'元';
            $list[$k]['recent_money'] = $v['recent_money'].'元';
            $list[$k]['status'] = $v['status']==1?'返还中':'完成';
        }

        $this->exportExcel($list);

    }


    function exportExcel($data, $isDown = false)
    {
        $filename = '满额返现队列' . date('YmdHis');
        $header = array('会员ID', '时间','会员',  '已返现金额', '未返现金额', '最近一次返现时间','最近一次返现金额','状态');
        $index = array('member_id','add_time','buyer_name', 'gived_money', 'no_gived_money', 'recent_time', 'recent_money','status');
        createtable($data, $filename, $header, $index);
    }








    
}
