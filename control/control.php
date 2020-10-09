<?php
/**
 * 前台control父类,店铺control父类,会员control父类
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class Control{
    /**
     * 检查短消息数量
     *
     */
    protected function checkMessage() {
        if($_SESSION['member_id'] == '') return ;
        //判断cookie是否存在
        $cookie_name = 'msgnewnum'.$_SESSION['member_id'];
        if (cookie($cookie_name) != null){
            $countnum = intval(cookie($cookie_name));
        }else {
            $message_model = Model('message');
            $countnum = $message_model->countNewMessage($_SESSION['member_id']);
            setWtCookie($cookie_name,"$countnum",2*3600);//保存2小时
        }
        Tpl::output('message_num',$countnum);
    }

    /**
     *  输出头部的公用信息
     *
     */
    protected function showLayout() {
        $this->checkMessage();//短消息检查
        $this->article();//文章输出

        $this->showCartCount();
        
        //热门搜索
        Tpl::output('hot_search',@explode(',',C('hot_search')));
        if (C('rec_search') != '') {
            $rec_search_list = @unserialize(C('rec_search'));
        }
        Tpl::output('rec_search_list',is_array($rec_search_list) ? $rec_search_list : array());

        //历史搜索
        if (cookie('his_sh') != '') {
            $his_search_list = explode('~', cookie('his_sh'));
        }
        Tpl::output('his_search_list',is_array($his_search_list) ? $his_search_list : array());

        $model_class = Model('goods_class');
        $goods_class = $model_class->get_all_category();
        Tpl::output('show_goods_class',$goods_class);//商品分类

        //获取导航
        Tpl::output('nav_list', rkcache('nav',true));
        //查询保障服务项目
        Tpl::output('contract_list',Model('contract')->getContractItemByCache());
    }

    /**
     * 显示购物车数量
     */
    protected function showCartCount() {
        if (cookie('cart_goods_num') != null){
            $cart_num = intval(cookie('cart_goods_num'));
        }else {
            //已登录状态，存入数据库,未登录时，优先存入缓存，否则存入COOKIE
            if($_SESSION['member_id']) {
                $save_type = 'db';
            } else {
                $save_type = 'cookie';
            }
            $cart_num = Model('cart')->getCartNum($save_type,array('buyer_id'=>$_SESSION['member_id']));//查询购物车商品种类
        }
        Tpl::output('cart_goods_num',$cart_num);
    }

    /**
     * 输出会员等级
     * @param bool $is_return 是否返回会员信息，返回为true，输出会员信息为false
     */
    protected function getMemberAndGradeInfo($is_return = false){
        $member_info = array();
        //会员详情及会员级别处理
        if($_SESSION['member_id']) {
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            if ($member_info){
                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
                $member_info = array_merge($member_info,$member_gradeinfo);
                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);
            }
        }
        if ($is_return == true){//返回会员信息
            return $member_info;
        } else {//输出会员信息
            Tpl::output('member_info',$member_info);
        }
    }

    /**
     * 验证会员是否登录
     *
     */
    protected function checkLogin(){
        if ($_SESSION['is_login'] !== '1'){
            if (trim($_GET['t']) == 'favoritegoods' || trim($_GET['t']) == 'favoritestore'){
                $lang = Language::getLangContent('UTF-8');
                echo json_encode(array('done'=>false,'msg'=>$lang['no_login']));
                die;
            }
            $ref_url = request_uri();
            if ($_GET['inajax']){
                showDialog('','','js',"login_dialog();",200);
            }else {
                @header("location: " . urlLogin('login', 'index', array('ref_url' => $ref_url)));
            }
            exit;
        }
    }

    //文章输出
    protected function article() {

        if (C('cache_open')) {
            if ($article = rkcache("index/article")) {
                Tpl::output('show_article', $article['show_article']);
                Tpl::output('article_list', $article['article_list']);
                return;
            }
        } else {
            if (file_exists(BASE_DATA_PATH.'/cache/index/article.php')){
                include(BASE_DATA_PATH.'/cache/index/article.php');
                Tpl::output('show_article', $show_article);
                Tpl::output('article_list', $article_list);
                return;
            }
        }

        $model_article_class    = Model('article_class');
        $model_article  = Model('article');
        $show_article = array();//商城公告
        $article_list   = array();//下方文章
        $notice_class   = array('notice');
        $code_array = array('member','payment','sold','service');
        $notice_limit   = 6;
        $faq_limit  = 5;

        $class_condition    = array();
        $class_condition['home_index'] = 'home_index';
        $class_condition['order'] = 'ac_sort asc';
        $article_class  = $model_article_class->getClassList($class_condition);
        $class_list = array();
        if(!empty($article_class) && is_array($article_class)){
            foreach ($article_class as $key => $val){
                $ac_code = $val['ac_code'];
                $ac_id = $val['ac_id'];
                $val['list']    = array();//文章
                $class_list[$ac_id] = $val;
            }
        }

        $condition  = array();
        $condition['article_show'] = '1';
        $condition['field'] = 'article.article_id,article.ac_id,article.article_url,article_class.ac_code,article.article_position,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';
        $condition['order'] = 'article_sort asc,article_time desc';
        $condition['limit'] = '300';
        $article_array  = $model_article->getJoinList($condition);
        if(!empty($article_array) && is_array($article_array)){
            foreach ($article_array as $key => $val){
                if ($val['ac_code'] == 'notice' && !in_array($val['article_position'],array(ARTICLE_POSIT_SHOP,ARTICLE_POSIT_ALL))) continue;
                $ac_id = $val['ac_id'];
                $ac_parent_id = $val['ac_parent_id'];
                if($ac_parent_id == 0) {//顶级分类
                    $class_list[$ac_id]['list'][] = $val;
                } else {
                    $class_list[$ac_parent_id]['list'][] = $val;
                }
            }
        }
        if(!empty($class_list) && is_array($class_list)){
            foreach ($class_list as $key => $val){
                $ac_code = $val['ac_code'];
                if(in_array($ac_code,$notice_class)) {
                    $list = $val['list'];
                    array_splice($list, $notice_limit);
                    $val['list'] = $list;
                    $show_article[$ac_code] = $val;
                }
                if (in_array($ac_code,$code_array)){
                    $list = $val['list'];
                    $val['class']['ac_name']    = $val['ac_name'];
                    array_splice($list, $faq_limit);
                    $val['list'] = $list;
                    $article_list[] = $val;
                }
            }
        }
        if (C('cache_open')) {
            wkcache('index/article', array(
                'show_article' => $show_article,
                'article_list' => $article_list,
            ));
        } else {
            $string = "<?php\n\$show_article=".var_export($show_article,true).";\n";
            $string .= "\$article_list=".var_export($article_list,true).";\n?>";
            file_put_contents(BASE_DATA_PATH.'/cache/index/article.php',($string));
        }

        Tpl::output('show_article',$show_article);
        Tpl::output('article_list',$article_list);
    }
    
    /**
     * 自动登录
     */ 
    protected function auto_login() {
        $data = cookie('auto_login');
        if (empty($data)) {
            return false;
        }
        $model_member = Model('member');
        if ($_SESSION['is_login']) {
            $model_member->auto_login();
        }
        $member_id = intval(decrypt($data, MD5_KEY));
        if ($member_id <= 0) {
            return false;
        }
        $member_info = $model_member->getMemberInfoByID($member_id);
        $model_member->createSession($member_info);
    }
}

