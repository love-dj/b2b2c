<?php
/**
 * 虚拟抢购分类管理
 *
 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class vr_robbuy_classModel extends Model
{
    public function __construct()
    {
        parent::__construct('vr_robbuy_class');
    }

    /**
     * 线下分类信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getVrRobbuyClassInfo($condition, $field = '*')
    {
        return $this->table('vr_robbuy_class')->field($field)->where($condition)->find();
    }

    /**
     * 线下分类列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @param string $limit
     */
    public function getVrRobbuyClassList($condition = array(), $field = '*', $order = 'class_sort', $limit='0,1000')
    {
        return $this->table('vr_robbuy_class')->where($condition)->order($order)->limit($limit)->select();
    }

    /**
     * 添加线下分类
     * @param array $data
     */
    public function addVrRobbuyClass($data)
    {
        return $this->table('vr_robbuy_class')->insert($data);
    }

    /**
     * 编辑线下分类
     * @param array $condition
     * @param array $data
     */
    public function editVrRobbuyClass($condition, $data)
    {
        return $this->table('vr_robbuy_class')->where($condition)->update($data);
    }

    /**
     * 删除线下分类
     * @param array $condition
     */
    public function delVrRobbuyClass($condition)
    {
        return $this->table('vr_robbuy_class')->where($condition)->delete();
    }
}
