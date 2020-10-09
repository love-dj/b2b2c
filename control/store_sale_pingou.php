<?php
/**
 * 商家中心-拼团 6.4
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class store_sale_pingouControl extends BaseSellerControl {

    const LINK_LIST = 'index.php?w=store_sale_pingou&t=index';
    const LINK_MANAGE = 'index.php?w=store_sale_pingou&t=pingou_manage&pingou_id=';

    public function __construct() {
        parent::__construct();
        $model_pingou = Model('p_pingou');
        $model_pingou->getStateArray();
    }

    public function indexWt() {
        $model_pingou = Model('p_pingou');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_quota = $model_pingou->getQuotaCurrent($_SESSION['store_id']);
            Tpl::output('current_quota', $current_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if(!empty($_GET['name'])) {
            $condition['pingou_name'] = array('like', '%'.$_GET['name'].'%');
        }
        $pingou_list = $model_pingou->getList($condition, 10);
        Tpl::output('list', $pingou_list);
        Tpl::output('show_page', $model_pingou->showpage());

        self::profile_menu('pingou_list');
        Tpl::showpage('store_sale_pingou.list');
    }

    /**
     * 添加活动
     **/
    public function pingou_addWt() {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $model_pingou = Model('p_pingou');
            $current_pingou = $model_pingou->getQuotaCurrent($_SESSION['store_id']);
            if(empty($current_pingou)) {
                showMessage(Language::get('pingou_current_error1'),'','','error');
            }
            Tpl::output('current_pingou_quota',$current_pingou);
        }

        //输出导航
        self::profile_menu('pingou_add');
        Tpl::showpage('store_sale_pingou.add');

    }

    /**
     * 保存添加的活动
     **/
    public function pingou_saveWt() {
        $model_pingou = Model('p_pingou');
        //验证输入
        $pingou_name = trim($_POST['pingou_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']);
        $min_num = intval($_POST['min_num']);
        if($min_num <= 1) {
            $min_num = 2;
        }
        if (!checkPlatformStore()) {
            $current_pingou = $model_pingou->getQuotaCurrent($_SESSION['store_id']);
            if(empty($current_pingou)) {
                showDialog('没有可用套餐,请先购买套餐');
            }
            $quota_start_time = intval($current_pingou['start_time']);
            $quota_end_time = intval($current_pingou['end_time']);
            if($start_time < $quota_start_time) {
                showDialog(sprintf('开始时间不能为空且不能早于%s',date('Y-m-d',$current_pingou['start_time'])));
            }
            if($end_time > $quota_end_time) {
                showDialog(sprintf('结束时间不能为空且不能晚于%s',date('Y-m-d',$current_pingou['end_time'])));
            }
        }
        $param = array();
        $param['pingou_name'] = $pingou_name;
        $param['pingou_title'] = $_POST['pingou_title'];
        $param['pingou_explain'] = $_POST['pingou_explain'];
        $param['quota_id'] = $current_pingou['quota_id'] ? $current_pingou['quota_id'] : 0;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];
        $param['member_id'] = $_SESSION['member_id'];
        $param['member_name'] = $_SESSION['member_name'];
        $param['min_num'] = $min_num;
        $result = $model_pingou->add($param);
        if($result) {
            $this->recordSellerLog('添加拼团活动，活动名称：'.$pingou_name.'，活动编号：'.$result);
            showDialog(Language::get('wt_common_save_succ'),self::LINK_MANAGE.$result,'succ','',3);
        }else {
            showDialog(Language::get('wt_common_save_fail'));
        }
    }

    /**
     * 编辑活动
     **/
    public function pingou_editWt() {
        $model_pingou = Model('p_pingou');
        $pingou_id = intval($_GET['pingou_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        if(empty($pingou_info)) {
            showMessage('参数错误','','','error');
        }

        Tpl::output('pingou_info', $pingou_info);

        //输出导航
        self::profile_menu('pingou_edit');
        Tpl::showpage('store_sale_pingou.add');
    }

    /**
     * 编辑保存活动
     **/
    public function pingou_edit_saveWt() {
        $model_pingou = Model('p_pingou');
        $pingou_id = intval($_POST['pingou_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        if(empty($pingou_info)) {
            showDialog(Language::get('param_error'));
        }

        //验证输入
        $pingou_name = trim($_POST['pingou_name']);
        $min_num = intval($_POST['min_num']);
        if($min_num <= 1) {
            $min_num = 2;
        }

        //生成活动
        $param = array();
        $param['pingou_name'] = $pingou_name;
        $param['pingou_title'] = $_POST['pingou_title'];
        $param['pingou_explain'] = $_POST['pingou_explain'];
        $param['min_num'] = $min_num;
        $result = $model_pingou->edit($param, array('pingou_id'=>$pingou_id));
        if($result) {
            $model_pingou->editGoods($param, array('pingou_id'=>$pingou_id));
            $this->recordSellerLog('编辑拼团活动，活动名称：'.$pingou_name.'，活动编号：'.$pingou_id);
            showDialog(Language::get('wt_common_op_succ'),self::LINK_LIST,'succ','',3);
        }else {
            showDialog(Language::get('wt_common_op_fail'));
        }
    }

    /**
     * 活动删除
     **/
    public function pingou_delWt() {
        $pingou_id = intval($_POST['pingou_id']);

        $model_pingou = Model('p_pingou');

        $data = array();
        $data['result'] = true;

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        if(!$pingou_info) {
            showDialog('参数错误');
        }

        $model_pingou = Model('p_pingou');
        $result = $model_pingou->del($condition);
        if($result) {
            $this->recordSellerLog('删除拼团活动，活动名称：'.$pingou_info['pingou_name'].'活动编号：'.$pingou_id);
            showDialog(L('wt_common_op_succ'), urlShop('store_sale_pingou', 'pingou_list'), 'succ');
        } else {
            showDialog(L('wt_common_op_fail'));
        }
    }

    /**
     * 活动管理
     **/
    public function pingou_manageWt() {
        $model_pingou = Model('p_pingou');

        $pingou_id = intval($_GET['pingou_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        Tpl::output('pingou_info',$pingou_info);

        //获取商品列表
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $goods_list = $model_pingou->getGoodsList($condition, 10);
        Tpl::output('show_page', $model_pingou->showpage());
        Tpl::output('pingou_goods_list', $goods_list);

        //输出导航
        self::profile_menu('pingou_manage');
        Tpl::showpage('store_sale_pingou.manage');
    }


    /**
     * 套餐购买
     **/
    public function quota_addWt() {
        //输出导航
        self::profile_menu('pingou_add');
        Tpl::showpage('store_sale_pingou_quota.add');
    }

    /**
     * 套餐购买保存
     **/
    public function quota_add_saveWt() {

        $pingou_quantity = intval($_POST['pingou_quantity']);

        if($pingou_quantity <= 0 || $pingou_quantity > 12) {
            $pingou_quantity = 1;
        }

        //获取当前价格
        $current_price = intval(C('sale_pingou_price'));

        //获取该用户已有套餐
        $model_pingou = Model('p_pingou');
        $current_pingou= $model_pingou->getQuotaCurrent($_SESSION['store_id']);
        $add_time = 86400 *30 * $pingou_quantity;
        if(empty($current_pingou)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_pingou->addQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_pingou->editQuota($param, array('quota_id' => $current_pingou['quota_id']));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $pingou_quantity, '购买拼团');

        $this->recordSellerLog('购买'.$pingou_quantity.'份拼团套餐，单价'.$current_price.$lang['wt_yuan']);

        showDialog(Language::get('wt_common_save_succ'),self::LINK_LIST,'succ');
    }

    /**
     * 选择活动商品
     **/
    public function goods_selectWt() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
		$condition['goods_sale_type'] = 0;
		$condition['is_bat'] = 0;
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsOnlineList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_sale_pingou.goods', 'null_layout');
    }

    /**
     * 商品添加
     **/
    public function pingou_goods_addWt() {
        $goods_id = intval($_POST['goods_id']);
        $pingou_id = intval($_POST['pingou_id']);
        $pingou_price = floatval($_POST['pingou_price']);
		$goods_maxnum = intval($_POST['goods_maxnum']);

        $model_goods = Model('goods');
        $model_pingou = Model('p_pingou');

        $data = array();
        $data['result'] = true;

        $goods_content = $model_goods->getGoodsInfoByID($goods_id);
        if(empty($goods_content) || $goods_content['store_id'] != $_SESSION['store_id']) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_id'] = $pingou_id;
        $pingou_info = $model_pingou->getInfo($condition);
        if(!$pingou_info) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        //检查商品是否已经参加同时段活动
        $condition = array();
        $condition['end_time'] = array('gt', $pingou_info['start_time']);
        $condition['goods_id'] = $goods_id;
        $pingou_goods = $model_pingou->getGoodsList($condition);
        if(!empty($pingou_goods)) {
            $data['result'] = false;
            $data['message'] = '该商品已经参加了同时段活动';
            echo json_encode($data);die;
        }

        //添加到活动商品表
        $param = array();
        $param['pingou_id'] = $pingou_info['pingou_id'];
        $param['pingou_name'] = $pingou_info['pingou_name'];
        $param['pingou_title'] = $pingou_info['pingou_title'];
        $param['pingou_explain'] = $pingou_info['pingou_explain'];
        $param['goods_id'] = $goods_content['goods_id'];
        $param['store_id'] = $goods_content['store_id'];
        $param['gc_id'] = $goods_content['gc_id_1'];
        $param['goods_name'] = $goods_content['goods_name'];
        $param['goods_price'] = $goods_content['goods_price'];
        $param['pingou_price'] = $pingou_price;
        $param['goods_image'] = $goods_content['goods_image'];
        $param['start_time'] = $pingou_info['start_time'];
        $param['end_time'] = $pingou_info['end_time'];
        $param['min_num'] = $pingou_info['min_num'];
        $param['goods_maxnum'] = $goods_maxnum;
        $result = $model_pingou->addGoods($param);
        if($result) {
            $data['message'] = '添加成功';
            $param['pingou_goods_id'] = $result;
            $param['goods_url'] =  urlShop('goods', 'index', array('goods_id' => $param['goods_id']));
            $param['image_url'] = thumb($param, 240);
            $data['pingou_goods'] = $param;
            $this->recordSellerLog('添加拼团商品，活动名称：'.$pingou_info['pingou_name'].'，商品名称：'.$goods_content['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = '参数错误';
        }
        echo json_encode($data);die;
    }

    /**
     * 商品价格修改
     **/
    public function pingou_goods_price_editWt() {
        $pingou_goods_id = intval($_POST['pingou_goods_id']);
        $pingou_price = floatval($_POST['pingou_price']);
		$goods_maxnum = intval($_POST['goods_maxnum']);

        $data = array();
        $data['result'] = true;

        $model_pingou = Model('p_pingou');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_goods_id'] = $pingou_goods_id;
        $pingou_goods_content = $model_pingou->getGoodsInfo($condition);
        if(!$pingou_goods_content) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        $update = array();
        $update['pingou_price'] = $pingou_price;
        $update['goods_maxnum'] = $goods_maxnum;
        $result = $model_pingou->editGoods($update, $condition);

        if($result) {
            $pingou_goods_content['pingou_price'] = $pingou_price;
            $data['pingou_price'] = $pingou_goods_content['pingou_price'];
            $data['goods_maxnum'] = $goods_maxnum;

            $this->recordSellerLog('价格修改为：'.$pingou_goods_content['pingou_price'].'，商品名称：'.$pingou_goods_content['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = L('wt_common_op_succ');
        }
        echo json_encode($data);die;
    }

    /**
     * 商品删除
     **/
    public function pingou_goods_deleteWt() {
        $model_pingou = Model('p_pingou');

        $data = array();
        $data['result'] = true;

        $pingou_goods_id = intval($_POST['pingou_goods_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pingou_goods_id'] = $pingou_goods_id;
        $pingou_goods_content = $model_pingou->getGoodsInfo($condition);
        if(!$pingou_goods_content) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        if(!$model_pingou->delGoods($condition)) {
            $data['result'] = false;
            $data['message'] = '删除失败';
            echo json_encode($data);die;
        }

        $this->recordSellerLog('删除拼团商品，活动名称：'.$pingou_info['pingou_name'].'，商品名称：'.$pingou_goods_content['goods_name']);
        echo json_encode($data);die;
    }

    /**
     * 小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'pingou_list','menu_name'=>'活动列表','menu_url'=>'index.php?w=store_sale_pingou&t=pingou_list'),
        );
        switch ($menu_key){
            case 'pingou_add':
                $menu_array[] = array('menu_key'=>'pingou_add','menu_name'=>'添加活动','menu_url'=>'index.php?w=store_sale_pingou&t=pingou_add');
                break;
            case 'pingou_edit':
                $menu_array[] = array('menu_key'=>'pingou_edit','menu_name'=>'编辑活动','menu_url'=>'javascript:;');
                break;
            case 'quota_add':
                $menu_array[] = array('menu_key'=>'pingou_add','menu_name'=>'购买套餐','menu_url'=>'index.php?w=store_sale_pingou&t=quota_add');
                break;
            case 'pingou_manage':
                $menu_array[] = array('menu_key'=>'pingou_manage','menu_name'=>'商品管理','menu_url'=>'index.php?w=store_sale_pingou&t=pingou_manage&pingou_id='.$_GET['pingou_id']);
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
