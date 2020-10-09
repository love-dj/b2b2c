<?php
/**
 * 手机端首页控制
 * 


 

 *
 */

defined('ShopWT') or exit('Access Denied By ShopWT');
class indexControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    } 

    /**
     * 首页
     */
    public function indexWt() {
        $model_mb_special = Model('mb_special');
        $data = $model_mb_special->getMbSpecialIndex();
        //dumps($data);die;
		$seo = Model('seo')->type('index')->showwap();
			//分享用户id
			$share_member_info = '';
			$share_member_id = $this->getMemberIdIfExists();
			if($share_member_id>0){
				$encode_member_id = base64_encode(intval($share_member_id)*1);
				$share_member_info = "?smid=".$encode_member_id;
				if(strpos($url,'?') !==false){
					$share_member_info = "&smid=".$encode_member_id;
				}
			}
//			var_dump($data);die;
        $data[6]['goods']['item'] = array_splice($data[6]['goods']['item'],0,9);
//			var_dump($data[6]['goods']['item']);die;
        $this->_output_special(array('index'=>$data,'seo'=>$seo,'sm_info'=>$share_member_info), $_GET['type']);
    }

    /**
     * 专题
     */
    public function specialWt() {
        $model_mb_special = Model('mb_special');
        $info = $model_mb_special->getMbSpecialInfoByID($_GET['special_id']);
        $list = $model_mb_special->getMbSpecialItemUsableListByID($_GET['special_id']);
        $data = array_merge($info, array('list' => $list));
        $this->_output_special($data, $_GET['type'], $_GET['special_id']);
    }

    /**
     * 输出专题
     */
    private function _output_special($data, $type = 'json', $special_id = 0) {
        $model_special = Model('mb_special');
        if($_GET['type'] == 'html') {
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
            output_data($data);
        }
    }

    /**
     * android客户端版本号
     */
    public function apk_versionWt() {
        $version = C('mobile_apk_version');
        $url = C('mobile_apk');
        if(empty($version)) {
           $version = '';
        }
        if(empty($url)) {
            $url = '';
        }

        output_data(array('version' => $version, 'url' => $url));
    }

    /**
     * 默认搜索词列表
     */
    public function search_key_listWt() {
        $list = @explode(',',C('hot_search'));
        if (!$list || !is_array($list)) { 
            $list = array();
        }
        if ($_COOKIE['hisSearch'] != '') {
            $his_search_list = explode('~', $_COOKIE['hisSearch']);
        }
        if (!$his_search_list || !is_array($his_search_list)) {
            $his_search_list = array();
        }
        output_data(array('list'=>$list,'his_list'=>$his_search_list));
    }

    /**
     * 热门搜索列表
     */
    public function search_hot_infoWt() {
        if (C('rec_search') != '') {
            $rec_search_list = @unserialize(C('rec_search'));
        }
        $rec_search_list = is_array($rec_search_list) ? $rec_search_list : array();
        $result = $rec_search_list[array_rand($rec_search_list)];
        output_data(array('hot_info'=>$result ? $result : array()));
    }

    /**
     * 高级搜索
     */
    public function search_showWt() {
        $area_list = Model('area')->getAreaList(array('area_deep'=>1),'area_id,area_name');
        if (C('contract_allow') == 1) {
            $contract_list = Model('contract')->getContractItemByCache();
            $_tmp = array();$i = 0;
            foreach ($contract_list as $k => $v) {
                $_tmp[$i]['id'] = $v['cti_id'];
                $_tmp[$i]['name'] = $v['cti_name'];
                $i++;
            }
        }
		$max_goods_class = Model('goods_class')->getGoodsClassListByParentId(0);
		if(!empty($max_goods_class)&& is_array($max_goods_class)){
			foreach($max_goods_class as $key=>$val){
				if($val['is_show']==0){
					$goods_list[] = array('gc_id'=>$val['gc_id'],'gc_name'=>$val['gc_name']);
				}
			}
		}
        output_data(array('area_list'=>$area_list ? $area_list : array(),'contract_list'=>$_tmp,'gclist'=>$goods_list));
    }
	
	/**
     * 公告列表 
     */
    public function getggWt() {
        if(!empty($_GET['ac_id']) && intval($_GET['ac_id']) > 0)
		{
			$article_class_model	= Model('article_class');
			$article_model	= Model('article');
			$condition	= array();
			
			$child_class_list = $article_class_model->getChildClass(intval($_GET['ac_id']));
			$ac_ids	= array();
			if(!empty($child_class_list) && is_array($child_class_list)){
				foreach ($child_class_list as $v){
					$ac_ids[]	= $v['ac_id'];
				}
			}
			$ac_ids	= implode(',',$ac_ids);
			$condition['ac_ids']	= $ac_ids;
			$condition['article_show']	= '1';
			$article_list = $article_model->getArticleList($condition,5);			
			//$article_type_name = $this->article_type_name($ac_ids);
			//output_data(array('article_list' => $article_list, 'article_type_name'=> $article_type_name));
			output_data(array('article_list' => $article_list));		
		}
		else {
			output_error('缺少参数:文章类别编号');
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
        @header('Location: ' . WAP_SITE_URL.'/html/product_detail.html?goods_id='.$goods_id);
        exit;
    }

}
