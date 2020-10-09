<?php
/**
 * 前台模块编辑(首页)
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class page_configControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('page_config');
    }

    public function indexWt() {
        $this->page_configWt();
    }

    /**
     * 板块列表
     */
    public function page_configWt(){
        $model_page_config = Model('page_config');
        $style_array = $model_page_config->getStyleList();//板块样式数组
        Tpl::output('style_array',$style_array);
        $web_list = $model_page_config->getWebList(array('web_page' => array('in', array('index','index_fl'))));
        Tpl::output('web_list',$web_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('page_config.index');
    }

    /**
     * 基本设置
     */
    public function web_editWt(){
        $model_page_config = Model('page_config');
        $web_id = intval($_GET["web_id"]);
        if (chksubmit()){
            $web_array = array();
            $web_id = intval($_POST["web_id"]);
            $web_array['web_name'] = $_POST["web_name"];
            $web_array['style_name'] = $_POST["style_name"];
            $web_array['web_sort'] = intval($_POST["web_sort"]);
            $web_array['web_show'] = intval($_POST["web_show"]);
            $web_array['update_time'] = time();
            $model_page_config->updateWeb(array('web_id'=>$web_id),$web_array);
            $model_page_config->updateWebHtml($web_id);//更新前台显示的html内容
            $this->log(l('page_config_code_edit').'['.$_POST["web_name"].']',1);
            showMessage(Language::get('wt_common_save_succ'),'index.php?w=page_config&t=page_config');
        }
        $web_list = $model_page_config->getWebList(array('web_id'=>$web_id));
        Tpl::output('web_array',$web_list[0]);
		Tpl::setDirquna('shop');
        Tpl::showpage('page_config.edit');
    }

    /**
     * 板块编辑
     */
    public function code_editWt(){
        $model_page_config = Model('page_config');
        $web_id = intval($_GET["web_id"]);
        $code_list = $model_page_config->getCodeList(array('web_id'=>"$web_id"));
        if(is_array($code_list) && !empty($code_list)) {
            $model_class = Model('goods_class');
            $parent_goods_class = $model_class->getTreeClassList(2);//商品分类父类列表，只取到第二级
            if (is_array($parent_goods_class) && !empty($parent_goods_class)){
                foreach ($parent_goods_class as $k => $v){
                    $parent_goods_class[$k]['gc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['gc_name'];
                }
            }
            Tpl::output('parent_goods_class',$parent_goods_class);

            $goods_class = $model_class->getTreeClassList(1);//第一级商品分类
            Tpl::output('goods_class',$goods_class);

            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val["var_name"];
                $code_info = $val["code_info"];
                $code_type = $val["code_type"];
                $val['code_info'] = $model_page_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
            $style_array = $model_page_config->getStyleList();//样式数组
            Tpl::output('style_array',$style_array);
            $web_list = $model_page_config->getWebList(array('web_id'=>$web_id));
            Tpl::output('web_array',$web_list[0]);
			Tpl::setDirquna('shop');
            Tpl::showpage('web_code.edit');
        } else {
            showMessage(Language::get('wt_no_record'));
        }
    }

    /**
     * 板块编辑
     */
    public function fl_editWt(){
        $model_page_config = Model('page_config');
        $web_id = intval($_GET["web_id"]);
        $code_list = $model_page_config->getCodeList(array('web_id'=>"$web_id"));
        if(is_array($code_list) && !empty($code_list)) {
            $model_class = Model('goods_class');
            $parent_goods_class = $model_class->getTreeClassList(2);//商品分类父类列表，只取到第二级
            if (is_array($parent_goods_class) && !empty($parent_goods_class)){
                foreach ($parent_goods_class as $k => $v){
                    $parent_goods_class[$k]['gc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['gc_name'];
                }
            }
            Tpl::output('parent_goods_class',$parent_goods_class);

            $goods_class = $model_class->getTreeClassList(1);//第一级商品分类
            Tpl::output('goods_class',$goods_class);

            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val["var_name"];
                $code_info = $val["code_info"];
                $code_type = $val["code_type"];
                $val['code_info'] = $model_page_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
            $style_array = $model_page_config->getStyleList();//样式数组
            Tpl::output('style_array',$style_array);
            $web_list = $model_page_config->getWebList(array('web_id'=>$web_id));
            Tpl::output('web_array',$web_list[0]);
	    Tpl::setDirquna('shop');
            Tpl::showpage('web_code_fl.edit');
        } else {
            showMessage(Language::get('wt_no_record'));
        }
    }

    /**
     * 更新前台显示的html内容
     */
    public function web_htmlWt(){
        $model_page_config = Model('page_config');
        $web_id = intval($_GET["web_id"]);
        $web_list = $model_page_config->getWebList(array('web_id'=>$web_id));
        $web_array = $web_list[0];
        if(!empty($web_array) && is_array($web_array)) {
            $model_page_config->updateWebHtml($web_id,$web_array);
            showMessage(Language::get('wt_common_op_succ'),'index.php?w=page_config&t=page_config');
        } else {
            showMessage(Language::get('wt_common_op_fail'));
        }
    }

    /**
     * 头部切换图设置
     */
    public function focus_editWt() {
        $model_page_config = Model('page_config');
        $web_id = '101';
        $code_list = $model_page_config->getCodeList(array('web_id'=> $web_id));
        if(is_array($code_list) && !empty($code_list)) {
            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_page_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
        }
        $screen_show_list = $model_page_config->getShowList("screen");//焦点大图广告数据
        Tpl::output('screen_show_list',$screen_show_list);
        $focus_show_list = $model_page_config->getShowList("focus");//三张联动区广告数据
        Tpl::output('focus_show_list',$focus_show_list);
		Tpl::setDirquna('shop');

        Tpl::showpage('web_focus.edit');
    }

    /**
     * 更新html内容
     */
    public function html_updateWt() {
        $model_page_config = Model('page_config');
        $web_id = intval($_GET["web_id"]);
        $web_list = $model_page_config->getWebList(array('web_id'=> $web_id));
        $web_array = $web_list[0];
        if(!empty($web_array) && is_array($web_array)) {
            $model_page_config->updateWebHtml($web_id,$web_array);
            showMessage(Language::get('wt_common_op_succ'));
        } else {
            showMessage(Language::get('wt_common_op_fail'));
        }
    }

    /**
     * 头部促销区
     */
    public function sale_editWt() {
        $model_page_config = Model('page_config');
        $web_id = '121';
        $code_list = $model_page_config->getCodeList(array('web_id'=> $web_id));
        if(is_array($code_list) && !empty($code_list)) {
            $model_class = Model('goods_class');
            $goods_class = $model_class->getTreeClassList(1);//第一级商品分类
            Tpl::output('goods_class',$goods_class);
            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_page_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
        }
		Tpl::setDirquna('shop');
        Tpl::showpage('web_sale.edit');
    }

    /**
     * 商品分类
     */
    public function category_listWt() {
        $model_class = Model('goods_class');
        $gc_parent_id = intval($_GET['id']);
        $goods_class = $model_class->getGoodsClassListByParentId($gc_parent_id);
        Tpl::output('goods_class',$goods_class);
		Tpl::setDirquna('shop');
        Tpl::showpage('web_goods_class','null_layout');
    }

    /**
     * 商品推荐
     */
    public function recommend_listWt() {
        $model_page_config = Model('page_config');
        $condition = array();
        $gc_id = intval($_GET['id']);
        if ($gc_id > 0) {
            $condition['gc_id'] = $gc_id;
        }
        $goods_name = trim($_GET['goods_name']);
        if (!empty($goods_name)) {
            $goods_id = ''.intval($_GET['goods_name']);
            if ($goods_id == $goods_name) {
                $condition['goods_id'] = $goods_id;
            } else {
                $condition['goods_name'] = array('like','%'.$goods_name.'%');
            }
        }
        $goods_list = $model_page_config->getGoodsList($condition,'goods_id desc',6);
        Tpl::output('show_page',$model_page_config->showpage(2));
        Tpl::output('goods_list',$goods_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('web_goods.list','null_layout');
    }

    /**
     * 商品排序查询
     */
    public function goods_listWt() {
        $model_page_config = Model('page_config');
        $condition = array();
        $order = 'goods_salenum desc,goods_id desc';//销售数量
        $goods_order = trim($_GET['goods_order']);
        if (!empty($goods_order)) {
            $order = $goods_order.' desc,goods_id desc';
        }
        $gc_id = intval($_GET['id']);
        if ($gc_id > 0) {
            $condition['gc_id'] = $gc_id;
        }
        $goods_name = trim($_GET['goods_name']);
        if (!empty($goods_name)) {
            $goods_id = ''.intval($_GET['goods_name']);
            if ($goods_id == $goods_name) {
                $condition['goods_id'] = $goods_id;
            } else {
                $condition['goods_name'] = array('like','%'.$goods_name.'%');
            }
        }
        $goods_list = $model_page_config->getGoodsList($condition,$order,6);
        Tpl::output('show_page',$model_page_config->showpage(2));
        Tpl::output('goods_list',$goods_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('web_goods_order','null_layout');
    }

    /**
     * 品牌
     */
    public function brand_listWt() {
        $model_brand = Model('brand');
        /**
         * 检索条件
         */
        $condition = array();
        if (!empty($_GET['brand_name'])) {
            $condition['brand_name'] = array('like', '%' . trim($_GET['brand_name']) . '%');
        }
        if (!empty($_GET['brand_initial'])) {
            $condition['brand_initial'] = trim($_GET['brand_initial']);
        }
        $brand_list = $model_brand->getBrandPassedList($condition, '*', 6);
        Tpl::output('show_page',$model_brand->showpage());
        Tpl::output('brand_list',$brand_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('web_brand.list','null_layout');
    }

    /**
     * 保存设置
     */
    public function code_updateWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $state = $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
        }
        if($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }

    /**
     * 保存图片
     */
    public function upload_picWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $file_name = 'web-'.$web_id.'-'.$code_id;
            $pic_name = $this->_upload_pic($file_name);//上传图片
            if (!empty($pic_name)) {
                $code_info['pic'] = $pic_name;
            }

            Tpl::output('var_name',$var_name);
            Tpl::output('pic',$code_info['pic']);
            Tpl::output('type',$code_info['type']);
            Tpl::output('ap_id',$code_info['ap_id']);
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $state = $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
			Tpl::setDirquna('shop');
            Tpl::showpage('web_upload_pic','null_layout');
        }
    }

    /**
     * 中部推荐图片
     */
    public function recommend_picWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        $key_id = intval($_POST['key_id']);
        $pic_id = intval($_POST['pic_id']);
        if (!empty($code) && $key_id > 0 && $pic_id > 1) {
            $code_info = $code['code_info'];
            $code_type = $code['code_type'];
            $code_info = $model_page_config->get_array($code_info,$code_type);//原数组

            $var_name = "pic_list";
            $pic_info = $_POST[$var_name];
            $pic_info['pic_id'] = $pic_id;
            if (!empty($code_info[$key_id]['pic_list'][$pic_id]['pic_img'])) {//原图片
                $pic_info['pic_img'] = $code_info[$key_id]['pic_list'][$pic_id]['pic_img'];
            }

            $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$key_id.'-'.$pic_id;
            $pic_name = $this->_upload_pic($file_name);//上传图片
            if (!empty($pic_name)) {
                $pic_info['pic_img'] = $pic_name;
            }

            $recommend_list = array();
            $recommend_list = $_POST['recommend_list'];
            $recommend_list['pic_list'] = $code_info[$key_id]['pic_list'];
            $code_info[$key_id] = $recommend_list;
            $code_info[$key_id]['pic_list'][$pic_id] = $pic_info;

            Tpl::output('pic',$pic_info);
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $state = $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
			Tpl::setDirquna('shop');
            Tpl::showpage('web_recommend_pic','null_layout');
        }
    }
    
    /**
     * 中部推荐图片
     */
    public function recommend_pic2Wt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        $key_id = intval($_POST['key_id']);
        $pic_id = intval($_POST['pic_id']);
        if (!empty($code) && $key_id > 0 && $pic_id > 1) {
            $code_info = $code['code_info'];
            $code_type = $code['code_type'];
            $code_info = $model_page_config->get_array($code_info,$code_type);//原数组

            $var_name = "pic_list2";
            $pic_info = $_POST[$var_name];
            $pic_info['pic_id'] = $pic_id;
            if (!empty($code_info[$key_id]['pic_list2'][$pic_id]['pic_img'])) {//原图片
                $pic_info['pic_img'] = $code_info[$key_id]['pic_list2'][$pic_id]['pic_img'];
            }

            $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$key_id.'-'.$pic_id;
            $pic_name = $this->_upload_pic($file_name);//上传图片
            if (!empty($pic_name)) {
                $pic_info['pic_img'] = $pic_name;
            }

            $recommend_list = array();
            $recommend_list = $_POST['recommend_list'];
            $recommend_list['pic_list2'] = $code_info[$key_id]['pic_list2'];
            $code_info[$key_id] = $recommend_list;
            $code_info[$key_id]['pic_list2'][$pic_id] = $pic_info;

            Tpl::output('pic',$pic_info);
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $state = $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
	    Tpl::setDirquna('shop');
            Tpl::showpage('web_recommend_pic','null_layout');
        }
    }
        /**
     * 保存中部推荐切换图片
     */
    public function recommend_slideWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $key_id = intval($_POST['key_id']);
        $pic_id = intval($_POST['pic_id']);//起始编号
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_info = $code['code_info'];
            $code_type = $code['code_type'];
            $code_info = $model_page_config->get_array($code_info,$code_type);//原数组
            $pic_list2 = $code_info[$key_id]['pic_list2'];
            $adv_list = $_POST['adv'];
            for ($i = $pic_id;$i <= $pic_id+9;$i++) {
                unset($pic_list2[$i]);
            }
            if ($pic_id == 60) {//如果是第二个切换图片则清除原来的两个小图
                unset($pic_list2[33]);
                unset($pic_list2[34]);
            }
            foreach ($adv_list as $k => $v) {
                if (empty($pic_list2[$pic_id])) {
                    $pic_list2[$pic_id] = $v;//默认使用第一个图片
                    $pic_list2[$pic_id]['pic_id'] = $pic_id;
                }
                $pic_list2[$k] = $v;
            }
            $code_info[$key_id]['pic_list2'] = $pic_list2;
            $pic_id = intval($_POST['slide_id']);
            if ($pic_id > 0) {
                $var_name = "slide_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$key_id]['pic_list2'][$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$key_id]['pic_list2'][$pic_id]['pic_img'];
                }
                $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$key_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }
                $code_info[$key_id]['pic_list2'][$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
		Tpl::setDirquna('shop');
            Tpl::showpage('web_recommend_slide','null_layout');
        }
    }
    

    /**
     * 保存切换图片
     */
    public function slide_showWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $pic_id = intval($_POST['slide_id']);
            if ($pic_id > 0) {
                $var_name = "slide_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
			Tpl::setDirquna('shop');

            Tpl::showpage('web_upload_slide','null_layout');
        }
    }

    /**
     * 保存焦点区切换大图
     */
    public function screen_picWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $ap_pic_id = intval($_POST['ap_pic_id']);
            if ($ap_pic_id > 0 && $ap_pic_id == $key) {
                $ap_color = $_POST['ap_color'];
                $code_info[$ap_pic_id]['color'] = $ap_color;
                Tpl::output('ap_pic_id',$ap_pic_id);
                Tpl::output('ap_color',$ap_color);
            }
            $pic_id = intval($_POST['screen_id']);
            if ($pic_id > 0 && $pic_id == $key) {
                $var_name = "screen_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
			Tpl::setDirquna('shop');

            Tpl::showpage('web_upload_screen','null_layout');
        }
    }

    /**
     * 保存焦点区切换小图
     */
    public function focus_picWt() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_page_config = Model('page_config');
        $code = $model_page_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $slide_id = intval($_POST['slide_id']);
            $pic_id = intval($_POST['pic_id']);
            if ($pic_id > 0 && $slide_id == $key) {
                $var_name = "focus_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$slide_id]['pic_list'][$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$slide_id]['pic_list'][$pic_id]['pic_img'];
                }
                $file_name = 'web-'.$web_id.'-'.$code_id.'-'.$slide_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$slide_id]['pic_list'][$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            $code_info = $model_page_config->get_str($code_info,$code_type);
            $model_page_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));
			Tpl::setDirquna('shop');

            Tpl::showpage('web_upload_focus','null_layout');
        }
    }

    /**
     * 上传图片
     */
    private function _upload_pic($file_name) {
        $pic_name = '';
        if (!empty($file_name)) {
            if (!empty($_FILES['pic']['name'])) {//上传图片
                $upload = new UploadFile();
                $filename_tmparr = explode('.', $_FILES['pic']['name']);
                $ext = end($filename_tmparr);
                $upload->set('default_dir',ATTACH_EDITOR);
                $upload->set('file_name',$file_name.".".$ext);
                $result = $upload->upfile('pic');
                if ($result) {
                    $pic_name = ATTACH_EDITOR."/".$upload->file_name.'?'.mt_rand(100,999);//加随机数防止浏览器缓存图片
                }
            }
        }
        return $pic_name;
    }
}