/********************************** 前台control父类 **********************************************/

class BaseHomeControl extends Control {

    public function __construct(){
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        Language::read('common,home_layout');

        Tpl::setDir('home');

        Tpl::setLayout('home_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));
        // 自动登录
        $this->auto_login();
    }

}

//闲置市场前台control父类
class BaseFleaControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();
		if(intval(C('flea_isuse')) !== 1) {
			header('location: '.BASE_SITE_URL);die;
		}
		
		Language::read('home_flea_index');
	}
}

//闲置市场个人中心control父类
class BaseFleaMemberControl extends BaseMemberControl{
	public function __construct(){
		parent::__construct();
		if(intval(C('flea_isuse')) !== 1) {
			header('location: '.BASE_SITE_URL);die;
		}
	}
}

/********************************** 购买流程父类 **********************************************/

class BaseBuyControl extends Control {
    protected $member_info = array();   // 会员信息

    protected function __construct(){
        Language::read('common,home_layout');
        //输出会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        Tpl::output('member_info', $this->member_info);

        Tpl::setDir('buy');
        Tpl::setLayout('buy_layout');
        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));
        //获取导航
        Tpl::output('nav_list', rkcache('nav',true));

        Tpl::output('contract_list',Model('contract')->getContractItemByCache());
    }
}

/********************************** 会员control父类 **********************************************/

