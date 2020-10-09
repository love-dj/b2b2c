<?php
/**
 * 抢购活动模型
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class robbuyModel extends Model{

    const ROBBUY_STATE_REVIEW = 10;
    const ROBBUY_STATE_NORMAL = 20;
    const ROBBUY_STATE_REVIEW_FAIL = 30;
    const ROBBUY_STATE_CANCEL = 31;
    const ROBBUY_STATE_CLOSE = 32;

    private $robbuy_state_array = array(
        0 => '全部',
        self::ROBBUY_STATE_REVIEW => '审核中',
        self::ROBBUY_STATE_NORMAL => '正常',
        self::ROBBUY_STATE_CLOSE => '已结束',
        self::ROBBUY_STATE_REVIEW_FAIL => '审核失败',
        self::ROBBUY_STATE_CANCEL => '管理员关闭',
    );

    public function __construct() {
        parent::__construct('robbuy');
    }

    /**
     * 读取抢购列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuyList($condition, $page = null, $order = 'state asc', $field = '*', $limit = 0) {
        return $this->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 读取抢购列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuyExtendList($condition, $page = null, $order = 'state asc', $field = '*', $limit = 0) {
        $robbuy_list = $this->getRobbuyList($condition, $page, $order, $field, $limit);
        if(!empty($robbuy_list)) {
            for($i =0, $j = count($robbuy_list); $i < $j; $i++) {
                $robbuy_list[$i] = $this->getRobbuyExtendInfo($robbuy_list[$i]);
            }
        }
        return $robbuy_list;
    }
	 /**
     * 读取抢购列表商品共用IDS
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
	public function getRobbuyGoodsListAndGoodsList($condition,$page=null,$order='',$field='*',$limit=''){
        $on = 'robbuy.goods_id=goods.goods_id';
        $result = $this->table('robbuy,goods')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
		
	}
	public function getRobbuyGoodsExtendIds($condition, $page=null, $order='', $field='goods_id', $limit = 0) {
        $robbuy_goods_id_list = $this->getRobbuyList($condition, $page, $order, $field, $limit);
      
		if(!empty($robbuy_goods_id_list)){
			for($i=0;$i<count($robbuy_goods_id_list); $i++){
				
				$robbuy_goods_id_list[$i]=$robbuy_goods_id_list[$i]['goods_id'];
				 
			}
		}
		
        return $robbuy_goods_id_list;
	}
    /**
     * 读取可用抢购列表
     */
    public function getRobbuyAvailableList($condition) {
        $condition['state'] = array('in', array(self::ROBBUY_STATE_REVIEW, self::ROBBUY_STATE_NORMAL));
        return $this->getRobbuyExtendList($condition);
    }

    /**
     * 查询抢购数量
     * @param array $condition
     * @return int
     */
    public function getRobbuyCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 读取当前可用的抢购列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuyOnlineList($condition, $page = null, $order = 'state asc', $field = '*') {
        $condition['state'] = self::ROBBUY_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        return $this->getRobbuyExtendList($condition, $page, $order, $field);
    }

    /**
     * 读取即将开始的抢购列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuySoonList($condition, $page = null, $order = 'state asc', $field = '*') {
        $condition['state'] = self::ROBBUY_STATE_NORMAL;
        $condition['start_time'] = array('gt', TIMESTAMP);
        return $this->getRobbuyExtendList($condition, $page, $order, $field);
    }

    /**
     * 读取已经结束的抢购列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuyHistoryList($condition, $page = null, $order = 'state asc', $field = '*') {
        $condition['state'] = self::ROBBUY_STATE_CLOSE;
        return $this->getRobbuyExtendList($condition, $page, $order, $field);
    }

    /**
     * 读取推荐抢购列表
     * @param int $limit 要读取的数量
     */
    public function getRobbuyCommendedList($limit = 4) {
        $condition = array();
        $condition['state'] = self::ROBBUY_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        return $this->getRobbuyExtendList($condition, null, 'recommended desc', '*', $limit);
    }

    /**
     * 根据条件读取抢购信息
     * @param array $condition 查询条件
     * @return array 抢购信息
     *
     */
    public function getRobbuyInfo($condition) {
        $robbuy_info = $this->where($condition)->find();
        if (empty($robbuy_info)) return array();
        $robbuy_info = $this->getRobbuyExtendInfo($robbuy_info);
        return $robbuy_info;
    }

    /**
     * 根据条件读取抢购信息
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 抢购列表
     *
     */
    public function getRobbuyOnlineInfo($condition) {
        $condition['state'] = self::ROBBUY_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $robbuy_info = $this->where($condition)->find();
        return $robbuy_info;
    }

    /**
     * 根据抢购编号读取抢购信息
     * @param array $robbuy_id 抢购活动编号
     * @param int $store_id 如果提供店铺编号，判断是否为该店铺活动，如果不是返回null
     * @return array 抢购信息
     *
     */
    public function getRobbuyInfoByID($robbuy_id, $store_id = 0) {
        if(intval($robbuy_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['robbuy_id'] = $robbuy_id;
        $robbuy_info = $this->getRobbuyInfo($condition);

        if($store_id > 0 && $robbuy_info['store_id'] != $store_id) {
            return null;
        } else {
            return $robbuy_info;
        }
    }

    /**
     * 根据商品编号查询是否有可用抢购活动，如果有返回抢购信息，没有返回null
     * @param int $goods_id
     * @return array $robbuy_info
     *
     */
    public function getRobbuyInfoByGoodsCommonID($goods_commonid) {
        $info = $this->_rGoodsRobbuyCache($goods_commonid);
        if (empty($info)) {
            $condition = array();
            $condition['state'] = self::ROBBUY_STATE_NORMAL;
            $condition['end_time'] = array('gt', TIMESTAMP);
            $condition['goods_commonid'] = $goods_commonid;
            $robbuy_goods_list = $this->getRobbuyExtendList($condition, null, 'start_time asc', '*', 1);
            $info['info'] = serialize($robbuy_goods_list[0]);
            $this->_wGoodsRobbuyCache($goods_commonid, $info);
        }
        $robbuy_goods_content = unserialize($info['info']);
        if (!empty($robbuy_goods_content) && ($robbuy_goods_content['start_time'] > TIMESTAMP || $robbuy_goods_content['end_time'] < TIMESTAMP)) {
            $robbuy_goods_content = array();
        }
        return $robbuy_goods_content;
    }

    /**
     * 根据商品编号查询是否有可用抢购活动，如果有返回抢购活动，没有返回null
     * @param string $goods_string 商品编号字符串，例：'1,22,33'
     * @return array $robbuy_list
     *
     */
    public function getRobbuyListByGoodsCommonIDString($goods_commonid_string) {
        $robbuy_list = $this->_getRobbuyListByGoodsCommon($goods_commonid_string);
        $robbuy_list = array_under_reset($robbuy_list, 'goods_commonid');
        return $robbuy_list;
    }

    /**
     * 根据商品编号查询是否有可用抢购活动，如果有返回抢购活动，没有返回null
     * @param string $goods_id_string
     * @return array $robbuy_list
     *
     */
    private function _getRobbuyListByGoodsCommon($goods_commonid_string) {
        $condition = array();
        $condition['state'] = self::ROBBUY_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $condition['goods_commonid'] = array('in', $goods_commonid_string);
        $xianshi_goods_list = $this->getRobbuyExtendList($condition, null, 'robbuy_id desc', '*');
        return $xianshi_goods_list;
    }

    /**
     * 抢购状态数组
     */
    public function getRobbuyStateArray() {
        return $this->robbuy_state_array;
    }


    /*
     * 增加
     * @param array $param
     * @return bool
     *
     */
    public function addRobbuy($param){
        // 发布抢购锁定商品
        $this->_lockGoods($param['goods_commonid']);

        $param['state'] = self::ROBBUY_STATE_REVIEW;
        $param['recommended'] = 0;
        $result = $this->insert($param);
        if ($result) {
            // 更新商品抢购缓存
            $this->_dGoodsRobbuyCache($param['goods_commonid']);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 锁定商品
     */
    private function _lockGoods($goods_commonid) {
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;

        $model_goods = Model('goods');
        $model_goods->editGoodsCommonLock($condition);
    }

    /**
     * 解锁商品
     */
    private function _unlockGoods($goods_commonid) {
        $model_goods = Model('goods');
        $model_goods->editGoodsCommonUnlock(array('goods_commonid' => $goods_commonid));
        // 添加对列 更新商品促销价格
        QueueClient::push('updateGoodsSalePriceByGoodsCommonId', $goods_commonid);
    }

    /**
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function editRobbuy($update, $condition) {
        $robbuy_list = $this->getRobbuyList($condition, null, '', 'goods_commonid');
        $result = $this->where($condition)->update($update);
        if ($result) {
            if (!empty($robbuy_list)) {
                foreach ($robbuy_list as $val) {
                    // 更新商品抢购缓存
                    $this->_dGoodsRobbuyCache($val['goods_commonid']);
                }
            }
        }
        return $result;
    }

    /*
     * 审核成功
     * @param int $robbuy_id
     * @return bool
     *
     */
    public function reviewPassRobbuy($robbuy_id) {
        $condition = array();
        $condition['robbuy_id'] = $robbuy_id;

        $update = array();
        $update['state'] = self::ROBBUY_STATE_NORMAL;

        return $this->editRobbuy($update, $condition);
    }

    /*
     * 审核失败
     * @param int $robbuy_id
     * @return bool
     *
     */
    public function reviewFailRobbuy($robbuy_id) {
        // 商品解锁
        $robbuy_info = $this->getRobbuyInfoByID($robbuy_id);

        $condition = array();
        $condition['robbuy_id'] = $robbuy_id;

        $update = array();
        $update['state'] = self::ROBBUY_STATE_REVIEW_FAIL;

        $return =  $this->editRobbuy($update, $condition);
        if ($return) {
            $this->_unlockGoods($robbuy_info['goods_commonid']);
        }
        return $return;
    }

    /*
     * 取消
     * @param int $robbuy_id
     * @return bool
     *
     */
    public function cancelRobbuy($robbuy_id) {
        // 商品解锁
        $robbuy_info = $this->getRobbuyInfoByID($robbuy_id);

        $condition = array();
        $condition['robbuy_id'] = $robbuy_id;

        $update = array();
        $update['state'] = self::ROBBUY_STATE_CANCEL;

        $return = $this->editRobbuy($update, $condition);
        if ($return) {
            $this->_unlockGoods($robbuy_info['goods_commonid']);
        }
        return $return;
    }

    /**
     * 过期抢购修改状态，解锁对应商品
     */
    public function editExpireRobbuy($condition) {
        $condition['end_time'] = array('lt', TIMESTAMP);
        $condition['state'] = array('in', array(self::ROBBUY_STATE_REVIEW, self::ROBBUY_STATE_NORMAL));

        $expire_robbuy_list = $this->getRobbuyExtendList($condition, null);
        if (!empty($expire_robbuy_list)) {
            $goodscommonid_array = array();
            foreach ($expire_robbuy_list as $val) {
                $goodscommonid_array[] = $val['goods_commonid'];
            }
            // 更新商品促销价格，需要考虑抢购是否在进行中
            QueueClient::push('updateGoodsSalePriceByGoodsCommonId', $goodscommonid_array);
        }
        $robbuy_id_string = '';
        if(!empty($expire_robbuy_list)) {
            foreach ($expire_robbuy_list as $value) {
                $robbuy_id_string .= $value['robbuy_id'].',';
            }
        }

        if($robbuy_id_string != '') {
            $update = array();
            $update['state'] = self::ROBBUY_STATE_CLOSE;
            $condition = array();
            $condition['robbuy_id'] = array('in', rtrim($robbuy_id_string, ','));
            $result = $this->editRobbuy($update, $condition);
            if ($result) {
                foreach ($expire_robbuy_list as $value) {
                    $this->_unlockGoods($value['goods_commonid']);
                }
            }
        }
        return true;
    }

    /*
     * 删除抢购活动
     * @param array $condition
     * @return bool
     *
     */
    public function delRobbuy($condition){
        $robbuy_list = $this->getRobbuyExtendList($condition);
        $result = $this->where($condition)->delete();

        if(!empty($robbuy_list) && $result) {
            foreach ($robbuy_list as $value) {
                // 商品解锁
                $this->_unlockGoods($value['goods_commonid']);
                // 更新商品抢购缓存
                $this->_dGoodsRobbuyCache($value['goods_commonid']);

                list($base_name, $ext) = explode('.', $value['robbuy_image']);
                list($store_id) = explode('_', $base_name);
                $path = BASE_UPLOAD_PATH.DS.ATTACH_ROBBUY.DS.$store_id.DS;
                @unlink($path.$base_name.'.'.$ext);
                @unlink($path.$base_name.'_small.'.$ext);
                @unlink($path.$base_name.'_mid.'.$ext);
                @unlink($path.$base_name.'_max.'.$ext);

                if(!empty($value['robbuy_image1'])) {
                    list($base_name, $ext) = explode('.', $value['robbuy_image1']);
                    @unlink($path.$base_name.'.'.$ext);
                    @unlink($path.$base_name.'_small.'.$ext);
                    @unlink($path.$base_name.'_mid.'.$ext);
                    @unlink($path.$base_name.'_max.'.$ext);
                }
            }
        }
        return true;
    }

    /**
     * 获取抢购扩展信息
     */
    public function getRobbuyExtendInfo($robbuy_info) {
        $robbuy_info['robbuy_url'] = urlShop('robbuy', 'robbuy_detail', array('group_id' => $robbuy_info['robbuy_id']));
        $robbuy_info['goods_url'] = urlShop('goods', 'index', array('goods_id' => $robbuy_info['goods_id']));
        $robbuy_info['start_time_text'] = date('Y-m-d H:i', $robbuy_info['start_time']);
        $robbuy_info['end_time_text'] = date('Y-m-d H:i', $robbuy_info['end_time']);
        if(empty($robbuy_info['robbuy_image1'])) {
            $robbuy_info['robbuy_image1'] = $robbuy_info['robbuy_image'];
        }
        if($robbuy_info['start_time'] > TIMESTAMP && $robbuy_info['state'] == self::ROBBUY_STATE_NORMAL) {
            $robbuy_info['robbuy_state_text'] = '正常(未开始)';
        } elseif ($robbuy_info['end_time'] < TIMESTAMP && $robbuy_info['state'] == self::ROBBUY_STATE_NORMAL) {
            $robbuy_info['robbuy_state_text'] = '已结束';
        } else {
            $robbuy_info['robbuy_state_text'] = $this->robbuy_state_array[$robbuy_info['state']];
        }

        if($robbuy_info['state'] == self::ROBBUY_STATE_REVIEW) {
            $robbuy_info['reviewable'] = 1;
        } else {
            $robbuy_info['reviewable'] = 0;
        }

        if($robbuy_info['state'] == self::ROBBUY_STATE_NORMAL) {
            $robbuy_info['cancelable'] = 1;
        } else {
            $robbuy_info['cancelable'] = 0;
        }

        switch ($robbuy_info['state']) {
            case self::ROBBUY_STATE_REVIEW:
                $robbuy_info['state_flag'] = 'not-verify';
                $robbuy_info['button_text'] = '未审核';
                break;
            case self::ROBBUY_STATE_REVIEW_FAIL:
            case self::ROBBUY_STATE_CANCEL:
            case self::ROBBUY_STATE_CLOSE:
                $robbuy_info['state_flag'] = 'close';
                $robbuy_info['button_text'] = '已结束';
                break;
            case self::ROBBUY_STATE_NORMAL:
                if($robbuy_info['start_time'] > TIMESTAMP) {
                    $robbuy_info['state_flag'] = 'not-start';
                    $robbuy_info['button_text'] = '未开始';
                    $robbuy_info['count_down_text'] = '距抢购开始';
                    $robbuy_info['count_down'] = $robbuy_info['start_time'] - TIMESTAMP;
                } elseif ($robbuy_info['end_time'] < TIMESTAMP) {
                    $robbuy_info['state_flag'] = 'close';
                    $robbuy_info['button_text'] = '已结束';
                } else {
                    $robbuy_info['state_flag'] = 'buy-now';
                    $robbuy_info['button_text'] = '立即抢购';
                    $robbuy_info['count_down_text'] = '距抢购结束';
                    $robbuy_info['count_down'] = $robbuy_info['end_time'] - TIMESTAMP;
                }
                break;
        }
        return $robbuy_info;
    }

    /**
     * 读取商品抢购缓存
     * @param int $goods_commonid
     * @return array/bool
     */
    private function _rGoodsRobbuyCache($goods_commonid) {
        return rcache($goods_commonid, 'goods_robbuy');
    }

    /**
     * 写入商品抢购缓存
     * @param int $goods_commonid
     * @param array $info
     * @return boolean
     */
    private function _wGoodsRobbuyCache($goods_commonid, $info) {
        return wcache($goods_commonid, $info, 'goods_robbuy');
    }

    /**
     * 删除商品抢购缓存
     * @param int $goods_commonid
     * @return boolean
     */
    private function _dGoodsRobbuyCache($goods_commonid) {
        return dcache($goods_commonid, 'goods_robbuy');
    }

    /**
     * 读取抢购分类
     *
     * @return array
     */
    public function getRobbuyClasses()
    {
        return $this->getCachedData('robbuy_classes');
    }

    /**
     * 读取虚拟抢购分类
     *
     * @return array
     */
    public function getRobbuyVrClasses()
    {
        return $this->getCachedData('robbuy_vr_classes');
    }

    /**
     * 读取虚拟抢购地区
     *
     * @return array
     */
    public function getRobbuyVrCities()
    {
        return $this->getCachedData('robbuy_vr_cities');
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存键
     */
    public function dropCachedData($key) {
        unset($this->cachedData[$key]);
        dkcache($key);
    }

    /**
     * 获取缓存
     *
     * @param string $key 缓存键
     * @return array 缓存数据
     */
    protected function getCachedData($key) {

        $data = $this->cachedData[$key];

        // 属性中存在则返回
        if ($data || is_array($data)) {
            return $data;
        }

        $data = rkcache($key);

        // 缓存中存在则返回
        if ($data || is_array($data)) {
            // 写入属性
            $this->cachedData[$key] = $data;
            return $data;
        }

        $data = $this->getCachingDataByQuery($key);

        // 写入缓存
        wkcache($key, $data);

        // 写入属性
        $this->cachedData[$key] = $data;

        return $data;
    }

    protected function getCachingDataByQuery($key) {
        $data = array();

        switch ($key) {
        case 'robbuy_classes': // 抢购分类
            $classes = Model()->table('robbuy_class')->order('sort asc')->limit(false)->select();
            foreach ((array) $classes as $v) {
                $id = $v['class_id'];
                $pid = $v['class_parent_id'];
                $data['name'][$id] = $v['class_name'];
                $data['parent'][$id] = $pid;
                $data['children'][$pid][] = $id;
            }
            break;

        case 'robbuy_vr_classes': // 虚拟抢购分类
            $classes = Model()->table('vr_robbuy_class')->order('class_sort asc')->limit(false)->select();
            foreach ((array) $classes as $v) {
                $id = $v['class_id'];
                $pid = $v['parent_class_id'];
                $data['name'][$id] = $v['class_name'];
                $data['parent'][$id] = $pid;
                $data['children'][$pid][] = $id;
            }
            break;

        case 'robbuy_vr_cities': // 虚拟抢购地区
            // 一级地区 城市
            $arr = (array) Model()->table('vr_robbuy_area')->where(array(
                'hot_city' => 1,
                'parent_area_id' => 0,
            ))->order('area_id asc')->limit(false)->key('area_id')->select();
            foreach ($arr as $v) {
                $id = $v['area_id'];
                $pid = $v['parent_area_id'];
                $data['name'][$id] = $v['area_name'];
                $data['parent'][$id] = $pid;
                $data['children'][$pid][] = $id;
            }
            if ($pids = array_keys($arr)) {
                // 二级地区 区域
                $arr = (array) Model()->table('vr_robbuy_area')->where(array(
                    'parent_area_id' => array('in', $pids),
                ))->order('area_id asc')->limit(false)->key('area_id')->select();
                foreach ($arr as $v) {
                    $id = $v['area_id'];
                    $pid = $v['parent_area_id'];
                    $data['name'][$id] = $v['area_name'];
                    $data['parent'][$id] = $pid;
                    $data['children'][$pid][] = $id;
                }
                if ($pids = array_keys($arr)) {
                    // 三级地区 街区
                    $arr = (array) Model()->table('vr_robbuy_area')->where(array(
                        'parent_area_id' => array('in', $pids),
                    ))->order('area_id asc')->limit(false)->key('area_id')->select();
                    $pids = array_keys($arr);
                    foreach ($arr as $v) {
                        $id = $v['area_id'];
                        $pid = $v['parent_area_id'];
                        $data['name'][$id] = $v['area_name'];
                        $data['parent'][$id] = $pid;
                        $data['children'][$pid][] = $id;
                    }
                }
            }
            break;

        default:
            throw new Exception("Invalid data key: {$key}");
        }

        return $data;
    }

    /**
     * 缓存数据（抢购分类、虚拟抢购分类、虚拟抢购地区）
     * 数组键为缓存名称 值为缓存数据
     *
     * 例 抢购分类缓存数据格式如下
     * array(
     *   'name' => array(
     *     '分类id' => '分类名称',
     *     // ..
     *   ),
     *   'parent' => array(
     *     '子分类id' => '父分类id',
     *     // ..
     *   ),
     *   'children' => array(
     *     '父分类id' => array(
     *       '子分类id 1',
     *       '子分类id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     * )
     *
     * @return array
     */
    protected $cachedData;

}
