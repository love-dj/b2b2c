<?php
/**
 * 默认展示页面
 *
 *
 *

 
 
 */


defined('ShopWT') or exit('Access Denied By ShopWT');
class indexControl extends BaseHomeControl{
    public function indexWt(){
        Language::read('home_index_index');
        Tpl::output('index_sign','index');

	//把加密的用户id写入cookie  by 3 3h ao.co m 已换另一个方式，临时去掉此方法
	$uid = intval(base64_decode($_COOKIE['uid']));
        //抢购专区
        Language::read('member_robbuy');
        $model_robbuy = Model('robbuy');
        $group_list = $model_robbuy->getRobbuyCommendedList(5);
        Tpl::output('group_list', $group_list);
		
		//专题获取
        $model_special = Model('news_special');
		$conition = array();
        $conition['special_state'] = 2;
        $special_list = $model_special->getShopindexList($conition);
        Tpl::output('special_list', $special_list);
	
	//友情链接
	$model_link = Model('link');
	$link_list = $model_link->getLinkList($condition,$page);
	if (is_array($link_list)){
		foreach ($link_list as $k => $v){
			if (!empty($v['link_pic'])){
				$link_list[$k]['link_pic'] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common/'.DS.$v['link_pic'];
			}
		}
	}
	Tpl::output('$link_list',$link_list);
		
        //限时折扣
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_item = $model_xianshi_goods->getXianshiGoodsCommendList(8);
        Tpl::output('xianshi_item', $xianshi_item);
		
		//直达楼层信息
		 if (C('wt_lc') != '') {
            $lc_list = @unserialize(C('wt_lc'));
        }
        Tpl::output('lc_list',is_array($lc_list) ? $lc_list : array());
		
		//首页推荐词链接
		 if (C('wt_rc') != '') {
            $rc_list = @unserialize(C('wt_rc'));
        }
        Tpl::output('rc_list',is_array($rc_list) ? $rc_list : array());

        //推荐品牌
        $brand_r_list = Model('brand')->getBrandPassedList(array('brand_recommend'=>1) ,'brand_id,brand_name,brand_pic,brand_xbgpic,brand_tjstore', 0, 'brand_sort asc, brand_id desc', 16);
        Tpl::output('brand_r',$brand_r_list);
		
		
		//评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsList(8);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);
		
		//猜你喜欢
		//$guestulike_goodslist = Model('goods')->getGoodsOnlineList(array('goods_recommend' => 1, 'is_book' => 0), 'goods_id,goods_name,goods_jingle,goods_image,store_id,goods_price,goods_image', 0, 'goods_id', 10, 'goods_commonid');
		$rec_model = Model('goods_recommend');
		$rec_list = Model('goods_recommend')->getGoodsRecommendList(array(),10,'','*','','rec_goods_id');
		$total_page = pagecmd('gettotalpage');
		Tpl::output('guestulike_totalpage',$total_page);
		if (!empty($rec_list)) {
			$guestulike_goodslist = Model('goods')->getGoodsOnlineList(array('goods_id'=>array('in',array_keys($rec_list))));
			if (!empty($guestulike_goodslist)) {
				Tpl::output('guestulike_goodslist',$guestulike_goodslist);
			}
		}
		

        //板块信息
        $model_page_config = Model('page_config');
        $web_html = $model_page_config->getWebHtml('index');
        Tpl::output('web_html',$web_html);
        Model('seo')->type('index')->show();
        Tpl::showpage('index');
    }
	
