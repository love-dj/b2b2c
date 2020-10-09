<?php
/**
 * 分销商品
 *
 *
 *
 * *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_goodsModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 增加记录
     *
     * @param
     * @return bool
     */
    public function addGoods($condition, $fx_commis_rate) {
        $model_goods = Model('goods');
        $data = array();
        $data['is_fx'] = 1;
        $data['fx_add_time'] = time();
        $data['fx_commis_rate'] = $fx_commis_rate; 
        $result = $model_goods->editGoodsCommon($data, $condition);
        if ($result) {
            $model_goods->editGoods(array('is_fx' => 1), $condition);
            $data = array();
            $data['store_id'] = $condition['store_id'];
            $data['goods_commonid'] = $condition['goods_commonid'];
            $data['num_update_time'] = time();
            $data['pay_update_time'] = time();
            $this->table('fx_goods_sta')->insert($data,true);
        }
        return $result;
    }

    /**
     * 修改记录
     *
     * @param
     * @return bool
     */
    public function editGoods($condition, $data) {
        $model_goods = Model('goods');
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $model_goods->editGoodsCommon($data, $condition);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除记录
     *
     * @param
     * @return bool
     */
    public function delGoods($condition) {
        $model_goods = Model('goods');
        $data = array();
        $data['is_fx'] = 0;
        $data['fx_add_time'] = 0;
        $data['fx_commis_rate'] = 0; 
        $result = $model_goods->editGoodsCommon($data, $condition);
        if ($result) {
            $model_goods->editGoods(array('is_fx' => 0), $condition);
            $goods_commonid = $condition['goods_commonid'];
            $condition = array();
            $condition['goods_commonid'] = $goods_commonid;
            $this->delFenxiaoGoods($condition);//取消分销时删除已经领取的记录
        }
        return $result;
    }

    /**
     * 分销商品列表
     *
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array 二维数组
     */
    public function getFenxiaoGoodsList($condition = array(), $field = '*', $page = 0,$order = 'goods_commonid desc', $limit = 0) {
        return $this->table('fx_goods')->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
    }

    public function getFenxiaoGoodsCount($condition){
        return $this->table('fx_goods')->where($condition)->count();
    }

    /**
     * 分销商品公共信息列表
     *
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array 二维数组
     */
    public function getFenxiaoGoodsCommonList($condition = array(), $field = '*', $page = 0,$order = 'fx_goods.goods_commonid desc', $limit = 0){
        return $this->table('fx_goods,goods_common')->join('Left')->on('fx_goods.goods_commonid = goods_common.goods_commonid')->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销商品信息列表
     *
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array 二维数组
     */
    public function getFenxiaoGoodsInfoList($condition = array(), $field = '*', $page = 0, $order = 'fx_goods.goods_commonid desc', $limit = 0, $group = ''){
        return $this->table('fx_goods,goods')->join('Left')->on('fx_goods.goods_commonid = goods.goods_commonid')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->page($page)->select();
    }

    /**
     * 分销商品领取
     *
     * @param array $data
     * @return bool
     */
    public function addFenxiaoGoods($data){
        return $this->table('fx_goods')->insert($data);
    }

    /**
     * 获取单个分销商品信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getFenxiaoGoodsInfo($condition, $field = '*'){
        return $this->table('fx_goods')->field($field)->where($condition)->find();
    }

    /**
     * 更新分销商品信息
     *
     * @param array $condition
     * @param array $data
     * @return bool
     */
    public function updateFenxiaoGoods($condition, $data){
        return $this->table('fx_goods')->where($condition)->update($data);
    }

    /**
     * 软删除分销商品
     *
     * @param array $condition
     * @return bool
     */
    public function delFenxiaoGoods($condition){
        $condition['fx_goods_state'] = 1;
        $this->updateDisSta($condition);
        return $this->table('fx_goods')->where($condition)->update(array('fx_goods_state'=>2,'quite_time'=>time()));
    }

    /**
     * 真实删除分销商品
     *
     * @param array $condition
     * @return bool
     */
    public function dbDelFenxiaoGoods($condition){
        return $this->table('fx_goods')->where($condition)->delete();
    }

    /**
     * 分销商品统计
     *
     * @param array $goods_list
     * @return array
     */
    public function getDisStaList($goods_list) {
        $list = array();
        if (!empty($goods_list)) {
            $commonid_array = array();
            foreach ($goods_list as $value) {
                $commonid_array[] = $value['goods_commonid'];
            }
            $list = $this->table('fx_goods_sta')->where(array('goods_commonid' => array('in', $commonid_array)))->key('goods_commonid')->select();
        }
        return $list;
    }
    public function updateDisSta($condition){
        $fx_list = $this->table('fx_goods')->field('count(fx_id) as fx_num,goods_commonid')->group('goods_commonid')->where($condition)->limit(9999)->select();
        if (!empty($fx_list)) {
            foreach ($fx_list as $v) {
                $condition = array();
                $condition['goods_commonid'] = $v['goods_commonid'];
                $data = array();
                $_fx_num = $v['fx_num'];
                $data['fx_num'] = array('exp','fx_num-'.$_fx_num);
                $this->editDisSta($condition, $data);
            }
        }
    }

    /**
     * 分销商品统计单条记录
     *
     * @param array
     * @return array
     */
    public function getDisStaInfo($condition = array(), $order = 'goods_commonid desc') {
        return $this->table('fx_goods_sta')->where($condition)->order($order)->find();
    }

    /**
     * 修改分销商品统计记录
     *
     * @param
     * @return bool
     */
    public function editDisSta($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('fx_goods_sta')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 增加记录
     *
     * @param
     * @return bool
     */
    public function addDisCart($goods_commonid,$fx_member_id,$buyer_id,$t = 86400) {
        $data = array();
        $data['goods_commonid'] = $goods_commonid;
        $data['fx_member_id'] = $fx_member_id;
        $data['buyer_id'] = $buyer_id;
        $data['end_time'] = time()+$t;
        $result = $this->table('fx_cart')->insert($data,true);
        return $result;
    }
    public function getDisCartList($buyer_id){
        $condition = array();
        $condition['buyer_id'] = $buyer_id;
        $condition['end_time'] = array('gt',TIMESTAMP);
        $result = $this->table('fx_cart')->field('fx_member_id,goods_commonid')->where($condition)->limit(9999)->key('goods_commonid')->select();
        return $result;
    }
    public function delDisCart($goods_commonid,$buyer_id) {
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $condition['buyer_id'] = $buyer_id;
        $result = $this->table('fx_cart')->where($condition)->delete();
        return $result;
    }
}
