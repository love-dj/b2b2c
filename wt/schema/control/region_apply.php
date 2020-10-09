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
class region_applyControl extends SystemControl{
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

		$this->region_applyWt();

	}




    /**
     * 区域代理申请
     */
    public function region_applyWt() {

//        $schema_config = Model('setting');
//        $setting_list = $schema_config->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('schema');
        Tpl::showpage('region_apply');
    }


    public function get_xmlWt(){

        $model = Model();
        $count = $model->table('member_agent_apply')->count();


        $list = $model->table('member_agent_apply')->page(15,$count)->select();

//var_dump($list);die;
//var_dump($list);die;
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
//        $param = array('member_id', 'account', 'member_mobile', 'become_agent_time', 'agent_area_id','agent_level', 'area_total', 'area_total',);





        foreach ($list as $value) {
            $param = array();
            $param['member_id'] = $value['member_id'];             //会员id
            $param['account'] = $value['account'];         //会员名

            $param['truename'] = $value['truename'];         //姓名

            $param['phone'] = $value['phone'];            //手机
            $param['agent_area_name'] = $this->get_area($value['province']).'&nbsp;'.$this->get_area($value['city']).'&nbsp;'.$this->get_area($value['area']);         //申请区域

            $param['agent_level'] = $value['agent_level']==1?'省':($value['agent_level']==2?'市':'区县');             //代理等级

            $param['add_time'] =  $value['add_time'];       // 申请时间
            $param['chk_status'] = $value['chk_status']==0?'<span class="aa">待审核</span>':($value['chk_status']==1?'<span class="bb">通过</span>':'<span class="cc">不通过</span>');                 //累计结算金额
            if($value['chk_status']==0){
            $param['operation'] = "<a class='btn red' onclick=\"check_({$value['id']})\"><i class=' fa-trash-o'></i>审核</a>";
            }else{
                $param['operation'] = "<a class='btn red' onclick=\"del({$value['id']})\"><i class='fa fa-trash-o'></i>删除</a>";

            }
            $data['list'][$value['id']] = $param;
        }

        echo Tpl::flexigridXML($data);
    }





    //获取地名
    public function get_area($area_code){
        $area = Model('area')->where(array('area_id'=>$area_code))->find();
        return $area['area_name'];
    }


    //区域代理申请-------审核操作
    public function check_applyWt(){
        $id = $_POST['id'];
        $status = $_POST['status'];  //1通过  2不通过

        $info = Model('member_agent_apply')->where(array('id'=>$id))->find();

        if($status ==1){  //通过

            $is_area_dividend = get_setting_value('is_area_dividend'); //是否开启独家代理

            if($is_area_dividend){  //开启独家代理
                $exist = Model('member_agent')->where(array('agent_area_id'=>$info['agent_area_id']))->find();

                if($exist['id']){
                    echo  json_encode(array('status'=>0,'msg'=>'该地区已存在代理商'));exit;
                }
            }
        }
        $sql = 'update lbsz_member_agent_apply set chk_status='.$status.' where(id='.$id.')';
        $model = Model();

        $model->beginTransaction();
        $res = $model->execute($sql);
        if(!$res){
            echo json_encode(array('status'=>0,'msg'=>'审核失败请重试'));exit;
        }
        if($status==1){ //审核通过数据进member_agent表
            $apply =  Model('member_agent_apply')->where(array('id'=>$id))->find();
            $data = array(
                'account'     => $apply['account'],     //账号
                'truename'    => $apply['truename'],    //真实姓名
                'member_id'   => $apply['member_id'],   //member表id
                'agent_level' => $apply['agent_level'],  //申请等级
                'agent_area_id' => $apply['agent_area_id'],  //申请代理区域
                'become_agent_time' => date('Y-m-d H:i:s',time()),  //成为区域代理时间
                'province' => $info['province'],   //省id
                'city' => $info['city'],           //市id
                'area' => $info['area'],           //区id
            );
            $res = Model('member_agent')->insert($data);
        }
        if($res){
            $model->commit();
            echo json_encode(array('status'=>1,'msg'=>'审核成功'));exit;
        }else{
            $model->rollback();
            echo json_encode(array('status'=>0,'msg'=>'审核失败请重试'));exit;
        }
    }

    //删除审核申请
    public function delWt(){
        $id = $_POST['id'];
       $res = Model('member_agent_apply')->where(array('id'=>$id))->delete();
        if($res){
            echo json_encode(array('status'=>1,'msg'=>'删除成功'));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'删除失败，请重试'));
        }
    }



}
