<?php
/**
 * 社区举报
 *
 *
 *
 *

 
 
 */



defined('ShopWT') or exit('Access Denied By ShopWT');
class bbs_informControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('bbs_inform');
    }

    public function indexWt() {
        $this->inform_listWt();
    }
    /**
     * 举报列表
     */
    public function inform_listWt(){
        Tpl::setDirquna('bbs');
 
Tpl::showpage('bbs_inform');
    }
    
    /**
     * 输出XML数据
     */
    public function get_xmlWt() {
        $model = Model();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('inform_id', 'theme_name', 'inform_content', 'inform_time', 'inform_state', 'member_name', 'member_id', 'bbs_name'
                , 'bbs_id', 'inform_opname', 'inform_opid', 'inform_opexp', 'inform_opresult'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $inform_list = $model->table('bbs_inform')->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($inform_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del(".$value['inform_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
            $param['inform_id'] = $value['inform_id'];
            $param['theme_name'] = "<a href=". $this->spellInformUrl($value) ."  target=\"_blank\">". $value['theme_name'] ."</a>";
            $param['inform_content'] = $value['inform_content'];
            $param['inform_time'] = date('Y-m-d H:i:s', $value['inform_time']);
            $param['inform_state'] = $this->informStatr(intval($value['inform_state']));
            $param['member_name'] = $value['member_name'];
            $param['member_id'] = $value['member_id'];
            $param['bbs_name'] = $value['bbs_name'];
            $param['bbs_id'] = $value['bbs_id'];
            $param['inform_opname'] = $value['inform_opname'] != '' ? $value['inform_opname'] : '--';
            $param['inform_opid'] = $value['inform_opid'] > 0 ? $value['inform_opid'] : '--';
            $param['inform_opexp'] = $value['inform_opexp'] > 0 ? $value['inform_opexp'] : '--';
            $param['inform_opresult'] = $value['inform_opresult'] != '' ? $value['inform_opresult'] : '--';
            $data['list'][$value['inform_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    
    
    /**
     * 删除举报
     */
    public function inform_delWt(){
        $ids = explode(',', $_GET['id']);
        if (count($ids) == 0){
            exit(json_encode(array('state'=>false,'msg'=>L('wrong_argument'))));
        }
        $rs = Model()->table('bbs_inform')->where(array('inform_id'=>array('in', $ids)))->delete();
        if($rs){
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
    /**
     * 举报URL链接
     */
    private function spellInformUrl($param){
        if($param['reply_id'] == 0) return $url = 'index.php?w=theme&t=theme_detail&c_id='.$param['bbs_id'].'&t_id='.$param['theme_id'];

        $where = array();
        $where['bbs_id'] = $param['bbs_id'];
        $where['theme_id']  = $param['theme_id'];
        $where['reply_id']  = array('elt', $param['reply_id']);
        $count = Model()->table('bbs_threply')->where($where)->count();
        $page = ceil($count/15);
        return $url = 'index.php?w=theme&t=theme_detail&c_id='.$param['bbs_id'].'&t_id='.$param['theme_id'].'&curpage='.$page.'#f'.$param['reply_id'];
    }
    /**
     * 举报状态
     */
    private function informStatr($state){
        switch ($state){
            case 0:
                return L('bbs_inform_untreated');
                break;
            case 1:
                return L('bbs_inform_treated');
                break;
        }
    }
}
