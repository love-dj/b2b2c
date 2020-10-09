<?php
/**
 * 分销推广 6.6 * 

 

 */


defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_goodsControl extends BaseHomeControl
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexWt()
    {
        $fx_id = intval($_GET['goods_id']);
        $model_fx_goods = Model('fx_goods');
        $condition = array();
        $condition['fx_id'] = $fx_id;
        $fx_goods = $model_fx_goods->getFenxiaoGoodsInfo($condition);
        $goods_commonid = $fx_goods['goods_commonid'];
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods = $model_goods->getGoodsInfo($condition);
        $goods_id = $goods['goods_id'];
        if ($goods_id && $fx_goods['fx_goods_state'] == 1) {
            $buyer_id = $_SESSION['member_id'];
            if (empty($buyer_id)) {
                $key = $_COOKIE['key'];
                if ($key) {
                    $model_mb_user_token = Model('mb_user_token');
                    $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
                    if ($mb_user_token_info['member_id']) $buyer_id = $mb_user_token_info['member_id'];
                }
            }
            if ($goods['is_fx']) {
                $t = 3600*24;
                if ($buyer_id) {
                    $model_fx_goods->addDisCart($goods_commonid,$fx_goods['member_id'],$buyer_id,$t);
                }
                setWtCookie('fx_' . $goods_commonid, $fx_goods['member_id'],$t);
            }
            $mobilebrowser_list = 'mobile|nokia|iphone|ipad|android|samsung|htc|blackberry';
            if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'])) {
                @header('Location: ' . C('wap_site_url') . '/html/product_detail.html?goods_id=' . $goods_id . '&fx_id=' . $fx_id);
                exit;
            } else {
                @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
                exit;
            }
        } else {
            $mobilebrowser_list = 'mobile|nokia|iphone|ipad|android|samsung|htc|blackberry';
            if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'])) {
                if ($goods_id) {
                    @header('Location: ' . C('wap_site_url') . '/html/product_detail.html?goods_id=' . $goods_id);
                    exit;
                } else {
                    @header('Location: ' . C('wap_site_url'));
                    exit;
                }
            } else {
                if ($goods_id) {
                    @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
                    exit;
                } else {
                    @header('Location: ' . C('shop_site_url'));
                    exit;
                }
            }
        }
    }

    /**
     * 分销商品详情
     */
    public function goods_detailWt()
    {
        $goods_commonid = intval($_GET['goods_id']);
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods = $model_goods->getGoodsInfo($condition);
        $goods_id = $goods['goods_id'];
        @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
        exit;
    }
}
