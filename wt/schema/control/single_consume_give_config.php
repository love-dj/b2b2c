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
class single_consume_give_configControl extends SystemControl
{
    /*private $links = array(
        array('url'=>'act=schema_config&op=settlement', 'lang'=>'settlement'),
        array('url'=>'act=schema_config&op=active', 'lang'=>'active'),
        array('url'=>'act=schema_config&op=center', 'lang'=>'center'),
    );*/

    public function __construct()
    {
        parent::__construct();
        Language::read('schema');
    }

    public function indexWt()
    {

//$this->auto_single_backCash();
        $schema_config = Model('setting');
        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting', $setting_list);
        Tpl::setDirquna('schema');

        Tpl::setDirquna('schema');
        Tpl::showpage('single_consume_give_config');

    }

    //消费返现设置
    public function configWt()
    {
        if ($_POST) {
            $arr = array(
                array('name' => 'is_consume_return', 'value' => $_POST['is_consume_return']),  //是否开启满额赠送  0关 1开

                array('name' => 'consume_return_method', 'value' => $_POST['consume_return_method']),   //是否显示前端赠送列表  0不显示 1显示
                array('name' => 'consume_return_default_rate', 'value' => $_POST['consume_return_default_rate']),   //默认消费赠送比例

                array('name' => 'consume_return_issue_rate', 'value' => $_POST['consume_return_issue_rate']),     //每期返现比例

                array('name' => 'consume_return_time', 'value' => $_POST['consume_return_time']),    //返现时间 0每天  1每周
                array('name' => 'consume_return_everyday', 'value' => $_POST['consume_return_everyday']),    //每天几点

                array('name' => 'consume_return_week', 'value' => $_POST['consume_return_week']),            //周几
                array('name' => 'consume_return_interval_time', 'value' => $_POST['consume_return_interval_time']) //延时返现时间
            );

            foreach ($arr as $k => $v) {
                $res = Model('setting')->update($v);
                if (!$res) {
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
            showMessage(Language::get('nc_common_save_succ'));
        }
    }

//自动执行单品消费返现
    function auto_single_backCash(){


        $delay_time = get_setting_value('consume_return_interval_time')*86400;  //延期发放时间
        $back_cash_method = get_setting_value('consume_return_method');  //返现方式  0递减返现 1等比返现
        $issue_rate = get_setting_value('consume_return_issue_rate')/100;    //每期返现比例

        $goods_back =  Model('order_single_consume')->where(array('status'=>1))->select();  //查询还处于返还中的商品

        foreach($goods_back as $k=>$val){

            if($val['add_time']+$delay_time>time()) continue;  //还未到返现时间

            $money = $back_cash_method==0?$val['no_gived_money']*$issue_rate:$val['total_give']*$issue_rate; //根据返现方式 获取本次返现金额

            if($money+$val['gived_money']>=$val['total_give']){  //本次能返现完
                $money = $val['total_give']-$val['gived_money'];
                $arr1 = array(
                    'id' =>$val['id'],
                    'gived_money'=>$val['total_give'],
                    'no_gived_money' =>0,
                    'recent_time'  => time(),
                    'recent_money' => $money,
                    'issue_rate'   => $issue_rate,  //本次返现比例
                    'status'       => 2            //返现完成状态
                );

            }else{ //本次不能返现完
                $arr1 = array(
                    'id' =>$val['id'],
                    'gived_money'=>$val['gived_money']+$money,
                    'no_gived_money' =>$val['no_gived_money']-$money,
                    'recent_time'  => time(),
                    'recent_money' => $money,
                    'issue_rate'   => $issue_rate   //本次返现比例
                );


            }

            Model('order_single_consume')->update($arr1);
            $arr2 = array(
                'add_time' => time(),
                'member_id' => $val['member_id'],    //会员id
                'buyer_name' =>$val['buyer_name'],   //会员名
                'give_type'  =>$back_cash_method,     //返现方式
                'give_money' => $money,               //本期返现金额
                'each_rate'  => get_setting_value('consume_return_issue_rate')  //每期返现比例
            );
            Model('order_single_consume_record')->insert($arr2);

            Model('member')->where(array('member_id'=>$val['member_id']))->setInc('single_backcash_wallet',$money);  //member表的钱包 增加
        }
    }
}
