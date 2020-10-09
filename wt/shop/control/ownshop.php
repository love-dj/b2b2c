<?php
/**
 * 自营店铺 6.5
 *

 
 
 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class ownshopControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexWt()
    {
        $this->listWt();
    }

    public function listWt()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('ownshop.list'); 
    }

    public function addWt()
    {
        $count = Model('store')->getStoreCount(array());
        if ($count >= C('store_limit') && C('store_limit') != 0) {
            showMessage('开店数量超过允许开店最大值，不能继续申请', 'index.php?w=ownshop&t=list', 'html', 'error');
        }
        if (chksubmit())
        {
            $memberName = $_POST['member_name'];
            $memberPasswd = (string) $_POST['member_passwd'];

            if (strlen($memberName) < 3 || strlen($memberName) > 15
                || strlen($_POST['seller_name']) < 3 || strlen($_POST['seller_name']) > 15)
                showMessage('账号名称必须是3~15位', '', 'html', 'error');

            if (strlen($memberPasswd) < 6)
                showMessage('登录密码不能短于6位', '', 'html', 'error');

            if (!$this->checkMemberName($memberName))
                showMessage('店主账号已被占用', '', 'html', 'error');

            if (!$this->checkSellerName($_POST['seller_name']))
                showMessage('店主商家账号名称已被其它店铺占用', '', 'html', 'error');

            try
            {
                $memberId = model('member')->addMember(array(
                    'member_name' => $memberName,
                    'member_passwd' => $memberPasswd,
                    'member_email' => '',
                ));
            }
            catch (Exception $ex)
            {
                showMessage('店主账号新增失败', '', 'html', 'error');
            }

            $storeModel = model('store');

            $saveArray = array();
            $saveArray['store_name'] = $_POST['store_name'];
            $saveArray['grade_id'] = 0;
            $saveArray['member_id'] = $memberId;
            $saveArray['member_name'] = $memberName;
            $saveArray['seller_name'] = $_POST['seller_name'];
            $saveArray['bind_all_gc'] = 1;
            $saveArray['store_state'] = 1;
            $saveArray['store_time'] = time();
            $saveArray['is_own_shop'] = 1;

            $storeId = $storeModel->addStore($saveArray);

            model('seller')->addSeller(array(
                'seller_name' => $_POST['seller_name'],
                'member_id' => $memberId,
                'store_id' => $storeId,
                'seller_group_id' => 0,
                'is_admin' => 1,
            ));

            // 添加相册默认
            $album_model = Model('album');
            $album_arr = array();
            $album_arr['aclass_name'] = '默认相册';
            $album_arr['store_id'] = $storeId;
            $album_arr['aclass_des'] = '';
            $album_arr['aclass_sort'] = '255';
            $album_arr['aclass_cover'] = '';
            $album_arr['upload_time'] = time();
            $album_arr['is_default'] = '1';
            $album_model->addClass($album_arr);

            //插入店铺扩展表
            $model = Model();
            $model->table('store_extend')->insert(array('store_id'=>$storeId));

            // 删除自营店id缓存
            Model('store')->dropCachedOwnShopIds();

            $this->log("新增自营店铺: {$saveArray['store_name']}");
            showMessage('操作成功', urlAdminShop('ownshop', 'list'));
            return;
        }
Tpl::setDirquna('shop');
        Tpl::showpage('ownshop.add');
    }

    public function delWt() {
        $id = intval($_GET['id']);
        if ($id <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
        $model_store = Model('store');

        $store_info = $model_store->getStoreInfoByID($id);
        if (empty($store_info)) {
            exit(json_encode(array('state'=>false,'msg'=>'自营店铺不存在')));
        }

        if (!$store_info['is_own_shop']) {
            exit(json_encode(array('state'=>false,'msg'=>'不能在此删除非自营店铺')));
        }

        $condition = array('store_id' => $id);

        $count = Model('goods')->getGoodsCount($condition);
        if ($count > 0) {
            exit(json_encode(array('state'=>false,'msg'=>'已经发布商品的自营店铺不能被删除')));
        }

        // 完全删除店铺
        $model_store->delStoreEntirely($condition);

        // 删除自营店id缓存
        $model_store->dropCachedOwnShopIds();

        $this->log("删除自营店铺: " . $store_info['store_name']);
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }

    public function editWt()
    {
        $storeModel = model('store');

        $storeArray = $storeModel->where(array('store_id'=>$_GET['id']))->find();

        if (!$storeArray['is_own_shop']) {
            showMessage('不能在此管理非自营店铺', '', 'html', 'error');
        }

        if (chksubmit()) {
            if (!$this->checkSellerName($_POST['seller_name'], $_GET['id']))
                showMessage('店主商家账号名称已被其它店铺占用', '', 'html', 'error');

            $saveArray = array();
            $saveArray['store_name'] = $_POST['store_name'];
            $saveArray['seller_name'] = $_POST['seller_name'];
            $saveArray['bind_all_gc'] = $_POST['bind_all_gc'] ? 1 : 0;
            $saveArray['store_state'] = $_POST['store_state'] ? 1 : 0;
            $saveArray['store_close_info'] = $_POST['store_close_info'];
            $province_id = intval($_POST['province_id']);
            if ($province_id > 0) {
                $saveArray['province_id'] = $province_id;
            }
            $saveArray['area_info']    = $_POST['region'];
            $saveArray['store_company_name']    = $_POST['store_company_name'];
            $saveArray['store_address']    = $_POST['store_address'];

            $storeModel->editStore($saveArray, array(
                'store_id' => $_GET['id'],
            ));

            if ($saveArray['seller_name'] != $storeArray['seller_name']) {
                model('seller')->editSeller(array(
                    'seller_name' => $_POST['seller_name'],
                ), array(
                    'store_id' => $_GET['id'],
                    'seller_group_id' => 0,
                    'is_admin' => 1,
                ));
            }

            /* 自营店不下架商品

            if ($storeArray['bind_all_gc'] == '1' && $saveArray['bind_all_gc'] == '0' && $_POST['offshelf'] == '1') {
                // 全部商品下架
                Model('goods')->editProducesLockUp(array(
                    'goods_stateremark' => '管理员编辑经营类目',
                ), array(
                    'store_id' => $_GET['id'],
                ));
            }

            */

            // 删除自营店id缓存
            Model('store')->dropCachedOwnShopIds();

            $this->log("编辑自营店铺: {$saveArray['store_name']}");
            showMessage('操作成功', urlAdminShop('ownshop', 'list'));
        }

        if (empty($storeArray))
            showMessage('店铺不存在', '', 'html', 'error');

        Tpl::output('store_array', $storeArray);
		Tpl::setDirquna('shop');
        Tpl::showpage('ownshop.edit');
    }

    public function check_seller_nameWt()
    {
        echo json_encode($this->checkSellerName($_GET['seller_name'], $_GET['id']));
        exit;
    }

    private function checkSellerName($sellerName, $storeId = 0)
    {
        // 判断store_joinin是否存在记录
        $count = (int) Model('store_joinin')->getStoreJoininCount(array(
            'seller_name' => $sellerName,
        ));
        if ($count > 0)
            return false;

        $seller = Model('seller')->getSellerInfo(array(
            'seller_name' => $sellerName,
        ));

        if (empty($seller))
            return true;

        if (!$storeId)
            return false;

        if ($storeId == $seller['store_id'] && $seller['seller_group_id'] == 0 && $seller['is_admin'] == 1)
            return true;

        return false;
    }

    public function check_member_nameWt()
    {
        echo json_encode($this->checkMemberName($_GET['member_name']));
        exit;
    }

    private function checkMemberName($memberName)
    {
        // 判断store_joinin是否存在记录
        $count = (int) Model('store_joinin')->getStoreJoininCount(array(
            'member_name' => $memberName,
        ));
        if ($count > 0)
            return false;

        return ! Model('member')->getMemberCount(array(
            'member_name' => $memberName,
        ));
    }

    public function bind_classWt()
    {
        //批量删除分类
        if (chksubmit()) {
            if (!empty($_POST['bid'])) {
                foreach ($_POST['bid'] as $bid) {
                    $this->_bind_class_del($bid);
                }
            }
        }
        $store_id = intval($_GET['id']);

        $model_store = Model('store');
        $model_store_bind_class = Model('store_bind_class');
        $model_goods_class = Model('goods_class');

        $gc_list = $model_goods_class->getGoodsClassListByParentId(0);
        Tpl::output('gc_list',$gc_list);

        $store_info = $model_store->getStoreInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('param_error'),'','','error');
        }
        Tpl::output('store_info', $store_info);

        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList(array('store_id'=>$store_id), 30);

        $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();

        for($i = 0, $j = count($store_bind_class_list); $i < $j; $i++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);
        Tpl::output('showpage',$model_store_bind_class->showpage());
