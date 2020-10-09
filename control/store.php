<?php
/**
 * 店铺列表
 *
 * by 33h ao.c om 好商 城
*/

defined('ShopWT') or exit('Access Denied By ShopWT');

class storeControl extends BaseHomeControl {
	/**
	 * 店铺列表
	 */
	public function indexWt(){
		//读取语言包
		Language::read('home_store_class_index');	
		$lang	= Language::getLangContent();
				
		//店铺类目快速搜索
		$class_list = rkcache('store_class', true);
		Tpl::output('class_list', $class_list);
		if (!key_exists($_GET['cate_id'], $class_list)) {
			$_GET['cate_id'] = 0;
		}

		//店铺搜索
		$model = Model();
		$condition = array();
		$keyword = trim($_GET['keyword']);

		if ($keyword != '') {
			$condition['store_name|store_zy'] = array('like','%'.$keyword.'%');
		}

		if ($_GET['user_name'] != '') {
			$condition['member_name'] = trim($_GET['user_name']);
		}

		if (!empty($_GET['area_info'])){
			$tabs = preg_split("#\s+#", $_GET['area_info'], -1, PREG_SPLIT_NO_EMPTY);
			$len=count($tabs);
			$area_name=$tabs[$len-1];
			if($area_name)
			{
				$area_name=trim($area_name);
				$condition['area_info'] = array('like','%'.$area_name.'%');
			}
		}
		if ($_GET['cate_id'] > 0){
			$child = array_merge((array)$class_list[$_GET['cate_id']]['child'],array($_GET['cate_id']));
			$condition['sc_id'] = array('in',$child);
		}

		$condition['store_state'] = 1;

		if (!in_array($_GET['order'],array('desc','asc'))){
			unset($_GET['order']);
		}
		if (!in_array($_GET['key'],array('store_sales','store_credit'))){
			unset($_GET['key']);
		}

		$order = 'store_sort asc';

        if (isset($condition['store.store_id'])){
            $condition['store_id'] = $condition['store.store_id'];unset($condition['store.store_id']);
        }
        
        $model_store = Model('store');
        $store_list = $model_store->where($condition)->order($order)->page(10)->select();
        //获取店铺商品数，推荐商品列表等信息
        $store_list = $model_store->getStoreSearchList($store_list);
        //print_r($store_list);exit();
        //信用度排序
        if($_GET['key'] == 'store_credit') {
            if($_GET['order'] == 'desc') {
                $store_list = sortClass::sortArrayDesc($store_list, 'store_credit_average');
            }else {
                $store_list = sortClass::sortArrayAsc($store_list, 'store_credit_average');
            }
        }else if($_GET['key'] == 'store_sales') {//销量排行
            if($_GET['order'] == 'desc') {
                $store_list = sortClass::sortArrayDesc($store_list, 'num_sales_jq');
            }else {
                $store_list = sortClass::sortArrayAsc($store_list, 'num_sales_jq');
            }
        }
		Tpl::output('store_list', $store_list);

		Tpl::output('show_page', $model->showpage(2));
		//当前位置
		if (intval($_GET['cate_id']) > 0){
			$nav_link[1]['link'] = 'index.php?w=store_list';
			$nav_link[1]['title'] = $lang['site_search_store'];
			$nav =$class_list[$_GET['cate_id']];
			//如果有父级
			if ($nav['sc_parent_id'] > 0){
				$tmp = $class_list[$nav['sc_parent_id']];
				//存入父级
				$nav_link[] = array(
					'title'=>$tmp['sc_name'],
					'link'=>"index.php?w=store_list&cate_id=".$nav['sc_parent_id']
				);
			}
			//存入当前级
			$nav_link[] = array(
				'title'=>$nav['sc_name']
			);
		}else{
			$nav_link[1]['link'] = 'index.php';
			$nav_link[1]['title'] = $lang['homepage'];
			$nav_link[2]['title'] = $lang['site_search_store'];
		}

		$purl = $this->getParam();
		Tpl::output('nav_link_list',$nav_link);
		Tpl::output('purl', urlShop($purl['w'], $purl['t'], $purl['param']));

		//SEO
		Model('seo')->type('index')->show();
		Tpl::output('html_title',(empty($_GET['keyword']) ? '' : $_GET['keyword'].' - ').C('site_name').$lang['wt_common_search']);
		
        Tpl::showpage('store');
	}

	function getParam() {
	    $param = $_GET;
	    $purl = array();
	    $purl['w'] = $param['w'];
	    unset($param['w']);
	    $purl['t'] = $param['t'];
	    unset($param['t']); unset($param['curpage']);
	    $purl['param'] = $param;
	    return $purl;
	}
}

class sortClass{
	//升序
	public static function sortArrayAsc($preData,$sortType='store_sort'){
		$sortData = array();
		foreach ($preData as $key_i => $value_i){
			$price_i = $value_i[$sortType];
			$min_key = '';
			$sort_total = count($sortData);
			foreach ($sortData as $key_j => $value_j){
				if($price_i<$value_j[$sortType]){
					$min_key = $key_j+1;
					break;
				}
			}
			if(empty($min_key)){
				array_push($sortData, $value_i);
			}else {
				$sortData1 = array_slice($sortData, 0,$min_key-1);
				array_push($sortData1, $value_i);
				if(($min_key-1)<$sort_total){
					$sortData2 = array_slice($sortData, $min_key-1);
					foreach ($sortData2 as $value){
						array_push($sortData1, $value);
					}
				}
				$sortData = $sortData1;
			}
		}
		return $sortData;
	}
	//降序
	public static function sortArrayDesc($preData,$sortType='store_sort'){
		$sortData = array();
		foreach ($preData as $key_i => $value_i){
			$price_i = $value_i[$sortType];
			$min_key = '';
			$sort_total = count($sortData);
			foreach ($sortData as $key_j => $value_j){
				if($price_i>$value_j[$sortType]){
					$min_key = $key_j+1;
					break;
				}
			}
			if(empty($min_key)){
				array_push($sortData, $value_i);
			}else {
				$sortData1 = array_slice($sortData, 0,$min_key-1);
				array_push($sortData1, $value_i);
				if(($min_key-1)<$sort_total){
					$sortData2 = array_slice($sortData, $min_key-1);
					foreach ($sortData2 as $value){
						array_push($sortData1, $value);
					}
				}
				$sortData = $sortData1;
			}
		}
		return $sortData;
	}
}
