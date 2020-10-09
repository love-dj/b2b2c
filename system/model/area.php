<?php
/**
 * 地区模型
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class areaModel extends Model {

    public function __construct() {
        parent::__construct('area');
    }

    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getAreaList($condition = array(), $fields = '*', $group = '', $page = null) {
        return $this->where($condition)->field($fields)->page($page)->limit(false)->group($group)->select();
    }

	/**
	 * 从缓存获取分类 通过分类id v1.0
	 *
	 * @param int $id 分类id
	 */
	public function getAreaInfoById($id) {
		$data = $this->getCache();
		return $data['name'][$id];
	}
	
	/**
     * 获取地址详情
     *
     * @return mixed
     */
    public function getAreaInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 获取一级地址（省级）名称数组
     *
     * @return array 键为id 值为名称字符串
     */
    public function getTopLevelAreas() {
        $data = $this->getCache();

        $arr = array();
        foreach ($data['children'][0] as $i) {
            $arr[$i] = $data['name'][$i];
        }

        return $arr;
    }

    /**
     * 获取获取市级id对应省级id的数组
     *
     * @return array 键为市级id 值为省级id
     */
    public function getCityProvince() {
        $data = $this->getCache();

        $arr = array();
        foreach ($data['parent'] as $k => $v) {
            if ($v && $data['parent'][$v] == 0) {
                $arr[$k] = $v;
            }
        }

        return $arr;
    }

    /**
     * 获取地区缓存
     *
     * @return array
     */
    public function getAreas() {
        return $this->getCache();
    }

    /**
     * 获取全部地区名称数组
     *
     * @return array 键为id 值为名称字符串
     */
    public function getAreaNames() {
        $data = $this->getCache();

        return $data['name'];
    }

    /**
     * 获取用于前端js使用的全部地址数组
     *
     * @return array
     */
    public function getAreaArrayForJson($src = 'cache') {
        if ($src == 'cache') {
            $data = $this->getCache();
        } else {
            $data = $this->_getAllArea();
        }

        $arr = array();
        foreach ($data['children'] as $k => $v) {
            foreach ($v as $vv) {
                $arr[$k][] = array($vv, $data['name'][$vv]);
            }
        }
        return $arr;
    }
	
	 /**
     * 根据父类ID获取下级列表用于前端js使用的地址数组
     *
     * @return array
     */
    public function getAreaArrayForJsonByParentId($parent_id=0,$qstr='',$src = 'cache') {
        if ($src == 'cache') {
            $data = $this->getCache();
        } else {
            $data = $this->_getAllArea();
        }
		
        $arr = array();
        foreach ($data['children'][$parent_id] as $k => $v) { //echo json_encode($data['children'][$parent_id]);
			$url = replaceParamAndStr(array('area_id' => $v),$qstr);
				$arr[] = array($v, $data['name'][$v],$url);											 
            //foreach ($v as $vv) {
            //    $arr[$k][] = array($vv, $data['name'][$vv]);
            //}
        }
        return $arr;
    }

    /**
     * 获取地区数组 格式如下
     * array(
     *   'name' => array(
     *     '地区id' => '地区名称',
     *     // ..
     *   ),
     *   'parent' => array(
     *     '子地区id' => '父地区id',
     *     // ..
     *   ),
     *   'children' => array(
     *     '父地区id' => array(
     *       '子地区id 1',
     *       '子地区id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     *   'region' => array(array(
     *     '华北区' => array(
     *       '省级id 1',
     *       '省级id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     * )
     *
     * @return array
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回
        if ($data = rkcache('area')) {
            $this->cachedData = $data;
            return $data;
        }

        // 查库
        $data = $this->_getAllArea();
        wkcache('area', $data);
        $this->cachedData = $data;

        return $data;
    }

    protected $cachedData;

    private function _getAllArea() {
        $data = array();
        $area_all_array = $this->limit(false)->select();
        foreach ((array) $area_all_array as $a) {
            $data['name'][$a['area_id']] = $a['area_name'];
            $data['parent'][$a['area_id']] = $a['area_parent_id'];
            $data['children'][$a['area_parent_id']][] = $a['area_id'];
        
            if ($a['area_deep'] == 1 && $a['area_region'])
                $data['region'][$a['area_region']][] = $a['area_id'];
        }
        return $data;
    }

    public function addArea($data = array()) {
        return $this->insert($data);
    }

    public function editArea($data = array(), $condition = array()) {
        return $this->where($condition)->update($data);
    }

    public function delArea($condition = array()) {
        return $this->where($condition)->delete();
    }

    /**
     * 递归取得本地区及所有上级地区名称
     * @return string
     */
    public function getTopAreaName($area_id,$area_name = '') {
        $info_parent = $this->getAreaInfo(array('area_id'=>$area_id),'area_name,area_parent_id');
        if ($info_parent) {
            return $this->getTopAreaName($info_parent['area_parent_id'],$info_parent['area_name']).' '.$info_parent['area_name'];
        }
    }

    /**
     * 递归取得本地区及所有上级地区ID
     * @return string
     */
    public function getTopAreaID($area_id,$area_name = '') {
        $info_parent = $this->getAreaInfo(array('area_id'=>$area_id),'area_id,area_parent_id');
        if ($info_parent) {
            return $this->getTopAreaID($info_parent['area_parent_id'],$info_parent['area_id']).','.$info_parent['area_id'];
        }
    }


    /**
     * 递归取得本地区所有孩子ID
     * @return array
     */
    public function getChildrenIDs($area_id) {
        $result = array();
        $list = $this->getAreaList(array('area_parent_id'=>$area_id),'area_id');
        if ($list) {
            foreach ($list as $v) {
                $result[] = $v['area_id'];
                $result = array_merge($result,$this->getChildrenIDs($v['area_id']));
            }
        }
        return $result;
    }

    //判断当前地区是省级还是市级还是区级
    public function findAreaDeep($area_id){
        $info_area = $this->getAreaInfo(array('area_id'=>$area_id),'area_deep');
        if($info_area){
            return $info_area['area_deep'];
        }else{
            return 0;
        }
    }
}