class BaseMemberControl extends Control {
    protected $member_info = array();   // 会员信息
    public function __construct(){

        if(!C('site_status')) halt(C('closed_reason'));

        Language::read('common,member_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        //会员验证
        $this->checkLogin();
        //输出头部的公用信息
        $this->showLayout();
        Tpl::setDir('member');
        Tpl::setLayout('member_layout');

        //获得会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        $this->member_info['voucher_count'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
        $this->member_info['coupon_count'] = Model('coupon')->getCurrentAvailableCouponCount($_SESSION['member_id']);
        Tpl::output('member_info', $this->member_info);

        // 常用操作及导航
        $menu_list = $this->_getNavLink();

        //系统公告
        $this->system_notice();
        
        // 交易数量提示
        $this->order_tip();
        
        // 页面高亮
        Tpl::output('w', $_GET['w']);
    }
    
    /**
     * 交易数量提示
     */
    private function order_tip() {
        $model_order = Model('order');
        //交易提醒 - 显示数量
        $order_tip['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'NewCount');
        $order_tip['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'SendCount');
        $order_tip['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'EvalCount');
        $order_tip['order_notakes_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'TakesCount');
        Tpl::output('order_tip', $order_tip);
    }

    /**
     * 系统公告
     */
    private function system_notice() {
        $model_message  = Model('article');
        $condition = array();
        $condition['ac_id'] = 1;
        $condition['article_show'] = '1';
        $condition['article_position_in'] = ARTICLE_POSIT_ALL.','.ARTICLE_POSIT_BUYER;
        $condition['limit'] = 5;
        $article_list  = $model_message->getArticleList($condition);
        Tpl::output('system_notice',$article_list);
    }

    /**
     * 常用操作
     *
     * @param string $w
     * 如果菜单中的切换卡不在一个菜单中添加$w参数，值为当前菜单的下标
     *
     */
    protected function _getNavLink ($w = '') {
        // 左侧导航
        $menu_list = $this->_getMenuList();
        Tpl::output('menu_list', $menu_list);
    }

    /**
     * 左侧导航
     * 菜单数组中child的下标要和其链接的w对应。否则面包屑不能正常显示
     * @return array
     */
    private function _getMenuList() {
        $menu_list = array(
            'trade' => array('name' => '交易中心', 'child' => array(
                'member_order'      => array('name' => '实物交易订单', 'url' => urlShop('member_order', 'index')),
                'member_order_vr'   => array('name' => '虚拟兑码订单', 'url' => urlShop('member_order_vr', 'index')),
                'member_evaluate'   => array('name' => '交易评价/晒单', 'url' => urlShop('member_evaluate', 'list')),
                'member_appoint'    => array('name' => '预约/到货通知', 'url' => urlShop('member_appoint', 'list'))
            )),
            'follow' => array('name' => '关注中心', 'child' => array(
                'member_favorite_goods' => array('name' => '商品收藏', 'url' => urlShop('member_favorite_goods', 'index')),
                'member_favorite_store' => array('name' => '店铺收藏', 'url' => urlShop('member_favorite_store', 'index')),
                'member_goodsbrowse'   => array('name' => '我的足迹', 'url' => urlShop('member_goodsbrowse', 'list')),
		'member_flea'     => array('name' => '我的闲置', 'url'=>urlShop('member_flea', 'index'))
            )),
            'client' => array('name' => '客户服务', 'child' => array(
                'member_refund'     => array('name' => '退款及退货', 'url' => urlShop('member_refund', 'index')),
                'member_complain'   => array('name' => '交易投诉', 'url' => urlShop('member_complain', 'index')),
                'member_consult'    => array('name' => '商品咨询', 'url' => urlShop('member_consult', 'my_consult')),
                'member_inform'     => array('name' => '违规举报', 'url' => urlShop('member_inform', 'index')),
                'member_mallconsult'=> array('name' => '平台客服', 'url' => urlShop('member_mallconsult', 'index'))
            )),
            'info' => array('name' => '会员资料', 'child' => array(
                'member_information'=> array('name' => '账户信息', 'url'=>urlMember('member_information', 'member')),
                'member_address'    => array('name' => '收货地址', 'url'=>urlMember('member_address', 'address'))
            )),
            'property' => array('name' => '财产中心', 'child' => array(
                'predeposit'        => array('name' => '账户余额', 'url'=>urlMember('predeposit', 'pd_log_list')),
                'member_voucher'    => array('name' => '我的代金券', 'url'=>urlMember('member_voucher', 'index')),
                'member_coupon'  => array('name' => '我的优惠券', 'url'=>urlMember('member_coupon', 'index')),
				'member_invite'  => array('name' => '我的推广', 'url'=>urlMember('member_invite', 'index'))
            )),
        );
        return $menu_list;
    }
}

/********************************** SNS control父类 **********************************************/

class BaseSNSControl extends Control {
    protected $relation = 0;//浏览者与主人的关系：0 表示游客 1 表示一般普通会员 2表示朋友 3表示自己4表示已关注主人
    protected $master_id = 0; //主人编号
    const MAX_RECORDNUM = 20;//允许插入新记录的最大条数
    protected $master_info;

    public function __construct(){

        Tpl::setDir('sns');

        Tpl::setLayout('sns_layout');

        Language::read('common,sns_layout');

        //验证会员及与主人关系
        $this->check_relation();

        //查询会员信息
        $this->getMemberAndGradeInfo(false);

        $this->master_info = $this->get_member_info();
        Tpl::output('master_info',$this->master_info);

        //添加访问记录
        $this->add_visit();

        //我的关注
        $this->my_attention();

        //获取设置
        $this->get_setting();

        //允许插入新记录的最大条数
        Tpl::output('max_recordnum',self::MAX_RECORDNUM);

        $this->showCartCount();

        Tpl::output('nav_list', rkcache('nav',true));
    }

    /**
     * 格式化时间
     * @param string $time时间戳
     */
    protected function formatDate($time){
        $handle_date = @date('Y-m-d',$time);//需要格式化的时间
        $reference_date = @date('Y-m-d',time());//参照时间
        $handle_date_time = strtotime($handle_date);//需要格式化的时间戳
        $reference_date_time = strtotime($reference_date);//参照时间戳
        if ($reference_date_time == $handle_date_time){
            $timetext = @date('H:i',$time);//今天访问的显示具体的时间点
        }elseif (($reference_date_time-$handle_date_time)==60*60*24){
            $timetext = Language::get('sns_yesterday');
        }elseif ($reference_date_time-$handle_date_time==60*60*48){
            $timetext = Language::get('sns_beforeyesterday');
        }else {
            $month_text = Language::get('wt_month');
            $day_text = Language::get('wt_day');
            $timetext = @date("m{$month_text}d{$day_text}",$time);
        }
        return $timetext;
    }

    /**
     * 会员信息
     *
     * @return array
     */
    public function get_member_info() {
        if($this->master_id <= 0){
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $model = Model();
        $member_info = Model('member')->getMemberInfoByID($this->master_id);
        if(empty($member_info)){
            showMessage(L('wrong_argument'), 'index.php?w=member_snshome', '', 'error');
        }
        //粉丝数
        $fan_count = $model->table('sns_friend')->where(array('friend_tomid'=>$this->master_id))->count();
        $member_info['fan_count'] = $fan_count;
        //关注数
        $attention_count = $model->table('sns_friend')->where(array('friend_frommid'=>$this->master_id))->count();
        $member_info['attention_count'] = $attention_count;
        //兴趣标签
        $mtag_list = $model->table('sns_membertag,sns_mtagmember')->field('mtag_name')->on('sns_membertag.mtag_id = sns_mtagmember.mtag_id')->join('inner')->where(array('sns_mtagmember.member_id'=>$this->master_id))->select();
        $tagname_array = array();
        if(!empty($mtag_list)){
            foreach ($mtag_list as $val){
                $tagname_array[] = $val['mtag_name'];
            }
        }
        $member_info['tagname'] = $tagname_array;
        return $member_info;
    }

    /**
     * 访客信息
     */
    protected function get_visitor(){
        $model = Model();
        //查询谁来看过我
        $visitme_list = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();
        if (!empty($visitme_list)){
            foreach ($visitme_list as $k=>$v){
                $v['adddate_text'] = $this->formatDate($v['v_addtime']);
                $v['addtime_text'] = @date('H:i',$v['v_addtime']);
                $visitme_list[$k] = $v;
            }
        }
        Tpl::output('visitme_list',$visitme_list);
        if($this->relation == 3){   // 主人自己才有我访问过的人
            //查询我访问过的人
            $visitother_list = $model->table('sns_visitor')->where(array('v_mid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();
            if (!empty($visitother_list)){
                foreach ($visitother_list as $k=>$v){
                    $v['adddate_text'] = $this->formatDate($v['v_addtime']);
                    $visitother_list[$k] = $v;
                }
            }
            Tpl::output('visitother_list',$visitother_list);
        }
    }

    /**
     * 验证会员及主人关系
     */
    private function check_relation(){
        $model = Model();
        //验证主人会员编号
        $this->master_id = intval($_GET['mid']);
        if ($this->master_id <= 0){
            if ($_SESSION['is_login'] == 1){
                $this->master_id = $_SESSION['member_id'];
            }else {
                @header("location: " . urlLogin('login', 'index', array('ref_url' => urlShop('member_snshome'))));
            }
        }
        Tpl::output('master_id', $this->master_id);

        $model = Model();

        //判断浏览者与主人的关系
        if ($_SESSION['is_login'] == '1'){
            if ($this->master_id == $_SESSION['member_id']){//主人自己
                $this->relation = 3;
            }else{
                $this->relation = 1;
                //查询好友表
                $friend_arr = $model->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id'],'friend_tomid'=>$this->master_id))->find();
                if (!empty($friend_arr) && $friend_arr['friend_followstate'] == 2){
                    $this->relation = 2;
                }elseif($friend_arr['friend_followstate'] == 1){
                    $this->relation = 4;
                }
            }
        }
        Tpl::output('relation',$this->relation);
    }

    /**
     * 增加访问记录
     */
    private function add_visit(){
        $model = Model();
        //记录访客
        if ($_SESSION['is_login'] == '1' && $this->relation != 3){
            //访客为会员且不是空间主人则添加访客记录
            $visitor_info = $model->table('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
            if (!empty($visitor_info)){
                //查询访客记录是否存在
                $existevisitor_info = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id, 'v_mid'=>$visitor_info['member_id']))->find();
                if (!empty($existevisitor_info)){//访问记录存在则更新访问时间
                    $update_arr = array();
                    $update_arr['v_addtime'] = time();
                    $model->table('sns_visitor')->update(array('v_id'=>$existevisitor_info['v_id'], 'v_addtime'=>time()));
                }else {//添加新访问记录
                    $insert_arr = array();
                    $insert_arr['v_mid']            = $visitor_info['member_id'];
                    $insert_arr['v_mname']          = $visitor_info['member_name'];
                    $insert_arr['v_mavatar']        = $visitor_info['member_avatar'];
                    $insert_arr['v_ownermid']       = $this->master_info['member_id'];
                    $insert_arr['v_ownermname']     = $this->master_info['member_name'];
                    $insert_arr['v_ownermavatar']   = $this->master_info['member_avatar'];
                    $insert_arr['v_addtime']        = time();
                    $model->table('sns_visitor')->insert($insert_arr);
                }
            }
        }

        //增加主人访问次数
        $cookie_str = cookie('visitor');
        $cookie_arr = array();
        $is_increase = false;
        if (empty($cookie_str)){
            //cookie不存在则直接增加访问次数
            $is_increase = true;
        }else{
            //cookie存在但是为空则直接增加访问次数
            $cookie_arr = explode('_',$cookie_str);
            if(!in_array($this->master_id,$cookie_arr)){
                $is_increase = true;
            }
        }
        if ($is_increase == true){
            //增加访问次数
            $model->table('member')->update(array('member_id'=>$this->master_id, 'member_snsvisitnum'=>array('exp', 'member_snsvisitnum+1')));
            //设置cookie，24小时之内不再累加
            $cookie_arr[] = $this->master_id;
            setWtCookie('visitor',implode('_',$cookie_arr),24*3600);//保存24小时
        }
    }
    //我的关注
    private function my_attention(){
        if(intval($_SESSION['member_id']) >0){
            $my_attention = Model()->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id']))->order('friend_addtime desc')->limit(4)->select();
            Tpl::output('my_attention', $my_attention);
        }
    }

    /**
     * 获取设置信息
     */
    private function get_setting(){
        $m_setting = Model()->table('sns_setting')->where(array('member_id'=>$this->master_id))->find();
        Tpl::output('skin_style', (!empty($m_setting['setting_skin'])?$m_setting['setting_skin']:'skin_01'));
    }
    /**
     * 留言板
     */
    protected function sns_messageboard(){
        $model = Model();
        $where = array();
        $where['from_member_id']    = array('neq',0);
        $where['to_member_id']      = $this->master_id;
        $where['message_state']     = array('neq',2);
        $where['message_parent_id'] = 0;
        $where['message_type']      = 2;
        $message_list = $model->table('message')->where($where)->order('message_id desc')->limit(10)->select();
        if(!empty($message_list)){
            $pmsg_id = array();
            foreach ($message_list as $key=>$val){
                $pmsg_id[]  = $val['message_id'];
                $message_list[$key]['message_time'] = $this->formatDate($val['message_time']);
            }
            $where = array();
            $where['message_parent_id'] = array('in',$pmsg_id);
            $rmessage_array = $model->table('message')->where($where)->select();
            $rmessage_list  = array();
            if(!empty($rmessage_array)){
                foreach ($rmessage_array as $key=>$val){
                    $val['message_time'] = $this->formatDate($val['message_time']);
                    $rmessage_list[$val['message_parent_id']][] = $val;
                }
                foreach ($rmessage_list as $key=>$val){
                    $rmessage_list[$key]     = array_slice($val, -3, 3);
                }
            }
            Tpl::output('rmessage_list', $rmessage_list);
        }
        Tpl::output('message_list', $message_list);
    }
}
/********************************** 店铺 control父类 **********************************************/



class BaseStoreControl extends Control {

    protected $store_info;
    protected $store_decoration_only = false;

    public function __construct(){

        Language::read('common,store_layout,store_show_store_index');

        if(!C('site_status')) halt(C('closed_reason'));

        //输出头部的公用信息
        $this->showLayout();
        Tpl::setDir('store');
        Tpl::setLayout('store_layout');

        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        $store_id = intval($_GET['store_id']);
        if($store_id <= 0) {
            showMessage(L('wt_store_close'), '', '', 'error');
        }

        $model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('wt_store_close'), '', '', 'error');
        } else {
            $this->store_info = $store_info;
        }
        if($store_info['store_decoration_switch'] > 0 & $store_info['store_decoration_only'] == 1) {
            $this->store_decoration_only = true;
        }

        //店铺装修
        $this->outputStoreDecoration($store_info['store_decoration_switch'], $store_id);

        $this->outputStoreInfo($this->store_info);
        $this->getStoreNavigation($store_id);
        $this->outputSeoInfo($this->store_info);
    }

    /**
     * 输出店铺装修
     */
    protected function outputStoreDecoration($decoration_id, $store_id) {
        if($decoration_id > 0 ) {
            $model_store_decoration = Model('store_decoration');

            $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $store_id);
            if($decoration_info) {
                $decoration_background_style = $model_store_decoration->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
                Tpl::output('decoration_background_style', $decoration_background_style);
                Tpl::output('decoration_nav', $decoration_info['decoration_nav']);
                Tpl::output('decoration_banner', $decoration_info['decoration_banner']);

                $html_file = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'decoration'.DS.'html'.DS.md5($store_id).'.html';
                if(is_file($html_file)) {
                    Tpl::output('decoration_file', $html_file);
                }

            }

            Tpl::output('store_theme', 'default');
        } else {
            Tpl::output('store_theme', $this->store_info['store_theme']);
        }
    }

    /**
     * 检查店铺开启状态
     *
     * @param int $store_id 店铺编号
     * @param string $msg 警告信息
     */
    protected function outputStoreInfo($store_info, $goods_content = null) {
        if (!$this->store_decoration_only) {

            // 自营店设置“显示商城相关数据”
            if ($goods_content && $store_info['is_own_shop'] && $store_info['left_bar_type'] == 2) {
                Tpl::output('left_bar_type_mall_related', true);

                // 推荐分类
                $mr_rel_gc = Model('goods_class')->getGoodsClassListBySiblingId($goods_content['gc_id']);
                Tpl::output('mr_rel_gc', $mr_rel_gc);

                // 分类 含所有父级分类
                $gcIds = array();
                $gcIds[(int) $goods_content['gc_id_1']] = null;
                $gcIds[(int) $goods_content['gc_id_2']] = null;
                $gcIds[(int) $goods_content['gc_id_3']] = null;
                unset($gcIds[0]);
                $gcIds = array_keys($gcIds);

                // 推荐品牌
                $mr_rel_brand = null;
                if ($gcIds) {
                    $mr_rel_brand = Model('brand')->getBrandPassedList(array(
                        'class_id' => array('in', $gcIds),
                    ));
                }
                Tpl::output('mr_rel_brand', $mr_rel_brand);

                // 同分类下销量排行
                $mr_hot_sales = null;
                if ($gcIds) {
                    $mr_hot_sales = Model('goods')->getGoodsOnlineList(array(
                        'gc_id' => array('in', $gcIds),
                        'goods_id' => array('neq', $goods_content['goods_id']),
                    ), '*', 0, 'goods_salenum desc', 6);
                }
                Tpl::output('mr_hot_sales', $mr_hot_sales);
                $gcArray = Model('goods_class')->getGoodsClassInfoById($goods_content['gc_id_1']);
                Tpl::output('mr_hot_sales_gc_name', $gcArray['gc_name']);

                // 推荐商品
                $mr_rec_products = null;
                if ($gcIds) {
                    $goodsIds = Model('p_booth')->getBoothGoodsIdRandList($gcIds, $goods_content['goods_id'], 6);
                    if ($goodsIds) {
                        $mr_rec_products = Model('goods')->getGoodsOnlineList(array(
                            'goods_id' => array('in', $goodsIds),
                        ), '*', 0, '', 6);
                    }
                }
                Tpl::output('mr_rec_products', $mr_rec_products);
            } else {
                $model_store = Model('store');
                $model_seller = Model('seller');

                //热销排行
                $hot_sales = $model_store->getHotSalesList($store_info['store_id'], 5);
                Tpl::output('hot_sales', $hot_sales);

                //收藏排行
                $hot_collect = $model_store->getHotCollectList($store_info['store_id'], 5);
                Tpl::output('hot_collect', $hot_collect);
            }
        }
        
        //店铺分类
        $goodsclass_model = Model('store_goods_class');
        $goods_class_list = $goodsclass_model->getShowTreeList($store_info['store_id']);
        Tpl::output('goods_class_list', $goods_class_list);

        Tpl::output('store_info', $store_info);
        Tpl::output('page_title', $store_info['store_name']);
    }

    protected function getStoreNavigation($store_id) {
        $model_store_navigation = Model('store_navigation');
        $store_navigation_list = $model_store_navigation->getStoreNavigationList(array('sn_store_id' => $store_id));
        Tpl::output('store_navigation_list', $store_navigation_list);
    }

    protected function outputSeoInfo($store_info) {
        $seo_param = array();
        $seo_param['shopname'] = $store_info['store_name'];
        $seo_param['key']  = $store_info['store_keywords'];
        $seo_param['description'] = $store_info['store_description'];
        Model('seo')->type('shop')->param($seo_param)->show();
    }

}

class BaseGoodsControl extends BaseStoreControl {

    public function __construct(){

        Language::read('common,store_layout');

        if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('store');
        Tpl::setLayout('home_layout');

        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);
    }

    protected function getStoreInfo($store_id, $goods_content = null) {
        $model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('wt_store_close'), '', '', 'error');
        }
        if ($_COOKIE['dregion']) {
            $store_info['deliver_region'] = $_COOKIE['dregion'];
        }
		if ($_COOKIE['wt0']) {
			$wt0=explode(',',$_COOKIE['wt0']);
			$store_info['wt_areaid'] = intval($wt0[0]);
        }
        if (strpos($store_info['deliver_region'],'|')) {
            $store_info['deliver_region'] = explode('|', $store_info['deliver_region']);
            $store_info['deliver_region_ids'] = explode(' ', $store_info['deliver_region'][0]);
            $store_info['deliver_region_names'] = explode(' ', $store_info['deliver_region'][1]);
        }
        $this->outputStoreInfo($store_info, $goods_content);
    }
}
class BaseChainControl extends BaseStoreControl {

