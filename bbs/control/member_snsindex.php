<?php
/**
 * SNS首页
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_snsindexControl extends BasebbsControl {
    const MAX_RECORDNUM = 20;//允许插入新记录的最大条数(注意在sns中该常量是一样的，注意与member_snshome中的该常量一致)
    public function __construct(){
        parent::__construct();
        Tpl::output('relation','3');//为了跟home页面保持一致所以输出此变量
        Language::read('member_sns');
        //允许插入新记录的最大条数
        Tpl::output('max_recordnum',self::MAX_RECORDNUM);
        if(!$_SESSION['is_login']){
            showDialog('请登录','','error','login_dialog()');
        }
    }
    /**
     * 喜欢商品(访客登录后操作)
     */
    public function editlikeWt(){
        $obj_validate = new Validate();
        $validate_arr[] = array("input"=>$_GET["id"], "require"=>"true","message"=>Language::get('sns_likegoods_choose'));
        $obj_validate -> validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            showDialog($error,'','error');
        }
        //查询会员信息
        $member_model = Model('member');
        $member_info = $member_model->getMemberInfo(array('member_id'=>$_SESSION['member_id'],'member_state'=>1));
        if (empty($member_info)){
            showDialog(Language::get('sns_member_error'),'','error');
        }
        //查询商品信息
        $goods_model = Model('goods');
        $goods_content = $goods_model->getGoodsOnlineInfoForShare(intval($_GET["id"]));
        if (empty($goods_content)){
            showDialog(Language::get('sns_goods_error'),'','error');
        }
        $sharegoods_model = Model('sns_sharegoods');
        //判断该商品是否已经存在分享记录
        $sharegoods_content = $sharegoods_model->getSharegoodsInfo(array('share_memberid'=>"{$_SESSION['member_id']}",'share_goodsid'=>"{$goods_content['goods_id']}"));
        if (!empty($sharegoods_content) && $sharegoods_content['share_islike'] == 1){
            showDialog(Language::get('sns_likegoods_exist'),'','error');
        }
        if (empty($sharegoods_content)){
            //添加分享商品信息
            $insert_arr = array();
            $insert_arr['share_goodsid'] = $goods_content['goods_id'];
            $insert_arr['share_memberid'] = $_SESSION['member_id'];
            $insert_arr['share_membername'] = $_SESSION['member_name'];
            $insert_arr['share_content'] = '';
            $insert_arr['share_likeaddtime'] = time();
            $insert_arr['share_privacy'] = 0;
            $insert_arr['share_commentcount'] = 0;
            $insert_arr['share_islike'] = 1;
            $result = $sharegoods_model->sharegoodsAdd($insert_arr);
            unset($insert_arr);
        }else {
            //更新分享商品信息
            $update_arr = array();
            $update_arr['share_likeaddtime'] = time();
            $update_arr['share_islike'] = 1;
            $result = $sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$sharegoods_content['share_id']}"));
            unset($update_arr);
        }
        if ($result){
            //商品缓存数据更新
            //生成缓存的键值
            $hash_key = $goods_content['goods_id'];
            //先查找$hash_key缓存
            if ($_cache = rcache($hash_key,'product')){
                $_cache['likenum'] = intval($_cache['likenum'])+1;
                //缓存商品信息
                wcache($hash_key,$_cache,'product');
            }
            //更新SNS商品表信息
            $snsgoods_model = Model('sns_goods');
            $snsgoods_content = $snsgoods_model->getGoodsInfo(array('snsgoods_goodsid'=>"{$goods_content['goods_id']}"));
            if (empty($snsgoods_content)){
                //添加SNS商品
                $insert_arr = array();
                $insert_arr['snsgoods_goodsid'] = $goods_content['goods_id'];
                $insert_arr['snsgoods_goodsname'] = $goods_content['goods_name'];
                $insert_arr['snsgoods_goodsimage'] = $goods_content['goods_image'];
                $insert_arr['snsgoods_goodsprice'] = $goods_content['goods_price'];
                $insert_arr['snsgoods_storeid'] = $goods_content['store_id'];
                $insert_arr['snsgoods_storename'] = $goods_content['store_name'];
                $insert_arr['snsgoods_addtime'] = time();
                $insert_arr['snsgoods_likenum'] = 1;
                $insert_arr['snsgoods_likemember'] = "{$_SESSION['member_id']}";
                $insert_arr['snsgoods_sharenum'] = 0;
                $snsgoods_model->goodsAdd($insert_arr);
                unset($insert_arr);
            }else {
                //更新SNS商品
                $update_arr = array();
                $update_arr['snsgoods_likenum'] = intval($snsgoods_content['snsgoods_likenum'])+1;
                $likemember_arr = array();
                if (!empty($snsgoods_content['snsgoods_likemember'])){
                    $likemember_arr = explode(',',$snsgoods_content['snsgoods_likemember']);
                }
                $likemember_arr[] = $_SESSION['member_id'];
                $update_arr['snsgoods_likemember'] = implode(',',$likemember_arr);
                $snsgoods_model->editGoods($update_arr,array('snsgoods_goodsid'=>"{$goods_content['goods_id']}"));
            }
            //添加喜欢动态
            $tracelog_model = Model('sns_tracelog');
            $insert_arr = array();
            $insert_arr['trace_originalid'] = '0';
            $insert_arr['trace_originalmemberid'] = '0';
            $insert_arr['trace_memberid'] = $_SESSION['member_id'];
            $insert_arr['trace_membername'] = $_SESSION['member_name'];
            $insert_arr['trace_memberavatar'] = $member_info['member_avatar'];
            $insert_arr['trace_title'] = Language::get('sns_likegoods_title');
            $content_str = '';
            $content_str .= "<div class=\"fd-media\">
                <div class=\"goodsimg\"><a target=\"_blank\" href=\"".urlShop('goods', 'index', array('goods_id'=>$goods_content['goods_id']))."\"><img src=\"".thumb($goods_content, 240)."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$goods_content['goods_name']}\"></a></div>
                <div class=\"goodsinfo\">
                    <dl>
                        <dt><a target=\"_blank\" href=\"".urlShop('goods', 'index', array('goods_id'=>$goods_content['goods_id']))."\">".$goods_content['goods_name']."</a></dt>
                        <dd>".Language::get('sns_sharegoods_price').Language::get('wt_colon').Language::get('currency').$goods_content['goods_price']."</dd>
                        <dd>".Language::get('sns_sharegoods_freight').Language::get('wt_colon').Language::get('currency').$goods_content['goods_freight']."</dd>
                        <dd wttype=\"collectbtn_{$goods_content['goods_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods(\'{$goods_content['goods_id']}\',\'succ\',\'collectbtn_{$goods_content['goods_id']}\');\">".Language::get('sns_sharegoods_collect')."</a>&nbsp;&nbsp;(".$goods_content['goods_collect'].Language::get('sns_collecttip').")</dd>
                    </dl>
                  </div>
             </div>";
            $insert_arr['trace_content'] = $content_str;
            $insert_arr['trace_addtime'] = time();
            $insert_arr['trace_state'] = '0';
            $insert_arr['trace_privacy'] = 0;
            $insert_arr['trace_commentcount'] = 0;
            $insert_arr['trace_copycount'] = 0;
            $result = $tracelog_model->tracelogAdd($insert_arr);
            $js = "var obj = $(\"#likestat_{$goods_content['goods_id']}\"); $(\"#likestat_{$goods_content['goods_id']}\").find('i').addClass('noaction');$(obj).find('a').addClass('noaction'); var countobj=$('[wt_type=\'likecount_{$goods_content['goods_id']}\']');$(countobj).html(parseInt($(countobj).text())+1);";
            showDialog(Language::get('wt_common_op_succ'),'','succ',$js);
        }else {
            showDialog(Language::get('wt_common_op_fail'),'','error');
        }
    }
}
