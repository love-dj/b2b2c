<?php
/**
 * 商品列表
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class saleControl extends BaseHomeControl {
    const PAGESIZE = 16;
    public function indexWt() {
        $model_xianshi_goods = Model('p_xianshi_goods');
        $model_goods = Model('goods');

        $condition = array();
        $condition['state'] = 1;
        $condition['start_time'] = array('elt',TIMESTAMP);
        $condition['end_time'] = array('gt',TIMESTAMP);
        if ($_GET['gc_id']) {
            $condition['gc_id_1'] = intval($_GET['gc_id']);
        }

        $goods_list = $model_xianshi_goods->getXianshiGoodsExtendList($condition,self::PAGESIZE,'xianshi_goods_id desc');
        $total_page = pagecmd('gettotalpage');
        if (intval($_GET['curpage'] > $total_page)) {
            exit();
        }
        $xs_goods_list = array();
        foreach ($goods_list as $k => $goods_content) {
            $xs_goods_list[$goods_content['goods_id']] = $goods_content;
            $xs_goods_list[$goods_content['goods_id']]['image_url_240'] = cthumb($goods_content['goods_image'], 240, $goods_content['store_id']);
            $xs_goods_list[$goods_content['goods_id']]['down_price'] = $goods_content['goods_price'] - $goods_content['xianshi_price'];
        }
        $condition = array('goods_id' => array('in',array_keys($xs_goods_list)));
        $goods_list = $model_goods->getGoodsOnlineList($condition, 'goods_id,gc_id_1,evaluation_good_star,store_id,store_name', 0, '', self::PAGESIZE, null, false);
        foreach ($goods_list as $k => $goods_content) {
            $xs_goods_list[$goods_content['goods_id']]['evaluation_good_star'] = $goods_content['evaluation_good_star'];
            $xs_goods_list[$goods_content['goods_id']]['store_name'] = $goods_content['store_name'];
            if ($xs_goods_list[$goods_content['goods_id']]['gc_id_1'] != $goods_content['gc_id_1']) {
                //兼容以前版本，如果限时商品表没有保存一级分类ID，则马上保存
                $model_xianshi_goods->editXianshiGoods(array('gc_id_1'=>$goods_content['gc_id_1']),array('xianshi_goods_id'=>$xs_goods_list[$goods_content['goods_id']]['xianshi_goods_id']));
            }
        }

        //查询商品评分信息
        $goodsevallist = Model("evaluate_goods")->getEvaluateGoodsList(array('geval_goodsid'=>array('in',array_keys($xs_goods_list))));
        $eval_list = array();
        if (!empty($goodsevallist)) {
            foreach ($goodsevallist as $v) {
                if($v['geval_content'] == '' || count($eval_list[$v['geval_goodsid']]) >=2) continue;
                $eval_list[$v['geval_goodsid']][] = $v;
            }
        }
        Tpl::output('goodsevallist',$eval_list);

        Tpl::output('goods_list', $xs_goods_list);
        if (!empty($_GET['curpage'])) {
            Tpl::showpage('sale.item','null_layout');
        } else {

            //导航
            $nav_link = array(
                    0=>array(
                            'title'=>Language::get('homepage'),
                            'link'=>BASE_SITE_URL,
                    ),
                    1=>array(
                            'title'=>'限时折扣'
                    )
            );
            Tpl::output('nav_link_list',$nav_link);

            //查询商品分类
            $goods_class = Model('goods_class')->getGoodsClassListByParentId(0);
            Tpl::output('goods_class', $goods_class);

            Tpl::output('total_page',pagecmd('gettotalpage'));
            Tpl::showpage('sale');
        }
    }

}