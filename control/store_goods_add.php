<?php
/**
 * 商品管理
 *
 *
 *
 *

 

 */



defined('ShopWT') or exit ('Access Denied By ShopWT');
class store_goods_addControl extends BaseSellerControl {
    /**
     * 三方店铺验证，商品数量，有效期
     */
    private function checkStore(){
        $goodsLimit = (int) $this->store_grade['sg_goods_limit'];
        if ($goodsLimit > 0) {
            // 是否到达商品数上限
            $goods_num = Model('goods')->getGoodsCommonCount(array('store_id' => $_SESSION['store_id']));
            if ($goods_num >= $goodsLimit) {
                showMessage(L('store_goods_index_goods_limit') . $goodsLimit . L('store_goods_index_goods_limit1'), 'index.php?w=store_goods_online&t=goods_list', 'html', 'error');
            }
        }
    }
    public function __construct() {
        parent::__construct();
        Language::read('member_store_goods_index');
    }
    public function indexWt() {
        $this->checkStore();
        $this->add_step_oneWt();
    }

    /**
     * 添加商品
     */
    public function add_step_oneWt() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);
        Tpl::showpage('store_goods_add.step1');
    }

    /**
     * 添加商品
     */
    public function add_step_twoWt() {
    	//批发文件缓存路经
    	$this->deldir(BASE_UPLOAD_PATH."/spec/");
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 现暂时改为从匿名“自营店铺专属等级”中判断
        $editor_multimedia = false;
        if ($this->store_grade['sg_function'] == 'editor_multimedia') {
            $editor_multimedia = true;
        }
        Tpl::output('editor_multimedia', $editor_multimedia);

        $gc_id = intval($_GET['class_id']);

        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = Model('goods_class')->getGoodsClassForCacheModel();
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            showDialog(L('store_goods_index_again_choose_category1'));
        }

        // 如果不是自营店铺或者自营店铺未绑定全部商品类目，读取绑定分类
        if (!checkPlatformStoreBindingAllGoodsClass()) {
            //商品分类支持批量显示分类
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

        //权限组对应分类权限判断
        if (!$_SESSION['seller_gc_limits']&& $_SESSION['seller_group_id']) {
            $rs = Model('seller_group_bclass')->getSellerGroupBclassInfo(array('group_id'=>$_SESSION['seller_group_id'],'gc_id'=>$gc_id));
            if (!$rs) {
                showMessage('您所在的组无权操作该分类下的商品', '', 'html', 'error');
            }
        }

        // 更新常用分类信息
        $goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
        Tpl::output('goods_class', $goods_class);
        Model('goods_class_staple')->autoIncrementStaple($goods_class, $_SESSION['member_id']);

        // 获取类型相关数据
        $typeinfo = Model('type')->getAttr($goods_class['type_id'], $_SESSION['store_id'], $gc_id);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        Tpl::output('sign_i', count($spec_list));
        Tpl::output('spec_list', $spec_list);
        Tpl::output('attr_list', $attr_list);
        Tpl::output('brand_list', $brand_list);
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        Tpl::output('custom_list', $custom_list);

        // 实例化店铺商品分类模型
        $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $_SESSION ['store_id'], 'stc_state' => '1'));
        Tpl::output('store_goods_class', $store_goods_class);

        // 小时分钟显示
        $hour_array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        Tpl::output('hour_array', $hour_array);
        $minute_array = array('05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');
        Tpl::output('minute_array', $minute_array);

        // 关联版式
        $plate_list = Model('store_plate')->getStorePlateList(array('store_id' => $_SESSION['store_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);
    	// 设置最大阶梯数量 v6.5
    	Tpl::output('bat_max_num', "3");
        
        // 供货商
        $supplier_list = Model('store_supplier')->getStoreSupplierList(array('sup_store_id' => $_SESSION['store_id']));
        Tpl::output('supplier_list', $supplier_list);

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
     * 保存商品（商品发布第二步使用）
     */
    public function save_goodsWt() {
        //获取视频数据，提交时候保存视频
        $datas = $this->_getGoodsVideo($_FILES);
        if($datas['status'] == 'success'){
            $_POST['goods_video'] = $datas['goods_video'];
        }elseif($datas['status'] == 'fail'){
            showMessage($datas['error'], '', 'html', 'error');
        }
        $handle_goods = Handle('goods');

        //设置商品独立三级分销佣金
        if(intval($_POST['is_independent_bonus']) == 1){//是否开启独立佣金 1开启 否则禁用
            $level_commission = $_POST['level_commission'];             
            $_POST['level_commission'] = serialize($level_commission);                                    
        }
        
        $result =  $handle_goods->saveGoods(
            $_POST,
            $_SESSION['store_id'], 
            $_SESSION['store_name'], 
            $this->store_info['store_state'], 
            $_SESSION['seller_id'], 
            $_SESSION['seller_name'],
            $_SESSION['bind_all_gc']
        );

        if(!$result['state']) {
            showMessage(L('error') . $result['msg'], urlShop('seller_center'), 'html', 'error');
        }

        redirect(urlShop('store_goods_add', 'add_step_three', array('commonid' => $result['data'])));
    }

    /**
     * 第三步添加颜色图片
     */
    public function add_step_threeWt() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlShop('seller_center'), 'html', 'error');
        }

        $model_goods = Model('goods');
        $img_array = $model_goods->getGoodsList(array('goods_commonid' => $common_id), 'color_id,min(goods_image) as goods_image', 'color_id');
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            $colorid_array = array();
            $image_array = array();
            foreach ($img_array as $val) {
                $image_array[$val['color_id']][0]['goods_image'] = $val['goods_image'];
                $image_array[$val['color_id']][0]['is_default'] = 1;
                $colorid_array[] = $val['color_id'];
            }
            Tpl::output('img', $image_array);
        }

        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'spec_value');
        $spec_value = unserialize($common_list['spec_value']);
        Tpl::output('value', $spec_value['1']);

        $model_spec = Model('spec');
        $value_array = $model_spec->getSpecValueList(array('sp_value_id' => array('in', $colorid_array), 'store_id' => $_SESSION['store_id']), 'sp_value_id,sp_value_name');
        if (empty($value_array)) {
            $value_array[] = array('sp_value_id' => '0', 'sp_value_name' => '无颜色');
        }
        Tpl::output('value_array', $value_array);

        Tpl::output('commonid', $common_id);
        Tpl::showpage('store_goods_add.step3');
    }

    /**
     * 保存商品颜色图片
     */
    public function save_imageWt(){
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            if ($common_id <= 0 || empty($_POST['img'])) {
                showMessage(L('wrong_argument'));
            }
            $model_goods = Model('goods');
            // 保存
            $insert_array = array();
            foreach ($_POST['img'] as $key => $value) {
                $k = 0;
                foreach ($value as $v) {
                    if ($v['name'] == '') {
                        continue;
                    }
                    // 商品默认主图
                    $update_array = array();        // 更新商品主图
                    $update_where = array();
                    $update_array['goods_image']    = $v['name'];
                    $update_where['goods_commonid'] = $common_id;
                    $update_where['color_id']       = $key;
                    if ($k == 0 || $v['default'] == 1) {
                        $k++;
                        $update_array['goods_image']    = $v['name'];
                        $update_where['goods_commonid'] = $common_id;
                        $update_where['color_id']       = $key;
                        // 更新商品主图
                        $model_goods->editGoods($update_array, $update_where);
                    }
                    $tmp_insert = array();
                    $tmp_insert['goods_commonid']   = $common_id;
                    $tmp_insert['store_id']         = $_SESSION['store_id'];
                    $tmp_insert['color_id']         = $key;
                    $tmp_insert['goods_image']      = $v['name'];
                    $tmp_insert['goods_image_sort'] = ($v['default'] == 1) ? 0 : intval($v['sort']);
                    $tmp_insert['is_default']       = $v['default'];
                    $insert_array[] = $tmp_insert;
                }
            }
            $rs = $model_goods->addGoodsImagesAll($insert_array);
            if ($rs) {
                redirect(urlShop('store_goods_add', 'add_step_four', array('commonid' => $common_id)));
            } else {
                showMessage(L('wt_common_save_fail'));
            }
        }
    }

    /**
     * 商品发布第四步
     */
    public function add_step_fourWt() {
        // 单条商品信息
        $goods_content = Model('goods')->getGoodsInfo(array('goods_commonid' => $_GET['commonid']));

        // 自动发布动态
        $data_array = array();
        $data_array['goods_id'] = $goods_content['goods_id'];
        $data_array['store_id'] = $goods_content['store_id'];
        $data_array['goods_name'] = $goods_content['goods_name'];
        $data_array['goods_image'] = $goods_content['goods_image'];
        $data_array['goods_price'] = $goods_content['goods_price'];
        $data_array['goods_transfee_charge'] = $goods_content['goods_freight'] == 0 ? 1 : 0;
        $data_array['goods_freight'] = $goods_content['goods_freight'];
        $this->storeAutoShare($data_array, 'new');

        Tpl::output('allow_gift', Model('goods')->checkGoodsIfAllowGift($goods_content));
        Tpl::output('allow_combo', Model('goods')->checkGoodsIfAllowCombo($goods_content));//v6.5 批发价
        Tpl::output('goods_id', $goods_content['goods_id']);
        Tpl::showpage('store_goods_add.step4');
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
            $result = $upload->upfile('goods_video_file',true);//shopwt
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
     * 上传图片
     */
    public function image_uploadWt() {
        $handle_goods = Handle('goods');

        $result =  $handle_goods->uploadGoodsImage(
            $_POST['name'],
            $_SESSION['store_id'],
            $this->store_grade['sg_album_limit']
        );

        if(!$result['state']) {
            echo json_encode(array('error' => $result['msg']));die;
        }

        echo json_encode($result['data']);die;
    }

    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classWt() {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);
        if ($gc_id <= 0 || $deep <= 0 || $deep >= 4) {
            exit();
        }
        $model_goodsclass = Model('goods_class');
        $list = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $gc_id, $deep,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
        if (empty($list)) {
            exit();
        }
        /**
         * 转码
         */
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list = Language::getUTF8 ( $list );
        }
        echo json_encode($list);
    }
    /**
     * ajax删除常用分类
     */
    public function ajax_stapledelWt() {
        Language::read ( 'member_store_goods_index' );
        $staple_id = intval($_GET ['staple_id']);
        if ($staple_id < 1) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => Language::get ( 'wrong_argument' )
            ) );
            die ();
        }
        /**
         * 实例化模型
         */
        $model_staple = Model('goods_class_staple');

        $result = $model_staple->delStaple(array('staple_id' => $staple_id, 'member_id' => $_SESSION['member_id']));
        if ($result) {
            echo json_encode ( array (
                    'done' => true
            ) );
            die ();
        } else {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => ''
            ) );
            die ();
        }
    }
    /**
     * ajax选择常用商品分类
     */
    public function ajax_show_commWt() {
        $staple_id = intval($_GET['stapleid']);

        /**
         * 查询相应的商品分类id
         */
        $model_staple = Model('goods_class_staple');
        $staple_info = $model_staple->getStapleInfo(array('staple_id' => intval($staple_id)), 'gc_id_1,gc_id_2,gc_id_3');
        if (empty ( $staple_info ) || ! is_array ( $staple_info )) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => ''
            ) );
            die ();
        }

        $list_array = array ();
        $list_array['gc_id'] = 0;
        $list_array['type_id'] = $staple_info['type_id'];
        $list_array['done'] = true;
        $list_array['one'] = '';
        $list_array['two'] = '';
        $list_array['three'] = '';

        $gc_id_1 = intval ( $staple_info['gc_id_1'] );
        $gc_id_2 = intval ( $staple_info['gc_id_2'] );
        $gc_id_3 = intval ( $staple_info['gc_id_3'] );

        /**
         * 查询同级分类列表
         */
        $model_goods_class = Model ( 'goods_class' );
        // 1级
        if ($gc_id_1 > 0) {
            $list_array['gc_id'] = $gc_id_1;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_1) {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 2级
        if ($gc_id_2 > 0) {
            $list_array['gc_id'] = $gc_id_2;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_1, 2,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_2) {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 3级
        if ($gc_id_3 > 0) {
            $list_array['gc_id'] = $gc_id_3;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_2, 3,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_3) {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" wttype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 转码
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list_array = Language::getUTF8 ( $list_array );
        }
        echo json_encode ( $list_array );
        die ();
    }
    /**
     * AJAX添加商品规格值
     */
    public function ajax_add_specWt() {
        $name = trim($_GET['name']);
        $gc_id = intval($_GET['gc_id']);
        $sp_id = intval($_GET['sp_id']);
        if ($name == '' || $gc_id <= 0 || $sp_id <= 0) {
            echo json_encode(array('done' => false));die();
        }
        $insert = array(
            'sp_value_name' => $name,
            'sp_id' => $sp_id,
            'gc_id' => $gc_id,
            'store_id' => $_SESSION['store_id'],
            'sp_value_color' => null,
            'sp_value_sort' => 0,
        );
        $value_id = Model('spec')->addSpecValue($insert);
        if ($value_id) {
            echo json_encode(array('done' => true, 'value_id' => $value_id));die();
        } else {
            echo json_encode(array('done' => false));die();
        }
    }

    /**
     * AJAX查询品牌
     */
    public function ajax_get_brandWt() {
        $type_id = intval($_GET['tid']);
        $initial = trim($_GET['letter']);
        $keyword = trim($_GET['keyword']);
        $type = trim($_GET['type']);
        if (!in_array($type, array('letter', 'keyword')) || ($type == 'letter' && empty($initial)) || ($type == 'keyword' && empty($keyword))) {
            echo json_encode(array());die();
        }

        // 实例化模型
        $model_type = Model('type');
        $where = array();
        $where['type_id'] = $type_id;
        // 验证类型是否关联品牌
        $count = $model_type->getTypeBrandCount($where);
        if ($type == 'letter') {
            switch ($initial) {
                case 'all':
                    break;
                case '0-9':
                    $where['brand_initial'] = array('in', array(0,1,2,3,4,5,6,7,8,9));
                    break;
                default:
                    $where['brand_initial'] = $initial;
                    break;
            }
        } else {
            $where['brand_name|brand_initial'] = array('like', '%' . $keyword . '%');
        }
        if ($count > 0) {
            $brand_array = $model_type->typeRelatedJoinList($where, 'brand', 'brand.brand_id,brand.brand_name,brand.brand_initial');
        } else {
            unset($where['type_id']);
            $brand_array = Model('brand')->getBrandPassedList($where, 'brand_id,brand_name,brand_initial', 0, 'brand_initial asc, brand_sort asc');
        }
        echo json_encode($brand_array);die();
    }
    /**
     * 清空临时阶梯价格文件 v6.5
     */
    private function deldir($dir) {
	 
	  if (is_dir($dir)){
			 if ($dh = opendir($dir)){
			  //$dh=opendir($dir);
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
	}
}
