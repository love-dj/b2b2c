<?php
/**
 * 分红管理
 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class member_shareholderControl extends mobileshareholderControl {
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 分红
     */
    public function indexWt() 
	{
        /****************股东分红触发发放*******************/  
		$setting_infos = Model('setting')->getListSetting();

		//功能必须开启并且用户分销等级大于等于设定分红最低等级
		if($setting_infos['shareholder_isuse'] == 1){
			//分红时间段0,每月；1,每天；2,每月
			if($setting_infos['shareholder_times'] == 1){
				$check_times = 86400;
			}if($setting_infos['shareholder_times'] == 2){
				$check_times = 86400 * 7;
			}else{
				$month_start_day = date('Y-m-01 00:00:00',strtotime("-1 month"));
				$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
				$check_start_time =  strtotime($month_start_day);
				$check_end_time =  strtotime($month_last_day);
				$days = cal_days_in_month(CAL_GREGORIAN,date('m',$check_start_time),date('Y',$check_end_time));
				$check_times = 86400 * $days;
			}
			
			$last_shareholder_return = Model('shareholder_return')->where('status <> -1')->order('createtime desc')->find();
			
			//如果空, 表示从未进行过股东分红
			if(!$last_shareholder_return){
				$no_return_times = 1;
			}else{
				//计算是否有未分红的次数
				$no_return_times = intval((time() - $last_shareholder_return['createtime'])/$check_times);
			}
			if($no_return_times > 0){
				for($i = 1; $i <= $no_return_times; $i++){
					//分红时间段0,每月；1,每天；2,每月
					if($setting_infos['shareholder_times'] == 1){
						$start_time =  strtotime(date('Y-m-d 00:00:00',strtotime("-{$i} day")));
						$end_time =  strtotime(date('Y-m-d 23:59:59', strtotime("-{$i} day")));
						$check_times = 86400;
					}else if($setting_infos['shareholder_times'] == 2){
						$start_time=mktime(0,0,0,date('m'),date('d')-date('w')+$i-7,date('Y'));
						$end_time=mktime(23,59,59,date('m'),date('d')-date('w')+($i*7)-7,date('Y'));
						$check_times = 86400 * 7;
					}else{
						$month_start_day = date('Y-m-01 00:00:00',strtotime("-{$i} month"));
						$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
						$start_time =  strtotime($month_start_day);
						$end_time =  strtotime($month_last_day);
						$days = cal_days_in_month(CAL_GREGORIAN,date('m',$start_time),date('Y',$end_time));
						$check_times = 86400 * $days;
					}
					
					
					//分红结算事件1,订单完成;2,订单支付;
					if($setting_infos['shareholder_settlement_event'] == 1){
						$where = 'o.finnshed_time > ' . $start_time . ' AND o.finnshed_time < ' . $end_time . ' AND o.order_state >= 40';
					}else{
						$where = 'o.add_time > ' . $start_time . ' AND o.add_time < ' . $end_time . ' AND o.order_state >= 20';
					}
					
					
					//返现基础
					//返现基数计算方式 1,订单实付金额-运费;2商品现价基数;3商品成本基数;4订单利润;
					if($setting_infos['shareholder_commission'] == 1){
						
						$sql = 'SELECT SUM(order_amount-shipping_fee) as money FROM `'. DBPRE .'orders` WHERE '.$where;
						
					}elseif($setting_infos['shareholder_commission'] == 2){
						
						$sql = 'SELECT SUM(goods_amount) as money FROM `'. DBPRE .'orders` WHERE '.$where;
						
					}elseif($setting_infos['shareholder_commission'] == 3){
						$sql = 'SELECT SUM(g.goods_costprice) as money FROM `'. DBPRE .'orders` AS o LEFT JOIN `'. DBPRE .'order_goods` AS og ON o.order_id = og.order_id LEFT JOIN '. DBPRE .'goods AS g ON og.goods_id = g.goods_id WHERE '.$where;
						
					}elseif($setting_infos['shareholder_commission'] == 4){
						$sql = 'SELECT SUM(o.order_amount - o.shipping_fee - g.goods_costprice) as money FROM `'. DBPRE .'orders` AS o LEFT JOIN `'. DBPRE .'order_goods` AS og ON o.order_id = og.order_id LEFT JOIN '. DBPRE .'goods AS g ON og.goods_id = g.goods_id WHERE '.$where;
					}
					
					//执行查询
					$result = Model()->query($sql);
					$all_money = $result[0]['money'];
					if($all_money > 0){
						//获取权重大于等于设置分红分销商等级权重分销商等级ID
						$shareholder_level = Model('distribution_level')->field('id')->where('level_weight >=' . $setting_infos['shareholder_level'])->select();
						foreach($shareholder_level as $v){
							$level_arr[] = $v['id'];
						}
						
						$shareholder_level_ids = implode(',', array_values($level_arr));
						
						//查询所有符合股东分红的分销商
						$member_count = Model()->table('member,member_chain')->join('left')->on('member_chain.member_id=member.member_id')->where('member.member_state = 1 AND member_chain.distribution_level in ('.$shareholder_level_ids.')')->count();
						
						//计算所有分红佣金
						$total_shareholder_commission = round($all_money * $setting_infos['shareholder_rate'] * 0.01,2);
						
						//组装分红队列数据
						$shareholder_return_data = array(
							'start_time' => $start_time,
							'end_time' => $end_time,
							'commission_type' => $setting_infos['shareholder_commission'],
							'total_shareholder_commission' => $total_shareholder_commission,
							'total_member' => $member_count,
							'createtime' => strtotime(date('Y-m-d 08:00:00',time()-$check_times*$i)),
							'status' => 1,
						);
						
						Model('shareholder_return')->insert($shareholder_return_data);
					}
				}
			}
			if($this->member_info['agent_level_weigth'] >= $setting_infos['shareholder_level']){
				$member_last_return_time = Model('shareholder_return_log')->where('uid = ' . $this->member_info['member_id'])->order('createtime desc')->find();
				
				//如果空, 从未分红过
				if(!$member_last_return_time){
					$member_no_return_times = 1;
				}else{
					//计算是否有未返现的次数
					$member_no_return_times = intval((time() - $member_last_return_time['createtime'])/$check_times);
				}
				
				if($member_no_return_times > 0){
					for($i = 1; $i <= $member_no_return_times; $i++){
						
						//分红时间段0,每月；1,每天；2,每月
						if($setting_infos['shareholder_times'] == 1){
							$start_time =  strtotime(date('Y-m-d 00:00:00',strtotime("-{$i} day")));
							$end_time =  strtotime(date('Y-m-d 23:59:59', strtotime("-{$i} day")));
						}else if($setting_infos['shareholder_times'] == 2){
							$start_time=mktime(0,0,0,date('m'),date('d')-date('w')+$i-7,date('Y'));
							$end_time=mktime(23,59,59,date('m'),date('d')-date('w')+($i*7)-7,date('Y'));
						}else{
							$month_start_day = date('Y-m-01 00:00:00',strtotime("-{$i} month"));
							$month_last_day =  date('Y-m-d 23:59:59', strtotime("$month_first_day +1 month -1 day"));
							$start_time =  strtotime($month_start_day);
							$end_time =  strtotime($month_last_day);
						}
						
						//查询该时间段是否有未分完分红队列
						$where = array('start_time'=>$start_time, 'end_time'=>$end_time, 'status'=>1);
						$member_shareholder_return = Model('shareholder_return')->where($where)->find();
						
						if($member_shareholder_return){
							//查询该队列该用户是否分红
							$return_log = Model('shareholder_return_log')->where(array('shareholder_return_id' => $member_shareholder_return['id'],'uid' => $this->member_info['member_id']))->find();
							
							if(!$return_log){
								$total_commission = round($member_shareholder_return['total_shareholder_commission']/$member_shareholder_return['total_member'],2);
								$shareholder_return_log_data = array(
									'shareholder_return_id' => $member_shareholder_return['id'],
									'uid' => $this->member_info['member_id'],
									'return_money' => $total_commission,
									'createtime' => $member_shareholder_return['createtime']+1
								);
								
								Model('shareholder_return_log')->insert($shareholder_return_log_data);
								
								//更新用户佣金总额
								Model('member')->where('member_id = ' . $this->member_info['member_id'])->setInc('trad_amount',$total_commission);
							}
						}
						
						//统计该队列所有分红金额
						$all_pay_money = Model('shareholder_return_log')->field('SUM(return_money) as moeny')->where(array('shareholder_return_id' => $member_shareholder_return['id']))->find();
						
						if($all_pay_money['moeny'] >= $member_shareholder_return['total_shareholder_commission']){
							$update_data = array(
								'id' => $member_shareholder_return['id'],
								'status' => 2,
							); 
							
							Model('shareholder_return')->update($update_data);
						}
					}
				}
				
			}
		}
			
		
		
        /****************股东分红触发发放*******************/
		
        $member_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_id,trad_amount,user_name');
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['available_fx_trad'] = wtPriceFormat($member_info['trad_amount']);
        $member_info['freeze_fx_trad'] = $no_pay_commission;
        $md_info = Model('member')->getMemberDistributionInfo(array('member_id'=>$this->member_info['member_id']));
        $member_info['level_name'] = $md_info['level_name'];
		
        output_data(array('member_info' => $member_info));
    }
	
	public function shareholder_listWt()
	{
		$model = Model('shareholder_return');
        $shareholder_list = $model->where('status <> -1')->order('createtime DESC')->page($this->page)->select();
        foreach($shareholder_list as $k=>$v){
            $shareholder_list[$k]['start_time'] = date('Y-m-d H:i:s',$v['start_time']);
            $shareholder_list[$k]['end_time'] = date('Y-m-d H:i:s',$v['end_time']);
            $shareholder_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        $shareholder_count = $model->where('statsu <> -1')->count;
        $page_count = ceil($shareholder_count/$this->page);
        output_data(array('shareholder_list' => $shareholder_list), mobile_page($page_count));
	}
	
	public function shareholder_logWt()
	{
		$where = array(
			'uid' => $this->member_info['member_id'],
		);
		
		$log_list = Model('shareholder_return_log')->field('*')->where($where)->page($this->page)->order('createtime DESC')->select();
		foreach($log_list as $k=>$v){
			$log_list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
		}
		
		$log_count = Model('shareholder_return_log')->where($where)->count();
        $page_count = ceil($log_count/$this->page);
		output_data(array('log_list' => $log_list), mobile_page($page_count));
	}
	
}