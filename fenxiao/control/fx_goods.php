<?php
/**
 * 分销商品
 *
 *
 * *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_goodsControl extends MemberfenxiaoControl{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 分销商品列表页
     */
    public function indexWt(){
        $this->goods_listWt();
    }

    public function goods_listWt(){
        $model_goods = Model('fx_goods');
        $condition = array('member_id'=>$_SESSION['member_id']);
        $condition['fx_goods.fx_goods_state'] = 1;
        if(trim($_GET['goods_name']) != ''){
            $condition['goods_common.goods_name|goods_common.goods_jingle'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        $goods_list = $model_goods->getFenxiaoGoodsCommonList($condition,'*',8);
        Tpl::output('goods_list',$goods_list);
        Tpl::output('show_page',$model_goods->showpage(2));
        Tpl::showpage('fx_goods.list');
    }

    /**
     * 删除分销商品
     */
    public function drop_goodsWt(){
        $fx_id = intval($_GET['fx_id']);
        if($fx_id <= 0){
            showMessage('参数错误');
        }
        $model_goods = Model('fx_goods');
        $condition = array('fx_id' => $fx_id);
        $condition['member_id'] = $_SESSION['member_id'];
        $stat = $model_goods->delFenxiaoGoods($condition);
        if($stat){
            showDialog('删除成功','index.php?w=fx_goods','succ');
        }else{
            showDialog('删除失败','index.php?w=fx_goods','error');
        }
    }
}