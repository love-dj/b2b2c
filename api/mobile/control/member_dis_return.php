<?php
/**
 * 分返现管理
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class member_dis_returnControl extends mobiledisreturnControl {
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 返现
     */
    public function indexWt() 
	{
		$model_pd = Model('predeposit');
        /****************单品消费返现触发发放*******************/  
		//查询正在返现中的用户队列
        $condition = array(
			'member_id' => $this->member_info['member_id'], 
			'status' => '1',
		);
		//获取配置信息
		$setting_infos = Model('setting')->getListSetting();
        $buy_list = $model_pd->getBuyList($condition, $this->page,'createtime desc');
		
		if($setting_infos['buy_return_isuse'] ==1 && $buy_list){
			
			//循环队列执行返现操作
			foreach($buy_list as $k=>$v){
				//查询最近一次返现时间
				$last_return_log = Model('buy_return_log')->field('createtime')->where('buy_return_id = '.$v['id'] . ' ORDER BY createtime DESC')->find();
				$last_return_log_time = $last_return_log['createtime'];
				
				//如果从未返现,则取队列的 最后一次更新时间
				if(!$last_return_log_time){
					$last_return_log_time = $v['updatetime'];
				}
				
				//返现时间段1,每天;2,每周;0,每月
				if($setting_infos['buy_return_times'] == 1){
					$check_times = 86400;
				}else if($setting_infos['buy_return_times'] == 2){
					$check_times = 86400 * 7;
				}else{
					$month_start_day = date('Y-m-01 00:00:00',strtotime("-1 month"));
					$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
					$start_time =  strtotime($month_start_day);
					$end_time =  strtotime($month_last_day);
					$days = cal_days_in_month(CAL_GREGORIAN,date('m',$start_time),date('Y',$end_time));
					$check_times = 86400 * $days;
				}
				//计算已有几次未返现(投机取巧,把主动改为被动,用户触发返现,然后计算次数,做假时间戳)
				$no_pay_times = intval((time() - $last_return_log_time)/$check_times);
				
				if($no_pay_times > 0){
					//缓存
					for($times = 1;$times <= $no_pay_times; $times++){
						//重新获取当前队列的信息
						$v = Model('buy_return')->where(array('status' => 1, 'id'=>$v['id']))->find();
						//如果剩余未返现金额大于0,继续返现,否则修改队列为已完成
						if($v['balance_commission'] > 0){
							//判断返现方式为递减还是等额
							if($v['return_type'] == 1){
								$this_return_commsissiom = round(($v['total_commission'] * $v['each_return_rate'] * 0.01), 2);
							}else{
								$this_return_commsissiom = round(($v['balance_commission'] * $v['each_return_rate'] * 0.01), 2);
							}
							//更新用户佣金总额
							Model('member')->where('member_id = ' . $this->member_info['member_id'])->setInc('trad_amount',$this_return_commsissiom);
							
							$pay_commission = $v['pay_commission'] + $this_return_commsissiom;
							$balance_commission = $v['balance_commission'] - $this_return_commsissiom;
							//更新返现队列信息
							$buy_return_data = array(
								'id' => $v['id'],
								'pay_commission' => $pay_commission,
								'balance_commission' => $balance_commission,
								'updatetime' => $v['updatetime']+$check_times,
							);
							if($balance_commission <= 0){
								$buy_return_data['status'] = 2;
							}
							Model('buy_return')->update($buy_return_data);
							//记录返现日志
							$buy_return_log_data = array(
								'buy_return_id' => $v['id'],
								'uid' => $v['uid'],
								'return_type' => $v['return_type'],
								'return_commission' => $this_return_commsissiom,
								'remark' => '',
								'createtime' => $buy_return_data['updatetime'],
								'status' => 1,
							);
							Model('buy_return_log')->insert($buy_return_log_data);
						}else{
							//更新队列为已完成
							$buy_return_data = array(
								'id' => $v['id'],
								'status' => 2,
							);
							Model('buy_return')->update($buy_return_data);
						}
					}
				}
			}
		}
		
        /****************单品消费返现触发发放*******************/
		
		
        /****************满额返现触发发放*******************/
		//检测开功能是否开启
		if($setting_infos['full_return_isuse'] == 1){
			//返利时间段1,每天;2,每月
			if($setting_infos['full_return_turnover_mode'] == 1){
				$check_times = 86400;
			}else{
				$month_start_day = date('Y-m-01 00:00:00',strtotime("-1 month"));
				$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
				$start_time =  strtotime($month_start_day);
				$end_time =  strtotime($month_last_day);
				$days = cal_days_in_month(CAL_GREGORIAN,date('m',$start_time),date('Y',$end_time));
				$check_times = 86400 * $days;
			}
			
			//获取最近一次返现时间. 可能是空,空就表示从未达到满额要求,从未返利,
			$last_return_time = Model('full_return_log')->where($where)->order('createtime DESC')->find();
			
			//如果空, 默认有一次返现机会,后面查询是否有资格进入返现队列
			if(!$last_return_time){
				$no_pay_times = 1;
			}else{
				//计算是否有未返现的次数
				$no_pay_times = intval((time() - $last_return_time['createtime'])/$check_times);
			}
			
			if($no_pay_times > 0){
				
				for($i = 1; $i <= $no_pay_times; $i++){
					//返利时间段1,每天;2,每月
					if($setting_infos['full_return_turnover_mode'] == 1){
						$start_time =  strtotime(date('Y-m-d 00:00:00',strtotime("-{$i} day")));
						$end_time =  strtotime(date('Y-m-d 23:59:59', strtotime("-{$i} day")));
						$check_times = 86400;
					}else{
						$month_start_day = date('Y-m-01 00:00:00',strtotime("-{$i} month"));
						$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
						$start_time =  strtotime($month_start_day);
						$end_time =  strtotime($month_last_day);
						$days = cal_days_in_month(CAL_GREGORIAN,date('m',$start_time),date('Y',$end_time));
						$check_times = 86400 * $days;
					}
					
					//满额结算事件1,订单完成;2,订单支付;
					if($setting_infos['full_return_settlement_event'] == 1){
						$where = 'o.finnshed_time > ' . $start_time . ' AND o.finnshed_time < ' . $end_time . ' AND o.order_state >= 40';
					}else{
						$where = 'o.add_time > ' . $start_time . ' AND o.add_time < ' . $end_time . ' AND o.order_state >= 20';
					}
					
					
					//返现基础
					//返现基数计算方式 1,订单实付金额-运费;2商品现价基数;3商品成本基数;4订单利润;
					if($setting_infos['full_return_commission'] == 1){
						
						$sql = 'SELECT SUM(order_amount-shipping_fee) as money FROM `'. DBPRE .'orders` WHERE '.$where;
						
					}elseif($setting_infos['full_return_commission'] == 2){
						
						$sql = 'SELECT SUM(goods_amount) as money FROM `'. DBPRE .'orders` WHERE '.$where;
						
					}elseif($setting_infos['full_return_commission'] == 3){
						$sql = 'SELECT SUM(g.goods_costprice) as money FROM `'. DBPRE .'orders` AS o LEFT JOIN `'. DBPRE .'order_goods` AS og ON o.order_id = og.order_id LEFT JOIN '. DBPRE .'goods AS g ON og.goods_id = g.goods_id WHERE '.$where;
						
					}elseif($setting_infos['full_return_commission'] == 4){
						$sql = 'SELECT SUM(o.order_amount - o.shipping_fee - g.goods_costprice) as money FROM `'. DBPRE .'orders` AS o LEFT JOIN `'. DBPRE .'order_goods` AS og ON o.order_id = og.order_id LEFT JOIN '. DBPRE .'goods AS g ON og.goods_id = g.goods_id WHERE '.$where;
					}
					
					//执行查询
					$result = Model()->query($sql);
					$all_money = $result[0]['money'];
					if($all_money > 0){
						//计算本次返现所有金额
						$total_money = round(($all_money * $setting_infos['full_return_base_ratio'] * 0.01), 2);
						
						if($total_money > 0){
							
							//统计所有用户所有购买金额
							$buy_money_sql = 'SELECT SUM(order_amount-shipping_fee) as money,buyer_id FROM `'. DBPRE .'orders` WHERE order_state >= 40 GROUP BY buyer_id';
							$members_buy_money_result = Model()->query($buy_money_sql);
							
							//循环统计此次满额返现所有权益数
							$count_member = 0;
							
							foreach($members_buy_money_result as $k=>$member){
							 
								//查询该用户是否有未返完队列
								$member_where = array(
									'uid' => $member['buyer_id'],
									'status' => 1,
								);
								$member_no_pay_full_return = Model('full_return')->where($where)->find();
								
								//如果该用户没有未返完队列
								if(!$member_no_pay_full_return){
									//统计该用户所有购买金额
									$member_buy_money = Model('orders')->where('order_state >= 40 AND buyer_id = ' . $member['buyer_id'])->field('SUM(order_amount - shipping_fee) as money')->find();
									
									//查询该用户历史满额返现队列占用金额;
									$history_full = Model('full_return')->field('SUM(`limit` * `limit_money`) AS `total_money`')->where('uid = ' . $member['buyer_id'] . ' AND status = 2')->find();
									
									//该用户剩余可占用权益金额
									$member_surplus_buy_money = $member_buy_money['money'] - $history_full['total_money'];
									
									$member_limit = intval($member_surplus_buy_money/$setting_infos['full_return_limit_money']);
									//每个用户最多可占权益数
									if($member_limit > $setting_infos['full_return_limit']){
										$member_limit = $setting_infos['full_return_limit'];
									}
									$count_member += $member_limit;
								}else{
									$count_member += $member_no_pay_full_return['limit'];
								}
								
							}
							
							//查询登录用户是否要未返现完的满额队列
							$where = array(
								'uid' => $this->member_info['member_id'],
								'status' => 1,
							);
							$no_pay_full_return = Model('full_return')->where($where)->find();
							
							//如果登录用户没有未返完队列,查询用户是否拥有创建新队列资格
							if(!$no_pay_full_return){
								//统计登录用户所有购买金额
								$login_member_buy_money = Model('orders')->where('order_state >= 40 AND buyer_id = ' . $this->member_info['member_id'])->field('SUM(order_amount - shipping_fee) as money')->find();
								
								//查询登录用户历史满额返现队列占用金额;
								$login_history_full = Model('full_return')->field('SUM(`limit` * `limit_money`) AS `total_money`')->where('uid = ' . $this->member_info['member_id'] . ' AND status = 2')->find();
								
								//登录用户剩余可占用权益金额
								$login_member_surplus_buy_money = $login_member_buy_money['money'] - $login_history_full['total_money'];
								
								//计算登录用户所占权益数
								$limit = intval($login_member_surplus_buy_money/$setting_infos['full_return_limit_money']);
								
								//每个用户最多可占权益数
								if($limit > $setting_infos['full_return_limit']){
									$limit = $setting_infos['full_return_limit'];
								}
								
								//计算本次返现登录用户可获得返现金额
								$total_commission = round((($total_money * $setting_infos['full_return_give_ratio'] * 0.01)/$count_member) * $limit, 2);
								
								
								//最大可获得返现金额
								$max_return_money = $limit * ($setting_infos['full_return_limit_money'] * $setting_infos['full_return_give_ratio'] * 0.01);
								if($total_commission > $max_return_money){
									$total_commission = $max_return_money;
								}
									
								//登录用户剩余可用权益和可获得返现金额大于0, 开启新队列
								if($limit > 0 && $total_commission > 0){
									//组装新队列数据
									$add_full_return_data = array(
										'uid' => $this->member_info['member_id'],
										'`limit`' => $limit,
										'limit_money' => $setting_infos['full_return_limit_money'],
										'commission_type' => $setting_infos['full_return_commission'],
										'return_money' => $limit * $setting_infos['full_return_limit_money'],
										'return_base_ratio' => $setting_infos['full_return_base_ratio'],
										'total_commission' => $max_return_money,
										'pay_commission' => 0,
										'balance_commission' => $max_return_money,
										'last_pay_commission' => 0,
										'createtime' => strtotime(date('Y-m-d 08:00:00',time())),
										'updatetime' => strtotime(date('Y-m-d 08:00:01',time())),
										'status' => 0, 
									);
									$full_return_id = Model('full_return')->insert($add_full_return_data);
									
								}
								
								//本次返现金额
								$pay_commission = $total_commission;
								$balance_commission = $max_return_money - $total_commission;
								$updatetime = $add_full_return_data['updatetime'];
								$return_money = $add_full_return_data['return_money'];
							}else{
								//计算本次返现登录用户可获得返现金额
								$total_commission = round((($total_money * $setting_infos['full_return_give_ratio'] * 0.01)/$count_member) * $no_pay_full_return['limit'],2);
								
								if($total_commission > $no_pay_full_return['balance_commission']){
									$total_commission = $no_pay_full_return['balance_commission'];
								}
								//返现队列ID
								$full_return_id = $no_pay_full_return['id'];
								//本次返现金额
								$pay_commission = $no_pay_full_return['pay_commission'] + $total_commission;
								$balance_commission = $no_pay_full_return['balance_commission'] - $total_commission;
								$updatetime = $no_pay_full_return['updatetime'] + $check_times;
								$return_money = $no_pay_full_return['return_money'];
							}
							if($full_return_id > 0){
								//更新返现队列信息
								$full_return_data = array(
									'id' => $full_return_id,
									'pay_commission' => $pay_commission,
									'balance_commission' => $balance_commission,
									'last_pay_commission' => $total_commission,
									'updatetime' => $updatetime,
									'status' => 1,
								);
								if($balance_commission <= 0){
									$buy_return_data['status'] = 2;
								}
								
								Model('full_return')->update($full_return_data);
								
								$full_return_log = array(
									'full_return_id' => $full_return_id,
									'uid' => $this->member_info['member_id'],
									'return_money' => $return_money,
									'return_base_ratio' => $setting_infos['full_return_base_ratio'],
									'return_commission' => $total_commission,
									'remark' => '',
									'createtime' => strtotime(date('Y-m-d 08:00:01',time())),
									'status' => 1,
								);
								Model('full_return_log')->insert($full_return_log);
								
								//更新用户佣金总额
								Model('member')->where('member_id = ' . $this->member_info['member_id'])->setInc('trad_amount',$total_commission);
							}
						}
					}
				}
			}
			
		}
        /****************满额返现触发发放*******************/
		
        $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['available_fx_trad'] = wtPriceFormat($member_info['trad_amount']);
        $member_info['freeze_fx_trad'] = $no_pay_commission;
        $md_info = Model('member')->getMemberDistributionInfo(array('member_id'=>$this->member_info['member_id']));
        $member_info['level_name'] = $md_info['level_name'];
		
		$buy_return_isuse = Model('setting')->getRowSetting('buy_return_isuse');//看看是否开启单品消费返利
		$full_return_isuse = Model('setting')->getRowSetting('full_return_isuse');//看看是否开启满额消费返利
		

        output_data(array('member_info' => $member_info, 'buy_return_isuse' => $buy_return_isuse['value'],'full_return_isuse' => $full_return_isuse['value']));
    }
	
	public function buy_listWt()
	{
		$model_pd = Model('predeposit');
        $condition = array('member_id' => $this->member_info['member_id']);//$this->page
        $buy_list = $model_pd->getBuyList($condition, $this->page,'createtime desc');
        foreach($buy_list as $k=>$v){
            $buy_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            if($v['status'] == 2){
                $buy_list[$k]['status'] = '已返现';
                $buy_list[$k]['updatetime'] = date("Y-m-d H:i:s",$v['updatetime']);
            }elseif($v['status'] == 1){
				$buy_list[$k]['status'] = '返现中';
                $buy_list[$k]['updatetime'] = date("Y-m-d H:i:s",$v['updatetime']);
			}else{
                $buy_list[$k]['status'] = '待返现';
                $buy_list[$k]['updatetime'] = '等待返现';
            }
        }
        $buy_count = $model_pd->getBuyCount($condition);
        $page_count = ceil($buy_count/$this->page);
        output_data(array('buy_list' => $buy_list), mobile_page($page_count));
	}
	
	public function buy_logWt()
	{
		$id = $_POST['id'];
		$where = array(
			'uid' => $this->member_info['member_id'],
		);
		if($id){
			$where['buy_return_id'] = $id;
		}
		$log_list = Model('buy_return_log')->field('id,buy_return_id,return_commission,createtime')->where($where)->page($this->page)->order('createtime DESC')->select();
		foreach($log_list as $k=>$v){
			$log_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
		}
		
		$log_count = Model('buy_return_log')->where($where)->count();
        $page_count = ceil($log_count/$this->page);
		output_data(array('log_list' => $log_list,'id'=>$id), mobile_page($page_count));
	}
	
	public function full_listWt()
	{
        $condition = array('uid' => $this->member_info['member_id']);
        $full_list = Model()->table('full_return')->field('*')->where($condition)->page($this->page)->order('createtime DESC')->select();
        foreach($full_list as $k=>$v){
            $full_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            if($v['status'] == 2){
                $full_list[$k]['status'] = '已返现';
                $full_list[$k]['updatetime'] = date("Y-m-d H:i:s",$v['updatetime']);
            }elseif($v['status'] == 1){
				$full_list[$k]['status'] = '返现中';
                $full_list[$k]['updatetime'] = date("Y-m-d H:i:s",$v['updatetime']);
			}else{
                $full_list[$k]['status'] = '待返现';
                $full_list[$k]['updatetime'] = '等待返现';
            }
        }
        $full_count = Model()->table('full_return')->where($condition)->count();
        $page_count = ceil($full_count/$this->page);
        output_data(array('full_list' => $full_list), mobile_page($page_count));
	}
	
	public function full_logWt()
	{
		$id = $_POST['id'];
		$where = array(
			'uid' => $this->member_info['member_id'],
		);
		if($id){
			$where['full_return_id'] = $id;
		}
		$log_list = Model('full_return_log')->field('id,full_return_id,return_commission,createtime')->where($where)->page($this->page)->order('createtime DESC')->select();
		foreach($log_list as $k=>$v){
			$log_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
		}
		
		$log_count = Model('full_return_log')->where($where)->count();
        $page_count = ceil($log_count/$this->page);
		output_data(array('log_list' => $log_list,'id'=>$id), mobile_page($page_count));
	}
}