<?php
/**
 * 会员模型
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class memberModel extends Model {

    public function __construct(){
        parent::__construct('member');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {
        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
        $member_info = rcache($member_id, 'member', $fields);
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),'*',true);
            wcache($member_id, $member_info, 'member', 60);
        }
        return $member_info;
    }

    //取得会员的关系信息以及身份信息
    public function getMemberChainInfo($condition, $field = '*', $master = false) {
        return $this->table('member_chain')->field($field)->where($condition)->master($master)->find();       
    }

    // 取得会员的关系信息以及身份信息（优先查询缓存）
    public function getMemberChainByID($member_id, $fields = '*'){
        $memberchain_info = rcache($member_id, 'member_chain', $fields);
        if (empty($memberchain_info)) {
            $memberchain_info = $this->getMemberChainInfo(array('member_id'=>$member_id),'*',true);
            wcache($member_id, $memberchain_info, 'member_chain', 60);
        }
        return $memberchain_info;
    }

    //获取会员的三级分销等级信息
    public function getMemberDistributionInfo($condition){
        $md_info = Model()->table('member_chain,distribution_level')->field('member_chain.distribution_level,distribution_level.level_name')->join('left')->on('member_chain.distribution_level=distribution_level.id')->where($condition)->find();
        return $md_info;
    }

    //获取会员的团队销等级信息
    public function getMemberTeamInfo($condition){
        $md_info = Model()->table('member_chain,team_level')->field('member_chain.team_level,team_level.level_name')->join('left')->on('member_chain.team_level=team_level.id')->where($condition)->find();
        return $md_info;
    }

    /**
     * 递归取得下面所有用户
     * @return array
     */
    public function getChildrenIDs($member_id) {
        $result = array();
        $list = $this->table('member')->field('member_id,member_name')->where(array('inviter_id'=>$member_id))->select();
        foreach ($list as $v) {
            $minfo['member_id'] = $v['member_id'];
            $minfo['member_name'] = $v['member_name'];
            $minfo['childs'] = $this->getChildrenIDs($v['member_id']);
            $result[] = $minfo;
        }
        return $result;
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = null, $order = 'member_id desc', $limit = '') {
       return $this->table('member')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

	/**
	*获取佣金订单数量
	*
	*/
	    public function getOrderInviteCount($inviteid,$memberid)
    {
		return $this->table('pd_log')->where(array('lg_invite_member_id' => $memberid,'lg_member_id' => $inviteid))->count();
    }
		/**
	*获取佣金订单总金额
	*
	*/
	    public function getOrderInviteamount($inviteid,$memberid)
    {
		return $this->table('pd_log')->where(array('lg_invite_member_id' => $memberid,'lg_member_id' => $inviteid))->sum('lg_av_amount');
    }
	    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMembersList($condition, $page = null, $order = 'member_id desc', $field = '*') {
       return $this->table('member')->field($field)->where($condition)->page($page)->order($order)->select();
    }

	
	/**
	 * 删除会员
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " member_id = '". intval($id) ."'";
			$result = Db::delete('member',$where);
			return $result;
		}else {
			return false;
		}
	}

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition) {
        return $this->table('member')->where($condition)->count();
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member');
        }
        return $update;
    }

    /**
     * 编辑会员关系链信息
     * @param array $condition
     * @param array $data
     */
    public function editMemberChain($condition, $data) {
        $update = $this->table('member_chain')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member_chain');
        }
        return $update;
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(),$reg = false) {
        if (empty($member_info) || !is_array($member_info)) return ;

        $_SESSION['is_login']   = '1';
        $_SESSION['member_id']  = $member_info['member_id'];
        $_SESSION['member_name']= $member_info['member_name'];
        $_SESSION['member_email']= $member_info['member_email'];
        $_SESSION['is_buy']     = isset($member_info['is_buy']) ? $member_info['is_buy'] : 1;
        $_SESSION['avatar']     = $member_info['member_avatar'];
        $_SESSION['is_fenxiaor'] = ($member_info['fx_state'] == 2) ? 1 : 0;
        
        // 头衔COOKIE
        $this->set_avatar_cookie();

        $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$_SESSION['member_id']));
        $_SESSION['store_id'] = $seller_info['store_id'];

        if (trim($member_info['member_qqopenid'])){
            $_SESSION['openid']     = $member_info['member_qqopenid'];
        }
        if (trim($member_info['member_sinaopenid'])){
            $_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
        }

        if (!$reg) {
            //添加会员积分
            $this->addPoint($member_info);
            //添加会员经验值
            $this->addExppoint($member_info);
        }

        if(!empty($member_info['member_login_time'])) {
            $update_info    = array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);
        }
        setWtCookie('cart_goods_num','',-3600);
        // cookie中的cart存入数据库
        Model('cart')->mergecart($member_info,$_SESSION['store_id']);    

        $member_chain_info = $this->getMemberChainByID($member_info['member_id']); 
        $member_info['distribution_level'] = $member_chain_info['distribution_level'];
        $member_info['team_level'] = $member_chain_info['team_level'];

        /*************三级分销等级升级校验**************/
        $this->upgrade_distribution($member_info);
        /*************三级分销等级升级校验**************/

        /*************团队等级升级校验**************/
        $this->upgrade_team($member_info);
        /*************团队等级升级校验**************/

        // 自动登录
        if ($member_info['auto_login'] == 1) {
            $this->auto_login();
        }
    }

    //验证会员的团队等级身份是否可以升级
    public function upgrade_team($member_info){
        $team_isuse = Model('setting')->getRowSetting('team_isuse');//是否启用团队无限级 1:开启；0：关闭
        if($team_isuse['value'] == 1){
            $member_now_level = $member_info['team_level'];//用户当前的分销等级
            $member_now_lpower = 0;//当前等级的权重
            $all_level = Model("team_level")->order("level_weight asc")->select();   
            $new_power = 0;//下一等级权重
            $new_level = 0;//下一等级的ID
            $new_level_condition = array(); //下一等级条件项目
            $new_level_value = array();  //下一等级条件参数值
            foreach($all_level as $v){
                if($v['id'] == $member_now_level){
                    $member_now_lpower = $v['level_weight'];
                }
                if($v['level_weight'] > $new_power){//现有的最大等级的权重
                    $new_power = $v['level_weight'];
                }
            }   
            foreach($all_level as $k=>$v){
                if($member_now_lpower >= $v['level_weight']){
                    unset($all_level[$k]);//去掉低于现在等级的数据
                }
            }
            if(!empty($all_level)){
                //不为空才去检测，为空的话说明是最高等级   
                foreach($all_level as $v){              
                    if($v['level_weight'] <= $new_power){
                        $new_power = $v['level_weight'];
                        $new_level = $v['id'];
                        $new_level_condition = unserialize($v['same_layer_condition']);
                        $new_level_value = unserialize($v['condition_value']);
                    }
                }
                $up_condition = $new_level_value['up_condition'];//升级条件组合类型 1：与；0：或  
                $new_condition = array();
                foreach($new_level_condition as $k=>$v){ 
                    if($v == 'first_cost_count'){//一级会员消费满 
                        $all_onechilds_order = Model()->table('member,orders')->field('orders.order_amount')->join('left')->on('orders.buyer_id=member.member_id')->where(array('invite_one'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->select();
                        $first_cost_num = count($all_onechilds_order);
                        $first_cost_count = 0;
                        foreach($all_onechilds_order as $v){
                            $first_cost_count += $v['order_amount'];
                        }
                        if($first_cost_num >= $new_level_value['first_cost_num'] && $first_cost_count >= $new_level_value['first_cost_count']){
                            $new_condition['first_cost_count'] = 1;  //满足条件
                        }else{
                            $new_condition['first_cost_count'] = 0;//不满足条件
                        }
                    }
                    if($v == 'team_first_member_count'){//一级会员数量满
                        $m_list = Model("member")->field('member_id')->where(array('invite_one'=>$_SESSION['member_id']))->select(); 
                        $m_count = count($m_list);
                        if($m_count >= $new_level_value['team_first_member_count']){
                            $new_condition['team_first_member_count'] = 1; //满足条件
                        }else{
                            $new_condition['team_first_member_count'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'second_cost_count'){//二级会员消费满 
                        $all_twochilds_order = Model()->table('member,orders')->field('orders.order_amount')->join('left')->on('orders.buyer_id=member.member_id')->where(array('invite_two'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->select();
                        $second_cost_num = count($all_twochilds_order);
                        $second_cost_count = 0;
                        foreach($all_twochilds_order as $v){
                            $second_cost_count += $v['order_amount'];
                        }
                        if($second_cost_num >= $new_level_value['second_cost_num'] && $second_cost_count >= $new_level_value['second_cost_count']){
                            $new_condition['second_cost_count'] = 1;  //满足条件
                        }else{
                            $new_condition['second_cost_count'] = 0;//不满足条件
                        }

                    }
                    if($v == 'team_second_member_count'){//二级会员数量满
                        $m_list = Model("member")->field('member_id')->where(array('invite_two'=>$_SESSION['member_id']))->select(); 
                        $m_count = count($m_list);
                        if($m_count >= $new_level_value['team_second_member_count']){
                            $new_condition['team_second_member_count'] = 1; //满足条件
                        }else{
                            $new_condition['team_second_member_count'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'team_order_money'){//团队业绩金额满  
                        $m_comm = Model("member_chain")->field('team_cost_money')->where(array('member_id'=>$_SESSION['member_id']))->find(); 
                        $team_cost_money = $m_comm['team_cost_money']>0?$m_comm['team_cost_money']:0;
                        if($team_cost_money >= $new_level_value['team_order_money']){
                            $new_condition['team_order_money'] = 1; //满足条件
                        }else{
                            $new_condition['team_order_money'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'team_member_count'){// 团队会员数量满  
                        $m_list = Model("member_chain")->field('member_id')->where("FIND_IN_SET(".$_SESSION['member_id'].",relation_chain)")->select();
                        $m_count = count($m_list);
                        if($m_count >= $new_level_value['team_member_count']){
                            $new_condition['team_member_count'] = 1; //满足条件
                        }else{
                            $new_condition['team_member_count'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'self_buy_money'){//自购订单金额满
                        $all_own_order = Model("orders")->field('sum(order_amount) as all_order_money')->where(array('buyer_id'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->find(); //只统计已收货的订单  并且没有退货产生
                        $self_buy_money = $all_own_order['all_order_money']>0?$all_own_order['all_order_money']:0;
                        if($self_buy_money >= $new_level_value['self_buy_money']){//自购订单金额满
                            $new_condition['self_buy_money'] = 1;  //满足条件
                        }else{
                            $new_condition['self_buy_money'] = 0;//不满足条件
                        }
                    }
                    if($v == 'self_buy_count'){//自购单数量满
                        $all_own_order = Model("orders")->field('count(order_id) as all_order_count')->where(array('buyer_id'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->find(); //只统计已收货的订单  并且没有退货产生
                        $self_buy_count = $all_own_order['all_order_count'];
                        if($self_buy_count >= $new_level_value['self_buy_count']){//自购单数量满
                            $new_condition['self_buy_count'] = 1;  //满足条件
                        }else{
                            $new_condition['self_buy_count'] = 0;//不满足条件
                        }
                    }
                    if($v == 'settle_money'){
                        $m_comm = Model("member_chain")->field('team_commission')->where(array('member_id'=>$_SESSION['member_id']))->find(); 
                        $team_commission = $m_comm['team_commission']>0?$m_comm['team_commission']:0;
                        if($team_commission >= $new_level_value['settle_money']){//结算分红金额满
                            $new_condition['settle_money'] = 1; //满足条件
                        }else{
                            $new_condition['settle_money'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'team_identity_member_count'){
                        $m_list = Model("member_chain")->field('member_id')->where("team_level > 0 and FIND_IN_SET(".$_SESSION['member_id'].",relation_chain)")->select();
                        $m_count = count($m_list);
                        if($m_count >= $new_level_value['team_identity_member_count']){//团队具备身份的会员数量满 
                            $new_condition['team_identity_member_count'] = 1; //满足条件
                        }else{
                            $new_condition['team_identity_member_count'] = 0; //不满足条件
                        } 
                    }
                    if($v == 'buy_one_goods'){
                        $member_str = implode(',', $new_level_value['goods_id_list']);
                        $all_buy_goods = Model()->table('order_goods,orders')->field('order_goods.goods_id,order_goods.order_id,orders.order_state,orders.refund_state')->join('left')->on('order_goods.order_id=orders.order_id')->where("order_state > 0 and refund_state = 0 and orders.buyer_id = {$_SESSION['member_id']} and FIND_IN_SET(goods_id,'{$member_str}')")->select();
                        $buy_one_goods = count($all_buy_goods);
                        if($buy_one_goods >= 1){//购买指定商品之一
                            $new_condition['buy_one_goods'] = 1;//满足条件
                        }else{
                            $new_condition['buy_one_goods'] = 0;//不满足条件
                        }
                    }
                }           

                //print_R($new_condition);
                if($up_condition){
                    //echo '与';
                    $can_upgrade = true;
                    foreach($new_condition as $k=>$v){
                        if($v == 0){
                            $can_upgrade = false;
                        }
                    }
                }else{
                    //echo '或';
                    $can_upgrade = false;
                    foreach($new_condition as $k=>$v){
                        if($v == 1){
                            $can_upgrade = true;
                        }
                    }
                }

                if($can_upgrade){
                    //echo '满足条件，可以升级';
                    $update_info    = array(
                        'team_level'=> $new_level
                    );
                    $this->editMemberChain(array('member_id'=>$_SESSION['member_id']),$update_info);//升级，将新的等级Id更新到用户记录里面去
                }else{
                    //echo '不满足条件，继续努力';
                }
            }else{
                //echo '已经是最高等级了';
            }            
        }
    }

    //验证会员的三级分销等级身份是否可以升级
    public function upgrade_distribution($member_info){
        $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');//是否启用三级分销 1:开启；0：关闭
        if($distribution_isuse['value'] == 1){
            $member_now_level = $member_info['distribution_level'];//用户当前的分销等级
            $member_now_lpower = 0;//当前等级的权重
            $all_level = Model("distribution_level")->order("level_weight asc")->select();   
            $new_power = 0;//下一等级权重
            $new_level = 0;//下一等级的ID
            $new_level_condition = array(); //下一等级条件项目
            $new_level_value = array();  //下一等级条件参数值
            foreach($all_level as $v){
                if($v['id'] == $member_now_level){
                    $member_now_lpower = $v['level_weight'];
                }
                if($v['level_weight'] > $new_power){//现将现有的最大化
                    $new_power = $v['level_weight'];
                }
            }   
            foreach($all_level as $k=>$v){
                if($member_now_lpower >= $v['level_weight']){
                    unset($all_level[$k]);//去掉低于现在等级的数据
                }
            }
            foreach($all_level as $v){                
                if($v['level_weight'] < $new_power){
                    $new_power = $v['level_weight'];
                    $new_level = $v['id'];
                    $new_level_condition = unserialize($v['level_condition']);
                    $new_level_value = unserialize($v['condition_value']);
                }
            }
            $up_condition = $new_level_value['up_condition'];//升级条件组合类型 1：与；0：或   
            $new_condition = array();
            foreach($new_level_condition as $k=>$v){ 
                if($v == 'first_cost_count'){//人数达到几人  下一级用户消费满多少元  
                    $all_childs = Model("member_chain")->where(array('superior_id'=>$_SESSION['member_id']))->select(); 
                    $first_cost_num = count($all_childs);  
                    //$all_childs_order = Model()->table('member_chain,distribution_order')->field('sum(distribution_order.commission_amount) as all_commission_money')->join('left')->on('distribution_order.buyer_id=member_chain.member_id')->where(array('superior_id'=>$_SESSION['member_id'],'status'=>1))->find();
                    $all_childs_order = Model()->table('member,orders')->field('sum(orders.order_amount) as all_commission_money')->join('left')->on('orders.buyer_id=member.member_id')->where(array('invite_one'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->find();

                    $first_cost_count = $all_childs_order['all_commission_money'];
                    if($first_cost_num >= $new_level_value['first_cost_num'] && $first_cost_count >= $new_level_value['first_cost_count']){
                        $new_condition['first_cost_count'] = 1;  //满足条件
                    }else{
                        $new_condition['first_cost_count'] = 0;//不满足条件
                    }
                }
                if($v == 'order_money'){
                    $all_team_order = Model()->table('member,distribution_order')->field('sum(distribution_order.commission_amount) as all_torder_money')->join('left')->on('distribution_order.buyer_id=member.member_id')->where("status=1 and (invite_one = {$_SESSION['member_id']} or invite_two = {$_SESSION['member_id']} or invite_three = {$_SESSION['member_id']})")->find();
                    $order_money = $all_team_order['all_torder_money'];
                    if($order_money >= $new_level_value['order_money']){//团队分销订单金额满  
                        $new_condition['order_money'] = 1;  //满足条件
                    }else{
                        $new_condition['order_money'] = 0;//不满足条件
                    }
                }
                if($v == 'order_count'){
                    $all_team_orders = Model()->table('member,distribution_order')->field('distribution_order.order_id')->join('left')->on('distribution_order.buyer_id=member.member_id')->where("status=1 and (invite_one = {$_SESSION['member_id']} or invite_two = {$_SESSION['member_id']} or invite_three = {$_SESSION['member_id']})")->select();
                    $order_count = count($all_team_orders);
                    if($order_count >= $new_level_value['order_count']){//团队分销订单数量满
                        $new_condition['order_count'] = 1;  //满足条件
                    }else{
                        $new_condition['order_count'] = 0;//不满足条件
                    }
                }
                if($v == 'self_buy_money'){
                    $all_own_order = Model("orders")->field('sum(order_amount) as all_order_money')->where(array('buyer_id'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->find(); //只统计已收货的订单  并且没有退货产生
                    $self_buy_money = $all_own_order['all_order_money'];
                    if($self_buy_money >= $new_level_value['self_buy_money']){//自购订单金额满
                        $new_condition['self_buy_money'] = 1;  //满足条件
                    }else{
                        $new_condition['self_buy_money'] = 0;//不满足条件
                    }
                }
                if($v == 'self_buy_count'){
                    $all_own_order = Model("orders")->field('count(order_id) as all_order_count')->where(array('buyer_id'=>$_SESSION['member_id'],'order_state'=>40,'refund_state'=>0))->find(); //只统计已收货的订单  并且没有退货产生
                    $self_buy_count = $all_own_order['all_order_count'];
                    if($self_buy_count >= $new_level_value['self_buy_count']){//自购单数量满
                        $new_condition['self_buy_count'] = 1;  //满足条件
                    }else{
                        $new_condition['self_buy_count'] = 0;//不满足条件
                    }
                }
                if($v == 'settle_money'){
                    $commission_orders = Model('distribution_order')->field('commission_one_level,commission_one_uid,commission_two_level,commission_two_uid,commission_three_level,commission_three_uid')->where("status = 1 and (commission_one_uid = {$_SESSION['member_id']} or commission_two_uid = {$_SESSION['member_id']} or commission_three_uid = {$_SESSION['member_id']})")->select();
                    $settle_money = 0;
                    foreach($commission_orders as $k=>$v){
                        if($v['commission_one_uid'] == $_SESSION['member_id']){
                            $settle_money += $v['commission_one_level'];
                        }
                        if($v['commission_two_uid'] == $_SESSION['member_id']){
                            $settle_money += $v['commission_two_level'];
                        }
                        if($v['commission_three_uid'] == $_SESSION['member_id']){
                            $settle_money += $v['commission_three_level'];
                        }
                    }
                    if($settle_money >= $new_level_value['settle_money']){//结算佣金总额满
                        $new_condition['settle_money'] = 1; //满足条件
                    }else{
                        $new_condition['settle_money'] = 0; //不满足条件
                    }
                }
                if($v == 'buy_one_goods'){
                    $member_str = implode(',', $new_level_value['goods_id_list']);
                    //select og.goods_id,og.order_id,o.order_state,o.refund_state from lbsz_order_goods as og left join lbsz_orders as o on og.order_id = o.order_id where order_state > 0 and refund_state = 0 and o.buyer_id = 11 and FIND_IN_SET(goods_id,'41,34,28,24')  
                    $all_buy_goods = Model()->table('order_goods,orders')->field('order_goods.goods_id,order_goods.order_id,orders.order_state,orders.refund_state')->join('left')->on('order_goods.order_id=orders.order_id')->where("order_state > 0 and refund_state = 0 and orders.buyer_id = {$_SESSION['member_id']} and FIND_IN_SET(goods_id,'{$member_str}')")->select();
                    $buy_one_goods = count($all_buy_goods);
                    if($buy_one_goods >= 1){//购买指定商品之一
                        $new_condition['buy_one_goods'] = 1;//满足条件
                    }else{
                        $new_condition['buy_one_goods'] = 0;//不满足条件
                    }
                }
            } 
            //print_R($new_condition);
            if($up_condition){
                //echo '与';
                $can_upgrade = true;
                foreach($new_condition as $k=>$v){
                    if($v == 0){
                        $can_upgrade = false;
                    }
                }
            }else{
                //echo '或';
                $can_upgrade = false;
                foreach($new_condition as $k=>$v){
                    if($v == 1){
                        $can_upgrade = true;
                    }
                }
            }

            if($can_upgrade){
                //echo '满足条件，可以升级';
                $update_info    = array(
                    'distribution_level'=> $new_level
                );
                $this->editMemberChain(array('member_id'=>$member_info['member_id']),$update_info);//升级，将新的等级Id更新到用户记录里面去
            }else{
                //echo '不满足条件，继续努力';
            }
        }
    }
	
	/**
	 * 获取会员信息
	 *
	 * @param	array $param 会员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoMember($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'member';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_list	= Db::select($param);
		$member_info	= $member_list[0];
		if (intval($member_info['store_id']) > 0){
	      $param	= array();
	      $param['table']	= 'store';
	      $param['field']	= 'store_id';
	      $param['value']	= $member_info['store_id'];
	      $field	= 'store_id,store_name,grade_id';
	      $store_info	= Db::getRow($param,$field);
	      if (!empty($store_info) && is_array($store_info)){
		      $member_info['store_name']	= $store_info['store_name'];
		      $member_info['grade_id']	= $store_info['grade_id'];
	      }
		}
		return $member_info;
	}
    
    /**
     * 7天内自动登录
     */
    public function auto_login() {
        // 自动登录标记 保存7天
        setWtCookie('auto_login', encrypt($_SESSION['member_id'], MD5_KEY), 7*24*60*60);
    }
    
    public function set_avatar_cookie() {
        setWtCookie('member_avatar', $_SESSION['avatar'], 365*24*60*60);
    }
    
    /**
     * 
     */
    public function login($login_info) {
        if (process::islock('login')) {
            return array('error' => '您的操作过于频繁，请稍后再试');
        }
        process::addprocess('login');
        $user_name = $login_info['user_name'];
        $password = $login_info['password'];
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array(
                "input" => $user_name,
                "require" => "true",
                "message" => "请填写用户名"
            ),
            array(
                "input" => $user_name,
                "validator" => "username",
                "message" => "请填写字母、数字、中文、_"
            ),
            array(
                "input" => $user_name,
                "max" => "20",
                "min" => "4",
                "validator" => "length",
                "message" => "用户名长度要在4~20个字符"
            ),
            array(
                "input" => $password,
                "require" => "true",
                "message" => "密码不能为空"
            )
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }
        $condition = array();
        $condition['member_name'] = $user_name;
        $condition['member_passwd'] = md5($password);
        $member_info = $this->getMemberInfo($condition);
        if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $user_name)) {//根据会员名没找到时查手机号
            $condition = array();
            $condition['member_mobile'] = $user_name;
            $condition['member_passwd'] = md5($password);
            $member_info = $this->getMemberInfo($condition);
        }
        if(empty($member_info) && (strpos($user_name, '@') > 0)) {//按邮箱和密码查询会员
            $condition = array();
            $condition['member_email'] = $user_name;
            $condition['member_passwd'] = md5($password);
            $member_info = $this->getMemberInfo($condition);
        }
        if (!empty($member_info)) {
            if(!$member_info['member_state']){
                return array('error' => '账号被停用');
            }
            process::clear('login');

            //添加会员积分
            $this->addPoint($member_info);
            //添加会员经验值
            $this->addExppoint($member_info);

            $update_info    = array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);
            
            return $member_info;
        } else {
            return array('error' => '用户名或密码错误，请重新登录');
        }
    }

    /**
     * 注册
     */
    public function register($register_info) {
        //重复注册验证
        if (process::islock('reg')){
            return array('error' => '您的操作过于频繁，请稍后再试');
        }
        // 注册验证
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array(
                "input" => $register_info["username"],
                "require" => "true",
                "message" => "请填写用户名"
            ),
            array(
                "input" => $register_info["username"],
                "validator" => "username",
                "message" => "请填写字母、数字、中文、_"
            ),
            array(
                "input" => $register_info["username"],
                "max" => "20",
                "min" => "4",
                "validator" => "length",
                "message" => "用户名长度要在4~20个字符"
            ),
            array(
                "input" => $register_info["password"],
                "require" => "true",
                "message" => "密码不能为空"
            ),
            array(
                "input" => $register_info["password_confirm"],
                "require" => "true",
                "validator" => "Compare",
                "operator" => "==",
                "to" => $register_info["password"],
                "message" => "密码与确认密码不相同"
            ),
            array(
                "input" => $register_info["email"],
                "require" => "true",
                "validator" => "email",
                "message" => "电子邮件格式不正确"
            ),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }

        if(is_numeric($register_info["username"])) {
            return array('error' => '用户名不能为纯数字');
        }

        // 验证用户名是否重复
        $check_member_name  = $this->getMemberInfo(array('member_name'=>$register_info['username']));
        if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
        }

        // 验证邮箱是否重复
        $check_member_email = $this->getMemberInfo(array('member_email'=>$register_info['email']));
        if(is_array($check_member_email) and count($check_member_email)>0) {
            return array('error' => '邮箱已存在');
        }
	
	// 会员添加
        $member_info    = array();
		$member_info['member_id']=$register_info['member_id'];
        $member_info['member_name']     = $register_info['username'];
        $member_info['member_passwd']   = $register_info['password'];
        $member_info['member_email']        = $register_info['email'];
		//添加邀请人(推荐人)会员积分
		$member_info['inviter_id']	= $register_info['inviter_id'];
		// 分销
		$member_info['invite_one']        = $register_info['invite_one'];
		$member_info['invite_two']        = $register_info['invite_two'];
		$member_info['invite_three']      = $register_info['invite_three'];

        $insert_id  = $this->addMember($member_info);
        if($insert_id) {
            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;
            /************查看三级分销是否开启注册赠送身份************/   
            $distribution_level = 0;     
            $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');//是否启用三级分销 1:开启；0：关闭
            $distribution_identity = Model('setting')->getRowSetting('distribution_identity');//注册送分销资格 1:开启；0：关闭
            if($distribution_identity['value'] == 1 && $distribution_isuse['value'] == 1){
                $first_level = Model("distribution_level")->order("level_weight asc")->find();//取权重最小的等级            
                if($first_level){
                    $distribution_level = $first_level['id'];//如果有等级，那就设置最小等级id，如果没有就为0
                }
            }
            //方便直销使用，重新插入一份直销数据到会员关系链表中
            $superior_chain = Model("member_chain")->where(array('member_id'=>$register_info['inviter_id']))->find();
            $member_chain_info    = array();
            $member_chain_info['member_id'] = $insert_id;
            $member_chain_info['superior_id']     = $register_info['inviter_id'];
            if($superior_chain['relation_chain']){
                $chain_str = $superior_chain['relation_chain'].','.$register_info['inviter_id'];
            }else{
                $chain_str = $register_info['inviter_id'];
            }
            $member_chain_info['relation_chain']   = $chain_str;//关系链
            $member_chain_info['virtual_superior_id']        = 0;
            $member_chain_info['position_type']        = 1;
            $member_chain_info['is_center']        = 0;
            $member_chain_info['distribution_level'] = $distribution_level;
            Model("member_chain")->insert($member_chain_info);
            /************查看三级分销是否开启注册赠送身份************/

            process::addprocess('reg');
            return $member_info;
        } else {
            return array('error' => '注册失败');
        }

    }

    /**
     * 注册商城会员
     *
     * @param   array $param 会员信息
     * @return  array 数组格式的返回结果
     */
    public function addMember($param) {
        if(empty($param)) {
            return false;
        }
        try {
            $this->beginTransaction();
            $member_info    = array();
            $member_info['member_id']           = $param['member_id'];
            $member_info['member_name']         = $param['member_name'];
            $member_info['member_passwd']       = md5(trim($param['member_passwd']));
            $member_info['member_email']        = $param['member_email'];
            $member_info['member_time']         = TIMESTAMP;
            $member_info['member_login_time']   = TIMESTAMP;
            $member_info['member_old_login_time'] = TIMESTAMP;
            $member_info['member_login_ip']     = getIp();
            $member_info['member_old_login_ip'] = $member_info['member_login_ip'];
			$member_info['member_reg_ip']     = getIp();

            $member_info['member_truename']     = $param['member_truename'];
            $member_info['member_qq']           = $param['member_qq'];
            $member_info['member_sex']          = $param['member_sex'];
            $member_info['member_avatar']       = $param['member_avatar'];
            $member_info['member_qqopenid']     = $param['member_qqopenid'];
            $member_info['member_qqinfo']       = $param['member_qqinfo'];
            $member_info['member_sinaopenid']   = $param['member_sinaopenid'];
            $member_info['member_sinainfo'] = $param['member_sinainfo'];
	    	//添加邀请人(推荐人)会员积分
	    	$member_info['inviter_id']	        = $param['inviter_id'];
			$member_info['invite_one']   = $param['invite_one'];
			$member_info['invite_two']   = $param['invite_two'];
			$member_info['invite_three']   = $param['invite_three'];
            if ($param['member_mobile_bind']) {
                $member_info['member_mobile'] = $param['member_mobile'];
                $member_info['member_mobile_bind'] = $param['member_mobile_bind'];
            }
            if ($param['weixin_unionid']) {
                $member_info['weixin_unionid'] = $param['weixin_unionid'];
                $member_info['weixin_info'] = $param['weixin_info'];
            }
            //$member_info['distribution_level'] = $param['distribution_level'];

            $insert_id  = $this->table('member')->insert($member_info);
            if (!$insert_id) {
                throw new Exception();
            }
            $insert = $this->addMemberCommon(array('member_id'=>$insert_id));
            if (!$insert) {
                throw new Exception();
            }

            // 添加默认相册
            $insert = array();
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $rs = $this->table('sns_albumclass')->insert($insert);
            //添加会员积分
            if (C('points_isuse')){
				$today_num = 0;
				if(C('points_ip_reg_isuse')){
					$condition = array();
					$condition['member_reg_ip'] = getIp();
					$condition['member_time']   = array('gt',mktime(0,0,0)); //strtotime(date('Y-m-d 00:00:00'));
					$today_num = $this->getMemberCount($condition);
				}
				if($today_num<2){
					Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$param['member_name']),false);
					//添加邀请人(推荐人)会员积分
					$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
					if(!empty($inviter_name) && $inviter_name!=''){
						Model('points')->savePointsLog('inviter',array('pl_memberid'=>$member_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
					}
				}
            }
            
            $this->commit();
            return $insert_id;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }

    public function loginh($login_info) {
        if (process::islock('login')) {
            return array('error' => '您的操作过于频繁，请稍后再试');
        }
        process::addprocess('login');
        $user_name = $login_info['user_name'];
        $password = $login_info['password'];
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array(
                "input" => $user_name,
                "require" => "true",
                "message" => "请填写用户名"
            ),
            array(
                "input" => $user_name,
                "validator" => "username",
                "message" => "请填写字母、数字、中文、_"
            ),
            array(
                "input" => $user_name,
                "max" => "20",
                "min" => "4",
                "validator" => "length",
                "message" => "用户名长度要在4~20个字符"
            ),
            array(
                "input" => $password,
                "require" => "true",
                "message" => "密码不能为空"
            )
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }
        $condition = array();
        $condition['member_name'] = $user_name;
        $condition['member_passwd'] = $password;
        $member_info = $this->getMemberInfo($condition);
        if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $user_name)) {//根据会员名没找到时查手机号
            $condition = array();
            $condition['member_mobile'] = $user_name;
            $condition['member_passwd'] = $password;
            $member_info = $this->getMemberInfo($condition);
        }
        if(empty($member_info) && (strpos($user_name, '@') > 0)) {//按邮箱和密码查询会员
            $condition = array();
            $condition['member_email'] = $user_name;
            $condition['member_passwd'] = $password;
            $member_info = $this->getMemberInfo($condition);
        }
        if (!empty($member_info)) {
            if(!$member_info['member_state']){
                return array('error' => '账号被停用');
            }
            process::clear('login');

            //添加会员积分
            $this->addPoint($member_info);
            //添加会员经验值
            $this->addExppoint($member_info);

            $update_info    = array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);

            return $member_info;
        } else {
            return array('error' => '用户名或密码错误，请重新登录');
        }
    }

    /**
     * 会员登录检查
     *
     */
    public function checkloginMember() {
        if($_SESSION['is_login'] == '1') {
            @header("Location: index.php");
            exit();
        }
    }

    /**
     * 检查会员是否允许举报商品
     *
     */
    public function isMemberAllowInform($member_id) {
        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->getMemberInfo($condition,'inform_allow');
        if(intval($member_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 取单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getMemberCommonInfo($condition = array(), $fields = '*') {
        return $this->table('member_common')->where($condition)->field($fields)->find();
    }

    /**
     * 插入扩展表信息
     * @param unknown $data
     * @return Ambigous <mixed, boolean, number, unknown, static>
     */
    public function addMemberCommon($data) {
        return $this->table('member_common')->insert($data);
    }

    /**
     * 编辑会员扩展表
     * @param unknown $data
     * @param unknown $condition
     * @return Ambigous <mixed, boolean, number, unknown, static>
     */
    public function editMemberCommon($data,$condition) {
        return $this->table('member_common')->where($condition)->update($data);
    }

    /**
     * 添加会员积分
     * @param unknown $member_info
     */
    public function addPoint($member_info) {
        if (!C('points_isuse') || empty($member_info)) return;

        //一天内只有第一次登录赠送积分
        if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;

        //加入队列
        $queue_content = array();
        $queue_content['member_id'] = $member_info['member_id'];
        $queue_content['member_name'] = $member_info['member_name'];
        QueueClient::push('addPoint',$queue_content);
    }

    /**
     * 添加会员经验值
     * @param unknown $member_info
     */
    public function addExppoint($member_info) {
        if (empty($member_info)) return;

        //一天内只有第一次登录赠送经验值
        if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;

        //加入队列
        $queue_content = array();
        $queue_content['member_id'] = $member_info['member_id'];
        $queue_content['member_name'] = $member_info['member_name'];
        QueueClient::push('addExppoint',$queue_content);
    }

    /**
     * 取得会员安全级别
     * @param unknown $member_info
     */
    public function getMemberSecurityLevel($member_info = array()) {
        $tmp_level = 0;
        if ($member_info['member_email_bind'] == '1') {
            $tmp_level += 1;
        }
        if ($member_info['member_mobile_bind'] == '1') {
            $tmp_level += 1;
        }
        if ($member_info['member_paypwd'] != '') {
            $tmp_level += 1;
        }
        return $tmp_level;
    }

    /**
     * 获得会员等级
     * @param bool $show_progress 是否计算其当前等级进度
     * @param int $exppoints  会员经验值
     * @param array $cur_level 会员当前等级
     */
    public function getMemberGradeArr($show_progress = false,$exppoints = 0,$cur_level = ''){
        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
        //处理会员等级进度
        if ($member_grade && $show_progress){
            $is_max = false;
            if ($cur_level === ''){
                $cur_gradearr = $this->getOneMemberGrade($exppoints, false, $member_grade);
                $cur_level = $cur_gradearr['level'];
            }
            foreach ($member_grade as $k=>$v){
                if ($cur_level == $v['level']){
                    $v['is_cur'] = true;
                }
                $member_grade[$k] = $v;
            }
        }
        return $member_grade;
    }

    /**
     * 获得某一会员等级
     * @param int $exppoints
     * @param bool $show_progress 是否计算其当前等级进度
     * @param array $member_grade 会员等级
     */
    public function getOneMemberGrade($exppoints,$show_progress = false,$member_grade = array()){
        if (!$member_grade){
            $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
        }
        if (empty($member_grade)){//如果会员等级设置为空
            $grade_arr['level'] = -1;
            $grade_arr['level_name'] = '暂无等级';
            return $grade_arr;
        }

        $exppoints = intval($exppoints);

        $grade_arr = array();
        if ($member_grade){
            foreach ($member_grade as $k=>$v){
                if($exppoints >= $v['exppoints']){
                    $grade_arr = $v;
                }
            }
        }
        //计算提升进度
        if ($show_progress == true){
            if (intval($grade_arr['level']) >= (count($member_grade) - 1)){//如果已达到顶级会员
                $grade_arr['downgrade'] = $grade_arr['level'] - 1;//下一级会员等级
                $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
                $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
                $grade_arr['upgrade'] = $grade_arr['level'];//上一级会员等级
                $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
                $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
                $grade_arr['less_exppoints'] = 0;
                $grade_arr['exppoints_rate'] = 100;
            } else {
                $grade_arr['downgrade'] = $grade_arr['level'];//下一级会员等级
                $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
                $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
                $grade_arr['upgrade'] = $member_grade[$grade_arr['level']+1]['level'];//上一级会员等级
                $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
                $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
                $grade_arr['less_exppoints'] = $grade_arr['upgrade_exppoints'] - $exppoints;
                $grade_arr['exppoints_rate'] = round(($exppoints - $member_grade[$grade_arr['level']]['exppoints'])/($grade_arr['upgrade_exppoints'] - $member_grade[$grade_arr['level']]['exppoints'])*100,2);
            }
        }
        return $grade_arr;
    }
}
