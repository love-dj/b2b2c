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
class full_amount_give_configControl extends SystemControl{
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


//$this->auto_fullamount_giveWt();
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('full_amount_give_config');


	}

    /**
     * 满额赠送设置
     */
    public function configWt() {

        if($_POST){
             $arr = array(
                 array('name'=>'fullamount_give_switch','value'=>$_POST['fullamount_give_switch'] ),  //是否开启满额赠送  0关 1开
                 array('name'=>'fullamount_show_table','value'=>$_POST['fullamount_show_table']),   //是否显示前端赠送列表  0不显示 1显示
                 array('name'=>'fullamount_sett_method','value'=>$_POST['fullamount_sett_method']),    //计算方式 0以营业额计算  1以利润计算
                 array('name'=>'fullamount_interval','value'=>$_POST['fullamount_interval']),        //结算时间段 0昨天  1上个月
                 array('name'=>'fullamount_give_basenum','value'=>$_POST['fullamount_give_basenum']),       //赠送基数()
                 array('name'=>'fullamount_interest_limit','value'=>intval($_POST['fullamount_interest_limit'])), //权益总数限制 [每个会员再返队列最多x个,只能输入正整数且不能小于1个]
                 array('name'=>'fullamount_give_rate','value'=>$_POST['fullamount_give_rate']),       //权益赠送比例  权益赠送比例,如果填空，默认100%  [100元一个权益，如果设置80%，那么一共赠送会员80元]
                 array('name'=>'fullamount_each_value','value'=>$_POST['fullamount_each_value']),      //1个权益等于(默认1元)
                 array('name'=>'fullamount_give_time','value'=>$_POST['fullamount_give_time']),              //赠送时间
                 array('name'=>'fullamount_give_everyday','value'=>$_POST['fullamount_give_time']==0?$_POST['fullamount_give_everyday']:null)//每天几点赠送
             );

            foreach($arr as $k=>$v){
                $res = Model('setting')->update($v);
                if(!$res){
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
            showMessage(Language::get('nc_common_save_succ'));
        }

    }


    /*
        * 消息通知设置
        * */
    public function region_dividendWt(){

        if($_POST){

        }

    }


////执行满额赠送
//    public function auto_fullamount_giveWt()
//    {
//        $is_switch = get_setting_value('fullamount_give_switch');  //满额赠送开关
//        if (!$is_switch) return; //未开启满额赠送
//        $time_type = get_setting_value('fullamount_interval');  //满额赠送时间段（0 昨天  1上个月）
//        if ($time_type == 1) {  //上个月起始时间
//            $start_time = mktime(0, 0, 0, date("m") - 1, 1, date("Y"));
//            $end_time = mktime(23, 59, 59, date("m"), 0, date("Y"));
//        } else {  //昨天起始时间
//            $start_time = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
//            $end_time = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
//        }
//
//        $orders = Model('orders')->where(array('order_state' => 40, 'finnshed_time' => array('between', "$start_time,$end_time")))->select(); //时间范围内完成的订单
//        $settle_method = get_setting_value('fullamount_sett_method');  //计算方式 0营业额 1利润
//        $platform_grant = 0; //平台发放金额（根据利润结算）
//
//        if ($settle_method == 0) { //根据营业额结算,获取订单的总额
//
//            foreach ($orders as $k => $values) {
//                $platform_grant += $values['goods_amount'];
//            }
//
//        } else { //根据利润结算，获取订单利润总额
//
//            foreach ($orders as $key => $values) { //获取每笔订单的利润
//                $goods = Model('order_goods')->where(array('order_id' => $values['order_id']))->field('goods_id,goods_num')->select();
//                $all_costprice = 0;  //每笔订单的利润
//
//                foreach ($goods as $k => $v) {  //遍历该订单的所有商品获取成本价
//
//                    $goods_commonid = Model('goods')->getfby_goods_id($v['goods_id'], 'goods_commonid');
//
//                    $costprice = Model('goods_common')->getfby_goods_commonid($goods_commonid, 'goods_costprice');  //成本价
//
//                    $all_costprice += $costprice * $v['goods_num']; //成本价
//                }
//
//                $orders[$key]['order_profit'] = ($values['goods_amount'] - $all_costprice) > 0 ? $values['goods_amount'] - $all_costprice : 0;  //每笔订单利润
//                $platform_grant += $orders[$key]['order_profit'];
//
//
//            }
//
//        }
//
//            $platform_give_rate = get_setting_value('fullamount_give_basenum'); //平台翻新比例
//            $platform_grant = $platform_grant*$platform_give_rate/100;  //平台营业额（利润）*赠送基数（比例）
//
//            $_platform_grant = $platform_grant; //平台本期营业额记录到order_full_give_record表(乘以比例之后的值)
//
//
//
//            $grant_dot =  Model('order_full_give')->where(array('status'=>1))->sum('interest_dot'); //要发放的权益点总数
//
//            if($grant_dot==0) return;
//            $each_dot_val = round($platform_grant/$grant_dot,2) ;  //每个权益点的钱
//
//            $queue_grant = Model('order_full_give')->where(array('status'=>1))->select();  //代发的所有队列
//
//            if(count($queue_grant)==0) return ;
//
//            foreach($queue_grant as $k=>$val) {
//
//                if($platform_grant<=0) break;
//
//                $this_grant = $val['interest_dot']*$each_dot_val; //该个队列此次应该发放的钱
//                $rest =  $val['no_give']-$val['gived'];  //未赠送-已赠送
//
//                if($this_grant>=$rest){ //该队列能赠送完成
//                    $platform_grant -= $rest;
//
//                    $arr = array(
//                         'gived'       => $val['no_give'],
//                         'recent_time' => time(),  //最近一次更新时间
//                        'recent_money' => $rest,   //最近一次赠送金额
//                             'no_give' => 0,
//                              'status' => 3  //已完成状态
//                    );
//                    Model('order_full_give')->where(array('id'=>$val['id']))->update($arr); //该队列赠送完成
//
//                   $ee = Model('order_full_give')->where(array('member_id'=>$val['member_id'],'rate'=>$val['rate'],'unit_price'=>$val['unit_price'],'status'=>1))->find();
//                   if(empty($ee)){ //该种队列在正常队列中已经不存在了，寻找冻结队列
//
//                        //查询有没有该种类型的冻结队列，有 就解冻
//                        $res = Model('order_full_give')->where(array('member_id'=>$val['member_id'],'rate'=>$val['rate'],'unit_price'=>$val['unit_price'],'status'=>2))->find();
//                        if($res){
//                            Model('order_full_give')->where(array('id'=>$res['id']))->update(array('status'=>1,'interest_dot'=>$res['interest_dot_freeze'],'interest_dot_freeze'=>0));
//                        }
//                   }
//                }else{  //该队列不能赠送完成
//                    $platform_grant -= $this_grant;
//                    $new = $val['gived']+$this_grant;
//                    $arr = array(
//                        'gived'       => $new,
//                        'no_give'     => $val['no_give']-$new,
//                        'recent_time' => time(),
//                        'recent_money'=> $this_grant,
//                    );
//                    Model('order_full_give')->where(array('id'=>$val['id']))->update($arr);
//
//                }
//
//
//                //数据进order_full_give_record表
//                $arr_record = array(
//                    'add_time'=>$arr['recent_time'],   //最近一次更新时间
//                    'member_id'=>$val['member_id'],      //会员id
//                    'recent_money' =>$arr['recent_money'], //最近一次赠送金额
//                    'platform_money' => $_platform_grant, //平台本期营业额
//                    'total_money'     =>$val['interest_dot']*$val['unit_price']*$platform_give_rate,  //该队列的总权益(要赠送的钱)*平台赠送比例
//                    'rest_money'    => $arr['no_give'],        //剩余权益（剩余要赠送的钱）
//                    'unit_price'    => $each_dot_val,           //本期权益单价
//                    'total_dot'     => $val['interest_dot'],   //该队权益点总数
//                    'paltform_give_rate' =>$platform_give_rate  //平台赠送比例
//                );
//
//                Model('order_full_give_record')->insert($arr_record);
//
//                //满额赠送的钱进入 用户钱包
//                Model('member')->where(array('member_id'=>$val['member_id']))->setInc('available_predeposit',$arr['recent_money']);
//
//            }
//
//    }
//
//





}