    public function __construct(){

        Language::read('common,store_layout');

        if(!C('site_status')) halt(C('closed_reason'));

        Tpl::setDir('store');
        Tpl::setLayout('home_layout');

        //输出头部的公用信息
        $this->showLayout();
    }

}
/**
 * 店铺 control新父类
 *
 */
class BaseSellerControl extends Control {

    //店铺信息
    protected $store_info = array();
    //店铺等级
    protected $store_grade = array();

    public function __construct(){
        Language::read('common,store_layout,member_layout');
        if(!C('site_status')) halt(C('closed_reason'));
        Tpl::setDir('seller');
        Tpl::setLayout('seller_layout');

        Tpl::output('nav_list', rkcache('nav',true));
        if ($_GET['w'] !== 'seller_login') {

            if(empty($_SESSION['seller_id'])) {
                @header('location: index.php?w=seller_login&t=show_login');die;
            }

            // 验证店铺是否存在
            $model_store = Model('store');
            $this->store_info = $model_store->getStoreInfoByID($_SESSION['store_id']);
            if (empty($this->store_info)) {
                @header('location: index.php?w=seller_login&t=show_login');die;
            }

            // 店铺关闭标志
            if (intval($this->store_info['store_state']) === 0) {
                Tpl::output('store_closed', true);
                Tpl::output('store_close_info', $this->store_info['store_close_info']);
            }

            // 店铺等级
            if (checkPlatformStore()) {
                $this->store_grade = array(
                    'sg_id' => '0',
                    'sg_name' => '自营店铺专属等级',
                    'sg_goods_limit' => '0',
                    'sg_album_limit' => '0',
                    'sg_space_limit' => '999999999',
                    'sg_template_number' => '6',
                    // see also store_settingControl.themeWt()
                    // 'sg_template' => 'default|style1|style2|style3|style4|style5',
                    'sg_price' => '0.00',
                    'sg_description' => '',
                    'sg_function' => 'editor_multimedia',
                    'sg_sort' => '0',
                );
            } else {
                $store_grade = rkcache('store_grade', true);
                $this->store_grade = $store_grade[$this->store_info['grade_id']];
            }

            if ($_SESSION['seller_is_admin'] !== 1 && $_GET['w'] !== 'seller_center' && $_GET['w'] !== 'seller_logout') {
                if (!in_array($_GET['w'], $_SESSION['seller_limits'])) {
                    showMessage('没有权限', '', '', 'error');
                }
            }

            // 卖家菜单
            Tpl::output('menu', $_SESSION['seller_menu']);
            // 当前菜单
            $current_menu = $this->_getCurrentMenu($_SESSION['seller_function_list']);
            Tpl::output('current_menu', $current_menu);
            // 左侧菜单
            if($_GET['w'] == 'seller_center') {
                if(!empty($_SESSION['seller_quicklink'])) {
                    $left_menu = array();
                    foreach ($_SESSION['seller_quicklink'] as $value) {
                        $left_menu[] = $_SESSION['seller_function_list'][$value];
                    }
                }
            } else {
                $left_menu = $_SESSION['seller_menu'][$current_menu['model']]['child'];
            }
            Tpl::output('left_menu', $left_menu);
            Tpl::output('seller_quicklink', $_SESSION['seller_quicklink']);

            $this->checkStoreMsg();
        }
    }

