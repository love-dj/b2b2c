<?php
/**
 * 积分中心
 *

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class pointsControl extends BasePointShopControl {
    public function __construct() {
        parent::__construct();
        //读取语言包
        Language::read('home_voucher');
    }

    public function indexWt(){
        //查询会员及其附属信息
        parent::pointsMInfo();

        //开启代金券功能后查询推荐的热门代金券列表
        if (C('voucher_allow') == 1){
            $recommend_voucher = Model('voucher')->getRecommendTemplate(6);
            Tpl::output('recommend_voucher',$recommend_voucher);
        }
        //开启积分兑换功能后查询推荐的热门兑换商品列表
        if (C('pointprod_isuse') == 1){
            //热门积分兑换商品
            $recommend_pointsprod = Model('pointprod')->getRecommendPointProd(10);
            Tpl::output('recommend_pointsprod',$recommend_pointsprod);
        }
        //开启平台优惠券功能后查询推荐的优惠券列表
        if (C('coupon_allow') == 1){
            $recommend_rpt = Model('coupon')->getRecommendRpt(10);
            Tpl::output('recommend_rpt',$recommend_rpt);
        }
        
        //SEO
        Model('seo')->type('point')->show();
        //分类导航
        $nav_link = array(
                0=>array('title'=>L('homepage'),'link'=>BASE_SITE_URL),
                1=>array('title'=>L('wt_pointprod'))
        );
        Tpl::output('nav_link_list', $nav_link);
        Tpl::showpage('pointprod');
    }
}
