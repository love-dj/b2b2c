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
class region_managementControl extends SystemControl{
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

		$this->region_managementWt();

	}




    /**
     * 区域代理管理
     */
    public function region_managementWt() {

        $model = Model();
        $count = $model->table('member_agent')->count();

        $where = 'common=0';
        if($member_keyword = $_POST['member_keyword']){ //会员id、会员名、手机
            $where .= ' and (member.member_id='."'".$member_keyword."'".' or member.member_name='."'".$member_keyword."'".' or member.member_mobile='."'".$member_keyword."')";

        }
        if($area_keyword = $_POST['area_keyword']){  //区域名称
            $area_id = Model('area')->getfby_area_name($area_keyword,'area_id');
            $where .= ' and (member_agent.province='."'".$area_id."'".' or member_agent.city='."'".$area_id."'".' or member_agent.area='."'".$area_id."')";
        }

        if($level_keyword = $_POST['level_keyword']){  //区域等级
            $where .=' and  member_agent.agent_level='."'".$level_keyword."'";
//            var_dump($where);die;
        }

        if($_POST['start_time']&&$_POST['end_time']){  //时间范围

            if($_POST['start_time']>$_POST['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and member_agent.become_agent_time >='."'".$_POST['start_time']."'".' and member_agent.become_agent_time <='."'".$_POST['end_time']."'";
        }

//var_dump($where);die;

        $on = 'member_agent.member_id=member_agent_log.member_id,member.member_id=member_agent.member_id,area.area_id=member_agent.agent_area_id';
        $list = $model->table('member_agent,member_agent_log,member,area')->join('left')->on($on)->field('member_agent.*,member_agent_log.money,member_agent_log.status,area.area_name,member.member_name,member.member_mobile')->where($where)->page(15,$count)->group('member_id')->select();
//var_dump($list);die;
        foreach ($list as $k=>$v){
            $list[$k]['set_money'] = Model('member_agent_log')->where(array('member_id'=>$v['member_id'],'status'=>0))->sum('money');  //累计结算金额(已领取的)
            $list[$k]['agent_dividend_rate'] = $this->get_dividend_rate($v['agent_level']).'%';      //分红红比例
            $list[$k]['agent_area_name'] = $this->get_area($v['province']).'&nbsp;'.$this->get_area($v['city']).'&nbsp;'.$this->get_area($v['area']);         //代理区域

        }


        Tpl::output('agent_list',$list);
        Tpl::setDirquna('schema');
        Tpl::output('count',$count);

        Tpl::output('page',$model->showpage());
        Tpl::showpage('region_management');
    }




//    public function get_xmlWt(){
//
////        var_dump($_GET['member_keyword'],$_GET['area_keyword'],$_GET['level_keyword'],$_GET['start_time'],$_GET['end_time']);die;
//        $model = Model();
//        $count = $model->table('member_agent')->count();
//
//
//        $on = 'member_agent.member_id=member_agent_log.member_id,member.member_id=member_agent.member_id,area.area_id=member_agent.agent_area_id';
//        $list = $model->table('member_agent,member_agent_log,member,area')->join('left')->on($on)->page(15,$count)->field('member_agent.*,member_agent_log.money,member_agent_log.status,area.area_name,member.member_id,member.member_name,member.member_mobile,member.member_provinceid,member.member_cityid,member.member_areaid')->where($where)->select();
//
//
////var_dump($list);die;
//
//        $data = array();
//        $data['now_page'] = $model->shownowpage();
//        $data['total_num'] = $model->gettotalnum();
////        $param = array('member_id', 'account', 'member_mobile', 'become_agent_time', 'agent_area_id','agent_level', 'area_total', 'area_total',);
//
//        foreach ($list as $k=>$v){
//            $list[$k]['set_money'] = Model('member_agent_log')->where(array('member_id'=>$v['member_id'],'status'=>0))->sum('money');
//        }
//
////    var_dump($list);die;
//
//        foreach ($list as $value) {
//
//
//            $operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['id'].");'><i class='fa fa-trash-o'></i>删除</a>";
//            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
//            $operation .= "<li><a href='index.php?act=region_management&op=editmanagement&id=".$value['id']."'>编辑</a></li>";
//            $operation .= "</ul></span>";
//
//
//            $param = array();
//            $param['member_id'] = $value['member_id'];             //会员id
//            $param['account'] = $value['account'];         //会员名
//
//            $param['become_agent_time'] = $value['become_agent_time']; //成为代理时间
//            $param['agent_area_name'] = $this->get_area($value['member_provinceid']).','.$this->get_area($value['member_cityid']).','.$this->get_area($value['member_areaid']);         //代理区域
//
//            $param['agent_level'] = $value['agent_level'];             //代理等级
//            $param['area_total'] = $value['area_total'];               //区域消费总额
//            $param['agent_dividend_rate'] = $this->get_dividend_rate($value['agent_level']).'%';//分红比例
//            $param['set_money'] = $value['set_money'];                 //累计结算金额
//            $param['operation']         = $operation;
//
//
//
//            $data['list'][$value['id']] = $param;
//
//        }
//
//
//        echo Tpl::flexigridXML($data);
//    }





    //管理区域代理-----添加
    public function addmanagementWt(){


        $province = Model('area')->where(array('area_parent_id'=>0))->select(); //获取全国各省信息

        if($_POST['agent_grade']){  //判断是否是表单提交
//            $is_only_agent = get_setting_value('is_only_agent');var_dump($is_only_agent);die;

            if(empty(trim($_POST['account']))||empty(trim($_POST['pwd']))||empty(trim($_POST['realname']))||empty(trim($_POST['connect_way']))){
                showMessage(Language::get('nc_common_save_fail'));
            }
            if(($_POST['agent_grade']==1&&empty($_POST['province']))||($_POST['agent_grade']==2&&empty($_POST['city']))||($_POST['agent_grade']==3&&empty($_POST['area']))){
                showMessage(Language::get('nc_common_save_fail'));
            }

            if($_POST['pwd2']!==$_POST['pwd']){  //两次输入密码不匹配
                showDialog('密码不匹配');

            }
            if(Model('member')->where(array('member_mobile'=>$_POST['connect_way']))->find()){
                showDialog('此号码已被注册');
            }

            $arr = array(
                'member_name'     => trim($_POST['account']),    //账号
                'member_passwd'   => md5($_POST['pwd']),  //登录密码
                'member_truename' => trim($_POST['realname']),  //真实姓名
                'member_mobile'   => trim($_POST['connect_way']), //联系方式


                'member_time'      => time()                //会员注册时间 varchar类型
            );

            Model()->beginTransaction();
            $id = Model('member')->insert($arr);

            if($id){
                $data = array(
                    'account' => $arr['member_name'],       //代理人账号/用户名
                    'truename' => $arr['member_truename'],   //代理人真实姓名
                    'member_id' => $id,                       //代理人在member表的id
                    'agent_level' => $_POST['agent_grade'],  //代理级别
                    'agent_area_id' => $_POST['agent_grade']==1?$_POST['province']:($_POST['agent_grade']==2?$_POST['city']:$_POST['area']), //代理区域id
                    'become_agent_time' => date('Y-m-d H:m:s',$arr['member_time']),  //成为区域代理人时间
                    'province'    => $_POST['province'],   //省id
                    'city'        => $_POST['city'],       //市id
                    'area'        => $_POST['area'],       //区id

                );

                $is_only_agent = get_setting_value('is_only_agent');  //判断是否是独家代理

                if($is_only_agent==1){ //是独家代理，查询该区有是否已存在代理人
                   $res_gent = Model('member_agent')->where(array('agent_area_id'=>$data['agent_area_id']))->find();
                   if($res_gent['id']){
                       Model()->rollback();
                       showDialog('该区域已有代理商');
                   }
                }

                $res = Model('member_agent')->insert($data);

                if($res){
                    Model()->commit();
                    showMessage(Language::get('nc_common_save_succ'));exit;
                }else{
                    Model()->rollback();
                }
            }else{
                Model()->rollback();
            }
            $this->log('添加区域代理保存', 0);
            showMessage(Language::get('nc_common_save_fail'));
        }

        Tpl::output('province',$province);
        Tpl::setDirquna('schema');
        Tpl::showpage('region_add_management');
    }


    //管理区域代理-----编辑
    public function editmanagementWt(){

        $id = $_GET['id']; //member_agent表id
        $province = Model('area')->where(array('area_parent_id'=>0))->select(); //获取全国各省信息
        $agent_info = Model()->table('member_agent,member')->join('left')->on('member.member_id=member_agent.member_id')->field('member.member_mobile,member_agent.*')->where(array('id'=>$id))->find();

        if($_POST['agent_grade']){  //是否是post提交

            $id = $_POST['id']; //member_agent表id
            $member_id = Model('member_agent')->getfby_id($id,'member_id');  //会员id

            if(empty(trim($_POST['account']))||empty(trim($_POST['realname']))||empty(trim($_POST['connect_way']))){
                showMessage(Language::get('nc_common_save_fail'));
            }

            if(Model('member')->where(array('member_mobile'=>$_POST['connect_way'],'member_id'=>array('neq',$member_id)))->find()){
                showDialog('此号码已被注册');
            }
            $arr = array(
                'member_name'     => trim($_POST['account']),    //账号
                'member_passwd'   => md5($_POST['pwd']),  //登录密码
                'member_truename' => trim($_POST['realname']),  //真实姓名
                'member_mobile'   => trim($_POST['connect_way']), //联系方式


            );

            Model()->beginTransaction();


            $res = Model('member')->where(array('member_id'=>$member_id))->update($arr);

            if($res===false){
                Model()->rollback();
                showMessage(Language::get('nc_common_save_fail'));exit;
            }else{

                $data = array(
                    'account' => $arr['member_name'],       //代理人账号/用户名
                    'truename' => $arr['member_truename'],   //代理人真实姓名

                    'agent_level' => $_POST['agent_grade'],  //代理级别
                    'agent_area_id' => $_POST['agent_grade']==1?$_POST['provence']:($_POST['agent_grade']==2?$_POST['city']:$_POST['area']), //代理区域id

                    'province' => $_POST['province'],  //省id
                    'city'     => $_POST['city'],      //市id
                    'area'     => $_POST['area'],      //区县id

                );


//                $is_only_agent = $this->get_setting_value('is_only_agent');//判断是否是独家代理(编辑就不用判断是否是独家了)
//                if($is_only_agent==1){ //是独家代理，查询该区有是否已存在代理人
//                    $res_gent = Model('member_agent')->where(array('agent_area_id'=>$data['agent_area_id'],'id'=>array('neq',$id)))->find();
//                    if($res_gent){
//                        Model()->rollback();
//                        showMessage(Language::get('nc_common_save_fail'));exit;
//                    }
//                }

               $result = Model('member_agent')->where(array('id'=>$id))->update($data);

                if($result===false){
                    Model()->rollback();
                    showMessage(Language::get('nc_common_save_fail'));exit;
                }else{
                    Model()->commit();
                    showMessage(Language::get('nc_common_save_succ'));exit;
                }
            }
        }

        Tpl::output('info',$agent_info);
        Tpl::output('province',$province);
        Tpl::setDirquna('schema');
        Tpl::showpage('region_edit_management');
    }

//管理区域代理-------删除
    public function del_managementWt(){
        $id = $_GET['id'];

        $res = Model('member_agent')->where(array('id'=>$id))->delete();
        if(!$res){
            showDialog('删除失败，请重试');
        }

    }









 //获取市、区/县信息
    public function get_cityareaWt(){

        $province_id = $_POST['province_id'];
        if($province_id!=0) {
            $area = Model('area')->where(array('area_parent_id' => $province_id))->field('area_id,area_name')->select();

            echo json_encode($area);
        }
    }


    //根据代理等级获取分红比例
    public function  get_dividend_rate($level){
        if($level==1){
            return $this->get_setting_value('province_rate'); //省比例
        }elseif($level==2){
            return $this->get_setting_value('city_rate');    //市比例
        }else{
            return $this->get_setting_value('area_rate');    //区比例
        }
    }


    //获取配置表的 对应键的值
    public function get_setting_value($name){

        $setting = Model()->query('select * from lbsz_setting');
        foreach($setting as $k=>$v){
            if($v['name']==$name){
                return $v['value'];
            }
        }
    }

//获取地名
    public function get_area($area_code){
       $area = Model('area')->where(array('area_id'=>$area_code))->find();
        return $area['area_name'];
    }




//导出excel
    public function export_agentWt() {

        $model = Model();


        $where = 'common=0';
        if($member_keyword = $_GET['member_keyword']){ //会员id、会员名、手机
            $where .= ' and (member.member_id='."'".$member_keyword."'".' or member.member_name='."'".$member_keyword."'".' or member.member_mobile='."'".$member_keyword."')";

        }
        if($area_keyword = $_GET['area_keyword']){  //区域名称
            $area_id = Model('area')->getfby_area_name($area_keyword,'area_id');
            $where .= ' and (member_agent.province='."'".$area_id."'".' or member_agent.city='."'".$area_id."'".' or member_agent.area='."'".$area_id."')";
        }

        if($level_keyword = $_GET['level_keyword']){  //区域等级
            $where .=' and  member_agent.agent_level='."'".$level_keyword."'";
//            var_dump($where);die;
        }

        if($_GET['start_time']&&$_GET['end_time']){  //时间范围

            if($_GET['start_time']>$_GET['end_time']){  showMessage('检索时间错误！');}

            $where .= ' and member_agent.become_agent_time >='."'".$_GET['start_time']."'".' and member_agent.become_agent_time <='."'".$_GET['end_time']."'";
        }



        $on = 'member_agent.member_id=member_agent_log.member_id,member.member_id=member_agent.member_id,area.area_id=member_agent.agent_area_id';
        $list = $model->table('member_agent,member_agent_log,member,area')->join('left')->on($on)->field('member_agent.*,member_agent_log.money,member_agent_log.status,area.area_name,member.member_name,member.member_mobile')->where($where)->group('member_id')->select();
//var_dump($list);die;
        foreach ($list as $k=>$v){
            $list[$k]['set_money'] = Model('member_agent_log')->where(array('member_id'=>$v['member_id'],'status'=>0))->sum('money');  //累计结算金额(已领取的)
            $list[$k]['agent_dividend_rate'] = $this->get_dividend_rate($v['agent_level']).'%';      //分红红比例
            $list[$k]['agent_area_name'] = $this->get_area($v['province']).','.$this->get_area($v['city']).','.$this->get_area($v['area']);         //代理区域

        }

        $this->exportExcel($list);
        Tpl::output('agent_list',$list);
        Tpl::setDirquna('schema');

        Tpl::showpage('region_management');
    }








    /**
     * 生成excel
     *
     * @param array $data
     */


    function exportExcel($data, $isDown = false)
    {
        $filename = '区域代理表' . date('YmdHis');
        $header = array('会员ID', '会员名', '成为代理时间', '代理区域', '代理等级(1省、2市、3区县)', '区域消费总额', '分红比例', '累计结算金额');
        $index = array('member_id', 'account', 'become_agent_time', 'agent_area_name', 'agent_level', 'area_total', 'agent_dividend_rate', 'set_money');
        createtable($data, $filename, $header, $index);
    }



}
