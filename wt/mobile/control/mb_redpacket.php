<?php
/**
 * 红包管理
 *
 *
 * *
 */

defined('ShopWT') or exit('Access Denied By ShopWT');

class mb_redpacketControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('mb_redpacket');
	}
	/**
	 * 红包列表
	 */
	public function indexWt(){
		$model = Model();
	
		//条件
		$condition_arr = array();
		//名称
		if (!empty($_GET['packet_name'])){
			$condition_arr['packet_name'] = array("like", "%{$_GET['packet_name']}%");
		}
		//状态
		if (!empty($_GET['state'])){
			$condition_arr['state'] = $_GET['state'];
		}

		//活动列表
		$list	= $model->table('mb_redpacket')->where($condition_arr)->page('10')->order('id desc')->select();
		//输出
		Tpl::output('show_page',$model->showpage());
		Tpl::output('list',$list);
		Tpl::setDirquna('mobile');
		Tpl::showpage('mb_redpacket.index');
	}

	/**
	 * 未抽取红包记录
	 */
	 public function listWt(){
		 $model = Model();
		 $id = $_GET['id'];
		 $packet = $model->table('mb_redpacket')->where(array('id'=>$id))->find();
		 if(empty($packet)){
			 showMessage('该红包活动不存在');
		 }
		 $list = $model->table('mb_redpacket_list')->where(array('packet_id'=>$id))->page('20')->select();
		 $lists = $model->table('mb_redpacket_list')->where(array('packet_id'=>$id))->limit('100000')->select();
		 if(!empty($lists)){
			 $total = 0;
			 foreach($lists as $k=>$v){
				 $total += $v['packet_price'];
			 }
		 }
		 Tpl::output('total',$total);
		 Tpl::output('number',count($lists));
		 Tpl::output('list',$list);

		 Tpl::output('show_page',$model->showpage());
		 Tpl::setDirquna('mobile');
		 Tpl::showpage('mb_redpacket.list');
	 }

	 /**
	 * 已使用红包记录
	 */
	 public function uselistWt(){
		 $model = Model();
		 $id = $_GET['id'];
		 $packet = $model->table('mb_redpacket')->where(array('id'=>$id))->find();
		 if(empty($packet)){
			 showMessage('该红包活动不存在');
		 }
		 $condition = array();
		 $condition['packet_id'] = $id;
		 if(!empty($_GET['state'])){
			 $condition['is_use'] = $_GET['state'];
		 }

		 $list = $model->table('mb_redpacket_rec')->where($condition)->page('20')->select();
		 $lists = $model->table('mb_redpacket_rec')->where($condition)->limit('100000')->select();
		 if(!empty($lists)){
			 $total = 0;
			 foreach($lists as $k=>$v){
				 $total += $v['packet_price'];
			 }
		 }
		 Tpl::output('id',$id);
		 Tpl::output('total',$total);
		 Tpl::output('number',count($lists));
		 Tpl::output('list',$list);

		 Tpl::output('show_page',$model->showpage());
		 Tpl::setDirquna('mobile');
		 Tpl::showpage('mb_redpacket.uselist');
	 }

	/**
	 * 新建红包活动
	 */
	public function newWt(){
		$model = Model();
		//新建处理
		if($_POST['form_submit'] != 'ok'){
			Tpl::setDirquna('mobile');
			Tpl::showpage('mb_redpacket.add');
			exit;
		}
		//提交表单
		$obj_validate = new Validate();
		$validate_arr[] = array("input"=>$_POST["packet_name"],"require"=>"true","message"=>"红包名称不能为空");
		$validate_arr[] = array("input"=>$_POST["start_time"],"require"=>"true","message"=>"开始时间不能为空");
		$validate_arr[] = array("input"=>$_POST["end_time"],"require"=>"true","message"=>"结束时间不能为空");
		$validate_arr[] = array("input"=>$_POST["packet_number"],"require"=>"true","message"=>"红包数量不能为空");
		$validate_arr[] = array("input"=>$_POST["packet_amount"],"require"=>"true","message"=>"红包总金额不能为空");
		$validate_arr[] = array("input"=>$_POST["win_rate"],"require"=>"true","message"=>"中奖机率不能为空");

		$obj_validate->validateparam = $validate_arr;
		$error = $obj_validate->validate();
		if ($error != ''){
			showMessage(Language::get('error').$error,'','','error');
		}
		if(floatval($_POST["packet_number"])>1000){
			showMessage('单次生成红包数量不能超过1000个！');
		}
      	if(floatval($_POST["packet_amount"])>100000){
			showMessage('红包的金额不能超过100000元！');
		}
		if(floatval($_POST["packet_number"]*0.01)>floatval($_POST["packet_amount"])){
			showMessage('红包的金额最低为0.01元');
		}
		/* if(empty($_POST['valid_date']) && empty($_POST['valid_date2'])){
			showMessage('必须填写一项红包有效期');
		}
		if(!empty($_POST['valid_date']) && !empty($_POST['valid_date2'])){
			showMessage('只能填写一项红包有效期');
		}
		if(!empty($_POST['valid_date'])){
			$valid_date = strtotime(trim($_POST['valid_date']));
		}
		if(!empty($_POST['valid_date2'])){
			if($_POST['valid_date2'] <= 0){
				showMessage('红包有效天数不能小于或等于0');
			}
			$valid_date2 = $_POST['valid_date2'];
		} */

		//保存
		$input	= array();
		$input['packet_name']	  = trim($_POST['packet_name']);
		$input['start_time']	  = strtotime(trim($_POST['start_time']));
		$input['end_time']	      = strtotime(trim($_POST['end_time']));
		$input['packet_number']	  = trim($_POST['packet_number']);
		$input['packet_amount']	  = trim($_POST['packet_amount']);
		$input['valid_date']	  = 1;//$valid_date;
		$input['valid_date2']	  =1;// $valid_date2;
		$input['win_rate']	      = trim($_POST['win_rate']);
		$input['packet_descript'] = '';//trim($_POST['packet_descript']);
		$input['add_time']        = time();

		$result	= $model->table('mb_redpacket')->insert($input);
		
		if($result){
			$bonus_total = $input['packet_amount'];
			$bonus_count = $input['packet_number'];
			$average = $input['packet_amount']/$input['packet_number'];
			$bonus_min   = 0.01;
			//判断每个红包金额累加与红包总金额是否相等，否则重新计算
			
			$result_bonus = $this->getBonus($bonus_total, $bonus_count,$bonus_min);
				
			foreach($result_bonus as $val){
				$model->table('mb_redpacket_list')->insert(array('packet_id'=>$result, 'packet_price'=>$val));
			}

			showMessage(Language::get('wt_common_op_succ'),'index.php?w=mb_redpacket');
		}else{
			showMessage(Language::get('wt_common_op_fail'));
		}

	}

	/**
	 * 删除活动
	 */
	public function delWt(){
		$model = Model();
		$id	= $_GET['id'];
		$mb_redpacket = $model->table('mb_redpacket')->where(array('id'=>$id))->find();
		if(empty($id) || empty($mb_redpacket)){
			showMessage('该红包活动不存在');
		}
		$result = $model->table('mb_redpacket')->where(array('id'=>$id))->delete();
		if($result){
			$model->table('mb_redpacket_list')->where(array('packet_id'=>$id))->delete();
			
			showMessage(Language::get('wt_common_op_succ'),'index.php?w=mb_redpacket');
		}else{
			showMessage(Language::get('wt_common_op_fail'));
		}
	}

	/**
	 * 关闭/开启活动
	 */
	public function stateWt(){
		$model = Model();
		$state = $_GET['type'];
		$id = $_GET['id'];
		if(!in_array($state,array('close','open')) || empty($id)){
			showMessage('参数错误');
		}
		$mb_redpacket = $model->table('mb_redpacket')->where(array('id'=>$id))->find();
		if(empty($id) || empty($mb_redpacket)){
			showMessage('该红包活动不存在');
		}
		if($state=='open'){
			$result = $model->table('mb_redpacket')->where(array('id'=>$id))->update(array('state'=>1));
		}
		if($state=='close'){
			$result = $model->table('mb_redpacket')->where(array('id'=>$id))->update(array('state'=>2));
		}
		if($result){
			showMessage(Language::get('wt_common_op_succ'),'index.php?w=mb_redpacket');
		}else{
			showMessage(Language::get('wt_common_op_fail'));
		}
	}

	/**
	 * 编辑活动/保存编辑活动
	 */
	public function editWt(){
		$model = Model();
		if($_POST['form_submit'] != 'ok'){
			if(empty($_GET['id'])){
				showMessage('参数错误');
			}
			$mb_redpacket = $model->table('mb_redpacket')->where(array('id'=>$_GET['id']))->find();
			Tpl::output('mb_redpacket',$mb_redpacket);
			Tpl::setDirquna('mobile');
			Tpl::showpage('mb_redpacket.edit');
			exit;
		}
		//提交表单
		$obj_validate = new Validate();
		$validate_arr[] = array("input"=>$_POST["packet_name"],"require"=>"true","message"=>"红包名称不能为空");
		$validate_arr[] = array("input"=>$_POST["start_time"],"require"=>"true","message"=>"开始时间不能为空");
		$validate_arr[] = array("input"=>$_POST["end_time"],"require"=>"true","message"=>"结束时间不能为空");
		$validate_arr[] = array("input"=>$_POST["packet_number"],"require"=>"true","message"=>"红包数量不能为空");
		$validate_arr[] = array("input"=>$_POST["packet_amount"],"require"=>"true","message"=>"红包总金额不能为空");
		$validate_arr[] = array("input"=>$_POST["win_rate"],"require"=>"true","message"=>"中奖机率不能为空");

		$obj_validate->validateparam = $validate_arr;
		$error = $obj_validate->validate();
		if ($error != ''){
			showMessage(Language::get('error').$error,'','','error');
		}
		//构造更新内容
		$update	= array();
		$update['packet_name']	  = trim($_POST['packet_name']);
		$update['start_time']	  = strtotime(trim($_POST['start_time']));
		$update['end_time']	      = strtotime(trim($_POST['end_time']));
		$update['packet_number']	  = trim($_POST['packet_number']);
		$update['packet_amount']	  = trim($_POST['packet_amount']);
		$update['win_rate']	      = trim($_POST['win_rate']);
		$update['packet_descript'] ='';// trim($_POST['packet_descript']);

		$result	= $model->table('mb_redpacket')->where(array('id'=>$_GET['id']))->update($update);
		if($result){
			showMessage(Language::get('wt_common_op_succ'),'index.php?w=mb_redpacket');
		}else{
			showMessage(Language::get('wt_common_op_fail'));
		}
	}



     /**
     *  
     * @param $bonus_total 红包总额
     * @param $bonus_count 红包个数
     * @param $bonus_min 每个小红包的最小额
     * @return 存放生成的每个小红包的值的一维数组
     */ 
    public function getBonus($total, $num, $min) {  
        $result = array();  
      	
      	for ($i=1;$i<=$num;$i++)  
        {  
          	if($i==$num){
              $result[$i-1] = $total;
            }else{
              $temp_total = floatval($total-floatval(($num-$i)*$min));
              $temp_total = sprintf("%.2f",substr(sprintf("%.3f", $temp_total), 0, -1)); 
              $min2 = sprintf("%.2f",substr(sprintf("%.3f", $min), 0, -1)); 
              $total = sprintf("%.2f",substr(sprintf("%.3f",$total), 0, -1)); 
              
              $safe_total=floatval($temp_total/($num-$i));//随机安全上限 
             
              if(($temp_total-$min2)<0.01 || ($safe_total-$min2)<0.01){ 
                $total=$total-$min;  
                $result[$i-1] = $min;
              }else{
                
                $money=floatval(mt_rand($min*100,$safe_total*100)/100);  
                $total=$total-$money;  
                $result[$i-1] = $money;
                
              }
            }
        } 
 
       
        return $result;  
    }



}
