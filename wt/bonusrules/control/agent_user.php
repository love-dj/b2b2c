<?php
/**
 * 区域分红区域代理管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class agent_userControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('bonusrules');
	}

    /**
     * 代理商列表
     * @return team_list 
     */ 
	public function indexWt(){
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('agent_user.index');
	}

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('member_chain');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('member_id', 'agent_check_time', 'agent_apply_time', 'agent_check', 'agent_area_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $condition['agent_area_id'] = array('gt', 0);
        $list = Model()->table('member_chain,member')->field('member_chain.member_id,member_chain.agent_area_id,member_chain.agent_check,member_chain.agent_check_time,member_chain.agent_apply_time,member_chain.area_cost_money,member_chain.area_commission,member.member_name,member.member_truename,member.member_mobile')->join('left')->on('member_chain.member_id=member.member_id')->where($condition)->page($page)->order($order)->select();
        //$list = $model->where($condition)->page($page)->order($order)->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
		
		$setting_list = Model('Setting')->getListSetting();//获取系统参数配置
		$agent_province_rate = $setting_list['agent_province_rate'];//省级代理佣金比例
		$agent_city_rate = $setting_list['agent_city_rate'];//市级代理佣金比例
		$agent_area_rate = $setting_list['agent_area_rate'];//区级代理佣金比例
		$agent_level_difference = $setting_list['agent_level_difference'];//是否开启极差分红
		if($agent_level_difference){
			//开启了极差
			$agent_province_rate = $agent_province_rate - $agent_city_rate;
			$agent_city_rate = $agent_city_rate - $agent_area_rate;
		}
		//0：未审核；1：已审核；2：已撤销；
		$check_status = array(
			0 => '未审核',
			1 => '审核通过',
			2 => '审核不通过',
			3 => '已撤销'
		);
		
        foreach ($list as $value) { 
			$area_name = Model('area')->getTopAreaName($value['agent_area_id']);   
			$area_rate_result = Model('area')->findAreaDeep($value['agent_area_id']); 
			if($area_rate_result == 1){
				$agent_rate = $agent_province_rate . '%';
			}else if($area_rate_result == 2){
				$agent_rate = $agent_city_rate . '%';
			}else if($area_rate_result == 3){
				$agent_rate = $agent_area_rate . '%';
			}else{
				$agent_rate = '0 %';
			}
			
        	$param = array();
        	$operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['member_id'].");'><i class='fa fa-trash-o'></i>撤销</a>";
        	$operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
        	$operation .= "<li><a href='index.php?w=agent_user&t=edit_user&id=".$value['member_id']."'>更换代理区域</a></li>";            
        	$operation .= "</ul></span>";
        	$param['operation']     = $operation;
            $param['member_id']    = $value['member_id'];
            if(empty($value['member_truename'])){
                $param['member_truename']  = $value['member_name'];
            }else{
                $param['member_truename']  = $value['member_truename'];
            }
            $param['member_mobile']    = $value['member_mobile'];
            $param['agent_check']     = $check_status[$value['agent_check']];
            if($value['agent_apply_time'] > 0){
                $param['agent_apply_time']     = date('Y-m-d H:i:s',$value['agent_apply_time']);
            }else{
                $param['agent_apply_time']     = '--';//不是正规途径添加的
            }
            if($value['agent_check_time'] > 0){
                $param['agent_check_time']     = date('Y-m-d H:i:s',$value['agent_check_time']);
            }else{
                $param['agent_check_time']     = '--';//不是正规途径添加的
            }
            $param['area_name']     = $area_name;
            $param['agent_rate']     = $agent_rate;
            $param['area_remain_commission']   = '0';//后面统计订单
            $param['area_commission']   = $value['area_commission'];
        	$param['area_cost_money']   = $value['area_cost_money'];
            
        	$data['list'][$value['member_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }    


    /**
     * 新增代理商
     */
    public function add_userWt(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["area_id"], "require"=>"true", "message"=>"区域必须选择"),
                array("input"=>$_POST["member_id_list"], "require"=>"true", "message"=>"会员必须选择"),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $area_id = trim($_POST['area_id']);
                $member_id_list = $_POST['member_id_list'];
                $result = '';
                $team_model = Model('member_chain');
                foreach($member_id_list as $k=>$v){
                    $param  = array();                
                    $param['agent_area_id'] = $area_id;
                    $param['agent_check'] = 1;//0：未审核；1：审核通过；2：审核不通过；3：已撤销； 后台添加，直接审核通过
					$now_time = TIMESTAMP;
                    $param['agent_apply_time'] = $now_time;
                    $param['agent_check_time'] = $now_time;
                    $res = $team_model->where(array('member_id'=>$v))->update($param);
                    if($res){
                        $result .= '[member_id:'.$v.']';
                    }                    
                }
                if (!empty($result)){
                    $url = array(
                        array(
                            'url'=>'index.php?w=agent_user&t=index',
                            'msg'=>'返回代理商列表',
                        ),
                        array(
                            'url'=>'index.php?w=agent_user&t=add_user',
                            'msg'=>'继续添加代理商',
                        ),
                    );
                    dkcache('agent_user');
                    $this->log('新增无限级代理商'.$result,null);
                    showMessage("新增代理商成功",$url);
                }else{
                    showMessage("新增代理商失败");
                }
            }
        }
		
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('agent_user.add');
    }

     /**
     * 编辑代理商等级
     */
    public function edit_userWt(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["area_id"], "require"=>"true", "message"=>"区域必须选择"),
            );

            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $area_id = trim($_POST['area_id']);
                $member_id = $_POST['member_id'];
                $team_model = Model('member_chain');        
                $param['agent_area_id'] = $area_id;
                $result = $team_model->where(array('member_id'=>$member_id))->update($param);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?w=agent_user&t=index',
                            'msg'=>'返回代理商列表',
                        ),
                        array(
                            'url'=>'index.php?w=agent_user&t=edit_user&id='.intval($member_id),
                            'msg'=>'重新编辑该代理商代理区域',
                        ),
                    );
                    dkcache('agent_user');
                    $this->log('编辑代理商区域'.'['.$member_id.']',null);
                    showMessage("编辑代理商区域成功",$url);
                }else{
                    showMessage("编辑代理商区域失败");
                }
            }
        }  
        $id = trim($_REQUEST['id']);
        $chain_model = Model('member_chain');
        $condition['member_id'] = $id;   
        $chain_array = $chain_model->where($condition)->find();  
        if (empty($chain_array)){
            showMessage('参数错误');
        }
		$chain_array['area_name'] = Model('area')->getTopAreaName($chain_array['agent_area_id']); 
 
        Tpl::output('chain_array',$chain_array);
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('agent_user.edit');
    }

    /**
     * 撤销代理商
     */
    public function remove_userWt(){
        $id = trim($_REQUEST['del_id']);
        $chain_model = Model('member_chain');
        $condition['member_id'] = array('in',$id);   
		$param['agent_area_id'] = 0;
		$param['agent_check'] = 3;//0：未审核；1：审核通过；2：审核不通过；3：已撤销； 后台添加，直接审核通过 		
        //$param['area_cost_money'] = 0;
        //$param['area_commission'] = 0;
        $result = $chain_model->where($condition)->update($param);
        if($result) {
            dkcache('agent_user');
            $this->log('撤销代理商['.$id.']', 1);
            showMessage('撤销代理商成功','');
        } else {
            showMessage('撤销代理商失败','','','error');
        }

    }

    //搜索会员
    public function get_userlistWt(){
        $model_member = Model('member');
        $condition = array();
        if (!empty($_GET['user_key'])) {
            $user_key = $_GET['user_key'];
            $user_list = Model()->table('member_chain,member')->field('member.member_id,member.member_truename,member.member_mobile,member.member_name,member.member_email,member.member_avatar')->join('left')->on('member_chain.member_id=member.member_id')->where("member_chain.agent_area_id = 0 and (member_truename like '%{$user_key}%' or member_email like '%{$user_key}%' or member_mobile like '%{$user_key}%')")->page(8)->select();
        }else{
            $user_list = Model()->table('member_chain,member')->field('member.member_id,member.member_truename,member.member_mobile,member.member_name,member.member_email,member.member_avatar')->join('left')->on('member_chain.member_id=member.member_id')->where("member_chain.agent_area_id = 0")->page(8)->select();
        }
        $html = "<ul class=\"dialog-goodslist-s2\">";
        foreach($user_list as $v) {
            $html .= <<<EOB
            <li>
            <div class="goods-pic" onclick="select_recommend_goods({$v['member_id']});">
            <span class="ac-ico"></span>
            <span class="thumb size-72x72">
            <i></i>
            <img width="72" src="{$v['member_avatar']}" member_name="{$v['member_name']}" member_id="{$v['member_id']}" title="{$v['member_name']}">
            </span>
            </div>
            <div class="goods-name">
            <a target="_blank" href="#">{$v['member_name']}</a>
            </div>
            </li>
EOB;
        }
        $admin_tpl_url = ADMIN_TEMPLATES_URL;
        $html .= '<div class="clear"></div></ul><div id="pagination" class="pagination">'.$model_member->showpage(1).'</div><div class="clear"></div>';
        $html .= <<<EOB
        <script>
        $('#pagination').find('.demo').ajaxContent({
                event:'click',
                loaderType:"img",
                loadingMsg:"{$admin_tpl_url}/images/transparent.gif",
                target:'#show_recommend_goods_list'
            });
        </script>
EOB;
        echo $html;
    }
}
