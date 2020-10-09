<?php
/**
 * SNS我的空间
 *

 
  
 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class member_snshomeControl extends BaseSNSControl {
    const MAX_RECORDNUM = 20;//允许插入新记录的最大条数(注意在sns中该常量是一样的，注意与member_snshome中的该常量一致)
    public function __construct(){
        parent::__construct();
        Language::read('member_sns,sns_home');
        $where = array();
        $where['name']  = !empty($this->master_info['member_truename'])?$this->master_info['member_truename']:$this->master_info['member_name'];
        Model('seo')->type('sns')->param($where)->show();
        //允许插入新记录的最大条数
        Tpl::output('max_recordnum',self::MAX_RECORDNUM);
    }
    /**
     * SNS首页
     */
    public function indexWt(){
        $this->get_visitor();   // 获取访客
        $this->sns_messageboard();  // 留言版

        $model = Model();
        // 分享的商品
        $where = array();
        $where['share_memberid']    = $this->master_id;
        $where['share_isshare']     = 1;
        switch ($this->relation){
            case 2:
                $where['share_privacy'] = array('in', array(0,1));
                break;
            case 1:
            default:
                $where['share_privacy'] = 0;
                break;
        }
        $goodslist = $model->table('sns_sharegoods,sns_goods')
                        ->on('sns_sharegoods.share_goodsid = sns_goods.snsgoods_goodsid')->join('inner')
                        ->where($where)->order('share_addtime desc')->limit(3)->select();
        if ($_SESSION['is_login'] == '1' && !empty($goodslist)){
            foreach ($goodslist as $k=>$v){
                if (!empty($v['snsgoods_likemember'])){
                    $v['snsgoods_likemember_arr'] = explode(',',$v['snsgoods_likemember']);
                    $v['snsgoods_havelike'] = in_array($_SESSION['member_id'],$v['snsgoods_likemember_arr'])?1:0;
                }
                $goodslist[$k] = $v;
            }
        }
        Tpl::output('goodslist', $goodslist);

        // 我的图片
        $pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id))->order('ap_id desc')->limit(3)->select();
        Tpl::output('pic_list', $pic_list);

        // 分享的店铺
        $condition = array();
        $condition['share_memberid'] = "{$this->master_id}";
        $condition['limit'] = 1;
        switch ($this->relation){
            case 3:
                $condition['share_privacyin'] = "";
                break;
            case 2:
                $condition['share_privacyin'] = "0','1";
                break;
            case 1:
                $condition['share_privacyin'] = "0";
                break;
            default:
                $condition['share_privacyin'] = "0";
                break;
        }
        $sharestore_model = Model("sns_sharestore");
        $storelist = $sharestore_model->getShareStoreList($condition,'','*','detail');
        $storelist_new = array();
        if (!empty($storelist)){
            //获得店铺ID
            $storeid_arr = '';
            foreach ($storelist as $k=>$v){
                $storelist_new[$v['store_id']] = $v;
            }
            $storeid_arr = array_keys($storelist_new);
            //查询店铺推荐商品
            $goods_model = Model('goods');
            $goodslist = $goods_model->getGoodsOnlineList(array('store_id'=> array('in', $storeid_arr)), 'goods_id,goods_name,goods_image,store_id');
            if (!empty($goodslist)){
                foreach ($goodslist as $k=>$v){
                    $v['goodsurl'] = urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));
                    $storelist_new[$v['store_id']]['goods'][] = $v;
                }
            }
        }
        //信息输出
        Tpl::output('storelist',$storelist_new);

        $where = array();
        $where['trace_memberid']    = $this->master_id;
        $where['trace_state']       = 0;
        switch ($this->relation){
            case 2:
                $where['trace_privacy'] = array('in',array(0,1));
                break;
            case 1:
            default:
                $where['trace_privacy'] = 0;
        }
        $tracelist = $model->table('sns_tracelog')->where($where)->order('trace_id desc')->limit(4)->select();
        if (!empty($tracelist)){
            foreach ($tracelist as $k=>$v){
                if ($v['trace_title']){
                    $v['trace_title'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $v['trace_title']);
                    $v['trace_title_forward'] = '|| @'.$v['trace_membername'].Language::get('wt_colon').preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:|：]|$)/is",'@${4}${5}',$v['trace_title']);
                }
                if(!empty($v['trace_content'])){
                    //替换内容中的siteurl
                    $v['trace_content'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $v['trace_content']);
                }
                $tracelist[$k] = $v;
            }
        }
        Tpl::output('tracelist',$tracelist);

        Tpl::output('type','snshome');
        Tpl::output('menu_sign','snshome');
        Tpl::showpage('sns_home');
    }
    /**
     * 获取分享和喜欢商品列表
     */
    public function shareglistWt(){
        //查询分享商品信息
        $page   = new Page();
        $page->setEachNum(20);
        $page->setStyle('admin');
        //动态列表
        $condition = array();
        $condition['share_memberid'] = $this->master_id;
        switch ($this->relation){
            case 3:
                $condition['share_privacyin'] = "";
                break;
            case 2:
                $condition['share_privacyin'] = "0','1";
                break;
            case 1:
                $condition['share_privacyin'] = "0";
                break;
            default:
                $condition['share_privacyin'] = "0";
                break;
        }
        if ($_GET['type'] == 'like'){
            $condition['share_islike'] = "1";//喜欢的商品
            $condition['order'] = " share_likeaddtime desc";
        }else {
            $condition['share_isshare'] = "1";//分享的商品
            $condition['order'] = " share_addtime desc";
        }
        $sharegoods_model = Model('sns_sharegoods');
        $goodslist = $sharegoods_model->getSharegoodsList($condition,$page,'*','detail');
        if($_GET['type'] != 'like' && !empty($goodslist)){
            $shareid_array = array();
            foreach($goodslist as $val){
                $shareid_array[]    = $val['share_id'];
            }
            $pic_array = Model()->table('sns_albumpic')->field('count(item_id) as count,item_id,min(ap_cover) as ap_cover')->where(array('ap_type'=>1, 'item_id'=>array('in', $shareid_array)))->group('item_id')->select();
            if(!empty($pic_array)){
                $pic_list = array();
                foreach ($pic_array as $val){
                    $val['ap_cover'] = UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.str_ireplace('.', '_1024.', $val['ap_cover']);
                    $pic_list[$val['item_id']]  = $val;
                }
                Tpl::output('pic_list', $pic_list);
            }
        }
        if ($_SESSION['is_login'] == '1' && !empty($goodslist)){
            foreach ($goodslist as $k=>$v){
                if (!empty($v['snsgoods_likemember'])){
                    $v['snsgoods_likemember_arr'] = explode(',',$v['snsgoods_likemember']);
                    $v['snsgoods_havelike'] = in_array($_SESSION['member_id'],$v['snsgoods_likemember_arr'])?1:0;
                }
                $goodslist[$k] = $v;
            }
        }
        //信息输出
        Tpl::output('goodslist',$goodslist);
        Tpl::output('show_page',$page->show());
        Tpl::output('menu_sign','sharegoods');
        if ($_GET['type'] == 'like'){
            Tpl::showpage('sns_likegoodslist');
        }else {
            Tpl::showpage('sns_sharegoodslist');
        }
    }
    /**
     * 分享和喜欢商品详细页面
     */
    public function goodsinfoWt(){
        $share_id = intval($_GET['id']);
        if ($share_id <= 0){
            showDialog(Language::get('wrong_argument'),"index.php?w=member_snshome&mid={$this->master_id}",'error');
        }
        //查询分享和喜欢商品信息
        $sharegoods_model = Model('sns_sharegoods');
        $condition = array();
        $condition['share_id'] = "$share_id";
        $condition['share_memberid'] = "{$this->master_id}";
        $sharegoods_list = $sharegoods_model->getSharegoodsList($condition,'','','detail');
        unset($condition);
        if (empty($sharegoods_list)){
            showDialog(Language::get('wrong_argument'),"index.php?w=member_snshome&mid={$this->master_id}",'error');
        }
        $sharegoods_content = $sharegoods_list[0];
        if (!empty($sharegoods_content['snsgoods_goodsimage'])){
            $image_arr = explode('_small',$sharegoods_content['snsgoods_goodsimage']);
            $sharegoods_content['snsgoods_goodsimage'] = $image_arr[0];
        }
        $sharegoods_content['snsgoods_goodsurl'] = urlShop('goods', 'index', array('goods_id'=>$sharegoods_content['snsgoods_goodsid']));
        if ($_SESSION['is_login'] == '1'){
            if (!empty($sharegoods_content['snsgoods_likemember'])){
                $sharegoods_content['snsgoods_likemember_arr'] = explode(',',$sharegoods_content['snsgoods_likemember']);
                $sharegoods_content['snsgoods_havelike'] = in_array($_SESSION['member_id'],$sharegoods_content['snsgoods_likemember_arr'])?1:0;
            }
        }
        unset($sharegoods_list);

        //查询上一条记录
        $condition = array();
        $condition['share_memberid'] = "{$this->master_id}";
        if ($_GET['type'] == 'like'){
            $condition['share_likeaddtime_gt'] = "{$sharegoods_content['share_likeaddtime']}";
            $condition['share_islike'] = "1";
            $condition['order'] = "share_likeaddtime asc";
        }else {
            $condition['share_addtime_gt'] = "{$sharegoods_content['share_addtime']}";
            $condition['share_isshare'] = "1";
            $condition['order'] = "share_addtime asc";
        }
        $condition['limit'] = "1";
        $sharegoods_list = $sharegoods_model->getSharegoodsList($condition);
        unset($condition);
        if (empty($sharegoods_list)){
            $sharegoods_content['snsgoods_isfirst'] = true;
        }else {
            $sharegoods_content['snsgoods_isfirst'] = false;
            $sharegoods_content['snsgoods_previd'] = $sharegoods_list[0]['share_id'];
        }
        unset($sharegoods_list);
        //查询下一条记录
        $condition = array();
        $condition['share_memberid'] = "{$this->master_id}";
        if ($_GET['type'] == 'like'){
            $condition['share_likeaddtime_lt'] = "{$sharegoods_content['share_likeaddtime']}";
            $condition['share_islike'] = "1";
            $condition['order'] = "share_likeaddtime desc";
        }else {
            $condition['share_addtime_lt'] = "{$sharegoods_content['share_addtime']}";
            $condition['share_isshare'] = "1";
            $condition['order'] = "share_addtime desc";
        }
        $condition['limit'] = "1";

        $sharegoods_list = $sharegoods_model->getSharegoodsList($condition);
        unset($condition);
        if (empty($sharegoods_list)){
            $sharegoods_content['snsgoods_islast'] = true;
        }else {
            $sharegoods_content['snsgoods_islast'] = false;
            $sharegoods_content['snsgoods_nextid'] = $sharegoods_list[0]['share_id'];
        }
        unset($sharegoods_list);

        $model = Model();

        if ($_GET['type'] != 'like'){
            // 买下秀图片
            $pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id, 'ap_type'=>1, 'item_id'=>$share_id))->select();
            if(!empty($pic_list)) {
                foreach ($pic_list as $key=>$val){
                    $pic_list[$key]['ap_cover'] = UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.str_ireplace('.', '_1024.', $val['ap_cover']);
                }
                Tpl::output('pic_list', $pic_list);
            }
        }

        $where = array();
        $where['share_memberid']    = $this->master_id;
        $where['share_id']          = array('neq', $share_id);
        if ($_GET['type'] == 'like'){
            $where['share_islike']  = 1;
        }else{
            $where['share_isshare'] = 1;
        }

        // 更多分享/喜欢商品
        $sharegoods_list = $model->table('sns_sharegoods,sns_goods')->join('inner')->on('sns_sharegoods.share_goodsid=sns_goods.snsgoods_goodsid')
                            ->where($where)->limit(9)->select();
        Tpl::output('sharegoods_list', $sharegoods_list);
        Tpl::output('sharegoods_content',$sharegoods_content);
        Tpl::output('menu_sign','sharegoods');
        Tpl::showpage('sns_goodsinfo');
    }
    /**
     * 评论前10条记录
     */
    public function commenttopWt(){
        $comment_model = Model('sns_comment');
        //查询评论总数
        $condition = array();
        $condition['comment_originalid'] = "{$_GET['id']}";
        $condition['comment_originaltype'] = "{$_GET['type']}";//原帖类型 0表示动态信息 1表示分享商品 2表示喜欢商品
        $condition['comment_state'] = "0";//0表示正常，1表示屏蔽
        $countnum = $comment_model->getCommentCount($condition);
        //动态列表
        $condition['limit'] = "10";
        $commentlist = $comment_model->getCommentList($condition);
        $showmore = '0';//是否展示更多的连接
        if ($countnum > count($commentlist)){
            $showmore = '1';
        }
        Tpl::output('countnum',$countnum);
        Tpl::output('showmore',$showmore);
        Tpl::output('showtype',1);//页面展示类型 0表示分页 1表示显示前几条
        Tpl::output('tid',$_GET['id']);
        Tpl::output('type',$_GET['type']);
        //验证码
        Tpl::output('wthash',substr(md5(BASE_SITE_URL.$_GET['w'].$_GET['t']),0,8));
        Tpl::output('commentlist',$commentlist);
        Tpl::showpage('sns_commentlist','null_layout');
    }
    /**
     * 评论列表
     */
    public function commentlistWt(){
        $comment_model = Model('sns_comment');
        //查询评论总数
        $condition = array();
        $condition['comment_originalid'] = "{$_GET['id']}";
        $condition['comment_originaltype'] = "{$_GET['type']}";//原帖类型 0表示动态信息 1表示分享商品
        $condition['comment_state'] = "0";//0表示正常，1表示屏蔽
        $countnum = $comment_model->getCommentCount($condition);
        //评价列表
        $page   = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        $commentlist = $comment_model->getCommentList($condition,$page);

        Tpl::output('countnum',$countnum);
        Tpl::output('tid',$_GET['id']);
        Tpl::output('type',$_GET['type']);
        Tpl::output('showtype','0');//页面展示类型 0表示分页 1表示显示前几条
        //验证码
        Tpl::output('wthash',substr(md5(BASE_SITE_URL.$_GET['w'].$_GET['t']),0,8));
        Tpl::output('commentlist',$commentlist);
        Tpl::output('show_page',$page->show());
        Tpl::showpage('sns_commentlist','null_layout');
    }
    /**
     * 获取店铺列表(不登录就可以查看)
     */
    public function storelistWt(){
        //查询分享店铺信息
        $page   = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        //动态列表
        $condition = array();
        $condition['share_memberid'] = "{$this->master_id}";
        switch ($this->relation){
            case 3:
                $condition['share_privacyin'] = "";
                break;
            case 2:
                $condition['share_privacyin'] = "0','1";
                break;
            case 1:
                $condition['share_privacyin'] = "0";
                break;
            default:
                $condition['share_privacyin'] = "0";
                break;
        }
        $sharestore_model = Model("sns_sharestore");
        $storelist = $sharestore_model->getShareStoreList($condition,$page,'*','detail');
        $storelist_new = array();
        if (!empty($storelist)){
            //获得店铺ID
            $storeid_arr = '';
            foreach ($storelist as $k=>$v){
                $storelist_new[$v['store_id']] = $v;
            }
            $storeid_arr = array_keys($storelist_new);
            //查询店铺推荐商品
            $goods_model = Model('goods');
            $goodslist = $goods_model->getGoodsOnlineList(array('store_id'=> array('in', $storeid_arr), 'goods_commend' => 1), 'goods_id,store_id,goods_name,goods_image');
            if (!empty($goodslist)){
                foreach ($goodslist as $k=>$v){
                    $v['goodsurl'] = urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));
                    $storelist_new[$v['store_id']]['goods'][] = $v;
                }
            }
            foreach ($storeid_arr as $val) {
                $storelist_new[$val]['goods_count'] = $goods_model->getGoodsCommonCount(array('store_id'=> $val));
            }
        }
        //信息输出
        Tpl::output('storelist',$storelist_new);
        Tpl::output('show_page',$page->show());
        Tpl::output('menu_sign','sharestore');
        Tpl::showpage('sns_storelist');
    }
    /**
     * 动态列表页面
     */
    public function traceWt(){
        $this->get_visitor();   // 获取访客
        $this->sns_messageboard();  // 留言版
        $is_owner = false;//是否为主人自己
        if ($_SESSION['member_id'] == intval($_GET['mid'])){
            $is_owner = true;
        }
        Tpl::output('is_owner',$is_owner);
        Tpl::output('menu_sign','snstrace');
        Tpl::showpage('sns_hometrace');
    }
    /**
     * 某会员的SNS动态列表
     */
    public function tracelistWt(){
        $tracelog_model = Model('sns_tracelog');
        $condition = array();
        $condition['trace_memberid'] = $this->master_id;
        switch ($this->relation){
            case 3:
                $condition['trace_privacyin'] = "";
                break;
            case 2:
                $condition['trace_privacyin'] = "0','1";
                break;
            case 1:
                $condition['trace_privacyin'] = "0";
                break;
            default:
                $condition['trace_privacyin'] = "0";
                break;
        }
        $condition['trace_state'] = "0";
        $count = $tracelog_model->countTrace($condition);
        //分页
        $page   = new Page();
        $page->setEachNum(30);
        $page->setStyle('admin');
        $page->setTotalNum($count);
        $delaypage = intval($_GET['delaypage'])>0?intval($_GET['delaypage']):1;//本页延时加载的当前页数
        $lazy_arr = lazypage(10,$delaypage,$count,true,$page->getNowPage(),$page->getEachNum(),$page->getLimitStart());
        //动态列表
        $condition['limit'] = $lazy_arr['limitstart'].",".$lazy_arr['delay_eachnum'];
        $tracelist = $tracelog_model->getTracelogList($condition);
        if (!empty($tracelist)){
            foreach ($tracelist as $k=>$v){
                if ($v['trace_title']){
                    $v['trace_title'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $v['trace_title']);
                    $v['trace_title_forward'] = '|| @'.$v['trace_membername'].Language::get('wt_colon').preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:|：]|$)/is",'@${4}${5}',$v['trace_title']);
                }
                if(!empty($v['trace_content'])){
                    //替换内容中的siteurl
                    $v['trace_content'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $v['trace_content']);
                }
                $tracelist[$k] = $v;
            }
        }
        Tpl::output('hasmore',$lazy_arr['hasmore']);
        Tpl::output('tracelist',$tracelist);
        Tpl::output('show_page',$page->show());
        Tpl::output('type','home');
        //验证码
        Tpl::output('wthash',substr(md5(BASE_SITE_URL.$_GET['w'].$_GET['t']),0,8));
        Tpl::output('menu_sign', 'snstrace');
        Tpl::showpage('sns_tracelist','null_layout');
    }
    /**
     * 一条SNS动态及其评论
     */
    public function traceinfoWt(){
        $id = intval($_GET['id']);
        if ($id<=0){
            showDialog(Language::get('wrong_argument'),'','error');
        }
        //查询动态详细
        $tracelog_model = Model('sns_tracelog');
        $condition = array();
        $condition['trace_id'] = "$id";
        $condition['trace_memberid'] = "{$this->master_id}";
        switch ($this->relation){
            case 3:
                $condition['trace_privacyin'] = "";
                break;
            case 2:
                $condition['trace_privacyin'] = "0','1";
                break;
            case 1:
                $condition['trace_privacyin'] = "0";
                break;
            default:
                $condition['trace_privacyin'] = "0";
                break;
        }
        $condition['trace_state'] = "0";
        $tracelist = $tracelog_model->getTracelogList($condition);
        $traceinfo = array();
        if (!empty($tracelist)){
            $traceinfo = $tracelist[0];
            if ($traceinfo['trace_title']){
                $traceinfo['trace_title'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $traceinfo['trace_title']);
                $traceinfo['trace_title_forward'] = '|| @'.$traceinfo['trace_membername'].':'.preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:|：]|$)/is",'@${4}${5}',$traceinfo['trace_title']);
            }
            if(!empty($traceinfo['trace_content'])){
                //替换内容中的siteurl
                $traceinfo['trace_content'] = str_replace("%siteurl%", BASE_SITE_URL.DS, $traceinfo['trace_content']);
            }
        }
        Tpl::output('traceinfo',$traceinfo);
        Tpl::output('menu_sign','snshome');
        //验证码
        Tpl::output('wthash',substr(md5(BASE_SITE_URL.$_GET['w'].$_GET['t']),0,8));
        Tpl::showpage('sns_traceinfo');
    }
    /**
     * 追加买家秀
     */
    public function add_shareWt(){
        $sid = intval($_GET['sid']);
        $model = Model();
        if($sid > 0){
            // 查询已秀图片
            $where = array();
            $where['member_id'] = $_SESSION['member_id'];
            $where['ap_type']   = 1;
            $where['item_id']   = $sid;
            $pic_list = $model->table('sns_albumpic')->where($where)->select();
            if(!empty($pic_list)) {
                foreach ($pic_list as $key=>$val){
                    $pic_list[$key]['ap_cover'] = UPLOAD_SITE_URL.'/'.ATTACH_MALBUM.'/'.$_SESSION['member_id'].'/'.str_ireplace('.', '_240.', $val['ap_cover']);
                }
                Tpl::output('pic_list', $pic_list);
            }
        }
        $sharegoods_content = $model->table('sns_goods')->where(array('snsgoods_goodsid'=>intval($_GET['gid'])))->find();
        Tpl::output('sharegoods_content', $sharegoods_content);
        Tpl::output('sid', $sid);
        Tpl::showpage('sns_addshare', 'null_layout');
    }
    /**
     * ajax图片上传
     */
    public function image_uploadWt(){
        $ap_id = intval($_POST['apid']);
        /**
         * 相册
         */
        $model = Model();
        $default_class = $model->table('sns_albumclass')->where(array('member_id'=>$_SESSION['member_id'], 'is_default'=>1))->find();
        if(empty($default_class)){  // 验证时候存在买家秀相册，不存在添加。
            $default_class = array();
            $default_class['ac_name']       = Language::get('sns_buyershow');
            $default_class['member_id']     = $this->master_id;
            $default_class['ac_des']        = Language::get('sns_buyershow_album_des');
            $default_class['ac_sort']       = '255';
            $default_class['is_default']    = 1;
            $default_class['upload_time']   = time();
            $default_class['ac_id']         = $model->table('sns_albumclass')->insert($default_class);
        }

        // 验证图片数量
        $count = $model->table('sns_albumpic')->where(array('member_id'=>$_SESSION['member_id']))->count();
        if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
            $output = array();
            $output['error']    = Language::get('sns_upload_img_max_num_error');
            $output = json_encode($output);
            echo $output;die;
        }

        /**
         * 上传图片
         */
        $upload = new UploadFile();
        if($ap_id > 0){
            $pic_info = $model->table('sns_albumpic')->where(array('ap_id'=>$ap_id))->find();
            if(!empty($pic_info)) $upload->set('file_name',$pic_info['ap_cover']);      // 原图存在设置图片名称为原图名称
        }
        $upload_dir = ATTACH_MALBUM.DS.$_SESSION['member_id'].DS;

        $upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
        $thumb_width    = '240,1024';
        $thumb_height   = '2048,1024';
        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('thumb_width', $thumb_width);
        $upload->set('thumb_height',$thumb_height);

        $upload->set('fprefix',$_SESSION['member_id']);
        $upload->set('thumb_ext',   '_240,_1024');
        $result = $upload->upfile(trim($_POST['id']));
        if (!$result){
            if (strtoupper(CHARSET) == 'GBK'){
                $upload->error = Language::getUTF8($upload->error);
            }
            $output = array();
            $output['error']    = $upload->error;
            $output = json_encode($output);
            echo $output;die;
        }


        if($ap_id <= 0){        // 如果原图存在，则不需要在插入数据库
            $img_path       = $upload->getSysSetPath().$upload->file_name;
            list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.$_SESSION['member_id'].DS.$img_path);

            $image = explode('.', $_FILES[trim($_POST['id'])]["name"]);


            if(strtoupper(CHARSET) == 'GBK'){
                $image['0'] = Language::getGBK($image['0']);
            }
            $insert = array();
            $insert['ap_name']      = $image['0'];
            $insert['ac_id']        = $default_class['ac_id'];
            $insert['ap_cover']     = $img_path;
            $insert['ap_size']      = intval($_FILES[trim($_POST['id'])]['size']);
            $insert['ap_spec']      = $width.'x'.$height;
            $insert['upload_time']  = time();
            $insert['member_id']    = $_SESSION['member_id'];
            $insert['ap_type']      = 1;
            $insert['item_id']      = intval($_POST['sid']);
            $result = $model->table('sns_albumpic')->insert($insert);
        }
        $data = array();
        $data['file_name']  = $ap_id > 0?$pic_info['ap_cover']:$upload->getSysSetPath().$upload->thumb_image;
        $data['file_id']    = $ap_id > 0?$pic_info['ap_id']:$result;

        /**
         * 整理为json格式
         */
        $output = json_encode($data);
        echo  $output;die;
    }
    /**
     * ajax删除图片
     */
    public function del_sharepicWt(){
        $ap_id = intval($_GET['apid']);
        $data = array();
        if($ap_id > 0){
            Model()->table('sns_albumpic')->where(array('ap_id'=>$ap_id, 'member_id'=>$_SESSION['member_id']))->delete();
            $data['type']   = 'true';
        }else{
            $data['type']   = 'false';
        }
        /**
         * 整理为json格式
         */
        $output = json_encode($data);
        echo  $output;die;
    }
    /**
     * 查询会员是否允许发送站内信
     *
     * @return bool
     */
    private function allowSendMessage($member_id){
        $member_info = Model('member')->getMemberInfoByID($member_id,'is_allowtalk');
        if ($member_info['is_allowtalk'] == '1'){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 站内信保存操作
     *
     * @param
     * @return
     */
    public function savemsgWt() {
        //查询会员是否允许发送站内信
        $isallowsend = $this->allowSendMessage($_SESSION['member_id']);
        if (!$isallowsend || empty($_SESSION['member_id'])){
            showDialog(Language::get('home_message_noallowsend'));
        }
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["to_member_name"],"require"=>"true","message"=>Language::get('home_message_receiver_null')),
                array("input"=>$_POST["msg_content"],"require"=>"true","message"=>Language::get('home_message_content_null')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error);
            }
            $msg_content = trim($_POST['msg_content']);
            $membername_arr = explode(',',$_POST['to_member_name']);
            if (empty($membername_arr)){
                showDialog(Language::get('home_message_receiver_null'));
            }
            if (in_array("{$_SESSION['member_name']}",$membername_arr)){
                unset($membername_arr[array_search("{$_SESSION['member_name']}",$membername_arr)]);
            }
            //查询有效会员
            $member_model = Model('member');
            $member_list = $member_model->getMemberList(array('member_name'=>array('in', $membername_arr)));
            if (!empty($member_list)){
                $model_message  = Model('message');
                foreach ($member_list as $k=>$v){
                    $insert_arr = array();
                    $insert_arr['from_member_id'] = $_SESSION['member_id'];
                    $insert_arr['from_member_name'] = $_SESSION['member_name'];
                    $insert_arr['member_id'] = $v['member_id'];
                    $insert_arr['to_member_name'] = $v['member_name'];
                    $insert_arr['msg_content'] = $msg_content;
                    $insert_arr['message_type'] = intval($_POST['msg_type']);
                    $return = $model_message->saveMessage($insert_arr);
                }
            }else {
                showDialog(Language::get('home_message_receiver_null'));
            }
            if($_GET['type'] == 'sns_board'){
                $insert_arr['msg_id']       = $return;
                $insert_arr['msg_content']  = parsesmiles($insert_arr['msg_content']);
                $data = json_encode($insert_arr);
                $js = "leaveMsgSuccess(".$data.")";
                showDialog(Language::get('home_message_send_success'),'','succ',$js);
            }
        }
    }
    /**
     * 删除普通信
     */
    public function dropcommonmsgWt() {
        $message_id = trim($_GET['message_id']);
        $drop_type = trim($_GET['drop_type']);
        if(!in_array($drop_type,array('msg_private','msg_list','sns_msg')) || empty($message_id) || empty($_SESSION['member_id'])) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $messageid_arr = explode(',',$message_id);
        $messageid_str = '';
        if (!empty($messageid_arr)){
            $messageid_str = "'".implode("','",$messageid_arr)."'";
        }
        $model_message  = Model('message');
        $param  = array('message_id_in'=>$messageid_str);
        if($drop_type == 'msg_private'){
            $param['from_member_id'] = "{$_SESSION['member_id']}";
        }elseif($drop_type == 'msg_list'){
            $param['to_member_id_common']   = "{$_SESSION['member_id']}";
        }elseif($drop_type == 'sns_msg'){
            $param['from_to_member_id'] = $_SESSION['member_id'];
        }
        $drop_state = $model_message->dropCommonMessage($param,$drop_type);
        if ($drop_state){
            //更新未读站内信数量cookie值
            $cookie_name = 'msgnewnum'.$_SESSION['member_id'];
            $countnum = $model_message->countNewMessage($_SESSION['member_id']);
            setWtCookie($cookie_name,$countnum,2*3600);//保存2小时
            showDialog(Language::get('home_message_delete_success'),'reload','succ');
        }else {
            showDialog(Language::get('home_message_delete_fail'),'','error');
        }
    }
}
