<?php
/**
 * 分销商品
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class store_fx_goodsControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 分销商品列表
     *
     */
    public function indexWt() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['is_fx'] = 1;
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGoodsCommonList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        self::profile_menu('fx_goods','index');
        Tpl::showpage('fx_goods.index');
    }

    /**
     * 普通商品列表
     **/
    public function goods_listWt() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['is_fx'] = 0;
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsCommonList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        self::profile_menu('goods_list','goods_list');
        Tpl::showpage('fx_goods_list');
    }
    /**
     * 添加分销商品
     *
     */
    public function add_goodsWt() {
        $model_store_ext = Model('store_extend');
        $store_ext = $model_store_ext->getStoreExtendInfo(array('store_id'=> $_SESSION['store_id']));
        $model_fx_goods = Model('fx_goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_commonid'] = intval($_GET['id']);
        $state = $model_fx_goods->addGoods($condition, $store_ext['fx_commis_rate']);
        if ($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }
    /**
     * 编辑分销商品佣金比例
     *
     */
    public function edit_goodsWt() {
        $fx_commis_rate = intval($_GET['num']);
        if ($fx_commis_rate && $fx_commis_rate <= 30) {
            $model_fx_goods = Model('fx_goods');
            $condition = array();
            $condition['store_id'] = $_SESSION['store_id'];
            $condition['goods_commonid'] = intval($_GET['id']);
            $state = $model_fx_goods->editGoods($condition, array('fx_commis_rate'=> $fx_commis_rate));
        }
        if ($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }
    /**
     * 删除分销
     *
     */
    public function del_goodsWt() {
        $model_fx_goods = Model('fx_goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_commonid'] = intval($_GET['id']);
        $state = $model_fx_goods->delGoods($condition);
        if ($state) {
            showDialog(L('wt_common_op_succ'), 'reload', 'succ');
        } else {
            showDialog(L('wt_common_op_fail'), 'reload', 'error');
        }
    }
    /**
     * 小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'fx_goods':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销商品 ',  'menu_url'=>'index.php?w=store_fx_goods&t=index')
                );
                break;
            case 'goods_list':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'分销商品 ',  'menu_url'=>'index.php?w=store_fx_goods&t=index'),
                    array('menu_key'=>'goods_list','menu_name'=>'添加商品 ',  'menu_url'=>'index.php?w=store_fx_goods&t=goods_list')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
