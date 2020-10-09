<?php
/**
 * 分销前台父类
 *
 *
 * *

 

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
     * 系统公告
     */
    protected function system_notice() {
        $model_message  = Model('article');
        $condition = array();
        $condition['ac_id'] = 1;
        $condition['article_position_in'] = ARTICLE_POSIT_ALL.','.ARTICLE_POSIT_BUYER;
        $condition['limit'] = 5;
        $article_list  = $model_message->getArticleList($condition);
        Tpl::output('system_notice',$article_list);
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
                $member_info['voucher_count'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
                $member_info['coupon_count'] = Model('coupon')->getCurrentAvailableCouponCount($_SESSION['member_id']);
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
        $code_array = array('member','store','payment','sold','service','about');
        $notice_limit   = 5;
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

class BasefenxiaoControl extends Control{
    /**
     * 构造函数
     */
    public function __construct(){
        if(!C('site_status')) halt(C('closed_reason'));
        /**
         * 判断分销市场是否关闭
         */
        if (C('fenxiao_isuse') != '1'){
            header('location: '.BASE_SITE_URL);die;
        }
        //输出头部的公用信息
        $this->showLayout();
        //输出会员信息
        $this->getMemberAndGradeInfo(false);

        Language::read('common,home_layout,core_lang_index');

        Tpl::setDir('home');

        Tpl::setLayout('home_layout');

        /**
         * 获取导航
         */
        Tpl::output('nav_list', rkcache('nav',true));

        // 自动登录
        $this->auto_login();
    }
}

class MemberfenxiaoControl extends Control{
    protected $member_info = array();   // 会员信息
    /**
     * 构造函数
     */
    function __construct(){
        if(!C('site_status')) halt(C('closed_reason'));
        if(!C('fenxiao_isuse')) halt('未开启分销功能');
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
        //可提现金额
        $available_trad = $this->member_info['trad_amount'];

        //冻结金额
        $freeze_trad = floatval($this->member_info['freeze_trad']);
        if($this->member_info['fx_state'] == 2){
            if($this->member_info['trad_amount'] >= C('fenxiao_bill_limit')){
                $freeze_trad += C('fenxiao_bill_limit');
                $available_trad -= C('fenxiao_bill_limit');
            }else{
                $freeze_trad += $this->member_info['trad_amount'];
                $available_trad = 0;
            }
        }

        $this->member_info['available_fx_trad'] = $available_trad;
        $this->member_info['freeze_fx_trad'] = $freeze_trad;

        Tpl::output('member_info', $this->member_info);

        if(!in_array($this->member_info['fx_state'],array(2,4,5)) || (in_array($this->member_info['fx_state'], array('4','5')) && $this->member_info['fx_show'] != 1)){
            showMessage('您尚未成为分销员，请认证成为分销员','index.php?w=fx_join','','error');
        }
        // 左侧导航
        $menu_list = $this->_getMenuList();

        Tpl::output('menu_list', $menu_list);

        // 系统消息
        $this->system_notice();

        // 页面高亮
        Tpl::output('w', $_GET['w']);
        /**
         * 文章
         */
        $this->article();
    }

    /**
     * 左侧导航
     * 菜单数组中child的下标要和其链接的act对应。否则面包屑不能正常显示
     * @return array
     */
    private function _getMenuList() {
        $menu_list = array(
            'fenxiao' => array('name' => '分销管理', 'child' => array(
                'fx_goods'=> array('name' => '我的分销', 'url'=>urlFenxiao('fx_goods', 'goods_list')),
                'fx_order'=> array('name' => '我的订单', 'url'=>urlFenxiao('fx_order', 'order_list')),
                'fx_bill'=> array('name' => '结算管理', 'url'=>urlFenxiao('fx_bill', 'bill_list')),
            )),
            'property' => array('name' => '结算中心', 'child' => array(
                'access_infomation'=> array('name' => '账户设置', 'url'=>urlFenxiao('access_infomation', 'member')),
                'commission'        => array('name' => '账户余额', 'url'=>urlFenxiao('commission', 'commission_info')),
                'cash'         => array('name' => '提现记录', 'url'=>urlFenxiao('cash','cash_list')),
            ))
        );
        return $menu_list;
    }
}
/**
 * 店铺 control父类
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
        if(!C('fenxiao_isuse')) halt('未开启分销功能');
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
                array('name' => '出售中的商品', 'w'=>'store_goods_online', 't'=>'index'),
                array('name' => '仓库中的商品', 'w'=>'store_goods_offline', 't'=>'index'),
                array('name' => '商品库的商品', 'w'=>'store_lib_goods', 't'=>'index'),
                array('name' => '预约/到货通知', 'w' => 'store_appoint', 't' => 'index'),
                array('name' => '关联版式', 'w'=>'store_plate', 't'=>'index'),
                array('name' => '商品规格', 'w' => 'store_spec', 't' => 'index'),
                array('name' => '图片空间', 'w'=>'store_album', 't'=>'album_cate'),
                array('name' => '视频空间', 'w'=>'store_video', 't'=>'video_cate'),//shopwt
            )),
            'order' => array('name' => '订单物流', 'child' => array(
                array('name' => '实物交易订单', 'w'=>'store_order', 't'=>'index'),
                array('name' => '虚拟兑码订单', 'w'=>'store_vr_order', 't'=>'index'),
                array('name' => '发货', 'w'=>'store_deliver', 't'=>'index'),
                array('name' => '发货设置', 'w'=>'store_deliver_set', 't'=>'daddress_list'),
                array('name' => '运单模板', 'w'=>'store_waybill', 't'=>'waybill_manage'),
                array('name' => '评价管理', 'w'=>'store_evaluate', 't'=>'list'),
                array('name' => '物流工具', 'w'=>'store_transport', 't'=>'index'),
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
                array('name' => '拼团', 'w'=>'store_sale_pingou', 't'=>'index'),
                array('name' => '代金券管理', 'w'=>'store_voucher', 't'=>'templatelist'),
                array('name' => '活动管理', 'w'=>'store_activity', 't'=>'store_activity'),
            )),
            'store' => array('name' => '店铺', 'child' => array(
                array('name' => '店铺设置', 'w'=>'store_setting', 't'=>'store_setting'),
                array('name' => '店铺装修', 'w'=>'store_decoration', 't'=>'decoration_setting'),
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
            'fenxiao' => array('name' => '分销管理', 'child' => array(
                array('name' => '分销商品', 'w'=>'store_fx_goods', 't'=>'index'),
                array('name' => '佣金设置', 'w'=>'store_fx_set', 't'=>'index'),
                array('name' => '分销订单', 'w'=>'store_fx_order', 't'=>'index'),
                array('name' => '商品统计', 'w'=>'store_fx_sta', 't'=>'index'),
            ))
        );
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
}