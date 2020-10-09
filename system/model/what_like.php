<?php
/**
 * 买什么喜欢模型
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');
class what_likeModel extends Model{

    public function __construct(){
        parent::__construct('what_like');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getList($condition,$page=null,$order='',$field='*'){
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 喜欢说说看列表
     */
    public function getGoodsList($condition,$page=null,$order='',$field='*') {
        $on = 'what_goods.commend_id = what_like.like_object_id,what_goods.commend_member_id=member.member_id';
        $result = $this->table('what_goods,what_like,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 喜欢买心得列表
     */
    public function getPersonalList($condition,$page=null,$order='',$field='*') {
        $on = 'what_personal.personal_id = what_like.like_object_id,what_personal.commend_member_id=member.member_id';
        $result = $this->table('what_personal,what_like,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 喜欢店铺列表
     */
    public function getStoreList($condition,$page=null,$order='',$field='*') {
        $result = $this->table('what_like')->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
    }

    /*
     *  判断是否存在
     *  @param array $condition
     *
     */
    public function isExist($condition) {
        $result = $this->getOne($condition);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function save($param){
        return $this->insert($param);
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function saveAll($param){
        return $this->insertAll($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function modify($update, $condition){
        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function drop($condition){
        return $this->where($condition)->delete();
    }

}
