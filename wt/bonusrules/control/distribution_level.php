<?php
/**
 * 三级分销管理
 * 2019/05/20
 * auth sam
 * @微商宝提供技术支持 授权请联系微商宝授权
 * @link    http://www.weisbao.com
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class distribution_levelControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('bonusrules');
	}

    /**
     * 等级列表
     * @return distribution_list 
     */ 
	public function indexWt(){
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('distribution_level.index');
	}

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
    	$model = Model('distribution_level');
    	$condition = array();
    	if ($_POST['query'] != '') {
    		$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
    	}
    	$order = '';
    	$param = array('id', 'level_weight', 'level_name', 'layer_one', 'layer_two', 'layer_three', 'level_people');
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
        	$operation .= "<li><a href='index.php?w=distribution_level&t=edit_level&id=".$value['id']."'>编辑</a></li>";            
        	$operation .= "</ul></span>";
        	$param['operation']     = $operation;
        	$param['level_weight']  = $value['level_weight'];
        	$param['level_name']    = $value['level_name'];
        	$param['layer_one']     = $value['layer_one'];
        	$param['layer_two']     = $value['layer_two'];
            $param['layer_three']   = $value['layer_three'];
            // $param['level_condition']   = $value['level_condition'];
            
        	$data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }    

    /**
     * 新增分销等级
     */
    public function add_levelWt(){
    	if(chksubmit()){
    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
    			array("input"=>$_POST["layer_two"], "require"=>"true"),
                array("input"=>$_POST["layer_three"], "require"=>"true"),
    			array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);
    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param  = array();                
                $param['level_weight']    = trim($_POST['level_weight']);
                $param['level_name']      = trim($_POST['level_name']);
                $param['layer_one']       = trim($_POST['layer_one']);
                $param['layer_two']       = trim($_POST['layer_two']);
                $param['layer_three']     = trim($_POST['layer_three']);
                $param['level_people']    = trim($_POST['level_people']);
                $param['level_condition'] = serialize($_POST['upgrade_type']);
                $param['condition_value'] = serialize($_POST['upgrade_value']); 

                $distribution_level_model = Model('distribution_level');
    			$result = $distribution_level_model->insert($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?w=distribution_level&t=index',
    						'msg'=>'返回',
    					),
    					array(
    						'url'=>'index.php?w=distribution_level&t=add_level',
    						'msg'=>'继续新增分销等级',
    					),
    				);
    				dkcache('distribution_level');
    				$this->log('新增分销等级'.'['.$_POST['level_name'].']',null);
    				showMessage("新增分销等级成功",$url);
    			}else{
    				showMessage("新增分销等级失败");
    			}
    		}
    	}
        
        Tpl::setDirquna('bonusrules');
        Tpl::showpage('distribution_level.add');
    }

    /**
     * 编辑等级
     */
    public function edit_levelWt(){
    	if(chksubmit()){

    		$obj_validate = new Validate();
    		$obj_validate->validateparam = array(
    			array("input"=>$_POST["level_weight"], "require"=>"true", "message"=>"等级权重不能为空"),
    			array("input"=>$_POST["level_name"], "require"=>"true", "message"=>"等级名称不能为空"),
    			array("input"=>$_POST["layer_one"], "require"=>"true"),
    			array("input"=>$_POST["layer_two"], "require"=>"true"),
                array("input"=>$_POST["layer_three"], "require"=>"true"),
    			array("input"=>$_POST["level_people"], "require"=>"true"),
                array("input"=>$_POST["level_condition"], "require"=>"true"),
    		);

    		$error = $obj_validate->validate();
    		if ($error != ''){
    			showMessage($error);
    		} else {
    			$param  = array();
                $con = array();
                $param['level_weight']    = trim($_POST['level_weight']);
                $param['level_name']      = trim($_POST['level_name']);
                $param['layer_one']       = trim($_POST['layer_one']);
                $param['layer_two']       = trim($_POST['layer_two']);
                $param['layer_three']     = trim($_POST['layer_three']);
                $param['level_people']    = trim($_POST['level_people']);  
                if(!empty($_POST['upgrade_type'])){            
                    $param['level_condition'] = serialize($_POST['upgrade_type']);
                }  
                if(!empty($_POST['upgrade_value'])){    
                    $param['condition_value'] = serialize($_POST['upgrade_value']); 
                }  

    			$distribution_model             = Model('distribution_level');
    			$result = $distribution_model->where(array('id'=>$_POST['id']))->update($param);
    			if ($result){
    				$url = array(
    					array(
    						'url'=>'index.php?w=distribution_level&t=index',
    						'msg'=>'返回等级列表',
    					),
    					array(
    						'url'=>'index.php?w=distribution_level&t=edit_level&id='.intval($_POST['id']),
    						'msg'=>'重新编辑该等级',
    					),
    				);
    				dkcache('distribution_level');
    				$this->log('编辑等级'.'['.$_POST['level_name'].']',null);
    				showMessage("编辑等级成功",$url);
    			}else{
    				showMessage("编辑等级失败");
    			}
    		}
    	}  
        $id = trim($_REQUEST['id']);
        $level_model = Model('distribution_level');
        $condition = "id = ".$id ;
        $level_array = $level_model->where($condition)->find();

    	if (empty($level_array)){
    		showMessage('参数错误');
    	}
        $upgrade_type_array = array();
        $upgrade_value_array = array();
        if($level_array['level_condition']){
            $upgrade_type_array = unserialize($level_array['level_condition']); 
        }
        if($level_array['condition_value']){
            $upgrade_value_array = unserialize($level_array['condition_value']); 
        }
        $goods_array = array();
        if($upgrade_value_array['goods_id_list']){
            foreach($upgrade_value_array['goods_id_list'] as $k=>$v){
                $goods_info = Model('goods')->getGoodsOnlineInfoByID($v,"goods_name,goods_image");
                $img = thumb($goods_info,240);
                $goods_array[$k] = array(
                        'goods_id' => $v,
                        'goods_name' => $goods_info['goods_name'],
                        'goods_image' => $img
                );
            }
        }

        //print_r($upgrade_type_array);
        //print_r($upgrade_value_array);
        //print_r($goods_array);
        Tpl::output('level_array',$level_array);
        Tpl::output('upgrade_type_array',$upgrade_type_array);
        Tpl::output('upgrade_value_array',$upgrade_value_array);
        Tpl::output('goods_array',$goods_array);
    	Tpl::setDirquna('bonusrules');
    	Tpl::showpage('distribution_level.edit');
    }

    /**
     * 删除等级
     */
    public function del_levelWt(){
    	$id = trim($_REQUEST['del_id']);
        $level_model = Model('distribution_level');
        $condition['id'] = array('in',$id);
    	$result = $level_model->where($condition)->delete();
        if($result) {
    		dkcache('distribution_level');
    		$this->log('删除等级['.$id.']', 1);
    		showMessage('删除等级成功','');
    	} else {
    		showMessage('删除等级失败','','','error');
    	}

    }


    public function get_goodslistWt(){
        $model_goods = Model('goods');
        $condition = array();
        if (!empty($_GET['goods_name'])) {
            $condition['goods_name'] = array('like',"%{$_GET['goods_name']}%");
        }
        $goods_list = $model_goods->getGoodsOnlineList($condition,'*',8);
        $html = "<ul class=\"dialog-goodslist-s2\">";
        foreach($goods_list as $v) {
            $url = urlShop('goods', 'index', array('goods_id' => $v['goods_id']));
            $img = thumb($v,240);
            $html .= <<<EOB
            <li>
            <div class="goods-pic" onclick="select_recommend_goods({$v['goods_id']});">
            <span class="ac-ico"></span>
            <span class="thumb size-72x72">
            <i></i>
            <img width="72" src="{$img}" goods_name="{$v['goods_name']}" goods_id="{$v['goods_id']}" title="{$v['goods_name']}">
            </span>
            </div>
            <div class="goods-name">
            <a target="_blank" href="{$url}">{$v['goods_name']}</a>
            </div>
            </li>
EOB;
        }
        $admin_tpl_url = ADMIN_TEMPLATES_URL;
        $html .= '<div class="clear"></div></ul><div id="pagination" class="pagination">'.$model_goods->showpage(1).'</div><div class="clear"></div>';
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
