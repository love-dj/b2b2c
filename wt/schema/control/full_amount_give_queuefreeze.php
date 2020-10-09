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
class full_amount_give_queuefreezeControl extends SystemControl{
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


        $where = 'order_full_give.status=2';

        if($member_kw = $_POST['member_keyword']){
            $where .= ' and (order_full_give.member_id='."'".$member_kw."'".' or member.member_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }
        if($interest_num = $_POST['interest_num']){
            $where .= ' and order_full_give.interest_dot='."'".$interest_num."'";
        }
        if($_POST['start_time']&&$_POST['end_time']){  //时间范围

            if($_POST['start_time']>$_POST['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_full_give.add_time >='."'".strtotime($_POST['start_time'].' 00:00:00')."'".' and order_full_give.add_time <='."'".strtotime($_POST['end_time'].' 23:59:59')."'";
        }



        $count = $model->table('order_full_give')->where(array('status'=>2))->count();

        $on = 'order_full_give.member_id=member.member_id';
        $list =  $model->table('order_full_give,member')->join('left')->on($on)->where($where)->field('order_full_give.*,member.member_name')->page(10,$count)->select();


        $model = Model();



        Tpl::output('list',$list);
        Tpl::setDirquna('schema');
        Tpl::output('count',$count);
        Tpl::output('page',$model->showpage());
        Tpl::showpage('full_amount_give_queuefreeze');

    }



    //解除冻结队列
    public function outforzenWt(){
        $data = $_POST['data'];
        $arr_id =explode(',',$data);

        foreach($arr_id as $v){
            $dot =  Model('order_full_give')->where(array('id'=>$v))->find();
            $res = Model('order_full_give')->where(array('id'=>$v))->update(array('status'=>1,'interest_dot_freeze'=>0,'interest_dot'=>$dot['interest_dot_freeze']));
        }
        echo json_encode(array('status'=>1));
    }


    //删除冻结权益队列
    public function del_queuefreezeWt()
    {
        $id = $_GET['id'];var_dump($id);die;
        $res = Model('order_full_give')->where(array('id' => $id))->delete();
        if ($res) {
            echo json_encode(array('status' => 1, 'msg' => '删除成功'));
        } else {
            echo json_encode(array('status' => 0, 'msg' => '删除失败'));
        }
    }



//导出Excel
    public function export_full_maountfreezeWt(){

        $model = Model();



        $where = 'order_full_give.status=2';

        if($member_kw = $_GET['member_keyword']){
            $where .= ' and (order_full_give.member_id='."'".$member_kw."'".' or member.member_name='."'".$member_kw."'".' or member.member_mobile='."'".$member_kw."')";
        }
        if($interest_num = $_GET['interest_num']){
            $where .= ' and order_full_give.interest_dot='."'".$interest_num."'";
        }

        if($_GET['start_time']&&$_GET['end_time']){  //时间范围

            if($_GET['start_time']>$_GET['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and order_full_give.add_time >='."'".strtotime($_GET['start_time'].' 00:00:00')."'".' and order_full_give.add_time <='."'".strtotime($_GET['end_time'].' 23:59:59')."'";
        }


        $on = 'order_full_give.member_id=member.member_id';
        $list =  $model->table('order_full_give,member')->join('left')->on($on)->where($where)->field('order_full_give.*,member.member_name,member.member_mobile')->select();


        foreach ($list as $k=>$v){
            $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            $list[$k]['recent_time'] = date('Y-m-d H:i:s',$v['recent_time']);
            $list[$k]['total_give'] = $v['interest_dot']*$v['unit_price'];
            $list[$k]['give_status'] = $v['status']==1?'返还中':($v['status']==2?'冻结中':'完成');
        }

        $this->exportExcel($list);

    }


    function exportExcel($data, $isDown = false)
    {
        $filename = '满额返现队列' . date('YmdHis');
        $header = array('会员ID','时间', '会员', '权益(个)', '共计赠送金额', '已赠送金额', '未赠送金额', '最近一次赠送时间','最近一次赠送金额','状态');
        $index = array('member_id','add_time','member_name', 'interest_dot', 'total_give', 'gived', 'no_give', 'recent_time', 'recent_money','give_status');
        createtable($data, $filename, $header, $index);
    }





    
}
