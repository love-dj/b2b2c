<?php
/**
 * 平台优惠券模型
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class couponModel extends Model {
        
    const GETTYPE_DEFAULT = 'points';//默认领取方式
    private $gettype_arr;
    private $templatestate_arr;
    private $coupon_state_arr;
    
    public function __construct(){
        parent::__construct();
        //优惠券领取方式
        $this->gettype_arr = array('points'=>array('sign'=>1,'name'=>'积分兑换'),'pwd'=>array('sign'=>2,'name'=>'卡密兑换'),'free'=>array('sign'=>3,'name'=>'免费领取'));
        //优惠券模板状态
        $this->templatestate_arr = array('usable'=>array('sign'=>1,'name'=>'有效'),'disabled'=>array('sign'=>2,'name'=>'失效'));
        //优惠券状态
        $this->coupon_state_arr = array('unused'=>array('sign'=>1,'name'=>'未使用'),'used'=>array('sign'=>2,'name'=>'已使用'),'expire'=>array('sign'=>3,'name'=>'已过期'));
    }

    /**
     * 取得当前有效优惠券数量
     * @param int $member_id
     */
    public function getCurrentAvailableCouponCount($member_id) {
        $info = rcache($member_id, 'm_coupon', 'coupon_count');
        if (empty($info['coupon_count']) && $info['coupon_count'] !== 0) {
            $condition['coupon_owner_id'] = $member_id;
            $condition['coupon_end_date'] = array('gt',TIMESTAMP);
            $condition['coupon_state'] = 1;
            $coupon_count = $this->table('coupon')->where($condition)->count();
            $coupon_count = intval($coupon_count);
            wcache($member_id, array('coupon_count' => $coupon_count), 'm_coupon');
        } else {
            $coupon_count = intval($info['coupon_count']);
        }
        return $coupon_count;
    }

    /**
     * 获取优惠券模板状态数组
     */
    public function getTemplateState(){
        return $this->templatestate_arr;
    }
    /**
     * 获取优惠券状态数组
     */
    public function getCouponState(){
        return $this->coupon_state_arr;
    }
    /**
     * 返回优惠券领取方式数组
     * @return array
     */
    public function getGettypeArr() {
        return $this->gettype_arr;
    }
    /**
     * 新增优惠券模板
     */
    public function addRptTemplate($param){
        if(!$param){
        	return false;
        }
        return $this->table('coupon_template')->insert($param);
    }
    /**
     * 查询优惠券模板列表
     */
    public function getRptTemplateList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('coupon_template')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('coupon_template')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('coupon_template')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
    
        if (!empty($list) && is_array($list)){
            foreach ($list as $k=>$v){
                if (!empty($v['coupon_t_customimg'])){
                    $v['coupon_t_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$v['coupon_t_customimg'];
                }else{
                    $v['coupon_t_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
                }
                //领取方式
                if($v['coupon_t_gettype']){
                    foreach($this->gettype_arr as $gtype_k=>$gtype_v){
                        if($v['coupon_t_gettype'] == $gtype_v['sign']){
                            $v['coupon_t_gettype_key'] = $gtype_k;
                            $v['coupon_t_gettype_text'] = $gtype_v['name'];
                        }
                    }
                }
                //状态
                if($v['coupon_t_state']){
                    foreach($this->templatestate_arr as $tstate_k=>$tstate_v){
                        if($v['coupon_t_state'] == $tstate_v['sign']){
                            $v['coupon_t_state_text'] = $tstate_v['name'];
                        }
                    }
                }
                //会员等级
                $v['coupon_t_mgradelimittext'] = $member_grade[$v['coupon_t_mgradelimit']]['level_name'];
    			$v['coupon_t_progress'] = floatval($v['coupon_t_giveout']/$v['coupon_t_total']*100);
                $list[$k] = $v;
            }
        }
        return $list;
    }
    /**
     * 获得优惠券模板详情
     */
    public function getRptTemplateInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('coupon_template')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
        	return array();
        }
        if($info['coupon_t_gettype']){
            foreach($this->gettype_arr as $k=>$v){
                if($info['coupon_t_gettype'] == $v['sign']){
                    $info['coupon_t_gettype_key'] = $k;
                    $info['coupon_t_gettype_text'] = $v['name'];
                }
            }
        }
        if($info['coupon_t_state']){
            foreach($this->templatestate_arr as $k=>$v){
                if($info['coupon_t_state'] == $v['sign']){
                    $info['coupon_t_state_text'] = $v['name'];
                }
            }
        }
        if (!empty($info['coupon_t_customimg'])){
            $info['coupon_t_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$info['coupon_t_customimg'];
        }else{
            $info['coupon_t_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
        }
        //会员等级
        $member_grade = Model('member')->getMemberGradeArr();
        $info['coupon_t_mgradelimittext'] = $member_grade[$info['coupon_t_mgradelimit']]['level_name'];
        return $info;
    }
    
    /**
     * 更新优惠券模板信息
     * @param array $data
     * @param array $condition
     */
    public function editRptTemplate($where,$data) {
        return $this->table('coupon_template')->where($where)->update($data);
    }
    
    /**
     * 删除优惠券模板信息
     * @param array $data
     * @param array $condition
     */
    public function dropRptTemplate($where) {
        $info = $this->getRptTemplateInfo($where);
        if (!$info){
        	return false;
        }
        $result = $this->table('coupon_template')->where($where)->delete($where);
        if ($result){
            //删除旧图片
            if ($info['coupon_t_customimg'] && is_file(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $info['coupon_t_customimg'])) {
                @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $info['coupon_t_customimg']);
                @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . str_ireplace('.', '_small.', $info['coupon_t_customimg']));
            }
        }
        return $result;
    }
    
    /*
     * 获取优惠券编码
     * */
    public function get_rpt_code($member_id = 0){
        static $num = 1;
        $sign_arr = array();
        $sign_arr[] = sprintf('%02d',mt_rand(10,99));
        $sign_arr[] = sprintf('%03d', (float) microtime() * 1000);
        $sign_arr[] = sprintf('%010d',time() - 946656000);
        if($member_id){
            $sign_arr[] = sprintf('%03d', (int) $member_id % 1000);
        } else {
            //自增变量
            $tmpnum = 0;
            if ($num > 99){
                $tmpnum = substr($num, -1, 2);
            } else {
                $tmpnum = $num;
            }
            $sign_arr[] = sprintf('%02d',$tmpnum);
            $sign_arr[] = mt_rand(1,9);
        }
        $code = implode('',$sign_arr);
        $num += 1;
        return $code;
    }

    /**
     * 返回当前可用的优惠券列表,每种类型(模板)的优惠券里取出一个优惠券(同一个模板所有码面额和到期时间都一样)
     * @param array $condition 条件
     * @param array $goods_total 商品总金额
     * @return string
     */
    public function getCurrentAvailableRpt($condition = array(), $goods_total = 0, $order = '') {
        $condition['coupon_end_date'] = array('egt',TIMESTAMP);
        $condition['coupon_start_date'] = array('elt',TIMESTAMP);
        $condition['coupon_state'] = 1;
        $rpt_list = $this->table('coupon')->field('coupon_id,coupon_end_date,coupon_price,coupon_limit,coupon_t_id,coupon_code,coupon_owner_id')->where($condition)->order($order)->key('coupon_t_id')->select();
        foreach ($rpt_list as $key => $rpt) {
            if ($goods_total > 0 && $goods_total < $rpt['coupon_limit']) {
                unset($rpt_list[$key]);
            } else {
                $rpt_list[$key]['desc'] = sprintf('%s元优惠券 有效期至 %s',$rpt['coupon_price'],date('Y-m-d',$rpt['coupon_end_date']));
                if ($rpt['coupon_limit'] > 0) {
                    $rpt_list[$key]['desc'] .= sprintf(' 消费满%s可用',$rpt['coupon_limit']);
                }

            }
        }
        return $rpt_list;
    }

    /**
     * 生成优惠券卡密
     */
    public function create_rpt_pwd($t_id) {
        if($t_id <= 0){
            return false;
        }
        static $num = 1;
        $sign_arr = array();
        //时间戳
        $time_tmp = uniqid('', true);
        $time_tmp = explode('.',$time_tmp);
        $sign_arr[] = substr($time_tmp[0], -1, 4).$time_tmp[1];
        //自增变量
        $tmpnum = 0;
        if ($num > 999){
            $tmpnum = substr($num, -1, 3);
        } else {
            $tmpnum = $num;
        }
        $sign_arr[] = sprintf('%03d',$tmpnum);
        //优惠券模板ID
        if($t_id > 9999){
            $t_id = substr($num, -1, 4);
        }
        $sign_arr[] = sprintf('%04d',$t_id);
        //随机数
        $sign_arr[] = sprintf('%04d',rand(1,9999));
        $pwd = implode('',$sign_arr);
        $num += 1;
        return array(md5($pwd), encrypt($pwd));
    }
    /**
     * 获取优惠券卡密
     */
    public function get_rpt_pwd($pwd) {
        if (!$pwd){
            return '';
        }
        $pwd = decrypt($pwd);
        $pattern = "/^([0-9]{20})$/i";
        if (preg_match($pattern, $pwd)){
            return $pwd;
        } else {
            return '';
        }
    }
    /**
     * 批量增加优惠券
     */
    public function addCouponBatch($insert_arr){
        return $this->table('coupon')->insertAll($insert_arr);
    }
    /**
     * 增加优惠券
     */
    public function addCoupon($insert_arr){
        return $this->table('coupon')->insert($insert_arr);
    }
    /**
     * 获得优惠券列表
     */
    public function getCouponList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = ''){
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('coupon')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('coupon')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('coupon')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!empty($list) && is_array($list)){
            foreach ($list as $k=>$v){
                if (!empty($v['coupon_customimg'])){
                    $v['coupon_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$v['coupon_customimg'];
                }else{
                    $v['coupon_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
                }
                foreach ($this->coupon_state_arr as $state_k=>$state_v){
                    if ($state_v['sign'] == $v['coupon_state']){
                    	$v['coupon_state_text'] = $state_v['name'];
                    	$v['coupon_state_key'] = $state_k;
                    }
                }
                $v['coupon_start_date_text'] = @date('Y-m-d',$v['coupon_start_date']);
                $v['coupon_end_date_text'] = @date('Y-m-d',$v['coupon_end_date']);
                $list[$k] = $v;
            }
        }
        return $list;
    }
    
    /**
     * 获得优惠券详情
     */
    public function getCouponInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('coupon')->where($where)->field($field)->order($order)->group($group)->find();
        if($info['coupon_state']){
            foreach ($this->coupon_state_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $info['coupon_state']){
                    $info['coupon_state_text'] = $state_v['name'];
                    $info['coupon_state_key'] = $state_k;
                }
            }
            if (!empty($info['coupon_customimg'])){
                $info['coupon_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$info['coupon_customimg'];
            }else{
                $info['coupon_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
            }
        }
        return $info;
    }
    /**
     * 更新过期优惠券状态
     */
    public function updateCouponExpire($member_id){
        $where = array();
        $where['coupon_owner_id'] = $member_id;
        $where['coupon_state'] = $this->coupon_state_arr['unused']['sign'];
        $where['coupon_end_date'] = array('lt', TIMESTAMP);
        $this->table('coupon')->where($where)->update(array('coupon_state'=>$this->coupon_state_arr['expire']['sign']));
        //清空缓存
        dcache($member_id, 'm_coupon');
    }
    
    /**
     * 获得推荐的优惠券列表
     * @param int $num 查询条数
     */
    public function getRecommendRpt($num){
        //查询推荐的热门优惠券列表
        $where = array();
        $where['coupon_t_state'] = $this->templatestate_arr['usable']['sign'];
        //领取方式为积分兑换
        $where['coupon_t_gettype'] = $this->gettype_arr['points']['sign'];
        //$where['coupon_t_start_date'] = array('elt',time());
        $where['coupon_t_end_date'] = array('egt',time());
        $recommend_rpt = $this->getRptTemplateList($where, $field = '*', $num, 0, 'coupon_t_recommend desc,coupon_t_id desc');
        return $recommend_rpt;
    }
    /**
     * 获得优惠券总数量
     */
    public function getCouponCount($where, $group = ''){
        return $this->table('coupon')->where($where)->group($group)->count();
    }
    
    /**
     * 更新优惠券信息
     * @param array $data
     * @param array $condition
     */
    public function editCoupon($where, $data, $member_id = 0) {
        $result = $this->table('coupon')->where($where)->update($data);
        if ($result && $member_id > 0){
            wcache($member_id, array('coupon_count' => null), 'm_coupon');
        }
        return $result;
    }
    
    /**
     * 查询可兑换优惠券模板详细信息
     */
    public function getCanChangeTemplateInfo($tid,$member_id){
        if ($tid <= 0 || $member_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //查询可用优惠券模板
        $where = array();
        $where['coupon_t_id']          = $tid;
        $where['coupon_t_state']       = $this->templatestate_arr['usable']['sign'];
        //$where['coupon_t_start_date']  = array('elt',time());
        $where['coupon_t_end_date']    = array('egt',time());
        $template_info = $this->getRptTemplateInfo($where);
        if (empty($template_info) || $template_info['coupon_t_total']<=$template_info['coupon_t_giveout']){//优惠券不存在或者已兑换完
            return array('state'=>false,'msg'=>'优惠券已兑换完');
        }
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($member_id);
        if (empty($member_info)){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //验证会员积分是否足够
        if ($template_info['coupon_t_gettype'] == $this->gettype_arr['points']['sign'] && $template_info['coupon_t_points'] > 0){
            if (intval($member_info['member_points']) < intval($template_info['coupon_t_points'])){
                return array('state'=>false,'msg'=>'您的积分不足，暂时不能兑换该优惠券');
            }
        }
        //验证会员级别
        $member_currgrade = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
        $member_info['member_currgrade'] = $member_currgrade?$member_currgrade['level']:0;
        if ($member_info['member_currgrade'] < intval($template_info['coupon_t_mgradelimit'])){
            return array('state'=>false,'msg'=>'您的会员级别不够，暂时不能兑换该优惠券');
        }
        //查询优惠券列表
        $where = array();
        $where['coupon_t_id']      = $tid;
        $where['coupon_owner_id']  = $member_id;
        $coupon_count = $this->getCouponCount($where);
        //同一张优惠券最多能兑换的次数
        if (intval($template_info['coupon_t_eachlimit']) > 0 && $coupon_count >= intval($template_info['coupon_t_eachlimit'])){
            $message = sprintf('该优惠券您已兑换%s次，不可再兑换了',$template_info['coupon_t_eachlimit']);
            return array('state'=>false,'msg'=>$message);
        }
        return array('state'=>true,'info'=>$template_info);
    }
    
    /**
     * 积分兑换优惠券
     */
    public function exchangeCoupon($template_info, $member_id, $member_name = ''){
        if (intval($member_id) <= 0 || empty($template_info)){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //查询会员信息
        if (!$member_name){
            $member_info = Model('member')->getMemberInfoByID($member_id);
            if (empty($template_info)){
                return array('state'=>false,'msg'=>'参数错误');
            }
            $member_name = $member_info['member_name'];
        }
        //添加优惠券信息
        $insert_arr = array();
        $insert_arr['coupon_code'] = $this->get_rpt_code($member_id);
        $insert_arr['coupon_t_id'] = $template_info['coupon_t_id'];
        $insert_arr['coupon_title'] = $template_info['coupon_t_title'];
        $insert_arr['coupon_desc'] = $template_info['coupon_t_desc'];
        $insert_arr['coupon_start_date'] = $template_info['coupon_t_start_date'];
        $insert_arr['coupon_end_date'] = $template_info['coupon_t_end_date'];
        $insert_arr['coupon_price'] = $template_info['coupon_t_price'];
        $insert_arr['coupon_limit'] = $template_info['coupon_t_limit'];
        $insert_arr['coupon_state'] = $this->coupon_state_arr['unused']['sign'];
        $insert_arr['coupon_active_date'] = time();
        $insert_arr['coupon_owner_id'] = $member_id;
        $insert_arr['coupon_owner_name'] = $member_name;
        $insert_arr['coupon_customimg'] = $template_info['coupon_t_customimg'];
        $result = $this->addCoupon($insert_arr);
        if (!$result){
            return array('state'=>false,'msg'=>'兑换失败');
        }
        //扣除会员积分
        if ($template_info['coupon_t_points'] > 0 && $template_info['coupon_t_gettype'] == $this->gettype_arr['points']['sign']){
            $points_arr['pl_memberid'] = $member_id;
            $points_arr['pl_membername'] = $member_name;
            $points_arr['pl_points'] = -$template_info['coupon_t_points'];
            $points_arr['pl_desc'] = '优惠券'.$insert_arr['coupon_code'].'消耗积分';
            $result = Model('points')->savePointsLog('app',$points_arr,true);
            if (!$result){
                return array('state'=>false,'msg'=>'兑换失败');
            }
        }
        if ($result){
            //优惠券模板的兑换数增加
            $result = $this->editRptTemplate(array('coupon_t_id'=>$template_info['coupon_t_id']), array('coupon_t_giveout'=>array('exp','coupon_t_giveout+1')));
            if (!$result){
                return array('state'=>false,'msg'=>'兑换失败');
            }
            wcache($member_id, array('coupon_count' => array('exp','coupon_count+1')), 'm_coupon');
            return array('state'=>true,'msg'=>'兑换成功');
        } else {
            return array('state'=>false,'msg'=>'兑换失败');
        }
    }
}