    /**
     * 记录卖家日志
     *
     * @param $content 日志内容
     * @param $state 1成功 0失败
     */
    protected function recordSellerLog($content = '', $state = 1){
        $seller_info = array();
        $seller_info['log_content'] = $content;
        $seller_info['log_time'] = TIMESTAMP;
        $seller_info['log_seller_id'] = $_SESSION['seller_id'];
        $seller_info['log_seller_name'] = $_SESSION['seller_name'];
        $seller_info['log_store_id'] = $_SESSION['store_id'];
        $seller_info['log_seller_ip'] = getIp();
        $seller_info['log_url'] = $_GET['w'].'&'.$_GET['t'];
        $seller_info['log_state'] = $state;
        $model_seller_log = Model('seller_log');
        $model_seller_log->addSellerLog($seller_info);
    }

    /**
     * 记录店铺费用
     *
     * @param $cost_price 费用金额
     * @param $cost_remark 费用备注
     */
    protected function recordStoreCost($cost_price, $cost_remark) {
        // 平台店铺不记录店铺费用
        if (checkPlatformStore()) {
            return false;
        }
        $model_store_cost = Model('store_cost');
        $param = array();
        $param['cost_store_id'] = $_SESSION['store_id'];
        $param['cost_seller_id'] = $_SESSION['seller_id'];
        $param['cost_price'] = $cost_price;
        $param['cost_remark'] = $cost_remark;
        $param['cost_state'] = 0;
        $param['cost_time'] = TIMESTAMP;
        $model_store_cost->addStoreCost($param);

        // 发送店铺消息
        $param = array();
        $param['code'] = 'store_cost';
        $param['store_id'] = $_SESSION['store_id'];
        $param['param'] = array(
            'price' => $cost_price,
            'seller_name' => $_SESSION['seller_name'],
            'remark' => $cost_remark
        );

        QueueClient::push('sendStoreMsg', $param);
    }

