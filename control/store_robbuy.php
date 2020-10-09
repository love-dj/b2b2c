<?php
/**
 * 商家中心抢购管理
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class store_robbuyControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();

        //读取语言包
        Language::read('member_robbuy');
        //检查抢购功能是否开启
        if (intval(C('robbuy_allow')) !== 1){
            showMessage(Language::get('robbuy_unavailable'),'index.php?w=seller_center','','error');
        }
    }
    /**
     * 默认显示抢购列表
     **/
    public function indexWt() {
        $this->robbuy_listWt();
    }

    /**
     * 抢购套餐购买
     **/
    public function robbuy_quota_addWt() {
        //输出导航
        self::profile_menu('robbuy_quota_add');
        Tpl::showpage('store_robbuy_quota.add');
    }

    /**
     * 抢购套餐购买保存
     **/
    public function robbuy_quota_add_saveWt() {
        $robbuy_quota_quantity = intval($_POST['robbuy_quota_quantity']);
        if($robbuy_quota_quantity <= 0) {
            showDialog('购买数量不能为空');
        }

        $model_robbuy_quota = Model('robbuy_quota');

        //获取当前价格
        $current_price = intval(C('robbuy_price'));

        //获取该用户已有套餐
        $current_robbuy_quota= $model_robbuy_quota->getRobbuyQuotaCurrent($_SESSION['store_id']);
        $add_time = 86400 * 30 * $robbuy_quota_quantity;
        if(empty($current_robbuy_quota)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_robbuy_quota->addRobbuyQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_robbuy_quota->editRobbuyQuota($param, array('quota_id' => $current_robbuy_quota['quota_id']));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $robbuy_quota_quantity, '购买抢购');

        $this->recordSellerLog('购买'.$robbuy_quota_quantity.'份抢购套餐，单价'.$current_price.L('wt_yuan'));

        showDialog(Language::get('robbuy_quota_add_success'), urlShop('store_robbuy', 'robbuy_list'), 'succ');
    }

    /**
     * 抢购列表
     **/
    public function robbuy_listWt() {
        $model_robbuy = Model('robbuy');
        $model_robbuy_quota = Model('robbuy_quota');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_robbuy_quota = $model_robbuy_quota->getRobbuyQuotaCurrent($_SESSION['store_id']);
            Tpl::output('current_robbuy_quota', $current_robbuy_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if(!empty($_GET['robbuy_state'])) {
            $condition['state'] = $_GET['robbuy_state'];
        }
        $condition['robbuy_name'] = array('like', '%'.$_GET['robbuy_name'].'%');

        if (strlen($robbuy_vr = trim($_GET['robbuy_vr']))) {
            $condition['is_vr'] = $robbuy_vr ? 1 : 0;
            Tpl::output('robbuy_vr', $robbuy_vr);
        }
        $robbuy_list = $model_robbuy->getRobbuyExtendList($condition, 10);
        Tpl::output('group',$robbuy_list);
        Tpl::output('show_page',$model_robbuy->showpage());
        Tpl::output('robbuy_state_array', $model_robbuy->getRobbuyStateArray());

        self::profile_menu('robbuy_list');
        Tpl::showpage('store_robbuy.list');
    }

    /**
     * 添加抢购页面
     **/
    public function robbuy_addWt() {
        $model_robbuy_quota = Model('robbuy_quota');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_robbuy_quota = $model_robbuy_quota->getRobbuyQuotaCurrent($_SESSION['store_id']);
            if(empty($current_robbuy_quota)) {
                showMessage('当前没有可用套餐，请先购买套餐',urlShop('store_robbuy', 'robbuy_quota_add'),'','error');
            }
            Tpl::output('current_robbuy_quota', $current_robbuy_quota);
        }

        // 根据后台设置的审核期重新设置抢购开始时间
        Tpl::output('robbuy_start_time', TIMESTAMP + intval(C('robbuy_review_day')) * 86400);

        Tpl::output('robbuy_classes', Model('robbuy')->getRobbuyClasses());

        self::profile_menu('robbuy_add');
        Tpl::showpage('store_robbuy.add');

    }

    /**
     * 抢购保存
     **/
    public function robbuy_saveWt() {
        //获取提交的数据
        $goods_id = intval($_POST['robbuy_goods_id']);
        if(empty($goods_id)) {
            showDialog(Language::get('param_error'));
        }

        $model_robbuy = Model('robbuy');
        $model_goods = Model('goods');
        $model_robbuy_quota = Model('robbuy_quota');

        if (!checkPlatformStore()) {
            // 检查套餐
            $current_robbuy_quota = $model_robbuy_quota->getRobbuyQuotaCurrent($_SESSION['store_id']);
            if(empty($current_robbuy_quota)) {
                showDialog('当前没有可用套餐，请先购买套餐',urlShop('store_robbuy', 'robbuy_quota_add'),'error');
            }
        }

        $goods_content = $model_goods->getGoodsInfoByID($goods_id, 'goods_id,goods_commonid,goods_name,goods_price,store_id,virtual_limit');
        if(empty($goods_content) || $goods_content['store_id'] != $_SESSION['store_id']) {
            showDialog(Language::get('param_error'));
        }

        $param = array();
        $param['robbuy_name'] = $_POST['robbuy_name'];
        $param['remark'] = $_POST['remark'];
        $param['start_time'] = strtotime($_POST['start_time']);
        $param['end_time'] = strtotime($_POST['end_time']);
        $param['robbuy_price'] = floatval($_POST['robbuy_price']);
        $param['robbuy_rebate'] = wtPriceFormat(floatval($_POST['robbuy_price'])/floatval($goods_content['goods_price'])*10);
        $param['robbuy_image'] = $_POST['robbuy_image'];
        $param['robbuy_image1'] = $_POST['robbuy_image1'];
        $param['virtual_quantity'] = intval($_POST['virtual_quantity']);
        $param['upper_limit'] = intval($_POST['upper_limit']);
        $param['robbuy_intro'] = $_POST['robbuy_intro'];
        $param['class_id'] = intval($_POST['class_id']);
        $param['s_class_id'] = intval($_POST['s_class_id']);
        $param['goods_id'] = $goods_content['goods_id'];
        $param['goods_commonid'] = $goods_content['goods_commonid'];
        $param['goods_name'] = $goods_content['goods_name'];
        $param['goods_price'] = $goods_content['goods_price'];
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];

        // 虚拟抢购
        if ($_GET['vr']) {
            if ($param['upper_limit'] > 0 && $goods_content['virtual_limit'] > 0
                && $param['upper_limit'] > $goods_content['virtual_limit']) {
                showDialog(sprintf('虚拟抢购活动的限购数量(%d)不能大于虚拟商品本身的限购数量(%d)',
                    $param['upper_limit'], $goods_content['virtual_limit']
                    ), 'index.php?w=store_robbuy');
            }

            $param += array(
                'is_vr' => 1,
                'vr_class_id' => (int) $_POST['class'],
                'vr_s_class_id' => (int) $_POST['s_class'],
                'vr_city_id' => (int) $_POST['city'],
                'vr_area_id' => (int) $_POST['area'],
                'vr_mall_id' => (int) $_POST['mall'],
            );
        }

        //保存
        $result = $model_robbuy->addRobbuy($param);
        if($result) {
            // 自动发布动态
            // group_id,group_name,goods_id,goods_price,robbuy_price,group_pic,rebate,start_time,end_time
            $data_array = array();
            $data_array['group_id']         = $result;
            $data_array['group_name']       = $param['group_name'];
            $data_array['goods_id']         = $param['goods_id'];
            $data_array['goods_price']      = $param['goods_price'];
            $data_array['robbuy_price']   = $param['robbuy_price'];
            $data_array['group_pic']        = $param['robbuy_image1'];
            $data_array['rebate']           = $param['robbuy_rebate'];
            $data_array['start_time']       = $param['start_time'];
            $data_array['end_time']         = $param['end_time'];
            $this->storeAutoShare($data_array, 'robbuy');

            $this->recordSellerLog('发布抢购活动，抢购名称：'.$param['robbuy_name'].'，商品名称：'.$param['goods_name']);
            showDialog(Language::get('robbuy_add_success'),'index.php?w=store_robbuy','succ');
        }else {
            showDialog(Language::get('robbuy_add_fail'),'index.php?w=store_robbuy');
        }
    }

    public function robbuy_goods_contentWt() {
        $goods_commonid = intval($_GET['goods_commonid']);

        $data = array();
        $data['result'] = true;

        $model_goods = Model('goods');

        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods_list = $model_goods->getGoodsOnlineList($condition);

        if(empty($goods_list)) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $goods_content = $goods_list[0];
        $data['goods_id'] = $goods_content['goods_id'];
        $data['goods_name'] = $goods_content['goods_name'];
        $data['goods_price'] = $goods_content['goods_price'];
        $data['goods_image'] = thumb($goods_content, 240);
        $data['goods_href'] = urlShop('goods', 'index', array('goods_id' => $goods_content['goods_id']));

        if ($goods_content['is_virtual']) {
            $data['is_virtual'] = 1;
            $data['virtual_indate'] = $goods_content['virtual_indate'];
            $data['virtual_indate_str'] = date('Y-m-d H:i', $goods_content['virtual_indate']);
            $data['virtual_limit'] = $goods_content['virtual_limit'];
        }

        echo json_encode($data);die;
    }

    public function check_robbuy_goodsWt() {
        $start_time = strtotime($_GET['start_time']);
        $goods_id = $_GET['goods_id'];

        $model_robbuy = Model('robbuy');

        $data = array();
        $data['result'] = true;

        //检查商品是否已经参加同时段活动
        $condition = array();
        $condition['end_time'] = array('gt', $start_time);
        $condition['goods_id'] = $goods_id;
        $robbuy_list = $model_robbuy->getRobbuyAvailableList($condition);
        if(!empty($robbuy_list)) {
            $data['result'] = false;
            echo json_encode($data);die;
        }

        echo json_encode($data);die;
    }

    /**
     * 上传图片
     **/
    public function image_uploadWt() {
        if(!empty($_POST['old_robbuy_image'])) {
            $this->_image_del($_POST['old_robbuy_image']);
        }
        $this->_image_upload('robbuy_image');
    }

    private function _image_upload($file) {
        $data = array();
        $data['result'] = true;
        if(!empty($_FILES[$file]['name'])) {
            $upload = new UploadFile();
            $uploaddir = ATTACH_PATH.DS.'robbuy'.DS.$_SESSION['store_id'].DS;
            $upload->set('default_dir', $uploaddir);
            $upload->set('thumb_width', '480,296,168');
            $upload->set('thumb_height', '480,296,168');
            $upload->set('thumb_ext', '_max,_mid,_small');
            $upload->set('fprefix', $_SESSION['store_id']);
            $result = $upload->upfile($file);
            if($result) {
                $data['file_name'] = $upload->file_name;
                $data['origin_file_name'] = $_FILES[$file]['name'];
                $data['file_url'] = gthumb($upload->file_name, 'mid');
            } else {
                $data['result'] = false;
                $data['message'] = $upload->error;
            }
        } else {
            $data['result'] = false;
        }
        echo json_encode($data);die;
    }

    /**
     * 图片删除
     */
    private function _image_del($image_name) {
        list($base_name, $ext) = explode(".", $image_name);
        $base_name = str_replace('/', '', $base_name);
        $base_name = str_replace('.', '', $base_name);
        list($store_id) = explode('_', $base_name);
        $image_path = BASE_UPLOAD_PATH.DS.ATTACH_ROBBUY.DS.$store_id.DS;
        $image = $image_path.$base_name.'.'.$ext;
        $image_small = $image_path.$base_name.'_small.'.$ext;
        $image_mid = $image_path.$base_name.'_mid.'.$ext;
        $image_max = $image_path.$base_name.'_max.'.$ext;
        @unlink($image);
        @unlink($image_small);
        @unlink($image_mid);
        @unlink($image_max);
    }

    /**
     * 选择活动商品
     **/
    public function search_goodsWt() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsCommonList($condition, '*', 8);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_robbuy.goods', 'null_layout');
    }

    /**
     * 添加虚拟抢购页面
     */
    public function robbuy_add_vrWt()
    {
        $model_robbuy_quota = Model('robbuy_quota');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_robbuy_quota = $model_robbuy_quota->getRobbuyQuotaCurrent($_SESSION['store_id']);
            if(empty($current_robbuy_quota)) {
                showMessage('当前没有可用套餐，请先购买套餐', urlShop('store_robbuy', 'robbuy_quota_add'), '', 'error');
            }
            Tpl::output('current_robbuy_quota', $current_robbuy_quota);
        }

        // 根据后台设置的审核期重新设置抢购开始时间
        Tpl::output('robbuy_start_time', TIMESTAMP + intval(C('robbuy_review_day')) * 86400);

        // 虚拟抢购分类
        // Tpl::output('robbuy_vr_classes', Model('robbuy')->getRobbuyVrClasses());
        $model_vr_robbuy_class = Model('vr_robbuy_class');
        $classlist = $model_vr_robbuy_class->getVrRobbuyClassList(array('parent_class_id'=>0));
        Tpl::output('classlist', $classlist);

        // 虚拟区域分类
        // Tpl::output('robbuy_vr_cities', Model('robbuy')->getRobbuyVrCities());
        $model_vr_robbuy_area = Model('vr_robbuy_area');
        $arealist = $model_vr_robbuy_area->getVrRobbuyAreaList(array('parent_area_id'=>0,'hot_city'=>1),'','100');
        Tpl::output('arealist', $arealist);

        self::profile_menu('robbuy_add_vr');
        Tpl::showpage('store_robbuy.add_vr');
    }

    public function ajax_vr_classWt()
    {
        $class_id = intval($_GET['class_id']);
        if (empty($class_id)) {
            exit('false');
        }

        $condition = array();
        $condition['parent_class_id'] = $class_id;

        $model_vr_robbuy_class = Model('vr_robbuy_class');
        $class_list = $model_vr_robbuy_class->getVrRobbuyClassList($condition);

        if (!empty($class_list)) {
            echo json_encode($class_list);
        } else {
            echo 'false';
        }

        exit;
    }

    public function ajax_vr_areaWt()
    {
        $area_id = intval($_GET['area_id']);
        if (empty($area_id)) {
            exit('false');
        }

        $condition = array();
        $condition['parent_area_id'] = $area_id;

        $model_vr_robbuy_area = Model('vr_robbuy_area');
        $area_list = $model_vr_robbuy_area->getVrRobbuyAreaList($condition);

        if (!empty($area_list)) {
            echo json_encode($area_list);
        } else {
            echo 'false';
        }

        exit;
    }

    /**
     * 选择活动虚拟商品
     */
    public function search_vr_goodsWt()
    {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getVrGoodsCommonList($condition, '*', 8);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_robbuy.goods', 'null_layout');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'robbuy_list','menu_name'=>L('wt_member_path_group_list'),'menu_url'=>urlShop('store_robbuy', 'robbuy_list'))
        );
        switch ($menu_key){
        case 'robbuy_add':
            $menu_array[] = array('menu_key'=>'robbuy_add','menu_name'=>L('wt_member_path_new_group'),'menu_url'=>'index.php?w=store_robbuy&t=robbuy_add');
            break;
        case 'robbuy_add_vr':
            $menu_array[] = array('menu_key'=>'robbuy_add_vr','menu_name'=>'新增虚拟抢购','menu_url'=>'index.php?w=store_robbuy&t=robbuy_add_vr');
            break;
        case 'robbuy_quota_add':
            $menu_array[] = array('menu_key'=>'robbuy_quota_add','menu_name'=>'购买套餐','menu_url'=>urlShop('store_robbuy', 'robbuy_quota_add'));
            break;
        case 'robbuy_edit':
            $menu_array[] = array('menu_key'=>'robbuy_edit','menu_name'=>L('wt_member_path_edit_group'),'menu_url'=>'index.php?w=store_robbuy');
            break;
        case 'cancel':
            $menu_array[] = array('menu_key'=>'robbuy_cancel','menu_name'=>L('wt_member_path_cancel_group'));
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
