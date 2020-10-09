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
class region_fenhong_recordControl extends SystemControl{
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

		$this->region_fenhong_recordWt();

	}




    /**
     * 区域分红记录
     */
    public function region_fenhong_recordWt() {

//        $schema_config = Model('setting');
//        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('region_fenhong_record');
    }



    public function get_xmlWt(){

        $model = Model();
        $count = $model->table('member_agent_log')->count();

//        $list = $model->table('member_agent_log,member,orders')->join('left')->on('member.member_id=member_agent_log.member_id,member_agent_log.order_id=orders.order_id')->field('member_agent_log.*,member.member_mobile,orders.order_sn,orders.goods_amount')->page(20,$count)->select();

        $list = $model->table('member_agent_log,member,orders,member_agent')->join('left')->on('member.member_id=member_agent_log.member_id,member_agent_log.order_id=orders.order_id,member_agent.member_id=member_agent_log.member_id')->field('member_agent_log.*,member.member_mobile,member_agent.province,member_agent.city,member_agent.area,orders.order_sn,orders.goods_amount')->page(15,$count)->select();


        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
//        $param = array('member_id', 'account', 'member_mobile', 'become_agent_time', 'agent_area_id','agent_level', 'area_total', 'area_total',);

//var_dump($list);die;
        foreach ($list as $value) {
            $param = array();
            $param['member_id'] = $value['member_id'];             //会员id
            $param['account'] = $value['agent_account'];           //会员名

            $param['phone'] = $value['member_mobile'];                    //手机
            $param['agent_area_name'] = $this->get_area($value['province']).'&nbsp;'.$this->get_area($value['city']).'&nbsp;'.$this->get_area($value['area']); //代理区域
            $param['agent_level'] =$value['agent_level']==1?'省':($value['agent_level']==2?'市':'区县');              //代理等级
            $param['add_time'] =  $value['add_time'];                   // 时间
            $param['order_sn'] =  $value['order_sn'];                   // 订单号
            $param['order_goods_amount'] =  $value['goods_amount'];   //订单金额
            $param['agent_dividend_rate'] =  $this->get_dividend_rate($value['agent_level']).'%';  // 分红比例
            $param['money'] =  $value['money'];                          //分红金额
            $param['status'] =  $value['status']==1?'未结算':'已结算';

            $data['list'][$value['id']] = $param;
//            var_dump($data);die;
        }

        echo Tpl::flexigridXML($data);
    }



    //获取地名
    public function get_area($area_code){
        $area = Model('area')->where(array('area_id'=>$area_code))->find();
        return $area['area_name'];
    }

    //根据代理等级获取分红比例
    public function  get_dividend_rate($level){
        if($level==1){
            return get_setting_value('province_rate'); //省比例
        }elseif($level==2){
            return get_setting_value('city_rate');    //市比例
        }else{
            return get_setting_value('area_rate');    //区比例
        }
    }

}
