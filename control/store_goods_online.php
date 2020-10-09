<?php
/**
 * 商品管理
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit ('Access Denied By ShopWT');
class store_goods_onlineControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct ();
        Language::read ('member_store_goods_index');
    }
    public function indexWt() {
        $this->goods_listWt();
    }

    /**
     * 出售中的商品列表
     */
    public function goods_listWt() {
        $model_goods = Model('goods');

        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
        }
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['goods_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['goods_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        if (intval($_GET['sup_id']) > 0) {
            $where['sup_id']= intval($_GET['sup_id']);
        }

        //权限组对应分类权限判断
        if (!$_SESSION['seller_gc_limits'] && $_SESSION['seller_group_id']) {
            $gc_list = Model('seller_group_bclass')->getSellerGroupBclasList(array('group_id'=>$_SESSION['seller_group_id']),'','','gc_id','gc_id');
            $where['gc_id'] = array('in',array_keys($gc_list));
        }
        $goods_list = $model_goods->getGoodsCommonOnlineList($where);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::output('goods_list', $goods_list);

        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);

        // 商品分类
        $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $_SESSION['store_id'], 'stc_state' => '1'));
        Tpl::output('store_goods_class', $store_goods_class);

        // 供货商
        $supplier_list = Model('store_supplier')->getStoreSupplierList(array('sup_store_id' => $_SESSION['store_id']));
        Tpl::output('supplier_list', $supplier_list);

        $this->profile_menu('goods_list', 'goods_list');
        Tpl::showpage('store_goods_list.online');
    }

    /**
     * 编辑商品页面
     */
    public function edit_goodsWt() {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $_SESSION['store_id'] || $goodscommon_info['goods_lock'] == 1) {
            showMessage(L('wrong_argument'), '', 'html', 'error');
        }

        //权限组对应分类权限判断
        if (!$_SESSION['seller_gc_limits'] && $_SESSION['seller_group_id']) {
            $gc_list = Model('seller_group_bclass')->getSellerGroupBclasList(array('group_id'=>$_SESSION['seller_group_id']),'','','gc_id','gc_id');
            if (!in_array($goodscommon_info['gc_id'],array_keys($gc_list))) {
                showMessage('您所在的组无权操作该分类下的商品','', 'html', 'error');
            }
        }
        $where = array('goods_commonid' => $common_id, 'store_id' => $_SESSION['store_id']);
        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);
        $goodscommon_info['goods_custom'] = unserialize($goodscommon_info['goods_custom']);
        if ($goodscommon_info['mobile_body'] != '') {
            $goodscommon_info['mb_body'] = unserialize($goodscommon_info['mobile_body']);
            if (is_array($goodscommon_info['mb_body'])) {
                $mobile_body = '[';
                foreach ($goodscommon_info['mb_body'] as $val ) {
                    $mobile_body .= '{"type":"' . $val['type'] . '","value":"' . $val['value'] . '"},';
                }
                $mobile_body = rtrim($mobile_body, ',') . ']';
            }
            $goodscommon_info['mobile_body'] = $mobile_body;
        }

        //反序列化三级分销佣金
        $level_commission = array();
        if($goodscommon_info['is_independent_bonus'] == 1 && $goodscommon_info['level_commission'] != ''){
            $level_commission = unserialize($goodscommon_info['level_commission']);
        }

        Tpl::output('levelcommission', $level_commission);
        Tpl::output('goods', $goodscommon_info);
        $transport = array();
        $transport_id = intval($goodscommon_info['transport_id']);
        if ($transport_id > 0) {
            $model_transport = Model('transport');
            $transport = $model_transport->getTransportInfo(array('id'=> $transport_id));
        }
        Tpl::output('transport',$transport);

        if (intval($_GET['class_id']) > 0) {
            $goodscommon_info['gc_id'] = intval($_GET['class_id']);
        }
        $goods_class = Model('goods_class')->getGoodsClassLineForTag($goodscommon_info['gc_id']);
        Tpl::output('goods_class', $goods_class);

        $model_type = Model('type');
        // 获取类型相关数据
        $typeinfo = $model_type->getAttr($goods_class['type_id'], $_SESSION['store_id'], $goodscommon_info['gc_id']);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        Tpl::output('spec_json', $spec_json);
        Tpl::output('sign_i', count($spec_list));
        Tpl::output('spec_list', $spec_list);
        Tpl::output('attr_list', $attr_list);
        Tpl::output('brand_list', $brand_list);
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        $custom_list = array_under_reset($custom_list, 'custom_id');
        Tpl::output('custom_list', $custom_list);

        // 取得商品规格的输入值
        $goods_array = $model_goods->getGoodsList($where, 'goods_id,goods_marketprice,goods_price,goods_storage,goods_serial,goods_storage_alarm,goods_spec,goods_barcode');
		//提取阶梯价格 v6.5
		$step_prices = $model_goods->getGoodStepPrice(array('common_id'=>$goods_array[0]['goods_id']));
		Tpl::output('step_prices', $step_prices);
        $sp_value = array();
        if (is_array($goods_array) && !empty($goods_array)) {

            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList ( 'goods_attr_index', array (
                    'goods_id' => intval ( $goods_array[0]['goods_id'] )
            ), 'attr_value_id' );
            if (is_array ( $attr_checked_l ) && ! empty ( $attr_checked_l )) {
                $attr_checked = array ();
                foreach ( $attr_checked_l as $val ) {
                    $attr_checked [] = $val ['attr_value_id'];
                }
            }
            Tpl::output ( 'attr_checked', $attr_checked );

            $spec_checked = array();
            foreach ( $goods_array as $k => $v ) {
                $a = unserialize($v['goods_spec']);
                if (!empty($a)) {
                    foreach ($a as $key => $val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    $id = str_replace ( ',', '', implode ( ',', $matchs ) );
                    $sp_value ['i_' . $id . '|marketprice'] = $v['goods_marketprice'];
                    $sp_value ['i_' . $id . '|price'] = $v['goods_price'];
                    $sp_value ['i_' . $id . '|id'] = $v['goods_id'];
                    $sp_value ['i_' . $id . '|stock'] = $v['goods_storage'];
                    $sp_value ['i_' . $id . '|alarm'] = $v['goods_storage_alarm'];
                    $sp_value ['i_' . $id . '|sku'] = $v['goods_serial'];
                    $sp_value ['i_' . $id . '|barcode'] = $v['goods_barcode'];
                }
            }
            Tpl::output('spec_checked', $spec_checked);
        }
        Tpl::output ( 'sp_value', $sp_value );

        // 实例化店铺商品分类模型
        $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $_SESSION ['store_id'], 'stc_state' => '1'));
        Tpl::output('store_goods_class', $store_goods_class);
        //处理商品所属分类
        $store_goods_class_tmp = array();
        if (!empty($store_goods_class)){
            foreach ($store_goods_class as $k=>$v) {
                $store_goods_class_tmp[$v['stc_id']] = $v;
                if (is_array($v['child'])) {
                    foreach ($v['child'] as $son_k=>$son_v){
                        $store_goods_class_tmp[$son_v['stc_id']] = $son_v;
                    }
                }
            }
        }
        $goodscommon_info['goods_stcids'] = trim($goodscommon_info['goods_stcids'], ',');
        $goods_stcids = empty($goodscommon_info['goods_stcids'])?array():explode(',', $goodscommon_info['goods_stcids']);
        $goods_stcids_tmp = $goods_stcids_new = array();
        if (!empty($goods_stcids)){
            foreach ($goods_stcids as $k=>$v){
                $stc_parent_id = $store_goods_class_tmp[$v]['stc_parent_id'];
                //分类进行分组，构造为array('1'=>array(5,6,8));
                if ($stc_parent_id > 0){//如果为二级分类，则分组到父级分类下
                    $goods_stcids_tmp[$stc_parent_id][] = $v;
                } elseif (empty($goods_stcids_tmp[$v])) {//如果为一级分类而且分组不存在，则建立一个空分组数组
                    $goods_stcids_tmp[$v] = array();
                }
            }
            foreach ($goods_stcids_tmp as $k=>$v){
                if (!empty($v) && count($v) > 0){
                    $goods_stcids_new = array_merge($goods_stcids_new,$v);
                } else {
                    $goods_stcids_new[] = $k;
                }
            }
        }
        Tpl::output('store_class_goods', $goods_stcids_new);

        // 是否能使用编辑器
        if(checkPlatformStore()){ // 平台店铺可以使用编辑器
            $editor_multimedia = true;
        } else {    // 三方店铺需要
            $editor_multimedia = false;
            if ($this->store_grade['sg_function'] == 'editor_multimedia') {
                $editor_multimedia = true;
            }
        }
        Tpl::output ( 'editor_multimedia', $editor_multimedia );

        // 小时分钟显示
        $hour_array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        Tpl::output('hour_array', $hour_array);
        $minute_array = array('05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');
        Tpl::output('minute_array', $minute_array);

        // 关联版式
        $plate_list = Model('store_plate')->getStorePlateList(array('store_id' => $_SESSION['store_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);

        // 供货商
        $supplier_list = Model('store_supplier')->getStoreSupplierList(array('sup_store_id' => $_SESSION['store_id']));
        Tpl::output('supplier_list', $supplier_list);

        $menu_sale = array(
            'lock' => $goodscommon_info['goods_lock'] == 1 ? true : false,
            'gift' => $goodscommon_info['is_virtual'] == 1 ? false : true
        );
        $this->profile_menu('edit_detail','edit_detail', $menu_sale);
        Tpl::output('edit_goods_sign', true);
		Tpl::output('bat_max_num', "3");//设置最大阶梯数量 v6.5

        // 分销等级        
        $level_list = Model('distribution_level')->order("level_weight asc")->select();
        Tpl::output('level_list', $level_list);
        $distribution_isuse = Model('setting')->getRowSetting('distribution_isuse');
        Tpl::output('distribution_isuse', $distribution_isuse);
		
		//单品消费返现
        $buy_return_isuse = Model('setting')->getRowSetting('buy_return_isuse');
        Tpl::output('buy_return_isuse', $buy_return_isuse);

        Tpl::showpage('store_goods_add.step2');
    }

    /**
     * 编辑商品保存
     */
    public function edit_save_goodsWt() {
        $handle_goods = Handle('goods');
		$gc_id = intval($_POST['cate_id']);

		// 三方店铺验证是否绑定了该分类
        if (!checkPlatformStore()) {
            //商品分类 by shopwt. com 提供批量显示所有分类插件
            $model_bind_class = Model('store_bind_class');
            $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
            $where['store_id'] = $_SESSION['store_id'];
            $class_2 = $goods_class[$gc_id]['gc_parent_id'];
            $class_1 = $goods_class[$class_2]['gc_parent_id'];
            $where['class_1'] =  $class_1;
            $where['class_2'] =  $class_2;
            $where['class_3'] =  $gc_id;
            $bind_info = $model_bind_class->getStoreBindClassInfo($where);
            if (empty($bind_info))
            {
                $where['class_3'] =  0;
                //修改不为三级分类时的操作
                $where['class_2'] =  $gc_id;
                $where['class_1'] =  $goods_class[$gc_id]['gc_parent_id'];
                $bind_info = $model_bind_class->getStoreBindClassInfo($where);
                if (empty($bind_info))
                {
                    $where['class_2'] =  0;
                    $where['class_3'] =  0;
                    $where['class_1'] =  $gc_id;
                    $bind_info = $model_bind_class->getStoreBindClassInfo($where);
                    if (empty($bind_info))
                    {
                        $where['class_1'] =  0;
                        $where['class_2'] =  0;
                        $where['class_3'] =  0;
                        $bind_info = Model('store_bind_class')->getStoreBindClassInfo($where);
                        if (empty($bind_info))
                        {
                            showDialog(L('store_goods_index_again_choose_category2'));
                        }
                    }

                }

            }
        }
	
    	if(!empty($_FILES['goods_video_file']['name'])){
            //获取视频数据，提交时候保存视频
            $datas = $this->_getGoodsVideo($_FILES);
            if($datas['status'] == 'success'){
                $_POST['goods_video'] = $datas['goods_video'];
            }elseif($datas['status'] == 'fail'){
                showDialog($datas['error'],'','error');
            }
        }else{
            $_POST['goods_video'] = $_POST['video_file'];
        }

        //设置商品独立三级分销佣金
        if(intval($_POST['is_independent_bonus']) == 1){//是否开启独立佣金 1开启 否则禁用
            $level_commission = $_POST['level_commission'];             
            $_POST['level_commission'] = serialize($level_commission);                                    
        }

        $result =  $handle_goods->updateGoods(
            $_POST,
            $_SESSION['store_id'], 
            $_SESSION['store_name'], 
            $this->store_info['store_state'], 
            $_SESSION['seller_id'], 
            $_SESSION['seller_name'],
            $_SESSION['bind_all_gc']
        );
        
        if ($result['state']) {
            //提交事务
            showDialog(L('wt_common_op_succ'), $_POST['ref_url'], 'succ');
        } else {
            //回滚事务
            showDialog($result['msg'], urlShop('store_goods_online', 'index'));
        }
    }

    //获取视频数据,加入店铺的专辑内
    public function _getGoodsVideo($file){
        $data = array();
        $store_id = $_SESSION['store_id'];
        if (!empty($file['goods_video_file']['name'])) {
            $upload = new UploadVideoFile();
            $upload->set('default_dir', ATTACH_GOODS . DS . $store_id . DS . 'goods_video'   );
            $upload->set('fprefix', $store_id);
            $upload->set('max_size' , 10240);
            $result = $upload->upfile('goods_video_file',true);
            if ($result) {
                $data['status'] = 'success';
                $data['goods_video'] = $upload->file_name;
            } else {
                $data['status'] = 'fail';
                $data['error'] = $upload->error;
            }
        }

        // 判断图片数量是否超限
        $model_album = Model('video_album');
        $album_limit = $this->store_grade['sg_album_limit'];
        if ($album_limit > 0) {
            $album_count = $model_album->getCount(array('store_id' => $store_id));
            if ($album_count >= $album_limit) {
                return callback(false, '您上传图片数达到上限，请升级您的店铺或跟管理员联系');
            }
        }

        $class_info = $model_album->getOne(array('store_id' => $store_id, 'is_default' => 1), 'video_album_class');

        $video_path = $upload->file_name;

        // 存入视频
        $video = explode('.', $_FILES["goods_video_file"]["name"]);
        $insert_array = array();
        $insert_array['video_name'] = $video['0'];
        $insert_array['video_tag'] = '';
        $insert_array['video_class_id'] = $class_info['video_class_id'];
        $insert_array['video_cover'] = $video_path;
        $insert_array['video_size'] = intval($_FILES['goods_video_file']['size']);
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['store_id'] = $store_id;
        Model('upload_video_album')->add($insert_array);

        return $data;
    }
    /**
     * 编辑图片
     */
    public function edit_imageWt() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlShop('seller_center'), 'html', 'error');
        }
        $model_goods = Model('goods');
        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'store_id,goods_lock,spec_value,is_virtual,is_fcode,is_presell');
        if ($common_list['store_id'] != $_SESSION['store_id'] || $common_list['goods_lock'] == 1) {
            showMessage(L('wrong_argument'), urlShop('seller_center'), 'html', 'error');
        }

        $spec_value = unserialize($common_list['spec_value']);
        Tpl::output('value', $spec_value['1']);

        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id));
        $image_list = array_under_reset($image_list, 'color_id', 2);

        $img_array = $model_goods->getGoodsList(array('goods_commonid' => $common_id), 'color_id,min(goods_image) as goods_image', 'color_id');
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            foreach ($img_array as $val) {
                if (isset($image_list[$val['color_id']])) {
                    $image_array[$val['color_id']] = $image_list[$val['color_id']];
                } else {
                    $image_array[$val['color_id']][0]['goods_image'] = $val['goods_image'];
                    $image_array[$val['color_id']][0]['is_default'] = 1;
                }
                $colorid_array[] = $val['color_id'];
            }
        }
        Tpl::output('img', $image_array);


        $model_spec = Model('spec');
        $value_array = $model_spec->getSpecValueList(array('sp_value_id' => array('in', $colorid_array), 'store_id' => $_SESSION['store_id']), 'sp_value_id,sp_value_name');
        if (empty($value_array)) {
            $value_array[] = array('sp_value_id' => '0', 'sp_value_name' => '无颜色');
        }
        Tpl::output('value_array', $value_array);

        Tpl::output('commonid', $common_id);

        $menu_sale = array(
                'lock' => $common_list['goods_lock'] == 1 ? true : false,
                'gift' => $model_goods->checkGoodsIfAllowGift($common_list)
        );
        $this->profile_menu('edit_detail', 'edit_image', $menu_sale);
        Tpl::output('edit_goods_sign', true);
        Tpl::showpage('store_goods_add.step3');
    }

    /**
     * 保存商品图片
     */
    public function edit_save_imageWt() {
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            $rs = Handle('goods')->editSaveImage($_POST['img'], $common_id, $_SESSION['store_id'], $_SESSION['seller_id'], $_SESSION['seller_name']);
            if ($rs['state']) {
                showDialog(L('wt_common_op_succ'), $_POST['ref_url'], 'succ');
            } else {
                showDialog(L('wt_common_save_fail'), urlShop('store_goods_online', 'index'));
            }
        }
    }

    /**
     * 编辑分类
     */
    public function edit_classWt() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id']);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);

        Tpl::output('commonid', $_GET['commonid']);
        $this->profile_menu('edit_class', 'edit_class');
        Tpl::output('edit_goods_sign', true);
        Tpl::showpage('store_goods_add.step1');
    }

    /**
     * 删除商品
     */
    public function drop_goodsWt() {
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        $commonid_array = explode(',', $common_id);
        $result = Handle('goods')->goodsDrop($commonid_array, $_SESSION['store_id'], $_SESSION['seller_id'], $_SESSION['seller_name']);
        if ($result['state']) {
            // 添加操作日志
            $this->recordSellerLog('删除商品，SPU：'.$common_id);
            showDialog(L('store_goods_index_goods_del_success'), 'reload', 'succ');
        } else {
            showDialog(L('store_goods_index_goods_del_fail'), '', 'error');
        }
    }
    /**
     * 批量更新商品二维码  V5.3
     */
    public function edit_qrcodeWt() {
        $common_id = $this->checkRequestCommonId($_GET['commonid']);

        $goods_array = Model()->table('goods')->where(array('goods_commonid'=>array('in',$common_id)))->field('goods_id')->select();
         $goods_array_list = array();
        foreach ($goods_array as &$value) {
            $url = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$_SESSION['store_id'].DS.$value.'.png';
            if(file_exists($url)){
                 @unlink($url);
            }
            $goods_array_list[] = $value['goods_id'];

           
        }
        
        // 生成商品二维码
        if (!empty($goods_array_list)) {
           $retrun = QueueClient::push('createGoodsQRCode', array('store_id' => $_SESSION['store_id'], 'goodsid_array' => $goods_array_list));
           $this->recordSellerLog('批量更新商品二维码，SPU：'.$common_id);
           showDialog("批量更新商品二维码成功", 'reload', 'succ');
           
        }else{
            showDialog('批量更新商品二维码失败', '', 'error');
        }

    }
    /**
     * 商品下架
     */
    public function goods_unshowWt() {
        $common_id = $this->checkRequestCommonId($_GET['commonid']);
        $commonid_array = explode(',', $common_id);
        $result = Handle('goods')->goodsUnShow($commonid_array, $this->store_info['store_id'], $_SESSION['seller_id'], $_SESSION['seller_name']);
        if ($result['state']) {
            showDialog(L('store_goods_index_goods_unshow_success'), getReferer() ? getReferer() : 'index.php?w=store_goods_online&t=goods_list', 'succ', '', 2);
        } else {
            showDialog(L('store_goods_index_goods_unshow_fail'), '', 'error');
        }
    }

    /**
     * 设置广告词
     */
    public function edit_jingleWt() {
        if (chksubmit()) {
            $common_id = $this->checkRequestCommonId($_POST['commonid']);
            $commonid_array = explode(',', $common_id);
            $where = array('goods_commonid' => array('in', $commonid_array), 'store_id' => $_SESSION['store_id']);
            $update = array('goods_jingle' => trim($_POST['g_jingle']));
            $return = Model('goods')->editProducesNoLock($where, $update);
            if ($return) {
                // 添加操作日志
                $this->recordSellerLog('设置广告词，SPU：'.$common_id);
                showDialog(L('wt_common_op_succ'), 'reload', 'succ');
            } else {
                showDialog(L('wt_common_op_fail'), 'reload');
            }
        }
        $common_id = $this->checkRequestCommonId($_GET['commonid']);

        Tpl::showpage('store_goods_list.edit_jingle', 'null_layout');
    }

    /**
     * 设置关联版式
     */
    public function edit_plateWt() {
        if (chksubmit()) {
            $common_id = $this->checkRequestCommonId($_POST['commonid']);
            $commonid_array = explode(',', $common_id);
            $where = array('goods_commonid' => array('in', $commonid_array), 'store_id' => $_SESSION['store_id']);
            $update = array();
            $update['plateid_top']        = intval($_POST['plate_top']) > 0 ? intval($_POST['plate_top']) : '';
            $update['plateid_bottom']     = intval($_POST['plate_bottom']) > 0 ? intval($_POST['plate_bottom']) : '';
            $return = Model('goods')->editGoodsCommon($update, $where);
            if ($return) {
                // 添加操作日志
                $this->recordSellerLog('设置关联版式，SPU：'.$common_id);
                showDialog(L('wt_common_op_succ'), 'reload', 'succ');
            } else {
                showDialog(L('wt_common_op_fail'), 'reload');
            }
        }
        $common_id = $this->checkRequestCommonId($_GET['commonid']);

        // 关联版式
        $plate_list = Model('store_plate')->getStorePlateList(array('store_id' => $_SESSION['store_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);

        Tpl::showpage('store_goods_list.edit_plate', 'null_layout');
    }

    /**
     * 添加赠品
     */
    public function add_giftWt() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlShop('seller_center'), 'html', 'error');
        }
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id, 'store_id,goods_lock');
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $_SESSION['store_id']) {
            showMessage(L('wrong_argument'), urlShop('seller_center'), 'html', 'error');
        }

        $model_gift = Model('goods_gift');
        // 商品列表
        $goods_array = $model_goods->getGoodsList(array('goods_commonid' => $common_id), '*');
        Tpl::output('goods_array', $goods_array);

        // 赠品列表
        $gift_list = $model_gift->getGoodsGiftList(array('goods_commonid' => $common_id));
        $gift_array = array();
        if (!empty($gift_list)) {
            foreach ($gift_list as $val) {
                $gift_array[$val['goods_id']][] = $val;
            }
        }
        Tpl::output('gift_array', $gift_array);
        $menu_sale = array(
            'lock' => $goodscommon_info['goods_lock'] == 1 ? true : false,
            'gift' => true
        );
        $this->profile_menu('edit_detail', 'add_gift', $menu_sale);
        Tpl::showpage('store_goods_edit.add_gift');
    }

    /**
     * 保存赠品
     */
    public function save_giftWt() {
        if (!chksubmit()) {
            showDialog(L('wrong_argument'));
        }
        $data = $_POST['gift'];
        $commonid = intval($_POST['commonid']);
        if ($commonid <= 0) {
            showDialog(L('wrong_argument'));
        }

        $model_goods = Model('goods');
        $model_gift = Model('goods_gift');

        // 验证商品是否存在
        $model_gift = Model('goods_gift');
        $goods_list = $model_gift->getAllowGiftGoodsList(array('goods_commonid' => $commonid, 'store_id' => $_SESSION['store_id']), 'goods_id');
        if (empty($goods_list)) {
            showDialog(L('wrong_argument'));
        }
        // 删除该商品原有赠品
        $model_gift->delGoodsGift(array('goods_commonid' => $commonid));
        // 重置商品礼品标记
        $model_goods->editGoods(array('have_gift' => 0), array('goods_commonid' => $commonid));
        // 商品id
        $goodsid_array = array();
        foreach ($goods_list as $val) {
            $goodsid_array[] = $val['goods_id'];
        }

        $insert = array();
        $update_goodsid = array();
        foreach ($data as $key => $val) {

            $owner_gid = intval($key);  // 主商品id
            // 验证主商品是否为本店铺商品,如果不是本店商品继续下一个循环
            if (!in_array($owner_gid, $goodsid_array)) {
                continue;
            }
            $update_goodsid[] = $owner_gid;
            foreach ($val as $k => $v) {
                $gift_gid = intval($k); // 礼品id
                // 验证赠品是否为本店铺商品，如果不是本店商品继续下一个循环
                $gift_info = $model_goods->getGoodsInfoByID($gift_gid, 'goods_name,store_id,goods_image,is_virtual,is_fcode,is_presell');
                $is_general = $model_goods->checkIsGeneral($gift_info);     // 验证是否为普通商品
                if ($gift_info['store_id'] != $_SESSION['store_id'] || $is_general == false) {
                    continue;
                }

                $array = array();
                $array['goods_id'] = $owner_gid;
                $array['goods_commonid'] = $commonid;
                $array['gift_goodsid'] = $gift_gid;
                $array['gift_goodsname'] = $gift_info['goods_name'];
                $array['gift_goodsimage'] = $gift_info['goods_image'];
                $array['gift_amount'] = intval($v);
                $insert[] = $array;
            }
        }
        // 插入数据
        if (!empty($insert)) $model_gift->addGoodsGiftAll($insert);
        // 更新商品赠品标记
        if (!empty($update_goodsid)) $model_goods->editGoodsById(array('have_gift' => 1), $update_goodsid);
        showDialog(L('wt_common_save_succ'), $_POST['ref_url'], 'succ');
    }
	
   /**
   * 设置商品佣金
     */
    public function edit_invite_priceWt() {
		$model_goods = Model('goods');
        if (chksubmit()) {
			$where = array();
            $where['goods_id'] = $_POST['goods_id'];
            $where['store_id'] = $_SESSION['store_id'];
			$update = array();
			$update['invite_rate'] = trim($_POST['invite_rate']);
			
            $return = $model_goods->editGoods($update, $where);;

            if ($return) {
                // 添加操作日志
                $this->recordSellerLog('设置商品佣金，平台货号：'.$common_id);
                showDialog(L('wt_common_op_succ'), 'reload', 'succ');
            } else {
                showDialog(L('wt_common_op_fail'), 'reload');
            }
        }
		$goods_content = $model_goods->getGoodsInfoByID($_GET['goods_id']);
        Tpl::output('goods', $goods_content);
        Tpl::showpage('store_goods_list.edit_invite_price', 'null_layout');
    }

    /**
     * 搜索商品（添加赠品/推荐搭配)
     */
    public function search_goodsWt() {
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if ($_GET['name']) {
            $where['goods_name'] = array('like', '%'. $_GET['name'] .'%');
        }
        $model_goods = Model('goods');
        $goods_list = $model_goods->getGeneralGoodsOnlineList($where, '*', 5);
        Tpl::output('show_page', $model_goods->showpage(2));
        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('store_goods_edit.search_goods', 'null_layout');
    }

    /**
     * 验证commonid
     */
    private function checkRequestCommonId($common_ids) {
        if (!preg_match('/^[\d,]+$/i', $common_ids)) {
            showDialog(L('para_error'), '', 'error');
        }
        return $common_ids;
    }

    /**
     * ajax获取商品列表
     */
    public function get_goods_list_ajaxWt() {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            echo 'false';exit();
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodsCommonInfoByID($common_id, 'spec_name,store_id');
        if (empty($goodscommon_list) || $goodscommon_list['store_id'] != $_SESSION['store_id']) {
            echo 'false';exit();
        }
        $goods_list = $model_goods->getGoodsList(array('store_id' => $_SESSION['store_id'], 'goods_commonid' => $common_id), 'goods_id,goods_spec,store_id,goods_price,goods_serial,goods_storage_alarm,goods_storage,goods_image,invite_rate');
        if (empty($goods_list)) {
            echo 'false';exit();
        }

        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods-spec">' . $spec_name[$k] . L('wt_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
            $goods_list[$key]['goods_id']       = $val['goods_id'];
            $goods_list[$key]['goods_serial']   = $val['goods_serial'];
			$goods_list[$key]['invite_rate']   = $val['invite_rate'];
            $goods_list[$key]['goods_image']    = thumb($val, '60');
            $goods_list[$key]['goods_spec']     = implode('', $spec_array);
            $goods_list[$key]['alarm']          = ($val['goods_storage_alarm'] != 0 && $val['goods_storage'] <= $val['goods_storage_alarm']) ? 'style="color:red;"' : '';
            $goods_list[$key]['url']            = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($goods_list);
        }
        echo json_encode($goods_list);
    }
    
    public function edit_iframe_ajaxWt() {

        Tpl::showpage('store_goods_list.edit_iframe', 'null_layout');
    }
    
    public function edit_body_ajaxWt() {
        $model_goods = Model('goods');
        if (chksubmit()) {
            $where = array();
            $where['goods_id'] = intval($_POST['goods_id']);
            $where['store_id'] = $_SESSION['store_id'];
            $update = array();
            $update['goods_body']         = $_POST['g_body'];
            // 序列化保存手机端商品描述数据
            if ($_POST['m_body'] != '') {
                $_POST['m_body'] = str_replace('&quot;', '"', $_POST['m_body']);
                $_POST['m_body'] = json_decode($_POST['m_body'], true);
                if (!empty($_POST['m_body'])) {
                    $_POST['m_body'] = serialize($_POST['m_body']);
                } else {
                    $_POST['m_body'] = '';
                }
            }
            $update['mobile_body']        = $_POST['m_body'];
            $result = $model_goods->editGoods($update, $where);
            if ($result) {
                showDialog(L('wt_common_op_succ'), 'reload', 'succ', 'setTimeout(function () {$(".dialog_close_button", parent.document).click();}, 2000)');
            } else {
                showDialog(L('store_goods_index_goods_edit_fail'), 'reload', 'error', 'setTimeout(function () {$(".dialog_close_button", parent.document).click();}, 2000)');
            }
        }
        $goods_content = $model_goods->getGoodsInfoByID($_GET['goods_id']);
        if ($goods_content['store_id'] == $_SESSION['store_id']) {
            if ($goods_content['mobile_body'] != '') {
                $goods_content['mb_body'] = unserialize($goods_content['mobile_body']);
                if (is_array($goods_content['mb_body'])) {
                    $mobile_body = '[';
                    foreach ($goods_content['mb_body'] as $val ) {
                        $mobile_body .= '{"type":"' . $val['type'] . '","value":"' . $val['value'] . '"},';
                    }
                    $mobile_body = rtrim($mobile_body, ',') . ']';
                }
                $goods_content['mobile_body'] = $mobile_body;
            }
            Tpl::output('goods', $goods_content);
        }
        Tpl::showpage('store_goods_list.edit_body', 'null_layout');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param boolean $allow_sale
     * @return
     */
    private function profile_menu($menu_type,$menu_key, $allow_sale = array()) {
        $menu_array = array();
        switch ($menu_type) {
            case 'goods_list':
                $menu_array = array(
                   array('menu_key' => 'goods_list',    'menu_name' => '出售中的商品', 'menu_url' => urlShop('store_goods_online', 'index'))
                );
                break;
            case 'edit_detail':
                if ($allow_sale['lock'] === false) {
                    $menu_array = array(
                        array('menu_key' => 'edit_detail',  'menu_name' => '编辑商品', 'menu_url' => urlShop('store_goods_online', 'edit_goods', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                        array('menu_key' => 'edit_image',   'menu_name' => '编辑图片', 'menu_url' => urlShop('store_goods_online', 'edit_image', array('commonid' => $_GET['commonid'], 'ref_url' => ($_GET['ref_url'] ? $_GET['ref_url'] : getReferer())))),
                    );
                }
                if ($allow_sale['gift'] == true) {
                    $menu_array[] = array('menu_key' => 'add_gift', 'menu_name' => '赠送赠品', 'menu_url' => urlShop('store_goods_online', 'add_gift', array('commonid' => $_GET['commonid'], 'ref_url' => ($_GET['ref_url'] ? $_GET['ref_url'] : getReferer()))));
                }
                break;
            case 'edit_class':
                $menu_array = array(
                    array('menu_key' => 'edit_class',   'menu_name' => '选择分类', 'menu_url' => urlShop('store_goods_online', 'edit_class', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                    array('menu_key' => 'edit_detail',  'menu_name' => '编辑商品', 'menu_url' => urlShop('store_goods_online', 'edit_goods', array('commonid' => $_GET['commonid'], 'ref_url' => $_GET['ref_url']))),
                    array('menu_key' => 'edit_image',   'menu_name' => '编辑图片', 'menu_url' => urlShop('store_goods_online', 'edit_image', array('commonid' => $_GET['commonid'], 'ref_url' => ($_GET['ref_url'] ? $_GET['ref_url'] : getReferer())))),
                );
                break;
        }
        Tpl::output ( 'member_menu', $menu_array );
        Tpl::output ( 'menu_key', $menu_key );
    }
	 /**
     * 批量生成更新商品二维码
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @param boolean $allow_sale
     * @return
     */
	public function maker_qrcodeWt()
	{
		$store_id=$_SESSION['store_id'];
		// 生成商店二维码
        require_once(BASE_STATIC_PATH.DS.'phpqrcode'.DS.'index.php');
        $PhpQRCode = new PhpQRCode();
        $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$_SESSION['store_id'].DS);
		$model_goods = Model('goods');
		$where=array();
	    $where['store_id'] = $store_id;
		$lst=$model_goods->getGoodsList($where,'goods_id');
		if(empty($lst))
		{
			echo '未找到商品信息';
			retrun;
		}
		foreach($lst as $k=>$v)
		{
			$goods_id=$v['goods_id'];
			//生成二维码
			$qrcode_url=WAP_SITE_URL . '/html/product_detail.html?goods_id='.$goods_id;
			$PhpQRCode->set('date',$qrcode_url);
			$PhpQRCode->set('pngTempName', $goods_id . '.png');
			$PhpQRCode->init();

		}
		
		//生成店铺二维码
		$qrcode_url=WAP_SITE_URL . '/html/store.html?store_id='.$store_id;
		$PhpQRCode->set('date',$qrcode_url);
		$PhpQRCode->set('pngTempName', $store_id . '_store.png');
		$PhpQRCode->init();
        showDialog(L('wt_common_op_succ'), $_POST['ref_url'], 'succ');
	}
	 /**
     * 清空临时阶梯价格文件
     *
     */
	private function deldir($dir) {
	  
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
		  if(!is_dir($fullpath)) {
			  unlink($fullpath);
		  } else {
			  deldir($fullpath);
		  }
		}
	  }
	  closedir($dh);
	}
}
