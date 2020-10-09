<?php
/**
 * 团队管理（级差）
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class team_userControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('bonusrules');
	}

    /**
     * 团队列表
     * @return team_list 
     */ 
	public function indexWt(){
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('team_user.index');
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
    	$param = array('member_id', 'team_level', 'become_team_time');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $condition['team_level'] = array('gt', 0);
        $list = Model()->table('member_chain,member')->field('member_chain.member_id,member_chain.team_level,member_chain.become_team_time,member_chain.team_cost_money,member_chain.team_commission,member_chain.team_remain_commission,member.member_name,member.member_truename,member.member_mobile')->join('left')->on('member_chain.member_id=member.member_id')->where($condition)->page($page)->order($order)->select();
        //$list = $model->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $value) {  
            $level_info = Model('team_level')->field("level_name,layer_rate")->where("id = " . $value['team_level'])->find();        
        	$param = array();
        	$operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['member_id'].");'><i class='fa fa-trash-o'></i>撤销</a>";
        	$operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
        	$operation .= "<li><a href='index.php?w=team_user&t=edit_user&id=".$value['member_id']."'>编辑等级</a></li>";            
        	$operation .= "</ul></span>";
        	$param['operation']     = $operation;
            $param['member_id']    = $value['member_id'];
            if(empty($value['member_truename'])){
                $param['member_truename']  = $value['member_name'];
            }else{
                $param['member_truename']  = $value['member_truename'];
            }
            $param['member_mobile']    = $value['member_mobile'];
            if($value['become_team_time'] > 0){
                $param['become_team_time']     = date('Y-m-d H:i:s',$value['become_team_time']);
            }else{
                $param['become_team_time']     = '';//不是正规途径添加的
            }
            $param['level_name']     = $level_info['level_name'];
            $param['layer_rate']     = $level_info['layer_rate'];
        	$param['team_cost_money']     = $value['team_cost_money'];
            $param['team_commission']   = $value['team_commission'];
            $param['team_remain_commission']   = $value['team_remain_commission'];
            
        	$data['list'][$value['member_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }    


    /**
     * 新增团队
     */
    public function add_userWt(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["level_id"], "require"=>"true", "message"=>"等级必须选择"),
                array("input"=>$_POST["member_id_list"], "require"=>"true", "message"=>"会员必须选择"),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $level_id = trim($_POST['level_id']);
                $member_id_list = $_POST['member_id_list'];
                $result = '';
                $team_model = Model('member_chain');
                foreach($member_id_list as $k=>$v){
                    $param  = array();                
                    $param['team_level'] = $level_id;
                    $param['become_team_time'] = TIMESTAMP;
                    $res = $team_model->where(array('member_id'=>$v))->update($param);
                    if($res){
                        $result .= '[member_id:'.$v.']';
                    }
                    
                }
                if (!empty($result)){
                    $url = array(
                        array(
                            'url'=>'index.php?w=team_user&t=index',
                            'msg'=>'返回团队列表',
                        ),
                        array(
                            'url'=>'index.php?w=team_user&t=add_user',
                            'msg'=>'继续添加团队',
                        ),
                    );
                    dkcache('team_user');
                    $this->log('新增无限级团队'.$result,null);
                    showMessage("新增团队成功",$url);
                }else{
                    showMessage("新增团队失败");
                }
            }
        }

        $level_array =  Model('team_level')->select();
        Tpl::output('level_array',$level_array);        
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('team_user.add');
    }

     /**
     * 编辑团队等级
     */
    public function edit_userWt(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["level_id"], "require"=>"true", "message"=>"等级必须选择"),
            );

            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            } else {
                $level_id = trim($_POST['level_id']);
                $member_id = $_POST['member_id'];
                $team_model = Model('member_chain');        
                $param['team_level'] = $level_id;
                $result = $team_model->where(array('member_id'=>$member_id))->update($param);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?w=team_user&t=index',
                            'msg'=>'返回团队列表',
                        ),
                        array(
                            'url'=>'index.php?w=team_user&t=edit_user&id='.intval($member_id),
                            'msg'=>'重新编辑该团队',
                        ),
                    );
                    dkcache('team_user');
                    $this->log('编辑团队'.'['.$member_id.']',null);
                    showMessage("编辑团队成功",$url);
                }else{
                    showMessage("编辑团队失败");
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

        $level_array =  Model('team_level')->select();
        Tpl::output('level_array',$level_array); 
        Tpl::output('chain_array',$chain_array);
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('team_user.edit');
    }

    /**
     * 撤销团队
     */
    public function remove_userWt(){
        $id = trim($_REQUEST['del_id']);
        $chain_model = Model('member_chain');
        $condition['member_id'] = array('in',$id);           
        $param['team_level'] = 0;
        $param['team_cost_money'] = 0;
        $param['team_commission'] = 0;
        $param['team_remain_commission'] = 0;
        $param['become_team_time'] = 0;
        $result = $chain_model->where($condition)->update($param);
        if($result) {
            dkcache('team_user');
            $this->log('删除团队['.$id.']', 1);
            showMessage('删除团队成功','');
        } else {
            showMessage('删除团队失败','','','error');
        }

    }

    //搜索会员
    public function get_userlistWt(){
        $model_member = Model('member');
        $condition = array();
        if (!empty($_GET['user_key'])) {
            $user_key = $_GET['user_key'];
            $user_list = Model()->table('member_chain,member')->field('member.member_id,member.member_truename,member.member_mobile,member.member_name,member.member_email,member.member_avatar')->join('left')->on('member_chain.member_id=member.member_id')->where("member_chain.team_level = 0 and (member_truename like '%{$user_key}%' or member_email like '%{$user_key}%' or member_mobile like '%{$user_key}%')")->page(8)->select();
        }else{
            $user_list = Model()->table('member_chain,member')->field('member.member_id,member.member_truename,member.member_mobile,member.member_name,member.member_email,member.member_avatar')->join('left')->on('member_chain.member_id=member.member_id')->where("member_chain.team_level = 0")->page(8)->select();
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
