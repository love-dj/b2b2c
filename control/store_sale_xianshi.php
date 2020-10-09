<?php
/**
 * 用户中心-限时折扣
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class store_sale_xianshiControl extends BaseSellerControl {

    const LINK_XIANSHI_LIST = 'index.php?w=store_sale_xianshi&t=xianshi_list';
    const LINK_XIANSHI_MANAGE = 'index.php?w=store_sale_xianshi&t=xianshi_manage&xianshi_id=';

    public function __construct() {
        parent::__construct() ;

        //读取语言包
        Language::read('member_layout,sale_xianshi');
        //检查限时折扣是否开启
        if (intval(C('sale_allow')) !== 1){
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'),'','error');
        }

    }

    public function indexWt() {
        $this->xianshi_listWt();
    }

    /**
     * 发布的限时折扣活动列表
     **/
    public function xianshi_listWt() {
        $model_xianshi_quota = Model('p_xianshi_quota');
        $model_xianshi = Model('p_xianshi');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_xianshi_quota = $model_xianshi_quota->getXianshiQuotaCurrent($_SESSION['store_id']);
            Tpl::output('current_xianshi_quota', $current_xianshi_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if(!empty($_GET['xianshi_name'])) {
            $condition['xianshi_name'] = array('like', '%'.$_GET['xianshi_name'].'%');
        }
        if(!empty($_GET['state'])) {
            $condition['state'] = intval($_GET['state']);
        }
        $xianshi_list = $model_xianshi->getXianshiList($condition, 10, 'state desc, end_time desc');
        Tpl::output('list', $xianshi_list);
        Tpl::output('show_page', $model_xianshi->showpage());
        Tpl::output('xianshi_state_array', $model_xianshi->getXianshiStateArray());

        self::profile_menu('xianshi_list');
        Tpl::showpage('store_sale_xianshi.list');
    }

    /**
     * 添加限时折扣活动
     **/
    public function xianshi_addWt() {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $model_xianshi_quota = Model('p_xianshi_quota');
            $current_xianshi_quota = $model_xianshi_quota->getXianshiQuotaCurrent($_SESSION['store_id']);
            if(empty($current_xianshi_quota)) {
                showMessage(Language::get('xianshi_quota_current_error1'),'','','error');
            }
            Tpl::output('current_xianshi_quota',$current_xianshi_quota);
        }

        //输出导航
        self::profile_menu('xianshi_add');
        Tpl::showpage('store_sale_xianshi.add');

    }

    /**
     * 保存添加的限时折扣活动
     **/
    public function xianshi_saveWt() {
        //验证输入
        $xianshi_name = trim($_POST['xianshi_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']);
        $lower_limit = intval($_POST['lower_limit']);
        if($lower_limit <= 0) {
            $lower_limit = 1;
        }
        if(empty($xianshi_name)) {
            showDialog(Language::get('xianshi_name_error'));
        }
        if($start_time >= $end_time) {
            showDialog(Language::get('greater_than_start_time'));
        }

        if (!checkPlatformStore()) {
            //获取当前套餐
            $model_xianshi_quota = Model('p_xianshi_quota');
            $current_xianshi_quota = $model_xianshi_quota->getXianshiQuotaCurrent($_SESSION['store_id']);
            if(empty($current_xianshi_quota)) {
                showDialog('没有可用限时折扣套餐,请先购买套餐');
            }
            $quota_start_time = intval($current_xianshi_quota['start_time']);
            $quota_end_time = intval($current_xianshi_quota['end_time']);
            if($start_time < $quota_start_time) {
                showDialog(sprintf(Language::get('xianshi_add_start_time_explain'),date('Y-m-d',$current_xianshi_quota['start_time'])));
            }
            if($end_time > $quota_end_time) {
                showDialog(sprintf(Language::get('xianshi_add_end_time_explain'),date('Y-m-d',$current_xianshi_quota['end_time'])));
            }
        }

        //生成活动
        $model_xianshi = Model('p_xianshi');
        $param = array();
        $param['xianshi_name'] = $xianshi_name;
        $param['xianshi_title'] = $_POST['xianshi_title'];
        $param['xianshi_explain'] = $_POST['xianshi_explain'];
        $param['quota_id'] = $current_xianshi_quota['quota_id'] ? $current_xianshi_quota['quota_id'] : 0;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];
        $param['member_id'] = $_SESSION['member_id'];
        $param['member_name'] = $_SESSION['member_name'];
        $param['lower_limit'] = $lower_limit;
        $result = $model_xianshi->addXianshi($param);
        if($result) {
            $this->recordSellerLog('添加限时折扣活动，活动名称：'.$xianshi_name.'，活动编号：'.$result);
            // 添加计划任务
            Model('cron')->addCron(array('exetime' => $param['end_time'], 'exeid' => $result, 'type' => 7), true);
            showDialog(Language::get('xianshi_add_success'),self::LINK_XIANSHI_MANAGE.$result,'succ','',3);
        }else {
            showDialog(Language::get('xianshi_add_fail'));
        }
    }

    /**
     * 编辑限时折扣活动
     **/
    public function xianshi_editWt() {
        $model_xianshi = Model('p_xianshi');

        $xianshi_info = $model_xianshi->getXianshiInfoByID($_GET['xianshi_id']);
        if(empty($xianshi_info) || !$xianshi_info['editable']) {
            showMessage(L('param_error'),'','','error');
        }

        Tpl::output('xianshi_info', $xianshi_info);

        //输出导航
        self::profile_menu('xianshi_edit');
        Tpl::showpage('store_sale_xianshi.add');
    }

    /**
     * 编辑保存限时折扣活动
     **/
    public function xianshi_edit_saveWt() {
        $xianshi_id = $_POST['xianshi_id'];

        $model_xianshi = Model('p_xianshi');
        $model_xianshi_goods = Model('p_xianshi_goods');

        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_id, $_SESSION['store_id']);
        if(empty($xianshi_info) || !$xianshi_info['editable']) {
            showMessage(L('param_error'),'','','error');
        }

        //验证输入
        $xianshi_name = trim($_POST['xianshi_name']);
        $lower_limit = intval($_POST['lower_limit']);
        if($lower_limit <= 0) {
            $lower_limit = 1;
        }
        if(empty($xianshi_name)) {
            showDialog(Language::get('xianshi_name_error'));
        }

        //生成活动
        $param = array();
        $param['xianshi_name'] = $xianshi_name;
        $param['xianshi_title'] = $_POST['xianshi_title'];
        $param['xianshi_explain'] = $_POST['xianshi_explain'];
        $param['lower_limit'] = $lower_limit;
        $result = $model_xianshi->editXianshi($param, array('xianshi_id'=>$xianshi_id));
        $result1 = $model_xianshi_goods->editXianshiGoods($param, array('xianshi_id'=>$xianshi_id));
        if($result && $result1) {
            $this->recordSellerLog('编辑限时折扣活动，活动名称：'.$xianshi_name.'，活动编号：'.$xianshi_id);
            showDialog(Language::get('wt_common_op_succ'),self::LINK_XIANSHI_LIST,'succ','',3);
        }else {
            showDialog(Language::get('wt_common_op_fail'));
        }
    }

    /**
     * 限时折扣活动删除
     **/
    public function xianshi_delWt() {
        $xianshi_id = intval($_POST['xianshi_id']);

        $model_xianshi = Model('p_xianshi');

        $data = array();
        $data['result'] = true;

        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_id, $_SESSION['store_id']);
        if(!$xianshi_info) {
            showDialog(L('param_error'));
        }

        $model_xianshi = Model('p_xianshi');
        $result = $model_xianshi->delXianshi(array('xianshi_id'=>$xianshi_id));

        if($result) {
            $this->recordSellerLog('删除限时折扣活动，活动名称：'.$xianshi_info['xianshi_name'].'活动编号：'.$xianshi_id);
            showDialog(L('wt_common_op_succ'), urlShop('store_sale_xianshi', 'xianshi_list'), 'succ');
        } else {
            showDialog(L('wt_common_op_fail'));
        }
    }

    /**
     * 限时折扣活动管理
     **/
    public function xianshi_manageWt() {
        $model_xianshi = Model('p_xianshi');
        $model_xianshi_goods = Model('p_xianshi_goods');

        $xianshi_id = intval($_GET['xianshi_id']);
        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_id, $_SESSION['store_id']);
        if(empty($xianshi_info)) {
            showDialog(L('param_error'));
        }
        Tpl::output('xianshi_info',$xianshi_info);

        //获取限时折扣商品列表
        $condition = array();
        $condition['xianshi_id'] = $xianshi_id;
        $xianshi_goods_list = $model_xianshi_goods->getXianshiGoodsExtendList($condition, 10);
        Tpl::output('show_page', $model_xianshi_goods->showpage());
        Tpl::output('xianshi_goods_list', $xianshi_goods_list);

        //输出导航
        self::profile_menu('xianshi_manage');
        Tpl::showpage('store_sale_xianshi.manage');
    }


    /**
     * 限时折扣套餐购买
     **/
    public function xianshi_quota_addWt() {
        //输出导航
        self::profile_menu('xianshi_quota_add');
        Tpl::showpage('store_sale_xianshi_quota.add');
    }

    /**
     * 限时折扣套餐购买保存
     **/
    public function xianshi_quota_add_saveWt() {

        $xianshi_quota_quantity = intval($_POST['xianshi_quota_quantity']);

        if($xianshi_quota_quantity <= 0 || $xianshi_quota_quantity > 12) {
            showDialog(Language::get('xianshi_quota_quantity_error'));
        }

        //获取当前价格
        $current_price = intval(C('sale_xianshi_price'));

        //获取该用户已有套餐
        $model_xianshi_quota = Model('p_xianshi_quota');
        $current_xianshi_quota= $model_xianshi_quota->getXianshiQuotaCurrent($_SESSION['store_id']);
        $add_time = 86400 *30 * $xianshi_quota_quantity;
        if(empty($current_xianshi_quota)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_xianshi_quota->addXianshiQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_xianshi_quota->editXianshiQuota($param, array('quota_id' => $current_xianshi_quota['quota_id']));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $xianshi_quota_quantity, '购买限时折扣');

        $this->recordSellerLog('购买'.$xianshi_quota_quantity.'份限时折扣套餐，单价'.$current_price.$lang['wt_yuan']);

        showDialog(Language::get('xianshi_quota_add_success'),self::LINK_XIANSHI_LIST,'succ');
    }

    /**
     * 选择活动商品
     **/
    public function goods_selectWt() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsOnlineList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_sale_xianshi.goods', 'null_layout');
    }

    /**
     * 限时折扣商品添加
     **/
    public function xianshi_goods_addWt() {
        $goods_id = intval($_POST['goods_id']);
        $xianshi_id = intval($_POST['xianshi_id']);
        $xianshi_price = floatval($_POST['xianshi_price']);

        $model_goods = Model('goods');
        $model_xianshi = Model('p_xianshi');
        $model_xianshi_goods = Model('p_xianshi_goods');

        $data = array();
        $data['result'] = true;

        $goods_content = $model_goods->getGoodsInfoByID($goods_id);
        if(empty($goods_content) || $goods_content['store_id'] != $_SESSION['store_id']) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_id, $_SESSION['store_id']);
        if(!$xianshi_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        //检查商品是否已经参加同时段活动
        $condition = array();
        $condition['end_time'] = array('gt', $xianshi_info['start_time']);
        $condition['goods_id'] = $goods_id;
        $xianshi_goods = $model_xianshi_goods->getXianshiGoodsExtendList($condition);
        if(!empty($xianshi_goods)) {
            $data['result'] = false;
            $data['message'] = '该商品已经参加了同时段活动';
            echo json_encode($data);die;
        }

        //添加到活动商品表
        $param = array();
        $param['xianshi_id'] = $xianshi_info['xianshi_id'];
        $param['xianshi_name'] = $xianshi_info['xianshi_name'];
        $param['xianshi_title'] = $xianshi_info['xianshi_title'];
        $param['xianshi_explain'] = $xianshi_info['xianshi_explain'];
        $param['goods_id'] = $goods_content['goods_id'];
        $param['store_id'] = $goods_content['store_id'];
        $param['goods_name'] = $goods_content['goods_name'];
        $param['goods_price'] = $goods_content['goods_price'];
        $param['xianshi_price'] = $xianshi_price;
        $param['goods_image'] = $goods_content['goods_image'];
        $param['start_time'] = $xianshi_info['start_time'];
        $param['end_time'] = $xianshi_info['end_time'];
        $param['lower_limit'] = $xianshi_info['lower_limit'];
        $param['gc_id_1'] = $goods_content['gc_id_1'];

        $result = array();
        $xianshi_goods_content = $model_xianshi_goods->addXianshiGoods($param);
        if($xianshi_goods_content) {
            $result['result'] = true;
            $data['message'] = '添加成功';
            $data['xianshi_goods'] = $xianshi_goods_content;
            // 自动发布动态
            // goods_id,store_id,goods_name,goods_image,goods_price,goods_freight,xianshi_price
            $data_array = array();
            $data_array['goods_id']         = $goods_content['goods_id'];
            $data_array['store_id']         = $_SESSION['store_id'];
            $data_array['goods_name']       = $goods_content['goods_name'];
            $data_array['goods_image']      = $goods_content['goods_image'];
            $data_array['goods_price']      = $goods_content['goods_price'];
            $data_array['goods_freight']    = $goods_content['goods_freight'];
            $data_array['xianshi_price']    = $xianshi_price;
            $this->storeAutoShare($data_array, 'xianshi');
            $this->recordSellerLog('添加限时折扣商品，活动名称：'.$xianshi_info['xianshi_name'].'，商品名称：'.$goods_content['goods_name']);

            // 添加任务计划
            Model('cron')->addCron(array('type' => 2, 'exeid' => $goods_content['goods_id'], 'exetime' => $param['start_time']));
        } else {
            $data['result'] = false;
            $data['message'] = L('param_error');
        }
        echo json_encode($data);die;
    }

    /**
     * 限时折扣商品价格修改
     **/
    public function xianshi_goods_price_editWt() {
        $xianshi_goods_id = intval($_POST['xianshi_goods_id']);
        $xianshi_price = floatval($_POST['xianshi_price']);

        $data = array();
        $data['result'] = true;

        $model_xianshi_goods = Model('p_xianshi_goods');

        $xianshi_goods_content = $model_xianshi_goods->getXianshiGoodsInfoByID($xianshi_goods_id, $_SESSION['store_id']);
        if(!$xianshi_goods_content) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $update = array();
        $update['xianshi_price'] = $xianshi_price;
        $condition = array();
        $condition['xianshi_goods_id'] = $xianshi_goods_id;
        $result = $model_xianshi_goods->editXianshiGoods($update, $condition);

        if($result) {
            $xianshi_goods_content['xianshi_price'] = $xianshi_price;
            $xianshi_goods_content = $model_xianshi_goods->getXianshiGoodsExtendInfo($xianshi_goods_content);
            $data['xianshi_price'] = $xianshi_goods_content['xianshi_price'];
            $data['xianshi_discount'] = $xianshi_goods_content['xianshi_discount'];

            // 添加对列修改商品促销价格
            QueueClient::push('updateGoodsSalePriceByGoodsId', $xianshi_goods_content['goods_id']);

            $this->recordSellerLog('限时折扣价格修改为：'.$xianshi_goods_content['xianshi_price'].'，商品名称：'.$xianshi_goods_content['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = L('wt_common_op_succ');
        }
        echo json_encode($data);die;
    }

    /**
     * 限时折扣商品删除
     **/
    public function xianshi_goods_deleteWt() {
        $model_xianshi_goods = Model('p_xianshi_goods');
        $model_xianshi = Model('p_xianshi');

        $data = array();
        $data['result'] = true;

        $xianshi_goods_id = intval($_POST['xianshi_goods_id']);
        $xianshi_goods_content = $model_xianshi_goods->getXianshiGoodsInfoByID($xianshi_goods_id);
        if(!$xianshi_goods_content) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_goods_content['xianshi_id'], $_SESSION['store_id']);
        if(!$xianshi_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        if(!$model_xianshi_goods->delXianshiGoods(array('xianshi_goods_id'=>$xianshi_goods_id))) {
            $data['result'] = false;
            $data['message'] = L('xianshi_goods_delete_fail');
            echo json_encode($data);die;
        }

        // 添加对列修改商品促销价格
        QueueClient::push('updateGoodsSalePriceByGoodsId', $xianshi_goods_content['goods_id']);

        $this->recordSellerLog('删除限时折扣商品，活动名称：'.$xianshi_info['xianshi_name'].'，商品名称：'.$xianshi_goods_content['goods_name']);
        echo json_encode($data);die;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'xianshi_list','menu_name'=>Language::get('sale_active_list'),'menu_url'=>'index.php?w=store_sale_xianshi&t=xianshi_list'),
        );
        switch ($menu_key){
            case 'xianshi_add':
                $menu_array[] = array('menu_key'=>'xianshi_add','menu_name'=>Language::get('sale_join_active'),'menu_url'=>'index.php?w=store_sale_xianshi&t=xianshi_add');
                break;
            case 'xianshi_edit':
                $menu_array[] = array('menu_key'=>'xianshi_edit','menu_name'=>'编辑活动','menu_url'=>'javascript:;');
                break;
            case 'xianshi_quota_add':
                $menu_array[] = array('menu_key'=>'xianshi_quota_add','menu_name'=>Language::get('sale_buy_product'),'menu_url'=>'index.php?w=store_sale_xianshi&t=xianshi_quota_add');
                break;
            case 'xianshi_manage':
                $menu_array[] = array('menu_key'=>'xianshi_manage','menu_name'=>Language::get('sale_goods_manage'),'menu_url'=>'index.php?w=store_sale_xianshi&t=xianshi_manage&xianshi_id='.$_GET['xianshi_id']);
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