    protected function getSellerMenuList($is_admin, $limits) {
        $seller_menu = array();
        if (intval($is_admin) !== 1) {
            $menu_list = $this->_getMenuList();
            foreach ($menu_list as $key => $value) {
                foreach ($value['child'] as $child_key => $child_value) {
                    if (!in_array($child_value['w'], $limits)) {
                        unset($menu_list[$key]['child'][$child_key]);
                    }
                }

                if(count($menu_list[$key]['child']) > 0) {
                    $seller_menu[$key] = $menu_list[$key];
                }
            }
        } else {
            $seller_menu = $this->_getMenuList();
        }
        $seller_function_list = $this->_getSellerFunctionList($seller_menu);
        return array('seller_menu' => $seller_menu, 'seller_function_list' => $seller_function_list);
    }

    private function _getCurrentMenu($seller_function_list) {
        $current_menu = $seller_function_list[$_GET['w']];
        if(empty($current_menu)) {
            $current_menu = array(
                'model' => 'index',
                'model_name' => '首页'
            );
        }
        return $current_menu;
    }

    private function _getMenuList() {
        $menu_list = array(
            'goods' => array('name' => '商品', 'child' => array(
                array('name' => '商品发布', 'w'=>'store_goods_add', 't'=>'index'),
				array('name' => '淘宝CSV导入', 'w'=>'taobao_import', 't'=>'index'),
                array('name' => '出售中的商品', 'w'=>'store_goods_online', 't'=>'index'),
                array('name' => '仓库中的商品', 'w'=>'store_goods_offline', 't'=>'index'),
                array('name' => '预约/到货通知', 'w' => 'store_appoint', 't' => 'index'),
                array('name' => '商品库的商品', 'w'=>'store_lib_goods', 't'=>'index'),
                array('name' => '关联版式', 'w'=>'store_plate', 't'=>'index'),
                array('name' => '商品规格', 'w' => 'store_spec', 't' => 'index'),
                array('name' => '图片空间', 'w'=>'store_album', 't'=>'album_cate'),
                array('name' => '视频空间', 'w'=>'store_video', 't'=>'video_cate'),
            )),
            'order' => array('name' => '订单物流', 'child' => array(
                array('name' => '实物交易订单', 'w'=>'store_order', 't'=>'index'),
                array('name' => '虚拟兑码订单', 'w'=>'store_order_vr', 't'=>'index'),
                array('name' => '发货', 'w'=>'store_deliver', 't'=>'index'),
                array('name' => '发货设置', 'w'=>'store_deliver_set', 't'=>'daddress_list'),
                array('name' => '运单模板', 'w'=>'store_waybill', 't'=>'waybill_manage'),
                array('name' => '评价管理', 'w'=>'store_evaluate', 't'=>'list'),
                array('name' => '物流工具', 'w'=>'store_transport', 't'=>'index'),
				array('name' => '来单提醒', 'w'=>'order_call', 't'=>'index'),
            )),
            'sale' => array('name' => '促销', 'child' => array(
                array('name' => '抢购管理', 'w'=>'store_robbuy', 't'=>'index'),
                array('name' => '加价购', 'w'=>'store_sale_cou', 't'=>'cou_list'),
                array('name' => '限时折扣', 'w'=>'store_sale_xianshi', 't'=>'xianshi_list'),
                array('name' => '满即送', 'w'=>'store_sale_mansong', 't'=>'mansong_list'),
                array('name' => '优惠套装', 'w'=>'store_sale_bundling', 't'=>'bundling_list'),
                array('name' => '推荐展位', 'w' => 'store_sale_booth', 't' => 'booth_goods_list'),
                array('name' => '预售商品', 'w' => 'store_sale_book', 't' => 'index'),
                array('name' => 'F码商品', 'w' => 'store_sale_fcode', 't' => 'index'),
                array('name' => '推荐组合', 'w' => 'store_sale_combo', 't' => 'index'),
                array('name' => '手机专享', 'w' => 'store_sale_sole', 't' => 'index'),
                array('name' => '拼团管理', 'w'=>'store_sale_pingou', 't'=>'index'),
                array('name' => '代金券管理', 'w'=>'store_voucher', 't'=>'templatelist'),
                array('name' => '活动管理', 'w'=>'store_activity', 't'=>'store_activity'),
            )),
            'store' => array('name' => '店铺', 'child' => array(
                array('name' => '店铺设置', 'w'=>'store_setting', 't'=>'store_setting'),
                array('name' => '店铺装修', 'w'=>'store_decoration', 't'=>'decoration_setting'),
                array('name' => '手机店铺装修', 'w'=>'mb_store_decoration', 't'=>'decoration_setting'),
                array('name' => '店铺导航', 'w'=>'store_navigation', 't'=>'navigation_list'),
                array('name' => '店铺动态', 'w'=>'store_sns', 't'=>'index'),
                array('name' => '店铺信息', 'w'=>'store_info', 't'=>'bind_class'),
                array('name' => '店铺分类', 'w'=>'store_goods_class', 't'=>'index'),
                array('name' => '品牌申请', 'w'=>'store_brand', 't'=>'brand_list'),
                array('name' => '供货商', 'w'=>'store_supplier', 't'=>'sup_list'),
                array('name' => '实体店铺', 'w'=>'store_map', 't'=>'index'),
                array('name' => '消费者保障服务', 'w'=>'store_contract', 't'=>'index'),
            )),
            'consult' => array('name' => '售后服务', 'child' => array(
                array('name' => '咨询管理', 'w'=>'store_consult', 't'=>'consult_list'),
                array('name' => '投诉管理', 'w'=>'store_complain', 't'=>'list'),
                array('name' => '退款记录', 'w'=>'store_refund', 't'=>'index'),
                array('name' => '退货记录', 'w'=>'store_return', 't'=>'index'),
            )),
            'statistics' => array('name' => '统计结算', 'child' => array(
                array('name' => '店铺概况', 'w'=>'statistics_general', 't'=>'general'),
                array('name' => '商品分析', 'w'=>'statistics_goods', 't'=>'goodslist'),
                array('name' => '运营报告', 'w'=>'statistics_sale', 't'=>'sale'),
                array('name' => '行业分析', 'w'=>'statistics_industry', 't'=>'hot'),
                array('name' => '流量统计', 'w'=>'statistics_flow', 't'=>'storeflow'),
                array('name' => '实物结算', 'w'=>'store_bill', 't'=>'index'),
                array('name' => '虚拟结算', 'w'=>'store_vr_bill', 't'=>'index'),
		array('name' => '账户余额', 'w'=>'seller_predeposit', 't'=>'index'),
                array('name' => '提现申请', 'w'=>'member_security', 't'=>'index'),
                array('name' => '提现记录', 'w'=>'seller_predeposit_tx', 't'=>'index'),
            )),
            'message' => array('name' => '客服消息', 'child' => array(
                array('name' => '客服设置', 'w'=>'store_callcenter', 't'=>'index'),
                array('name' => '系统消息', 'w'=>'store_msg', 't'=>'index'),
                array('name' => '聊天记录查询', 'w'=>'store_im', 't'=>'index'),
            )),
            'account' => array('name' => '账号', 'child' => array(
                array('name' => '账号列表', 'w'=>'store_account', 't'=>'account_list'),
                array('name' => '账号组', 'w'=>'store_account_group', 't'=>'group_list'),
                array('name' => '账号日志', 'w'=>'seller_log', 't'=>'log_list'),
                array('name' => '店铺消费', 'w'=>'store_cost', 't'=>'cost_list'),
                array('name' => '门店账号', 'w'=>'store_chain', 't'=>'index'),
            )),
			//临时注释
			/*'webchat' => array('name' => '微信', 'child' => array(
                array('name' => '微信接口管理', 'w'=>'seller_wechat', 't'=>'index'),
                array('name' => '关注自动回复', 'w'=>'seller_wechat_follow', 't'=>'follow_index'),
                array('name' => '关键词自动回复', 'w'=>'seller_wechat_keyword', 't'=>'keyword_index'),
                array('name' => '消息自动回复', 'w'=>'seller_wechat_message', 't'=>'message_index'),
                array('name' => '自定义菜单', 'w'=>'seller_wechat_menu', 't'=>'index'),
            ))*/
        );
        if(C('fenxiao_isuse') == 1) {
            $menu_list['fenxiao'] = array('name' => '分销管理', 'child' => array(
                array('name' => '分销商品', 'w'=>'store_fx_goods', 't'=>'index'),
                array('name' => '佣金设置', 'w'=>'store_fx_set', 't'=>'index'),
                array('name' => '分销订单', 'w'=>'store_fx_order', 't'=>'index'),
                array('name' => '商品统计', 'w'=>'store_fx_sta', 't'=>'index'),
            ));
        }
        return $menu_list;
    }

