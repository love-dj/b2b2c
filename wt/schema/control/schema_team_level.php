<?php
/**
 * 等级
 *
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @license    http://www.weisbao.com
 * @link       联系方式：13632978801
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class schema_team_levelControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('schema');
	}

	public function indexWt(){
		$this->schemaWt();
	}
	/**
	 * 等级列表
	 * @return schema_list 
	 */ 
	public function schemaWt() {
		$model_schema = Model('schema_level');
		$schema_list = $model_schema->select();
		Tpl::output('schema_list', $schema_list);
		Tpl::setDirquna('schema');
		Tpl::showpage('schema_team_level');
	}
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('schema_team_level');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'level_weight', 'level_name', 'layer_one','level_people','level_condition');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = $model->where($condition)->page($page)->order($order)->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        
        foreach ($list as $value) {
        	$param = array();
        	$operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_delete(".$value['id'].");'><i class='fa fa-trash-o'></i>删除</a>";
        	$operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
        	$operation .= "<li><a href='index.php?act=schema_team_level&op=edit_level&id=".$value['id']."'>编辑</a></li>";
        	$operation .= "</ul></span>";
        	$param['operation'] = $operation;
        	$param['level_weight'] = $value['level_weight'];
        	$param['level_name'] = $value['level_name'];
        	$param['layer_one'] = $value['layer_one'];
        	$param['level_people'] = $value['level_people'];

        	$data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }
    

    /**
     * 新增团队等级
     */
    function add_levelWt(){

    	if(chksubmit()){
    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
    			array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);
    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param  = array();
    			$param['level_weight'] = trim($_POST['level_weight']);
    			$param['level_name'] = trim($_POST['level_name']);
    			$param['layer_one'] = trim($_POST['layer_one']);
    			$param['level_people'] = trim($_POST['level_people']);
                $param['level_condition'] = serialize($_POST['upgrade_value']);
                $schema_team_level_model = Model('schema_team_level');
    			$result = $schema_team_level_model->insert($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?act=schema_team_level&op=index',
    						'msg'=>'返回',
    					),
    					array(
    						'url'=>'index.php?act=schema_team_level&op=add_level',
    						'msg'=>'继续新增团队等级',
    					),
    				);
    				dkcache('schema_team_level');
    				$this->log('新增团队等级'.'['.$_POST['level_name'].']',null);
    				showMessage("操作成功",$url);
    			}else{
    				showMessage("操作失败");
    			}
    		}
    	}
    	Tpl::setDirquna('schema');
    	Tpl::showpage('schema_team_level_add');
    }

    /**
     * 编辑等级
     */
    function edit_levelWt(){

    	if(chksubmit()){
    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
                array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);

    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param['level_weight'] = trim($_POST['level_weight']);
    			$param['level_name'] = trim($_POST['level_name']);
    			$param['layer_one'] = $_POST['layer_one'];
                $param['level_people'] = trim($_POST['level_people']);
                $param['level_condition'] = serialize($_POST['upgrade_value']);
                /*echo "<pre>";
                var_dump($_POST['id']);
                print_r($param);
                die;*/
    			$schema_model = Model('schema_team_level');
                $result = $schema_model->where(array('id'=>$_POST['id']))->update($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?act=schema_team_level&op=index',
    						'msg'=>'返回等级列表',
    					),
    					array(
    						'url'=>'index.php?act=schema_team_level&op=edit_level&id='.intval($_POST['id']),
    						'msg'=>'重新编辑该等级',
    					),
    				);
    				dkcache('schema_team_level');
    				$this->log('编辑等级'.'['.$_POST['level_name'].']',null);
    				showMessage("操作成功",$url);
    			}else{
    				showMessage("操作失败");
    			}
    		}
    	}
        $id = trim($_REQUEST['id']);
        $level_model = Model('schema_team_level');
        $condition = "id = ".$id;
        $level_array = $level_model->where($condition)->find();
    	if (empty($level_array)){
    		showMessage('参数错误');
    	}
    	Tpl::output('level_array',$level_array);
    	Tpl::setDirquna('schema');
    	Tpl::showpage('schema_team_level_edit');
    }

    /**
     * 删除等级
     */
    function del_levelWt(){
    	$id = trim($_REQUEST['id']);
        $level_model = Model('schema_team_level');
    	$condition['id'] = array('in',$id);
    	$result = $level_model->delete($condition);
    	if($result) {
    		dkcache('schema_team_level');
    		$this->log('删除等级['.$id.']', 1);
    		showMessage('操作成功','');
    	} else {
    		showMessage('操作失败','','','error');
    	}

    }
}
