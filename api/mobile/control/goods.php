<?php
/**
 * 商品
 *


 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class goodsControl extends mobileHomeControl{
    private $PI = 3.14159265358979324;
    private $x_pi = 0;
    
    public function __construct() {
        $this->x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function goods_listWt() {
        $model_goods = Model('goods');
        $model_search = Model('search');
        $_GET['is_book'] = 0;

        //查询条件
        $condition = array();
        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        $condition['is_book'] = 0;
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) {
			//空格搜
			$keys = explode(' ',$_GET['keyword']);
			$datakey = array();
			foreach($keys as $key=>$val){
				$datakey[] = array('like','%' . $val . '%');
			}
			$datakey[] = 'and';
			$condition['goods_name|goods_jingle'] = $datakey;
			
			
            if ($_COOKIE['hisSearch'] == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', $_COOKIE['hisSearch']);
            }
            if (strlen($_GET['keyword']) <= 20 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setcookie('hisSearch', implode('~', $his_sh_list), time()+2592000, '/', SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '', false);

        } elseif (!empty($_GET['barcode'])) {
            $condition['goods_barcode'] = $_GET['barcode'];
        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }
        $price_from = preg_match('/^[\d.]{1,20}$/',$_GET['price_from']) ? $_GET['price_from'] : null;
        $price_to = preg_match('/^[\d.]{1,20}$/',$_GET['price_to']) ? $_GET['price_to'] : null;

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_jingle,goods_price,goods_sale_price,goods_sale_type,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_id,store_name,is_own_shop';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        //全文搜索搜索参数
        $indexer_searcharr = $_GET;
        //搜索消费者保障服务
        $search_ci_arr = array();
        $_GET['ci'] = trim($_GET['ci'],'_');
        if ($_GET['ci'] && $_GET['ci'] != 0 && is_string($_GET['ci'])) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;
        }
        if ($_GET['own_shop'] == 1) {
            $indexer_searcharr['type'] = 1;
        }
        $indexer_searcharr['price_from'] = $price_from;
        $indexer_searcharr['price_to'] = $price_to;

        //优先从全文索引库里查找
        list($goods_list,$indexer_count) = $model_search->indexerSearch($indexer_searcharr,$this->page);
        if (!is_null($goods_list)) {
            $goods_list = array_values($goods_list);
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            //查询消费者保障服务
            $contract_item = array();
            if (C('contract_allow') == 1) {
                $contract_item = Model('contract')->getContractItemByCache();
            }
            //消费者保障服务
            if ($contract_item && $search_ci_arr) {
                foreach ($search_ci_arr as $ci_val) {
                    $condition["contract_{$ci_val}"] = 1;
                }
            }

            if ($price_from && $price_from) {
                $condition['goods_sale_price'] = array('between',"{$price_from},{$price_to}");
            } elseif ($price_from) {
                $condition['goods_sale_price'] = array('egt',$price_from);
            } elseif ($price_to) {
                $condition['goods_sale_price'] = array('elt',$price_to);
            }
            if ($_GET['gift'] == 1) {
                $condition['have_gift'] = 1;
            }
            if ($_GET['own_shop'] == 1) {
                $condition['store_id'] = 1;
            }
            if (intval($_GET['area_id']) > 0) {
                $condition['areaid_1'] = intval($_GET['area_id']);
            }

            //抢购和限时折扣搜索
            $_tmp = array();
            if ($_GET['robbuy'] == 1) {
                $_tmp[] = 1;
            }
            if ($_GET['xianshi'] == 1) {
                $_tmp[] = 2;
            }
            if ($_tmp) {
                $condition['goods_sale_type'] = array('in',$_tmp);
            }
            unset($_tmp);

            //虚拟商品
            if ($_GET['virtual'] == 1) {
                $condition['is_virtual'] = 1;
            }

            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        }
        $page_count = $model_goods->gettotalpage();
        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'is_own_shop desc,goods_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
                case '1' :
                    $result = 'goods_salenum' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' :
                    $result = 'goods_click' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'goods_price' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 处理商品列表(抢购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {
        //获取商品列表编号数组
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $goodsid_array[] = $value['goods_id'];
        }
        
        $sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));
        $sole_array = array_under_reset($sole_array, 'goods_id');

        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['sole_flag']      = false;
            $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = false;
            if (!empty($sole_array[$value['goods_id']])) {
                $goods_list[$key]['goods_price'] = $sole_array[$value['goods_id']]['sole_price'];
                $goods_list[$key]['sole_flag'] = true;
            } else {
                $goods_list[$key]['goods_price'] = $value['goods_sale_price'];
                switch ($value['goods_sale_type']) {
                    case 1:
                        $goods_list[$key]['group_flag'] = true;
                        break;
                    case 2:
                        $goods_list[$key]['xianshi_flag'] = true;
                        break;
                }
                
            }

            //商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);

            unset($goods_list[$key]['goods_sale_type']);
            unset($goods_list[$key]['goods_sale_price']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['wt_distinct']);
        }

        return $goods_list;
    }

    /**
     * 商品详细页
     */
    public function goods_detailWt() {
        $goods_id = intval($_GET['goods_id']);
        $area_id = intval($_GET['area_id']);
        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        if (empty($goods_detail)) {
            output_error('商品不存在');
        }

        // 默认预订商品不支持手机端显示
        if ($goods_detail['is_book']) {
            output_error('预订商品不支持手机端显示');
        }

        $fx_id = intval($_GET['fx_id']);
        if ($fx_id && $goods_detail['is_fx']) {//分销推广
            $condition = array();
            $condition['fx_id'] = $fx_id;
            $model_fx_goods = Model('fx_goods');
            $fx_goods = $model_fx_goods->getFenxiaoGoodsInfo($condition);
            if ($fx_goods['member_id'] && $fx_goods['fx_goods_state'] == 1) setWtCookie('fx_'.$goods_detail['goods_commonid'],$fx_goods['member_id'],3600*24);
        }
        $goods_detail['goods_content']['pingou_sale'] = '0';
        $model_pingou = Model('p_pingou');
        $condition = array();
        $condition['start_time'] = array('lt',TIMESTAMP);
        $condition['end_time'] = array('gt',TIMESTAMP);
        $condition['goods_id'] = $goods_id;
        $condition['state'] = 1;
        $pingou_info = $model_pingou->getGoodsInfo($condition);
        if (!empty($pingou_info) && is_array($pingou_info)) {
            $goods_detail['goods_content']['pingou_sale'] = '1';
			$goods_detail['goods_content']['goods_maxnum'] = $pingou_info['goods_maxnum'];
            $goods_detail['goods_content']['pingou_price'] = $pingou_info['pingou_price'];
            $goods_detail['goods_content']['pingou_goods_price'] = $goods_detail['goods_content']['sale_price'];
            if (empty($goods_detail['goods_content']['pingou_goods_price'])) $goods_detail['goods_content']['pingou_goods_price'] = $goods_detail['goods_content']['goods_price'];
            $_end_time = $pingou_info['end_time']-TIMESTAMP;
            $goods_detail['goods_content']['pingou_end_time'] = $_end_time > 0 ? $_end_time:0;
            $goods_detail['goods_content']['pingou_min_num'] = $pingou_info['min_num'];
        }
        $goods_list = $model_goods->getGoodsContract(array(0=>$goods_detail['goods_content']));
        $goods_detail['goods_content'] = $goods_list[0];
			
		//提取阶梯价格 v6.5
		$quantity = intval($_POST['quantity']);
		$step_prices = $model_goods->getGoodStepPrice(array('common_id'=>$goods_id));
		$goods_detail['goods_content']['step_prices'] = $step_prices;
		$goods_step_price = Model('goods')->getGoodStepPrice('common_id='.$goods_id.' and step_l_num<='.$quantity.' and step_h_num>'.$quantity);
		
		if($goods_step_price){
			$goods_detail['goods_content']['goods_price'] = $goods_step_price[0]['step_price'];
		}
	
		//end	

        //推荐商品
        $hot_sales = $model_goods->getGoodsCommendList($goods_detail['goods_content']['store_id'], 8);
        $goodsid_array = array();
        foreach($hot_sales as $value) {
            $goodsid_array[] = $value['goods_id'];
        }
        $sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));
        $sole_array = array_under_reset($sole_array, 'goods_id');
        $goods_commend_list = array();
        foreach($hot_sales as $value) {
            $goods_commend = array();
            $goods_commend['goods_id'] = $value['goods_id'];
            $goods_commend['goods_name'] = $value['goods_name'];
            $goods_commend['goods_price'] = $value['goods_price'];
            $goods_commend['is_bat']      = $value['is_bat'];//提取阶梯价格 v6.5
            $goods_commend['goods_sale_price'] = $value['goods_sale_price'];
            if (!empty($sole_array[$value['goods_id']])) {
                $goods_commend['goods_sale_price'] = $sole_array[$value['goods_id']]['sole_price'];
            }
            $goods_commend['goods_image_url'] = cthumb($value['goods_image'], 240);
            $goods_commend_list[] = $goods_commend;
        }
        
        $goods_detail['goods_commend_list'] = $goods_commend_list;
        $store_info = Model('store')->getStoreInfoByID($goods_detail['goods_content']['store_id']);

        $goods_detail['store_info']['store_id'] = $store_info['store_id'];
        $goods_detail['store_info']['store_name'] = $store_info['store_name'];
        $goods_detail['store_info']['member_id'] = $store_info['member_id'];
        $goods_detail['store_info']['member_name'] = $store_info['member_name'];
        $goods_detail['store_info']['is_own_shop'] = $store_info['is_own_shop'];

		$goods_detail['store_info']['store_qq'] = $store_info['store_qq'];
		$goods_detail['store_info']['node_chat'] = C('node_chat');

        $goods_detail['store_info']['goods_count'] = $store_info['goods_count'];

        $storeCredit = array();
        $percentClassTextMap = array(
            'equal' => '平',
            'high' => '高',
            'low' => '低',
        );
        foreach ((array) $store_info['store_credit'] as $k => $v) {
            $v['percent_text'] = $percentClassTextMap[$v['percent_class']];
            $storeCredit[$k] = $v;
        }
        $goods_detail['store_info']['store_credit'] = $storeCredit;

        //商品详细信息处理
        $goods_detail = $this->_goods_detail_extend($goods_detail);
	
		//修复抢购限制
		$IsHaveBuy = 0;

		
        // 如果已登录 判断该商品是否已被收藏&&添加浏览记录
        if ($member_id = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getGoodsFavoritesCountByGoodsId($goods_id, $member_id);
            $goods_detail['is_favorate'] = $c > 0;

            QueueClient::push('addViewedGoods', array('goods_id'=>$goods_id,'member_id'=>$member_id));

            if (!$goods_detail['goods_content']['is_virtual']) {
                // 店铺优惠券
                $condition = array();
                $condition['voucher_t_gettype'] = 3;
                $condition['voucher_t_state'] = 1;
                $condition['voucher_t_end_date'] = array('gt', time());
                $condition['voucher_t_store_id'] = array('in', $store_info['store_id']);
                $voucher_template = Model('voucher')->getVoucherTemplateList($condition);
                if (!empty($voucher_template)) {
                    foreach ($voucher_template as $val) {
                        $param = array();
                        $param['voucher_t_id'] = $val['voucher_t_id'];
                        $param['voucher_t_price'] = $val['voucher_t_price'];
                        $param['voucher_t_limit'] = $val['voucher_t_limit'];
                        $param['voucher_t_end_date'] = date('Y年m月d日', $val['voucher_t_end_date']);
                        $goods_detail['voucher'][] = $param;
                    }
                }                
            }

			//抢购限制
            $buyer_id= $member_id;
            $sale_type=$goods_detail['goods_content']["sale_type"];
			
            if($sale_type=='robbuy')
            {
                $upper_limit=$goods_detail['goods_content']["upper_limit"];
				
                if($upper_limit>0)
                {
					
                    $model_order= Model('order');
                    $order_goods_list = $model_order->getOrderGoodsList(array('goods_id'=>$goods_id,'buyer_id'=>$buyer_id,'goods_type'=>2));
                    if($order_goods_list)
                    {
                        $sales_id=$order_goods_list[0]["sales_id"];
                        $model_robbuy = Model('robbuy');
                        $robbuy_info = $model_robbuy->getRobbuyInfo(array('robbuy_id' => $sales_id));
                        if($robbuy_info)
                        {
                            $IsHaveBuy=1;
                        }
                        else
                        {
                            $IsHaveBuy=0;
							
                        }
                    }
                }
            }

	    

        }
		
		//拼团信息
		if ($goods_detail['goods_content']['pingou_sale'] =='1') {
			$model_pingou = Model('p_pingou');
			$condition = array();
			$condition['goods_id'] = $goods_id;
			$condition['pay_time'] = array('gt',0);
			$condition['lock_state'] = 0;
			$condition['end_time'] = array('gt',time());
			//$condition['goods_id'] = $goods_id;
			$p_allnum = $model_pingou->table('order_pingou')->where($condition)->count();
			$lis_pg = $model_pingou->getOrderList($condition, 5);
			$log_list = array();
			if(!empty($lis_pg) && is_array($lis_pg)){
				foreach ($lis_pg as $k => $_info) {
					$condition['buyer_type'] = $_info['log_id'];
					$p_allnum = $model_pingou->table('order_pingou')->where($condition)->count();
					$_array = array();
					$_array['buyer_id'] = $_info['buyer_id'];
					$_array['buyer_name'] = $_info['buyer_name'];
					$_array['buyer_type'] = $_info['buyer_type'];
					$_array['avatar'] = getMemberAvatarForID($_info['buyer_id']);
					$_array['time_text'] = date('Y-m-d H:i:s',$_info['pay_time']);
					$_array['type_text'] = '参团';
					$_array['near_num'] = (($_info['min_num']-$p_allnum)>0)?($_info['min_num']-$p_allnum):0;
					$_array['end_time'] = $_info['end_time']-TIMESTAMP;
					$log_list[] = $_array;
				}
			}
			$goods_detail['goods_content']['pingou_order_num'] = $p_allnum;
			$goods_detail['goods_content']['pingou_order_list'] = $log_list;
		
		}

        // 评价列表
        $goods_eval_list = Model("evaluate_goods")->getEvaluateGoodsList(array('geval_goodsid' => $goods_id), null, '3');
        $goods_eval_list = Handle('evaluate')->evaluateListDity($goods_eval_list);
        $goods_detail['goods_eval_list'] = $goods_eval_list;

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        $goods_detail['goods_evaluate_info'] = $goods_evaluate_info;
        
        $goods_detail['goods_hair_info'] = $this->_calc($area_id, $goods_id);
		//v5.2 修复抢购限制
        $goods_detail['IsHaveBuy']=$IsHaveBuy;
        output_data($goods_detail);
    }

    /**
     * 商品详细信息处理
     */
    private function _goods_detail_extend($goods_detail) {
        //整理商品规格
        unset($goods_detail['spec_list']);
        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];
        unset($goods_detail['spec_list_mobile']);

        //整理商品图片
        unset($goods_detail['goods_image']);
        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        unset($goods_detail['goods_image_mobile']);

        //商品链接
        $goods_detail['goods_content']['goods_url'] = urlShop('goods', 'index', array('goods_id' => $goods_detail['goods_content']['goods_id']));

        //整理数据
        unset($goods_detail['goods_content']['goods_commonid']);
        unset($goods_detail['goods_content']['gc_id']);
        unset($goods_detail['goods_content']['gc_name']);
        unset($goods_detail['goods_content']['store_id']);
        unset($goods_detail['goods_content']['store_name']);
        unset($goods_detail['goods_content']['brand_id']);
        unset($goods_detail['goods_content']['brand_name']);
        unset($goods_detail['goods_content']['type_id']);
        unset($goods_detail['goods_content']['goods_image']);
        unset($goods_detail['goods_content']['goods_body']);
        unset($goods_detail['goods_content']['goods_state']);
        unset($goods_detail['goods_content']['goods_stateremark']);
        unset($goods_detail['goods_content']['goods_verify']);
        unset($goods_detail['goods_content']['goods_verifyremark']);
        unset($goods_detail['goods_content']['goods_lock']);
        unset($goods_detail['goods_content']['goods_addtime']);
        unset($goods_detail['goods_content']['goods_edittime']);
        unset($goods_detail['goods_content']['goods_selltime']);
        unset($goods_detail['goods_content']['goods_show']);
        unset($goods_detail['goods_content']['goods_commend']);
        unset($goods_detail['goods_content']['explain']);
        unset($goods_detail['goods_content']['buynow_text']);
        unset($goods_detail['robbuy_info']);
        unset($goods_detail['xianshi_info']);

        return $goods_detail;
    }

    /**
     * 商品详细页
     */
    public function goods_bodyWt() {
        header("Access-Control-Allow-Origin:*");
        $goods_id = intval($_GET ['goods_id']);

        $model_goods = Model('goods');

        $result1 = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid,goods_body,mobile_body');
        if (empty($result1['goods_body'])) unset($result1['goods_body']); 
        if (empty($result1['mobile_body'])) unset($result1['mobile_body']); 
        $result2 = $model_goods->getGoodsCommonInfoByID($result1['goods_commonid'], 'goods_commonid,goods_body,mobile_body,plateid_top,plateid_bottom');
        $goods_content = array_merge($result2, $result1);
        
        // 手机商品描述
        if ($goods_content['mobile_body'] != '') {
            $mobile_body_array = unserialize($goods_content['mobile_body']);
            $mobile_body = '';
            if (is_array($mobile_body_array)) {
                foreach ($mobile_body_array as $val) {
                    switch ($val['type']) {
                        case 'text':
                            $mobile_body .= '<div>' . $val['value'] . '</div>';
                            break;
                        case 'image':
                            $mobile_body .= '<img src="' . $val['value'] . '">';
                            break;
                    }
                }
            }
            $goods_content['mobile_body'] = $mobile_body;
        }

        $model_plate = Model('store_plate');
        $goods_body = '';
        if ($goods_content['plateid_top'] > 0) {
            $plate_top = $model_plate->getStorePlateInfoByID($goods_content['plateid_top']);
            if (!empty($plate_top)) $goods_body .= '<div class="top-template">'. $plate_top['plate_content'] .'</div>';
        }
        $goods_body .= '<div class="default">' . $goods_content['goods_body'] . '</div>';
        // 底部关联版式
        if ($goods_content['plateid_bottom'] > 0) {
            $plate_bottom = $model_plate->getStorePlateInfoByID($goods_content['plateid_bottom']);
            if (!empty($plate_bottom)) $goods_body .= '<div class="bottom-template">'. $plate_bottom['plate_content'] .'</div>';
        }
        $goods_content['goods_body'] = $goods_body;

        Tpl::output('goods_content', $goods_content);
        Tpl::showpage('goods_body');
    }

    public function goods_evaluateWt() {
        $goods_id = intval($_GET['goods_id']);
        $type = intval($_GET['type']);

        $condition = array();
        $condition['geval_goodsid'] = $goods_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                break;
            case '4':
                $condition['geval_image|geval_image_again'] = array('neq', '');
                break;
            case '5':
                $condition['geval_content_again'] = array('neq', '');
                break;
        }
        
        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $goods_eval_list = $model_evaluate_goods->getEvaluateGoodsList($condition, 10);
        $goods_eval_list = Handle('evaluate')->evaluateListDity($goods_eval_list);

        $page_count = $model_evaluate_goods->gettotalpage();
        output_data(array('goods_eval_list' => $goods_eval_list), mobile_page($page_count));
    }

    /**
     * 商品详细页运费显示
     *
     * @return unknown
     */
    public function calcWt(){
        $area_id = intval($_GET['area_id']);
        $goods_id = intval($_GET['goods_id']);
        $goods_n = 1;
        if (intval($_GET['num'])) $goods_n = intval($_GET['num']);
        output_data($this->_calc($area_id, $goods_id, $goods_n));
    }

    public function _calc($area_id,$goods_id,$goods_n=1){
        $goods_content = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'transport_id,store_id,goods_freight,goods_storage,goods_trans_v');
        $store_info = Model('store')->getStoreInfoByID($goods_content['store_id']);
        if ($area_id <= 0) {
            if (strpos($store_info['deliver_region'],'|')) {
                $store_info['deliver_region'] = explode('|', $store_info['deliver_region']);
                $store_info['deliver_region_ids'] = explode(' ', $store_info['deliver_region'][0]);
            }
            $area_id = intval($store_info['deliver_region_ids'][1]);
            $area_name = $store_info['deliver_region'][1];
        }
        if ($goods_content['transport_id'] && $area_id > 0) {
            $goods_content['goods_n'] = $goods_n;
            $freight_total = Model('transport')->goods_trans_calc($goods_content,$area_id);
            if ($freight_total > 0) {
                if ($store_info['store_free_price'] > 0) {
                    $freight_total = '运费：'.$freight_total.' 元，店铺满 '.$store_info['store_free_price'].' 元 免运费';
                } else {
                    $freight_total = '运费：'.$freight_total.' 元';
                }
            } else {
                if ($freight_total === false) {
                    $if_store = false;
                }
                $freight_total = '免运费';
            }     
        } else {
            $freight_total = $goods_content['goods_freight'] > 0 ? '运费：'.$goods_content['goods_freight'].' 元' : '免运费';
        }

        return array('content'=>$freight_total,'if_store_cn'=>$if_store === false ? '无货' : ($goods_content['goods_storage']>0 ? '有货' : '无货'),'if_store'=>$if_store === false ? false : true,'area_name'=>$area_name ? $area_name : '全国');
    }

    /**
     * 取得店铺分店地址
     */
    public function store_o2o_addrWt() {
        $store_id = intval($_GET['store_id']);
        $lng = $_GET['lng'];
        $lat = $_GET['lat'];
        $condition = array();
        $condition['store_id'] = $store_id;
        $map_list = Model('store_map')->getStoreMapList($condition, '', '', 'map_id asc');
        $map_new_list = array();
        foreach ($map_list as $k => $v) {
            $map_new_list[$k]['key'] = $k;
            $map_new_list[$k]['map_id'] = $v['map_id'];
            $map_new_list[$k]['name_info'] = $v['name_info'];
            $map_new_list[$k]['address_info'] = $v['address_info'];
            $map_new_list[$k]['phone_info'] = $v['phone_info'];
            $map_new_list[$k]['bus_info'] = $v['bus_info'];
            $map_new_list[$k]['city'] = $v['baidu_city'];
            $map_new_list[$k]['district'] = $v['baidu_district'];
            $map_new_list[$k]['lng'] = $v['baidu_lng'];
            $map_new_list[$k]['lat'] = $v['baidu_lat'];
            $gcj = $this->bd_decrypt($v['baidu_lat'], $v['baidu_lng']);
            $map_new_list[$k]['gcjlng'] = $gcj['lon'];
            $map_new_list[$k]['gcjlat'] = $gcj['lat'];
            if ($lng != '' && $lat != '') {
                $map_new_list[$k]['distance'] = $this->getDistance($lat,$lng,$gcj['lat'],$gcj['lon']);
            } else {
                $map_new_list[$k]['distance'] = '';
            }
        }
        if ($lng != '' && $lat != '') {
            usort($map_new_list, function($a,$b){
            	if ($a['distance'] == $b['distance']) return 0;
            	return ($a['distance'] < $b['distance']) ? -1 :1;
            });
        }
		foreach ($map_new_list as $k => $v) {
			$map_new_list[$k]['distance'] = $this->parseDistance($v['distance']);
		}		
        output_data(array('addr_list'=>$map_new_list));
    }

    public function auto_completeWt() {
        $data = Model('search')->autoComplete(array('term'=>$_GET['term']));
        foreach ($data as $k => $v) {
            $data[$k] = $v['value'];
        }
        output_data(array('list'=>$data));
    }

    /**
     * 经纬度转换
     * @param unknown $bdLat
     * @param unknown $bdLon
     * @return multitype:number
     */
    public function bd_decrypt($bdLat, $bdLon) {
        $x = $bdLon - 0.0065; $y = $bdLat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $this->x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $this->x_pi);
        $gcjLon = $z * cos($theta);
        $gcjLat = $z * sin($theta);
        return array('lat' => $gcjLat, 'lon' => $gcjLon);
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
    private function getDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadius = 6367000; //approximate radius of earth in meters
    
        /*
         Convert these degrees to radians
        to work with the formula
        */
    
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
    
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
    
        /*
         Using the
        Haversine formula
    
        http://en.wikipedia.org/wiki/Haversine_formula
    
        calculate the distance
        */
    
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
    
        return round($calculatedDistance);
    }
	private function parseDistance($num = 0){
		$num = floatval($num);
		if ($num >= 1000) {
			$num = $num/1000;
			return str_replace('.0','',number_format($num,1,'.','')).'km';
		} else {
			return $num.'m';
		}
	}

	    /**
     * 打折限时zhekou
     */
    public function goods_dzlistWt() {
        $model_goods = Model('goods');       
	    $model_xianshi_goods = Model('p_xianshi_goods');       

        $condition = array();
        $condition['state'] = 1;
        $condition['start_time'] = array('elt',TIMESTAMP);
        $condition['end_time'] = array('gt',TIMESTAMP);
        
        $goods_list = $model_xianshi_goods->getXianshiGoodsExtendList($condition,15,'xianshi_goods_id desc');
	    $page_count = $model_xianshi_goods->gettotalpage();
		$xs_goods_list = array();
        foreach ($goods_list as $k => $goods_content) {
            $xs_goods_list[$goods_content['goods_id']] = $goods_content;
            $xs_goods_list[$goods_content['goods_id']]['image_url_240'] = cthumb($goods_content['goods_image'], 240, $goods_content['store_id']);
            $xs_goods_list[$goods_content['goods_id']]['down_price'] = $goods_content['goods_price'] - $goods_content['xianshi_price'];
			$xs_goods_list[$goods_content['goods_id']]['endtime'] = $goods_content['end_time'] - TIMESTAMP;
        }
        //查询条件
        $condition = array();
		$condition = array('goods_id' => array('in',array_keys($xs_goods_list)));
        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        $condition['is_book'] = 0;
       
        $price_from = preg_match('/^[\d.]{1,20}$/',$_GET['price_from']) ? $_GET['price_from'] : null;
        $price_to = preg_match('/^[\d.]{1,20}$/',$_GET['price_to']) ? $_GET['price_to'] : null;

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_jingle,goods_price,goods_sale_price,goods_sale_type,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        $fieldstr .= ',have_gift,store_name';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        //搜索消费者保障服务
        $search_ci_arr = array();
        $_GET['ci'] = trim($_GET['ci'],'_');
        if ($_GET['ci'] && $_GET['ci'] != 0 && is_string($_GET['ci'])) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
        }
      
	
		//查询消费者保障服务
		$contract_item = array();
		if (C('contract_allow') == 1) {
			$contract_item = Model('contract')->getContractItemByCache();
		}
		//消费者保障服务
		if ($contract_item && $search_ci_arr) {
			foreach ($search_ci_arr as $ci_val) {
				$condition["contract_{$ci_val}"] = 1;
			}
		}

		if ($price_from && $price_from) {
			$condition['goods_sale_price'] = array('between',"{$price_from},{$price_to}");
		} elseif ($price_from) {
			$condition['goods_sale_price'] = array('egt',$price_from);
		} elseif ($price_to) {
			$condition['goods_sale_price'] = array('elt',$price_to);
		}
		if ($_GET['gift'] == 1) {
			$condition['have_gift'] = 1;
		}
		if ($_GET['own_shop'] == 1) {
			$condition['store_id'] = 1;
		}
		if (intval($_GET['area_id']) > 0) {
			$condition['areaid_1'] = intval($_GET['area_id']);
		}

		//抢购和限时折扣搜索
		$_tmp = array();
		if ($_GET['robbuy'] == 1) {
			$_tmp[] = 1;
		}
		if ($_GET['xianshi'] == 1) {
			$_tmp[] = 2;
		}
		if ($_tmp) {
			$condition['goods_sale_type'] = array('in',$_tmp);
		}
		unset($_tmp);

		//虚拟商品
		if ($_GET['virtual'] == 1) {
			$condition['is_virtual'] = 1;
		}

		$goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, 15);
		$xs_list = array();
        foreach ($goods_list as $k => $goods_content) {
            $xs_list[] = $xs_goods_list[$goods_content['goods_id']];
        }
        
        //处理商品列表(抢购、限时折扣、商品图片)
       // $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $xs_list), mobile_page($page_count));
    }
	    /**
     * 抢购
     */
    public function goods_gblistWt() {
        $model_goods = Model('goods');       
	    $model_robbuy = Model('robbuy');       

        $condition = array(
            'is_vr' => 0,
        );
		$price_from = preg_match('/^[\d.]{1,20}$/',$_GET['price_from']) ? $_GET['price_from'] : null;
        $price_to = preg_match('/^[\d.]{1,20}$/',$_GET['price_to']) ? $_GET['price_to'] : null;

        if ($price_from && $price_from) {
			$condition['robbuy_price'] = array('between',"{$price_from},{$price_to}");
		} elseif ($price_from) {
			$condition['robbuy_price'] = array('egt',$price_from);
		} elseif ($price_to) {
			$condition['robbuy_price'] = array('elt',$price_to);
		}
        $robbuy_list = $model_robbuy->getRobbuyOnlineList($condition, 15, 'robbuy_price asc');
	    $page_count = $model_robbuy->gettotalpage();
		$xs_goods_list = array();
        foreach ($robbuy_list as $k => $goods_content) {
            $xs_goods_list[$goods_content['goods_id']] = $goods_content;   
			$xs_goods_list[$goods_content['goods_id']]['goods_image_url'] = gthumb($goods_content['robbuy_image'],'mid');
			$xs_goods_list[$goods_content['goods_id']]['endtime'] = $goods_content['end_time']-time();
			$xs_goods_list[$goods_content['goods_id']]['robbuy_rand'] = mt_rand(1,100);			
        }
        //查询条件
        $condition = array();
		$condition = array('goods_id' => array('in',array_keys($xs_goods_list)));
        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        $condition['is_book'] = 0;
       
        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_jingle,goods_price,goods_sale_price,goods_sale_type,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        $fieldstr .= ',have_gift,store_name';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        //搜索消费者保障服务
        $search_ci_arr = array();
        $_GET['ci'] = trim($_GET['ci'],'_');
        if ($_GET['ci'] && $_GET['ci'] != 0 && is_string($_GET['ci'])) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
        }
      
	
		//查询消费者保障服务
		$contract_item = array();
		if (C('contract_allow') == 1) {
			$contract_item = Model('contract')->getContractItemByCache();
		}
		//消费者保障服务
		if ($contract_item && $search_ci_arr) {
			foreach ($search_ci_arr as $ci_val) {
				$condition["contract_{$ci_val}"] = 1;
			}
		}

		
		if ($_GET['gift'] == 1) {
			$condition['have_gift'] = 1;
		}
		if ($_GET['own_shop'] == 1) {
			$condition['store_id'] = 1;
		}
		if (intval($_GET['area_id']) > 0) {
			$condition['areaid_1'] = intval($_GET['area_id']);
		}

		$condition['goods_sale_type'] =1;
		

		//虚拟商品
		if ($_GET['virtual'] == 1) {
			$condition['is_virtual'] = 1;
		}

		$goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, 15);
		$xs_list = array();
        foreach ($goods_list as $k => $goods_content) {
            $xs_list[] = $xs_goods_list[$goods_content['goods_id']];
        }
        
        //处理商品列表(抢购、限时折扣、商品图片)
       // $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $xs_list), mobile_page($page_count));
    }

	
	
}