    private function _getSellerFunctionList($menu_list) {
        $format_menu = array();
        foreach ($menu_list as $key => $menu_value) {
            foreach ($menu_value['child'] as $submenu_value) {
                $format_menu[$submenu_value['w']] = array(
                    'model' => $key,
                    'model_name' => $menu_value['name'],
                    'name' => $submenu_value['name'],
                    'w' => $submenu_value['w'],
                    't' => $submenu_value['t'],
                );
            }
        }
        return $format_menu;
    }

    /**
     * 自动发布店铺动态
     *
     * @param array $data 相关数据
     * @param string $type 类型 'new','coupon','xianshi','mansong','bundling','robbuy'
     *            所需字段
     *            new       goods表'             goods_id,store_id,goods_name,goods_image,goods_price,goods_freight
     *            xianshi   p_xianshi_goods表'   goods_id,store_id,goods_name,goods_image,goods_price,goods_freight,xianshi_price
     *            mansong   p_mansong表'         mansong_name,start_time,end_time,store_id
     *            bundling  p_bundling表'        bl_id,bl_name,bl_img,bl_discount_price,bl_freight_choose,bl_freight,store_id
     *            robbuy  goods_group表'       group_id,group_name,goods_id,goods_price,robbuy_price,group_pic,rebate,start_time,end_time
     *            coupon在后台发布
     */
    public function storeAutoShare($data, $type) {
        $param = array(
                3 => 'new',
                4 => 'coupon',
                5 => 'xianshi',
                6 => 'mansong',
                7 => 'bundling',
                8 => 'robbuy'
            );
        $param_flip = array_flip($param);
        if (!in_array($type, $param) || empty($data)) {
            return false;
        }

        $auto_setting = Model('store_sns_setting')->getStoreSnsSettingInfo(array('sauto_storeid' => $_SESSION ['store_id']));
        $auto_sign = false; // 自动发布开启标志

        if ($auto_setting['sauto_' . $type] == 1) {
            $auto_sign = true;
            if (CHARSET == 'GBK') {
                foreach ((array)$data as $k => $v) {
                    $data[$k] = Language::getUTF8($v);
                }
            }
            $goodsdata = addslashes(json_encode($data));
            if ($auto_setting['sauto_' . $type . 'title'] != '') {
                $title = $auto_setting['sauto_' . $type . 'title'];
            } else {
                $auto_title = 'wt_store_auto_share_' . $type . rand(1, 5);
                $title = Language::get($auto_title);
            }
        }
        if ($auto_sign) {
            // 插入数据
            $stracelog_array = array();
            $stracelog_array['strace_storeid'] = $this->store_info['store_id'];
            $stracelog_array['strace_storename'] = $this->store_info['store_name'];
            $stracelog_array['strace_storelogo'] = empty($this->store_info['store_avatar']) ? '' : $this->store_info['store_avatar'];
            $stracelog_array['strace_title'] = $title;
            $stracelog_array['strace_content'] = '';
            $stracelog_array['strace_time'] = TIMESTAMP;
            $stracelog_array['strace_type'] = $param_flip[$type];
            $stracelog_array['strace_goodsdata'] = $goodsdata;
            Model('store_sns_tracelog')->saveStoreSnsTracelog($stracelog_array);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 商家消息数量
     */
    private function checkStoreMsg() {//判断cookie是否存在
        $cookie_name = 'storemsgnewnum'.$_SESSION['seller_id'];
        if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){
            $countnum = intval(cookie($cookie_name));
        }else {
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $where['sm_readids'] = array('exp', 'sm_readids NOT LIKE \'%,'.$_SESSION['seller_id'].',%\' OR sm_readids IS NULL');
            if ($_SESSION['seller_smt_limits'] !== false) {
                $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
            }
            $countnum = Model('store_msg')->getStoreMsgCount($where);
            setWtCookie($cookie_name,intval($countnum),2*3600);//保存2小时
        }
        Tpl::output('store_msg_num',$countnum);
    }

}

class BaseStoreSnsControl extends Control {
    const MAX_RECORDNUM = 20;   // 允许插入新记录的最大次数，sns页面该常量是一样的。
    public function __construct(){
        Language::read('common,store_layout');
        Tpl::output('max_recordnum', self::MAX_RECORDNUM);
        Tpl::setDir('store');
        Tpl::setLayout('store_layout');
        // 自定义导航条
        $this->getStoreNavigation();
        //输出头部的公用信息
        $this->showLayout();
        //查询会员信息
        $this->getMemberAndGradeInfo(false);

    }

