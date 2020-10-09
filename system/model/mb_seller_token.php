<?php
/**
 * 客户端商家令牌模型
 *

 
 
 */

defined('ShopWT') or exit('Access Denied By ShopWT');

class mb_seller_tokenModel extends Model{
    public function __construct(){
        parent::__construct('mb_seller_token');
    }

    /**
     * 查询
     *
     * @param array $condition 查询条件
     * @return array
     */
    public function getSellerTokenInfo($condition) {
        return $this->where($condition)->find();
    }

    public function getSellerTokenInfoByToken($token) {
        if(empty($token)) {
            return null;
        }
        return $this->getSellerTokenInfo(array('token' => $token));
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addSellerToken($param){
        return $this->insert($param);
    }

    /**
     * 删除
     *
     * @param int $condition 条件
     * @return bool 布尔类型的返回结果
     */
    public function delSellerToken($condition){
        return $this->where($condition)->delete();
    }
}