Tpl::setDirquna('shop');
        Tpl::showpage('ownshop.bind_class');
    }

    /**
     * 添加经营类目
     */
    public function bind_class_addWt()
    {
        $store_id = intval($_POST['store_id']);
        $commis_rate = intval($_POST['commis_rate']);
        if($commis_rate < 0 || $commis_rate > 100) {
            showMessage(L('param_error'), '');
        }
        list($class_1, $class_2, $class_3) = explode(',', $_POST['goods_class']);
        $model_store_bind_class = Model('store_bind_class');
        $model_goods_class = Model('goods_class');

        $param = array();
        $param['store_id'] = $store_id;
        $param['class_1'] = $class_1;
        $param['state'] = 2;
        $param['commis_rate'] = $commis_rate;

        if (empty($class_2)) {
            //如果没选 二级
            $class_2_list = $model_goods_class->getGoodsClassList(array('gc_parent_id'=>$class_1));
            if (!empty($class_2_list)) {
                foreach ($class_2_list as $class_2_info) {
                    $class_3_list = $model_goods_class->getGoodsClassList(array('gc_parent_id'=>$class_2_info['gc_id']));
                    if (!empty($class_3_list)) {
                        $param['class_2'] = $class_2_info['gc_id'];
                        foreach ($class_3_list as $class_3_info) {
                            $param['class_3'] = $class_3_info['gc_id'];
                            $result = $this->_add_bind_class($param);
                        }
                    }
                }
            } else {
                //只有一级分类
                $param['class_2'] = $param['class_3'] = 0;
                $result = $this->_add_bind_class($param);
            }
        } else if (empty($class_3)) {
            //如果没选二没选三级
            $param['class_2'] = $class_2;
            $class_3_list = $model_goods_class->getGoodsClassList(array('gc_parent_id'=>$class_2));
            if (!empty($class_3_list)) {
                foreach ($class_3_list as $class_3_info) {
                    $param['class_3'] = $class_3_info['gc_id'];
                    // 检查类目是否已经存在
                    $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
                    if(empty($store_bind_class_info)) {
                        $result = $this->_add_bind_class($param);
                    }
                }
            } else {
                //二级就是最后一级
                $param['class_3'] = 0;
                $result = $this->_add_bind_class($param);
            }
        } else {
            $param['class_2'] = $class_2;
            $param['class_3'] = $class_3;
            $result = $this->_add_bind_class($param);
        }

        if($result) {
            // 删除自营店id缓存
            Model('store')->dropCachedOwnShopIds();

            $this->log('增加自营店铺经营类目，类目编号:'.$result.',店铺编号:'.$store_id);
            showMessage(L('wt_common_save_succ'), '');
        } else {
            showMessage(L('wt_common_save_fail'), '');
        }
    }

    private function _add_bind_class($param) {
        $model_store_bind_class = Model('store_bind_class');
        // 检查类目是否已经存在
        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
        if(!empty($store_bind_class_info)) return true;
        return $model_store_bind_class->addStoreBindClass($param);
    }

    /**
     * 删除经营类目
     */
    public function bind_class_delWt()
    {
        $bid = intval($_POST['bid']);
        $result = $this->_bind_class_del($bid);
        echo json_encode($result);
    }

    private function _bind_class_del($bid)
    {

        $data = array();
        $data['result'] = true;

        $model_store_bind_class = Model('store_bind_class');
        $model_goods = Model('goods');

        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo(array('bid' => $bid));
        if(empty($store_bind_class_info)) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
            return $data;
        }

        $result = $model_store_bind_class->delStoreBindClass(array('bid'=>$bid));

        if(!$result) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
        }

        // 删除自营店id缓存
        Model('store')->dropCachedOwnShopIds();

        $this->log('删除自营店铺经营类目，类目编号:'.$bid.',店铺编号:'.$store_bind_class_info['store_id']);
        return $data;
    }

    public function bind_class_updateWt()
    {
        $bid = intval($_GET['id']);
        if($bid <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_commis_rate = intval($_GET['value']);
        if ($new_commis_rate < 0 || $new_commis_rate >= 100) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        } else {
            $update = array('commis_rate' => $new_commis_rate);
            $condition = array('bid' => $bid);
            $model_store_bind_class = Model('store_bind_class');
            $result = $model_store_bind_class->editStoreBindClass($update, $condition);
            if($result) {
                // 删除自营店id缓存
                Model('store')->dropCachedOwnShopIds();

                $this->log('更新自营店铺经营类目，类目编号:'.$bid);
                echo json_encode(array('result'=>TRUE));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>L('wt_common_op_fail')));
                die;
            }
        }
    }

    /**
     * 验证店铺名称是否存在
     */
    public function ckeck_store_nameWt() {
        /**
         * 实例化商家模型
         */
        $where = array();
        $where['store_name'] = $_GET['store_name'];
        if (isset($_GET['store_id'])) {
            $where['store_id'] = array('neq', $_GET['store_id']);
        }
        $store_info = Model('store')->getStoreInfo($where);
        if(!empty($store_info['store_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
        $model_store = Model('store');
        // 设置页码参数名称
        $condition = array();
        $condition['is_own_shop'] = 1;
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_list = $model_store->getStoreList($condition, $page, $order);
        $store_ids = array();
        foreach ($store_list as $s) {
            $store_ids[$s['store_id']] = null;
        }

        $store_ids = array_keys($store_ids);
        $stores_with_goods = model('goods')->where(array('store_id' => array('in', $store_ids)))->field('distinct store_id')->key('store_id')->select();

        $data = array();
        $data['now_page'] = $model_store->shownowpage();
        $data['total_num'] = $model_store->gettotalnum();
        $link = $_SERVER['HTTP_HOST'];
        foreach ($store_list as $value) {
            $param = array();
            $pass=Model("member")->where("member_id = ".$value["member_id"])->field("member_passwd")->find();
            $operation = "<a class='btn blue' href='index.php?w=ownshop&t=edit&id=".$value['store_id']."'><i class='fa fa-pencil-square-o'></i>编辑</a><a class='btn blue' href='../../index.php?w=seller_login&t=login_store&name=".$value["seller_name"]."&pass=".$pass["member_passwd"]."' target='_blank'>登录</a>";
            if ($value['bind_all_gc'] != 1 || empty($stores_with_goods[$value['store_id']])) {
                $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('wt_set') . " <i class='arrow'></i></em><ul>";
                if ($value['bind_all_gc'] != 1) {
                    $operation .= "<li><a href='index.php?w=ownshop&t=bind_class&id=" . $value['store_id'] . "'>编辑经营类目</a></li>";
                }
                if (empty($stores_with_goods[$value['store_id']])) {
                    $operation .= "<li><a href=\"javascript:void(0);\" onclick=\"fg_delete('".$value['store_id']."');\">删除自营店铺</a></li>";
                }
                $operation .= "</ul></span>";
            }
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['member_name'] = $value['member_name'];
            $param['seller_name'] = $value['seller_name'];
            $param['store_state'] = $value['store_state']?L('open'):L('close');
            $param['bind_all_gc'] = $value['bind_all_gc']?"是":"否";
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
