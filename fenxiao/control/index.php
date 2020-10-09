<?php
/**
 * 默认展示页面
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class indexControl extends BasefenxiaoControl{
	
    //每页显示商品数
    const PAGESIZE = 20;

    //模型对象
    private $_model_search;
	
    function indexWt(){
        //板块信息
        $model_page_config = Model('fx_page_config');
        $web_html = $model_page_config->getWebHtml('index');
        Tpl::output('web_html',$web_html);
		$this->_index_search();
        Tpl::showpage('index');
    }
	
	public function _index_search()
    {
        Language::read('home_goods_class_index');
        $this->_model_search = Model('fx_search');
        //默认分类，从而显示相应的属性和品牌
        $default_classid = intval($_GET['cate_id']);



        //商品分类筛选
        $model_goods_class = Model('goods_class');
        $all_gc_list = $model_goods_class->getGoodsClassForCacheModel();
        $f_gc_list = $model_goods_class->getGoodsClassListByParentId(0);
        Tpl::output('f_gc_list',$f_gc_list);

        $s_gc_list = array();
        $s_parent_id = 0;
        $t_gc_list = array();
        $t_parent_id = 0;
        if($default_classid > 0){
            $gc_info = $all_gc_list[$default_classid];
            if($gc_info['gc_parent_id'] > 0 && isset($gc_info['child']) && $gc_info['depth'] == 2){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $s_parent_id = $gc_info['gc_parent_id'];

                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $t_parent_id = $default_classid;
            }elseif($gc_info['gc_parent_id'] > 0 && !isset($gc_info['child']) && $gc_info['depth'] == 2){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $s_parent_id = $gc_info['gc_parent_id'];

                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $t_parent_id = $default_classid;
            }elseif($gc_info['gc_parent_id'] > 0 && !isset($gc_info['child']) && $gc_info['depth'] == 3){
                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $t_parent_id = $gc_info['gc_parent_id'];
                $s_parent_id = $all_gc_list[$t_parent_id]['gc_parent_id'];
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($s_parent_id);
            }elseif($gc_info['gc_parent_id'] == 0){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $s_parent_id = $default_classid;
            }
        }
        Tpl::output('s_gc_list',$s_gc_list);
        Tpl::output('s_parent_id',$s_parent_id);
        Tpl::output('t_gc_list',$t_gc_list);
        Tpl::output('t_parent_id',$t_parent_id);

        Tpl::output('default_classid', $default_classid);

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);

      /*   Tpl::output('brand_array', $brand_array);
        Tpl::output('initial_array', $initial_array);
        Tpl::output('attr_array', $attr_array);
        Tpl::output('checked_brand', $checked_brand);
        Tpl::output('checked_attr', $checked_attr); */

        $model_goods = Model('goods');

        //查库搜索

        //处理排序
        $order = 'is_own_shop desc,goods_commonid desc';
        if (in_array($_GET['key'], array('1', '2', '3'))) {
            $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
            $order = str_replace(array('1', '2', '3'), array('sale_count', 'click_count', 'goods_price'), $_GET['key']);
            $order .= ' ' . $sequence;
        }

        // 字段
        $fields = "goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_image,sale_count,click_count,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,areaid_1,fx_commis_rate";

        $condition = array();
        if (isset($goods_param['class'])) {
            $condition['gc_id_' . $goods_param['class']['depth']] = $goods_param['class']['gc_id'];
        }
        if (intval($_GET['b_id']) > 0) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }


        $condition['is_fx'] = 1;

        if (isset($goods_param['goodsid_array'])) {
            $condition['goods_commonid'] = array('in', $goods_param['goodsid_array']);
        }
        $goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fields, self::PAGESIZE, $order);

        Tpl::output('show_page', $model_goods->showpage(7));

        if (!empty($goods_list)) {
            //查库搜索
            //$commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                //$commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $storeid_array = array_unique($storeid_array);
            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);
            
            foreach ($goods_list as $key => $value) {
                // 店铺的开店会员编号
                $store_id = $value['store_id'];
                $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];

            }
        }
        Tpl::output('goods_list', $goods_list);


        loadfunc('search');

    }


    //json输出商品分类
    public function josn_classWt() {
        /**
         * 实例化商品分类模型
         */
        $model_class        = Model('goods_class');
        $goods_class        = $model_class->getGoodsClassListByParentId(intval($_GET['gc_id']));
        $array              = array();
        if(is_array($goods_class) and count($goods_class)>0) {
            foreach ($goods_class as $val) {
                $array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
            }
        }
        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK'){
            $array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        } else {
            $array = array_values($array);
        }
        echo $_GET['callback'].'('.json_encode($array).')';
    }
}