    // 自定义导航条
    protected function getStoreNavigation() {
        $model_store_navigation = Model('store_navigation');
        $store_navigation_list = $model_store_navigation->getStoreNavigationList(array('sn_store_id' => intval($_GET['sid'])));
        Tpl::output('store_navigation_list', $store_navigation_list);
    }

    protected function getStoreInfo($store_id) {
        //得到店铺等级信息
        $store_info = Model('store')->getStoreInfoByID($store_id);
        if (empty($store_info)) {
            showMessage(Language::get('store_sns_store_not_exists'),'','html','error');
        }
        //处理地区信息
        $area_array = array();
        $area_array = explode("\t",$store_info["area_info"]);
        $map_city   = Language::get('store_sns_city');
        $city   = '';
        if(strpos($area_array[0], $map_city) !== false){
            $city   = $area_array[0];
        }else {
            $city   = $area_array[1];
        }
        $store_info['city'] = $city;

        Tpl::output('store_theme', $store_info['store_theme']);
        Tpl::output('store_info', $store_info);
        

        //店铺分类
        $goodsclass_model = Model('store_goods_class');
        $goods_class_list = $goodsclass_model->getShowTreeList($store_id);
        Tpl::output('goods_class_list', $goods_class_list);
    }
}

/**
 * 积分中心control父类
 */
class BasePointShopControl extends Control {
    protected $member_info;
    public function __construct(){
        Language::read('common,home_layout,home_pointprod');
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->member_info = $this->getMemberAndGradeInfo(true);
        Tpl::output('member_info',$this->member_info);


        Tpl::setDir('home');
        Tpl::setLayout('home_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){
            $_GET = Language::getGBK($_GET);
        }
        if(!C('site_status')) halt(C('closed_reason'));

        //判断系统是否开启积分和积分中心功能
        if (C('points_isuse') != 1 || C('pointscenter_isuse') != 1){
            showMessage(Language::get('points_unavailable'),urlShop('index','index'),'html','error');
        }
        Tpl::output('index_sign','points');
    }
    /**
     * 获得积分中心会员信息包括会员名、ID、会员头像、会员等级、经验值、等级进度、积分、已领代金券、已兑换礼品、礼品购物车
     */
    public function pointsMInfo($is_return = false){
        if($_SESSION['is_login'] == '1'){
            $model_member = Model('member');
            if (!$this->member_info){
                //查询会员信息
                $member_infotmp = $model_member->getMemberInfoByID($_SESSION['member_id']);
            } else {
                $member_infotmp = $this->member_info;
            }
            $member_infotmp['member_exppoints'] = intval($member_infotmp['member_exppoints']);

            //当前登录会员等级信息
            $membergrade_info = $model_member->getOneMemberGrade($member_infotmp['member_exppoints'],true);
            $member_info = array_merge($member_infotmp,$membergrade_info);
            Tpl::output('member_info',$member_info);

            //查询已兑换并可以使用的代金券数量
            $model_voucher = Model('voucher');
            $vouchercount = $model_voucher->getCurrentAvailableVoucherCount($_SESSION['member_id']);
            Tpl::output('vouchercount',$vouchercount);

            //购物车兑换商品数
            $pointcart_count = Model('pointcart')->countPointCart($_SESSION['member_id']);
            Tpl::output('pointcart_count',$pointcart_count);

            //查询已兑换商品数(未取消订单)
            $pointordercount = Model('pointorder')->getMemberPointsOrderGoodsCount($_SESSION['member_id']);
            Tpl::output('pointordercount',$pointordercount);
            if ($is_return){
                return array('member_info'=>$member_info,'vouchercount'=>$vouchercount,'pointcart_count'=>$pointcart_count,'pointordercount'=>$pointordercount);
            }
        }
    }
}
