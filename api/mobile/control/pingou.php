<?php
/**
 * 拼团
 *
 *
 *
 * *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class pingouControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function infoWt() {
        $pingou_id = intval($_GET['pingou_id']);
        $buyer_id = intval($_GET['buyer_id']);
        $data = array();
        
        $model_pingou = Model('p_pingou');
        $condition = array();
        $condition['log_id'] = $pingou_id;
        $condition['buyer_id'] = $buyer_id;
        $condition['pay_time'] = array('gt',0);
        $_info = $model_pingou->getOrderInfo($condition);
        if (!empty($_info) && is_array($_info)) {
            $order_id = $_info['order_id'];
            $goods_id = $_info['goods_id'];
            $_end_time = $_info['end_time']-TIMESTAMP;
            $data['pingou_end_time'] = 0;
            
            $member_id = $this->getMemberIdIfExists();
            $data['member_id'] = $member_id;
            
            $model_order = Model('order');
            $order_goods = $model_order->getOrderGoodsInfo(array('order_id'=> $order_id));
            if ($buyer_id == $member_id) $data['order_id'] = $order_id;//当前登录会员订单编号
            
            $condition = array();
            $condition['goods_id'] = $goods_id;
            $pingou_info = $model_pingou->getGoodsInfo($condition);
            if ($_info['lock_state'] && $_end_time > 0) {
                $data['log_id'] = $_info['log_id'];
                $data['buyer_id'] = $_info['buyer_id'];
                $data['pingou_end_time'] = $_end_time;
            }
            $data['goods_id'] = $_info['goods_id'];
            $data['min_num'] = $_info['min_num'];
            
            $data['goods_name'] = $order_goods['goods_name'];
            $data['pingou_price'] = $order_goods['goods_price'];
            $data['goods_image_url'] = cthumb($order_goods['goods_image'], 240);
            
            $data['goods_price'] = $pingou_info['goods_price'];
            
            $log_list = array();
            $buyer_type = $_info['buyer_type'];//参团类型:0为团长,其它为参团
            if ($buyer_type) {
                $_info = $model_pingou->getOrderInfo(array('log_id'=> $buyer_type));
            }
            $log_id = $_info['log_id'];
            if (!empty($_info)) {
                $_array = array();
                $_array['buyer_id'] = $_info['buyer_id'];
                $_array['buyer_name'] = $_info['buyer_name'];
                $_array['buyer_type'] = $_info['buyer_type'];
                $_array['avatar'] = getMemberAvatarForID($_info['buyer_id']);
                $_array['time_text'] = date('Y-m-d H:i:s',$_info['pay_time']);
                $_array['type_text'] = '开团';
                $log_list[] = $_array;
            }
            $condition = array();
            $condition['buyer_type'] = $log_id;
            $condition['goods_id'] = $goods_id;
            $condition['pay_time'] = array('gt',0);
            $list = $model_pingou->table('order_pingou')->where($condition)->order('pay_time asc')->select();
            foreach ($list as $k => $_info) {
                $_array = array();
                $_array['buyer_id'] = $_info['buyer_id'];
                $_array['buyer_name'] = $_info['buyer_name'];
                $_array['buyer_type'] = $_info['buyer_type'];
                $_array['avatar'] = getMemberAvatarForID($_info['buyer_id']);
                $_array['time_text'] = date('Y-m-d H:i:s',$_info['pay_time']);
                $_array['type_text'] = '参团';
                $log_list[] = $_array;
            }
            $num = $_info['min_num']-count($log_list);
            $data['num'] = $num > 0 ? $num:0;
            $data['log_list'] = $log_list;
        }
        output_data(array('pingou_info'=> $data));
    }
	
    public function pingou_listWt() {
       $model_pingou = Model('p_pingou');
	   $page = 10;
	   if(isset($_GET['page']) && intval($_GET['page'])>0){
		   $page = intval($_GET['page']);
	   }
       $condition = array();
	   $condition['start_time'] = array();
	   $condition['end_time'] = array('lt',time());
	   $condition['end_time'] = array('gt',time());
	   $condition['state'] = 1;
	   if(!empty($_GET['gc_id']) && intval($_GET['gc_id'])>0){
		   $condition['gc_id'] = intval($_GET['gc_id']);
	   }
       $goods_list=$model_pingou->getGoodsList($condition,$page);
       foreach($goods_list as $key=>$val)
       {
         $goods_list[$key]['goods_image'] = cthumb($val['goods_image'], 360);
       }
       
        foreach($goods_list as $k=>$v)
       {
         $goods_list[$k]['goods'] = Model('goods')->getGoodsInfo($v['goods_id']);
         $goods_list[$k]['goods_cun'] = Model('order_pingou')->where('goods_id='.$v['goods_id'])->count();
       }
		$page_count = $model_pingou->gettotalpage();
        output_data(array('goods_list'=> $goods_list), mobile_page($page_count));
    }
	
    public function agreementWt() {
        $doc = Model('document')->getOneByCode('pingou_doc');
        output_data($doc);
    }
}
