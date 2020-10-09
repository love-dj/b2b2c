<?php
/**
 * 社区成员模型
 *
 *
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class bbs_memberModel extends Model {
    public function __construct(){
        parent::__construct('bbs_member');
    }

    /**
     * 社区成员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getbbsMemberList($condition, $field = '*', $page = 0, $order = 'member_id desc') {
        return $this->where($condition)->field($field)->order($order)->page($page)->select();
    }

    /**
     * 超级管理员列表
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getSuperList($condition, $field = '*', $page = 0, $order = 'member_id desc') {
        $condition['bbs_id'] = 0;
        return $this->getbbsMemberList($condition, $field, $page, $order);
    }

    /**
     * 获得社区成员信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getbbsMenberInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 获取超级管理员信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSuperInfo($condition, $field = '*') {
        $condition['bbs_id'] = 0;
        return $this->getbbsMenberInfo($condition, $field);
    }

    /**
     * 添加管理员
     * @param unknown $insert
     * @return boolean
     */
    public function addbbsMember($insert) {
        $insert['cm_jointime'] = TIMESTAMP;
        $result = $this->insert($insert);
        if ($result) {
            dcache($insert['bbs_id'], 'bbs_managelist');
        }
        return $result;
    }

    /**
     * 添加超级管理员
     * @param unknown $insert
     * @return boolean
     */
    public function addSuper($insert) {
        $insert['bbs_id'] = 0;
        return $this->addbbsMember($insert);
    }

    /**
     * 删除管理员
     * @param unknown $condition
     */
    public function delbbsMember($condition) {
        $result = $this->where($condition)->delete();
        if ($result) {
            dcache($condition['bbs_id'], 'bbs_managelist');
        }
        return $result;
    }

    /**
     * 删除超级管理员
     * @param unknown $condition
     */
    public function delSuper($condition) {
        $condition['bbs_id'] = 0;
        return $this->delbbsMember($condition);
    }
}