	public function index_guestulikeWt() {
		if (intval($_GET['curpage'])>5) {
            exit();
        }
        //猜你喜欢
		$model=Model('goods_recommend');
		//$guestulike_goodslist = $model->getGoodsOnlineList(array('goods_recommend' => 1, 'is_book' => 0), 'goods_image,goods_id,goods_name,goods_jingle,goods_image,store_id,goods_price', 10);
		$rec_list =$model->getGoodsRecommendList(array(),10,'','*','','rec_goods_id');
		$total_page = pagecmd('gettotalpage');
		if (!empty($rec_list)) {
			$guestulike_goodslist = Model('goods')->getGoodsOnlineList(array('goods_id'=>array('in',array_keys($rec_list))));
			
		}
		
        if (intval($_GET['curpage']) > $total_page) {
            exit();
        }
        if(!empty($guestulike_goodslist)){
            Tpl::output('guestulike_goodslist',$guestulike_goodslist);
			 Tpl::showpage('index_guestulike.item','null_layout');
        }

       
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

	//闲置物品地区json输出
	public function flea_areaWt() {
		if(intval($_GET['check']) > 0) {
			$_GET['area_id'] = $_GET['region_id'];
		}
		if(intval($_GET['area_id']) == 0) {
			return ;
		}
		$model_area	= Model('flea_area');
		$area_array			= $model_area->getListArea(array('flea_area_parent_id'=>intval($_GET['area_id'])),'flea_area_sort desc');
		$array	= array();
		if(is_array($area_array) and count($area_array)>0) {
			foreach ($area_array as $val) {
				$array[$val['flea_area_id']] = array('flea_area_id'=>$val['flea_area_id'],'flea_area_name'=>htmlspecialchars($val['flea_area_name']),'flea_area_parent_id'=>$val['flea_area_parent_id'],'flea_area_sort'=>$val['flea_area_sort']);
			}
			/**
			 * 转码
			 */
			if (strtoupper(CHARSET) == 'GBK'){
				$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
			} else {
				$array = array_values($array);
			}
		}
		if(intval($_GET['check']) > 0) {//判断当前地区是否为最后一级
			if(!empty($array) && is_array($array)) {
				echo 'false';
			} else {
				echo 'true';
			}
		} else {
			echo json_encode($array);
		}
	}

	//json输出闲置物品分类
	public function josn_flea_classWt() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('flea_class');
		$goods_class		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['gc_id'])));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'gc_sort'=>$val['gc_sort']);
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
		echo json_encode($array);
	}
	
   /**
     * json输出地址数组
     */
    public function json_areaWt()
    {
		
        $_GET['src'] = $_GET['src'] != 'db' ? 'cache' : 'db';
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJson($_GET['src'])).')';
    }
	  /**
     * json输出地址数组
     */
    public function search_json_areaWt()
    {
		//$this->json_areaWt();exit;
		$area_id = intval($_GET['area_id']);
		$qstr = trim($_GET['qstr']);
        $_GET['src'] = $_GET['src'] != 'db' ? 'cache' : 'db';
		shopload('search');
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJsonByParentId($area_id,$qstr,$_GET['src'])).')';
    }

    /**
     * 根据ID返回所有父级地区名称
     */
    public function json_area_showWt()
    {
        $area_info['text'] = Model('area')->getTopAreaName(intval($_GET['area_id']));
        echo $_GET['callback'].'('.json_encode($area_info).')';
    }

    //判断是否登录
    public function loginWt(){
        echo ($_SESSION['is_login'] == '1')? '1':'0';
    }

    /**
     * 头部最近浏览的商品
     */
    public function viewed_infoWt(){
        $info = array();
        if ($_SESSION['is_login'] == '1') {
            $member_id = $_SESSION['member_id'];
            $info['m_id'] = $member_id;
            if (C('voucher_allow') == 1) {
                $time_to = time();//当前日期
                $info['voucher'] = Model()->table('voucher')->where(array('voucher_owner_id'=> $member_id,'voucher_state'=> 1,
                'voucher_start_date'=> array('elt',$time_to),'voucher_end_date'=> array('egt',$time_to)))->count();
            }
            $time_to = strtotime(date('Y-m-d'));//当前日期
            $time_from = date('Y-m-d',($time_to-60*60*24*7));//7天前
            $info['consult'] = Model()->table('consult')->where(array('member_id'=> $member_id,
            'consult_reply_time'=> array(array('gt',strtotime($time_from)),array('lt',$time_to+60*60*24),'and')))->count();
        }
        $goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],5);
        if(is_array($goods_list) && !empty($goods_list)) {
            $viewed_goods = array();
            foreach ($goods_list as $key => $val) {
                $goods_id = $val['goods_id'];
                $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id));
                $val['goods_image'] = thumb($val, 60);
                $viewed_goods[$goods_id] = $val;
            }
            $info['viewed_goods'] = $viewed_goods;
        }
        if (strtoupper(CHARSET) == 'GBK'){
            $info = Language::getUTF8($info);
        }
        echo json_encode($info);
    }
    /**
     * 查询每月的周数组
     */
    public function getweekofmonthWt(){
        import('function.datehelper');
        $year = $_GET['y'];
        $month = $_GET['m'];
        $week_arr = getMonthWeekArr($year, $month);
        echo json_encode($week_arr);
        die;
    }
	//头部选择地区
	public function area_listWt() {
		echo '{"code":200,"message":"操作成功","url":"","data":{"areaList":[{"areaId":36,"areaName":"北京","areaParentId":0,"areaDeep":1,"areaRegion":"华北"},{"areaId":40,"areaName":"天津","areaParentId":0,"areaDeep":1,"areaRegion":"华北"},{"areaId":3,"areaName":"河北","areaParentId":0,"areaDeep":1,"areaRegion":"华北"},{"areaId":4,"areaName":"山西","areaParentId":0,"areaDeep":1,"areaRegion":"华北"},{"areaId":5,"areaName":"内蒙古","areaParentId":0,"areaDeep":1,"areaRegion":"华北"},{"areaId":6,"areaName":"辽宁","areaParentId":0,"areaDeep":1,"areaRegion":"东北"},{"areaId":7,"areaName":"吉林","areaParentId":0,"areaDeep":1,"areaRegion":"东北"},{"areaId":8,"areaName":"黑龙江","areaParentId":0,"areaDeep":1,"areaRegion":"东北"},{"areaId":39,"areaName":"上海","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":10,"areaName":"江苏","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":11,"areaName":"浙江","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":12,"areaName":"安徽","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":13,"areaName":"福建","areaParentId":0,"areaDeep":1,"areaRegion":"华南"},{"areaId":14,"areaName":"江西","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":15,"areaName":"山东","areaParentId":0,"areaDeep":1,"areaRegion":"华东"},{"areaId":16,"areaName":"河南","areaParentId":0,"areaDeep":1,"areaRegion":"华中"},{"areaId":17,"areaName":"湖北","areaParentId":0,"areaDeep":1,"areaRegion":"华中"},{"areaId":18,"areaName":"湖南","areaParentId":0,"areaDeep":1,"areaRegion":"华中"},{"areaId":19,"areaName":"广东","areaParentId":0,"areaDeep":1,"areaRegion":"华南"},{"areaId":20,"areaName":"广西","areaParentId":0,"areaDeep":1,"areaRegion":"华南"},{"areaId":21,"areaName":"海南","areaParentId":0,"areaDeep":1,"areaRegion":"华南"},{"areaId":62,"areaName":"重庆","areaParentId":0,"areaDeep":1,"areaRegion":"西南"},{"areaId":23,"areaName":"四川","areaParentId":0,"areaDeep":1,"areaRegion":"西南"},{"areaId":24,"areaName":"贵州","areaParentId":0,"areaDeep":1,"areaRegion":"西南"},{"areaId":25,"areaName":"云南","areaParentId":0,"areaDeep":1,"areaRegion":"西南"},{"areaId":26,"areaName":"西藏","areaParentId":0,"areaDeep":1,"areaRegion":"西南"},{"areaId":27,"areaName":"陕西","areaParentId":0,"areaDeep":1,"areaRegion":"西北"},{"areaId":28,"areaName":"甘肃","areaParentId":0,"areaDeep":1,"areaRegion":"西北"},{"areaId":29,"areaName":"青海","areaParentId":0,"areaDeep":1,"areaRegion":"西北"},{"areaId":30,"areaName":"宁夏","areaParentId":0,"areaDeep":1,"areaRegion":"西北"},{"areaId":31,"areaName":"新疆","areaParentId":0,"areaDeep":1,"areaRegion":"西北"},{"areaId":32,"areaName":"台湾","areaParentId":0,"areaDeep":1,"areaRegion":"港澳台"},{"areaId":33,"areaName":"香港","areaParentId":0,"areaDeep":1,"areaRegion":"港澳台"},{"areaId":34,"areaName":"澳门","areaParentId":0,"areaDeep":1,"areaRegion":"港澳台"},{"areaId":45055,"areaName":"海外","areaParentId":0,"areaDeep":1,"areaRegion":"海外"}]}}';
	}
}
