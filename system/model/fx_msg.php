<?php
/**
 * 分销直播聊天 *

 

 */
defined('ShopWT') or exit('Access Denied By ShopWT');

class fx_msgModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //获取聊天表名
    public function getMsgTableName($live_id){
        $last_num = $live_id % 10; //获取店铺ID的末位数字
        $tablenum = ($t = intval(LIVE_MESSAGE_TABLE_NUM)) > 1 ? $t : 1; //处理流量统计记录表数量
        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "fx_msg_$t" : 'fx_msg';
        return $flow_tablename;
    }

    //获取直播访客记录表
    public function getLiveTableName($live_id){
        $last_num = $live_id % 10; //获取店铺ID的末位数字
        $tablenum = ($t = intval(LIVE_MEMBER_TABLE_NUM)) > 1 ? $t : 1; //处理流量统计记录表数量
        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "fx_live_log_$t" : 'fx_live_log';
        return $flow_tablename;
    }

    //获取聊天记录
    public function getTopMessage($table = 'fx_msg', $condition = array(),$number = 10){
        return $this->getMessageList($table, $condition, '*', 0, 'log_id desc', $number);
    }

    //获取聊天列表
    public function getMessageList($table = 'fx_msg', $condition = array(), $field = '*', $page = 0, $order = 'log_id desc', $limit = 0){
        return $this->table($table)->field($field)->where($condition)->order($order)->page($page)->limit($limit)->select();
    }

    //添加聊天记录
    public function addMessage($table = 'fx_msg' ,$data){
        return $this->table($table)->insert($data);
    }

    //添加访客记录
    public function addLiveLog($table = 'fx_live_log',$data){
        return $this->table($table)->insert($data);
    }

    //更新访客记录
    public function updateLiveLog($table = 'fx_live_log', $condition ,$data){
        return $this->table($table)->where($condition)->update($data);
    }

    //获取直播访客记录
    public function getTopLiveMember($table = 'fx_live_log', $condition = array(), $number = 18){
        return $this->getLiveMemberList($table, $condition, '*', 0, 'log_id desc', $number);
    }

    //获取直播访客列表
    public function getLiveMemberList($table = 'fx_live_log', $condition = array(), $field = '*', $page = 0, $order = 'log_id desc', $limit = 0){
        return $this->table($table)->field($field)->where($condition)->order($order)->page($page)->limit($limit)->select();
    }

    //获取直播访客数量
    public function getLiveMemberCount($table = 'fx_live_log', $condition = array()){
        return $this->table($table)->where($condition)->count();
    }


}