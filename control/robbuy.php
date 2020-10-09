<?php
/**
 * 前台抢购
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class robbuyControl extends BaseHomeControl {

    public function __construct() {
        parent::__construct();

        //读取语言包
        Language::read('member_robbuy,home_cart_index');

        //检查抢购功能是否开启
        if (intval(C('robbuy_allow')) !== 1){
            showMessage(Language::get('robbuy_unavailable'),urlShop(),'','error');
        }

        //分类导航
        $nav_link = array(
            0=>array(
                'title'=>Language::get('homepage'),
                'link'=>BASE_SITE_URL,
            ),
            1=>array(
                'title'=>Language::get('wt_robbuy')
            )
        );
        Tpl::output('nav_link_list',$nav_link);

        Tpl::setLayout('home_robbuy_layout');

        Tpl::output('index_sign', 'robbuy');

        if ($_GET['t'] != 'robbuy_detail') {
            // 抢购价格区间
            $this->robbuy_price = rkcache('robbuy_price', true);
            Tpl::output('price_list', $this->robbuy_price);

            $model_robbuy = Model('robbuy');

            // 线上抢购分类
            $this->robbuy_classes = $model_robbuy->getRobbuyClasses();
            Tpl::output('robbuy_classes', $this->robbuy_classes);

            // 虚拟抢购分类
            $this->robbuy_vr_classes = $model_robbuy->getRobbuyVrClasses();
            Tpl::output('robbuy_vr_classes', $this->robbuy_vr_classes);

            // 虚拟抢购城市
            $this->robbuy_vr_cities = $model_robbuy->getRobbuyVrCities();
            Tpl::output('robbuy_vr_cities', $this->robbuy_vr_cities);

            Tpl::output('city_name', $this->robbuy_vr_cities['name'][cookie('city_id')]);
        }
    }

    protected $robbuy_vr_cities;

    /*
     * 选择城市
     */
    public function select_cityWt()
    {
        $city_id = intval($_GET['city_id']);

        if ($city_id != 0 && (!isset($this->robbuy_vr_cities['name'][$city_id])
            || !isset($this->robbuy_vr_cities['parent'][$city_id])
            || $this->robbuy_vr_cities['parent'][$city_id] != 0)) {
            showMessage('该城市不存在，请选择其他城市');
        }

        setWtCookie('city_id', $city_id);

        redirect(urlShop('robbuy', $_GET['back_op']));
    }

    /**
     * 抢购聚合页
     */
    public function indexWt()
    {
        $model_robbuy = Model('robbuy');

        // 线上抢购
        $robbuy = $model_robbuy->getRobbuyOnlineList(array(
            'recommended' => 1,
            'is_vr' => 0,
        ), 9);
        Tpl::output('robbuy', $robbuy);

        // 虚拟抢购
        $vr_robbuy = $model_robbuy->getRobbuyOnlineList(array(
            'recommended' => 1,
            'is_vr' => 1,
        ), 9);

        Tpl::output('vr_robbuy', $vr_robbuy);

        // 轮播图片
        $picArr = array();

        foreach (range(1, 4) as $i) {
            $a = C('live_pic' . $i);
            if ($a) {
                $picArr[] = array($a,C('live_color'. $i),C('live_link'. $i));
            }
        }

        Tpl::output('picArr', $picArr);

        Tpl::output('current', 'online');
        Tpl::showpage('robbuy.index');
    }

    /**
     * 进行中的虚拟抢购
     */
    public function vr_robbuy_listWt()
    {
        Tpl::output('current', 'online');
        Tpl::output('buy_button', L('robbuy_buy'));
        $this->_show_vr_robbuy_list('getRobbuyOnlineList');
    }

    /**
     * 即将开始的虚拟抢购
     */
    public function vr_robbuy_soonWt()
    {
        Tpl::output('current', 'soon');
        Tpl::output('buy_button', '未开始');
        $this->_show_vr_robbuy_list('getRobbuySoonList');
    }

    /**
     * 往期虚拟抢购
     */
    public function vr_robbuy_historyWt()
    {
        Tpl::output('current', 'history');
        Tpl::output('buy_button', '已结束');
        $this->_show_vr_robbuy_list('getRobbuyHistoryList');
    }

    /**
     * 获取抢购列表
     */
    private function _show_vr_robbuy_list($function_name)
    {
        $model_robbuy = Model('robbuy');

        $condition = array(
            'is_vr' => 1,
        );

        $order = '';

        // 分类筛选条件
        if (($vr_class_id = (int) $_GET['vr_class']) > 0) {
            $condition['vr_class_id'] = $vr_class_id;

            if (($vr_s_class_id = (int) $_GET['vr_s_class']) > 0)
                $condition['vr_s_class_id'] = $vr_s_class_id;
        }

        // 区域筛选条件
        if (($vr_city_id = (int) cookie('city_id')) > 0) {
            $condition['vr_city_id'] = $vr_city_id;
            Tpl::output('vr_city_id', $vr_city_id);

            if (($vr_area_id = intval($_GET['vr_area'])) > 0) {
                $condition['vr_area_id'] = $vr_area_id;
                Tpl::output('vr_area_id', $vr_area_id);

                if (($vr_mall_id = (int) $_GET['vr_mall']) > 0) {
                    $condition['vr_mall_id'] = $vr_mall_id;
                    Tpl::output('vr_mall_id', $vr_mall_id);
                }
            }
        }

        // 价格区间筛选条件
        if (($price_id = intval($_GET['robbuy_price'])) > 0
            && isset($this->robbuy_price[$price_id])) {
            $p = $this->robbuy_price[$price_id];
            $condition['robbuy_price'] = array('between', array($p['range_start'], $p['range_end']));
        }

        // 排序
        $robbuy_order_key = trim($_GET['robbuy_order_key']);
        $robbuy_order = $_GET['robbuy_order'] == '2' ? 'desc' : 'asc';
        if (!empty($robbuy_order_key)) {
            switch ($robbuy_order_key) {
                case '1':
                    $order = 'robbuy_price ' . $robbuy_order;
                    break;
                case '2':
                    $order = 'robbuy_rebate ' . $robbuy_order;
                    break;
                case '3':
                    $order = 'buyer_count ' . $robbuy_order;
                    break;
            }
        }

        $robbuy_list = $model_robbuy->$function_name($condition, 20, $order);
        Tpl::output('robbuy_list', $robbuy_list);
        Tpl::output('show_page', $model_robbuy->showpage(5));

        Tpl::output('html_title', Language::get('text_robbuy_list'));

        Model('seo')->type('group')->show();

        shopload('search');

        Tpl::output('robbuyMenuIsVr', 1);
        Tpl::showpage('robbuy_vr_list');
    }

    /**
     * 进行中的抢购抢购
     **/
    public function robbuy_listWt() {
        Tpl::output('current', 'online');
        Tpl::output('buy_button', L('robbuy_buy'));
        $this->_robbuy_list('getRobbuyOnlineList');
    }

    /**
     * 即将开始的抢购
     **/
    public function robbuy_soonWt() {
        Tpl::output('current', 'soon');
        Tpl::output('buy_button', '未开始');
        $this->_robbuy_list('getRobbuySoonList');
    }

    /**
     * 往期抢购
     **/
    public function robbuy_historyWt() {
        Tpl::output('current', 'history');
        Tpl::output('buy_button', '已结束');
        $this->_robbuy_list('getRobbuyHistoryList');
    }

    /**
     * 获取抢购列表
     **/
    private function _robbuy_list($function_name) {
        $model_robbuy = Model('robbuy');

        $condition = array(
            'is_vr' => 0,
        );
        $order = '';

        // 分类筛选条件
        if (($class_id = (int) $_GET['class']) > 0) {
            $condition['class_id'] = $class_id;

            if (($s_class_id = (int) $_GET['s_class']) > 0)
                $condition['s_class_id'] = $s_class_id;
        }

        // 价格区间筛选条件
        if (($price_id = intval($_GET['robbuy_price'])) > 0
            && isset($this->robbuy_price[$price_id])) {
            $p = $this->robbuy_price[$price_id];
            $condition['robbuy_price'] = array('between', array($p['range_start'], $p['range_end']));
        }

        // 排序
        $robbuy_order_key = trim($_GET['robbuy_order_key']);
        $robbuy_order = $_GET['robbuy_order'] == '2'?'desc':'asc';
        if(!empty($robbuy_order_key)) {
            switch ($robbuy_order_key) {
                case '1':
                    $order = 'robbuy_price '.$robbuy_order;
                    break;
                case '2':
                    $order = 'robbuy_rebate '.$robbuy_order;
                    break;
                case '3':
                    $order = 'buyer_count '.$robbuy_order;
                    break;
            }
        }

        $robbuy_list = $model_robbuy->$function_name($condition, 20, $order);
        Tpl::output('robbuy_list', $robbuy_list);
        Tpl::output('show_page', $model_robbuy->showpage(5));

        Tpl::output('html_title', Language::get('text_robbuy_list'));

        Model('seo')->type('group')->show();

        shopload('search');

        Tpl::output('robbuyMenuIsVr', 0);
        Tpl::showpage('robbuy_list');
    }

    /**
     * 抢购详细信息
     **/
    public function robbuy_detailWt() {
        $group_id = intval($_GET['group_id']);

        $model_robbuy = Model('robbuy');
        $model_store = Model('store');

        //获取抢购详细信息
        $robbuy_info = $model_robbuy->getRobbuyInfoByID($group_id);
        if(empty($robbuy_info)) {
            showMessage(Language::get('param_error'),urlShop('robbuy', 'index'),'','error');
        }
        Tpl::output('robbuy_info',$robbuy_info);

        Tpl::output('robbuyMenuIsVr', (bool) $robbuy_info['is_vr']);

        if ($robbuy_info['is_vr']) {
            $goods_content = Model('goods')->getGoodsInfoByID($robbuy_info['goods_id']);
            $buy_limit = max(0, (int) $goods_content['virtual_limit']);
            $upper_limit = max(0, (int) $robbuy_info['upper_limit']);
            if ($buy_limit < 1 || ($buy_limit > 0 && $upper_limit > 0 && $buy_limit > $upper_limit)) {
                $buy_limit = $upper_limit;
            }

            Tpl::output('goods_content', $goods_content);
            Tpl::output('buy_limit', $buy_limit);
        } else {
            Tpl::output('buy_limit', $robbuy_info['upper_limit']);
        }

        // 输出店铺信息
        $store_info = $model_store->getStoreInfoByID($robbuy_info['store_id']);
        Tpl::output('store_info', $store_info);

        // 浏览数加1
        $update_array = array();
        $update_array['views'] = array('exp', 'views+1');
        $model_robbuy->editRobbuy($update_array, array('robbuy_id'=>$group_id));


        //获取店铺推荐商品
        $commended_robbuy_list = $model_robbuy->getRobbuyCommendedList(8);
        Tpl::output('commended_robbuy_list', $commended_robbuy_list);

        // 好评率
        $model_evaluate = Model('evaluate_goods');
        $evaluate_info = $model_evaluate->getEvaluateGoodsInfoByCommonidID($robbuy_info['goods_commonid']);
        Tpl::output('evaluate_info', $evaluate_info);

        Model('seo')->type('group_content')->param(array('name'=>$robbuy_info['robbuy_name']))->show();

        shopload('search');
        Tpl::showpage('robbuy_detail');
    }

    /**
     * 购买记录
     */
    public function robbuy_orderWt() {
        $group_id = intval($_GET['group_id']);
        if ($group_id > 0) {
            if (!$_GET['is_vr']) {
                //获取购买记录
                $model_order = Model('order');
                $condition = array();
                $condition['goods_type'] = 2;
                $condition['sales_id'] = $group_id;
                $order_goods_list = $model_order->getOrderGoodsList($condition, '*', 0 , 10);
                Tpl::output('order_goods_list', $order_goods_list);
                Tpl::output('show_page', $model_order->showpage());
                if (!empty($order_goods_list)) {
                    $orderid_array = array();
                    foreach ($order_goods_list as $value) {
                        $orderid_array[] = $value['order_id'];
                    }
                    $order_list = $model_order->getOrderList(array('order_id' => array('in', $orderid_array)), '', 'order_id,buyer_name,add_time');
                    $order_list = array_under_reset($order_list, 'order_id');
                    Tpl::output('order_list', $order_list);
                }
            } else {
                $model_order = Model('order_vr');
                $condition = array();
                $condition['order_sale_type'] = 1;
                $condition['sales_id'] = $group_id;
                $order_goods_list = $model_order->getOrderAndOrderGoodsSalesRecordList($condition, '*', 10);
                Tpl::output('order_goods_list', $order_goods_list);
                Tpl::output('show_page', $model_order->showpage());
            }
        }
        Tpl::showpage('robbuy_order', 'null_layout');
    }

    /**
     * 商品评价
     */
    public function robbuy_evaluateWt() {
        $goods_commonid = intval($_GET['commonid']);
        if ($goods_commonid > 0) {
            $condition = array();
            $condition['goods_commonid'] = $goods_commonid;
            $goods_list = Model('goods')->getGoodsList($condition, 'goods_id');
            if (!empty($goods_list)) {
                $goodsid_array = array();
                foreach ($goods_list as $value) {
                    $goodsid_array[] = $value['goods_id'];
                }
                $model_evaluate = Model('evaluate_goods');
                $where = array();
                $where['geval_goodsid'] = array('in', $goodsid_array);
                $evaluate_list = $model_evaluate->getEvaluateGoodsList($where, 10);
                Tpl::output('goodsevallist',$evaluate_list);
                Tpl::output('show_page',$model_evaluate->showpage());
            }
        }
        Tpl::showpage('robbuy_evaluate', 'null_layout');
    }
}
