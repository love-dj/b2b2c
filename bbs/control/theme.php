<?php
/**
 * 社区首页
 *
 *
 *

 

 */



defined('ShopWT') or exit('Access Denied By ShopWT');

class themeControl extends BasebbsThemeControl{
    protected $c_id = 0;        // 社区id
    protected $identity = 0;    // 身份 0游客 1圈主 2管理 3成员 4申请中 5申请失败
    protected $bbs_info = array();
    public function __construct(){
        parent::__construct();
        $this->c_id = intval($_GET['c_id']);
        if($this->c_id <= 0){
            @header("location: ".BBS_SITE_URL);
        }
        Tpl::output('c_id', $this->c_id);
    }
    /**
     * ajax获取话题详细信息 话题列表页使用
     */
    public function ajax_themeinfoWt(){
        // 话题信息
        $this->themeInfo();

        $data = $this->theme_info;
        $model = Model();
        // 话题商品
        $goods_list = $model->table('bbs_thg')->where(array('theme_id'=>$this->t_id, 'reply_id'=>0))->select();
        $goods_list = tidyThemeGoods($goods_list, 'themegoods_id');
        $data['goods_list'] = $goods_list;
        // 附件
        $affix_list = $model->table('bbs_affix')->where(array('affix_type'=>1, 'theme_id'=>$this->t_id))->select();
        if(!empty($affix_list)){
            foreach ($affix_list as $key=>$val){
                $affix_list[$key]['affix_filename'] = themeImageUrl($val['affix_filename']);
                $affix_list[$key]['affix_filethumb'] = themeImageUrl($val['affix_filethumb']);
            }
        }
        $data['affix_list'] = $affix_list;
        // 访问数增加
        $model->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update(array('theme_browsecount'=>array('exp', 'theme_browsecount+1')));

        $data['theme_content'] = ubb($data['theme_content']);
        if($data['theme_edittime'] != ''){
            $data['theme_edittime'] = @date('Y-m-d H:i', $data['theme_edittime']);
        }
        // 是否赞过话题
        $data['theme_nolike'] = 1;
        if (isset($_SESSION['member_id'])) {
            // 是否赞过话题
            $like_info = $model->table('bbs_like')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
            if(empty($like_info)){
                $data['theme_nolike'] = 1;
            }else{
                $data['theme_nolike'] = 0;
            }
        }
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);
        }
        echo json_encode($data);exit;
    }
    /**
     * ajax获取回复相关信息 话题列表页使用
     */
    public function ajax_quickreplyWt(){
        // 话题信息
        $this->themeInfo();

        $data = array();
        $data['form_action'] = BBS_SITE_URL.'/index.php?w=theme&t=save_reply&type=quick&c_id='.$this->c_id.'&t_id='.$this->t_id;
        $data['member_avatar'] = getMemberAvatarForID($_SESSION['member_id']); // 头像
        // 回复
        $reply_list = Model()->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'bbs_id'=>$this->c_id))->order('reply_id desc')->limit(5)->select();
        if(!empty($reply_list)){
            foreach($reply_list as $key=>$val){
                $reply_list[$key]['member_avatar'] = getMemberAvatarForID($val['member_id']);
                $reply_list[$key]['reply_addtime'] = date('Y-m-d H:i', $val['reply_addtime']);
                $reply_list[$key]['reply_content'] = removeUBBTag($val['reply_content']);
            }
        }
        $data['reply_list'] = $reply_list;
        $data['c_istalk']   = intval(C('bbs_istalk'));
        $data['c_contentleast'] = intval(C('bbs_contentleast'));
        if(intval(C('bbs_contentleast')) > 0){
            $data['c_contentmsg']   = sprintf(L('wt_content_min_length'), intval(C('bbs_contentleast')));
        }else{
            $data['c_contentmsg']   = L('wt_content_not_null');
        }

        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);
        }
        echo json_encode($data);exit;
    }
    /**
     * 保存话题
     */
    public function save_themeWt(){
        if(chksubmit()){
            // Reply function does close,throw error.
            if(!intval(C('bbs_istalk'))){
                showDialog(L('bbs_theme_cannot_be_published'));
            }
            // checked cookie of SEC
            if(cookie(bbs_intervaltime)){
                showDialog(L('bbs_operation_too_frequent'));
            }
            // 会员信息
            $this->memberInfo();
            // 社区信息
            $this->bbsInfo();

            // 不是社区成员不能发帖
            if(!in_array($this->identity, array(1,2,3))){
                showDialog(L('bbs_no_join_ban_release'));
            }

            $model = Model();

            // 主题分类
            $thclass_id = intval($_POST['thtype']);
            $thclass_name = '';
            if($thclass_id > 0){
                $thclass_info = $model->table('bbs_thclass')->where(array('thclass_id'=>$thclass_id))->find();
                $thclass_name = $thclass_info['thclass_name'];
            }

            /**
             * 验证
             */
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["name"], "require"=>"true","message"=>Language::get('wt_name_not_null'));
            $validate_arr[] = array("input"=>$_POST["name"], "validator"=>'Length',"min"=>4,"max"=>30,"message"=>Language::get('wt_name_min_max_length'));
            $validate_arr[] = array("input"=>$_POST["themecontent"], "require"=>"true","message"=>Language::get('wt_content_not_null'));
            if(intval(C('bbs_contentleast')) > 0) $validate_arr[] = array("input"=>$_POST["themecontent"],"validator"=>'Length',"min"=>intval(C('bbs_contentleast')),"message"=>Language::get('bbs_contentleast'));
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            $insert = array();
            $insert['theme_name']   = bbsCenterCensor($_POST['name']);
            $insert['theme_content']= bbsCenterCensor($_POST['themecontent']);
            $insert['bbs_id']    = $this->c_id;
            $insert['bbs_name']  = $this->bbs_info['bbs_name'];
            $insert['thclass_id']   = $thclass_id;
            $insert['thclass_name'] = $thclass_name;
            $insert['member_id']    = $_SESSION['member_id'];
            $insert['member_name']  = $_SESSION['member_name'];
            $insert['is_identity']  = $this->identity;
            $insert['theme_addtime']= time();
            $insert['lastspeak_time']= time();
            $insert['theme_readperm']= intval($_POST['readperm']);
            $insert['theme_special']= intval($_GET['sp']);
            $themeid = $model->table('bbs_theme')->insert($insert);
            if($themeid){
                $has_goods = 0; // 存在商品标记
                $has_affix = 0;// 存在附件标记
                // 插入话题商品
                if(!empty($_POST['goods'])){
                    $goods_insert = array();
                    foreach ($_POST['goods'] as $key=>$val){
                        $p = array();
                        $p['theme_id']      = $themeid;
                        $p['reply_id']      = 0;
                        $p['bbs_id']     = $this->c_id;
                        $p['goods_id']      = $val['id'];
                        $p['goods_name']    = $val['name'];
                        $p['goods_price']   = $val['price'];
                        $p['goods_image']   = $val['image'];
                        $p['store_id']      = $val['storeid'];
                        $p['thg_type']      = $val['type'];
                        $p['thg_url']       = ($val['type'] == 1)?$val['uri']:'';
                        $goods_insert[]     = $p;
                    }
                    $rs = $model->table('bbs_thg')->insertAll($goods_insert);
                    $has_goods = 1;
                }
                // 更新话题附件
                $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$_SESSION['member_id'], 'theme_id'=>0))->update(array('theme_id'=>$themeid, 'bbs_id'=>$this->c_id));

                // 更新话题信息
                $affixe_count = $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$_SESSION['member_id'], 'theme_id'=>$themeid))->count();
                if($affixe_count > 0){
                    $has_affix = 1;
                }
                if($has_goods || $has_affix){
                    $update = array();
                    $update['has_goods']    = $has_goods;
                    $update['has_affix']    = $has_affix;
                    $model->table('bbs_theme')->where(array('theme_id'=>$themeid))->update($update);
                }

                // 更新社区表话题数
                $update = array(
                            'bbs_thcount'=>array('exp', 'bbs_thcount+1')
                        );
                $model->table('bbs')->where(array('bbs_id'=>$this->c_id))->update($update);

                // 更新用户相关信息
                $update = array(
                            'cm_thcount'=>array('exp', 'cm_thcount+1'),
                            'cm_lastspeaktime'=>time()
                        );
                $model->table('bbs_member')->where(array('member_id'=>$_SESSION['member_id'], 'bbs_id'=>$this->c_id))->update($update);

                // Special theme
                if($_GET['sp'] == 1){
                    $insert = array();
                    $insert['theme_id']         = $themeid;
                    $insert['poll_multiple']    = intval($_POST['multiple']);
                    $insert['poll_startime']    = time();
                    $insert['poll_endtime']     = intval($_POST['days'])!=0?time()+intval($_POST['days'])*60*60*12:0;
                    $insert['poll_days']        = intval($_POST['days']);
                    $model->table('bbs_thpoll')->insert($insert);
                    if(!empty($_POST['polloption'])){
                        $insert_array = array();
                        foreach ($_POST['polloption'] as $val){
                            if ($val == '') continue;
                            $option = array();
                            $option['theme_id']     = $themeid;
                            $option['pollop_option']= $val;
                            $insert_array[] = $option;
                        }
                        $model->table('bbs_thpolloption')->insertAll($insert_array);
                    }
                }

                // set cookie of SEC
                if(intval(C('bbs_intervaltime')) > 0){
                    setWtCookie('bbs_intervaltime', true, intval(C('bbs_intervaltime')));
                }

                // Experience
                $param = array();
                $param['member_id']     = $_SESSION['member_id'];
                $param['member_name']   = $_SESSION['member_name'];
                $param['bbs_id']     = $this->c_id;
                $param['type']          = 'release';
                $param['itemid']        = $themeid;
                Model('bbs_exp')->saveExp($param);
                showDialog(L('wt_release_op_succ'), BBS_SITE_URL.'/index.php?w=theme&t=theme_detail&c_id='.$this->c_id.'&t_id='.$themeid, 'succ');
            }else{
                showDialog(L('wt_release_op_fail'));
            }
        }
        @header("location: ".BBS_SITE_URL);
    }
    /**
     * Submit voting options
     */
    public function save_votepollWt(){
        if(chksubmit()){
            $model = Model();
            // check theme
            $this->themeInfo();

            // Verify the vote ended or not
            $poll_info = $model->table('bbs_thpoll')->where(array('theme_id'=>$this->t_id))->find();
            if(empty($poll_info)){
                showDialog(L('wrong_argument'));
            }
            if($poll_info['poll_endtime'] != 0 && time() > $poll_info['poll_endtime']){
                showDialog(L('bbs_poll_has_end'));
            }
            $voter_info = $model->table('bbs_thpollvoter')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
            if(!empty($voter_info)){
                showDialog(L('bbs_poll_has_join'));
            }
            $pollopid = $_POST['pollopid'];
            foreach ((array)$pollopid as $key=>$val){
                if(!is_numeric($val)) unset($pollopid[$key]);
            }
            if(empty($pollopid)){
                showDialog(L('bbs_poll_choose_options'));
            }
            // Verify the voting options exist
            $option_list = $model->table('bbs_thpolloption')->where(array('pollop_id'=>array('in', $pollopid), 'theme_id'=>$this->t_id))->select();
            if(empty($option_list)){
                showDialog(L('bbs_poll_choose_options'));
            }

            $options = '';
            foreach ($option_list as $val){
                $options .= $val['pollop_option'].' ';
                $update = array();
                $update['pollop_votes']     = array('exp', 'pollop_votes+1');
                $update['pollop_votername'] = array('exp', '\''.$_SESSION['member_name'].' '.$val['pollop_votername'].'\'');
                $model->table('bbs_thpolloption')->where(array('pollop_id'=>$val['pollop_id']))->update($update);        //
            }

            // Recorded personal information
            $insert = array();
            $insert['theme_id']         = $this->t_id;
            $insert['member_id']        = $_SESSION['member_id'];
            $insert['member_name']      = $_SESSION['member_name'];
            $insert['pollvo_options']   = $options;
            $insert['pollvo_time']      = time();
            $model->table('bbs_thpollvoter')->insert($insert);

            // Update the total number of votes
            $update = array();
            $update['poll_voters']  = array('exp', 'poll_voters+'.count($option_list));
            $model->table('bbs_thpoll')->where(array('theme_id'=>$this->t_id))->update($update);
            showDialog(L('bbs_poll_success'), 'reload', 'succ');
        }
    }
    /**
     * 选择商品
     */
    public function choose_goodsWt(){
        $model = Model();
        // 三个月内 已购买的商品
        $order_goods = $model->table('order_goods,orders')
                ->field('order_goods.goods_id,order_goods.goods_name,order_goods.goods_image,order_goods.goods_price,orders.store_id as store_id')
                ->join('inner join')->on('order_goods.order_id=orders.order_id')
                ->where(array('orders.buyer_id'=>$_SESSION['member_id'], 'orders.order_state'=>40, 'orders.finnshed_time'=>array('gt',time()-60*60*24*30*3)))
                ->distinct(true)->select();
        // 收藏的商品
        $favorites_goods = $model->table('goods,favorites')
                ->field('goods.goods_id,goods.goods_name,goods.goods_image,goods.goods_price as goods_price,goods.store_id')
                ->join('inner join')->on('goods.goods_id=favorites.fav_id')
                ->where(array('favorites.fav_type'=>'goods', 'favorites.member_id'=>$_SESSION['member_id']))
                ->distinct(true)->select();
        Tpl::output('order_goods', $order_goods);
        Tpl::output('favorites_goods', $favorites_goods);

        Tpl::showpage('theme.choose_goods', 'null_layout');
    }

    /**
     * According to the product link to add goods
     */
    public function check_linkWt(){
        $url = html_entity_decode($_GET['link']);
        if(empty($url)) {
            echo 'false';exit;
        }
        $model_goods_content = Model('goods_content_by_url');
        $result = $model_goods_content->get_goods_content_by_url($url);
        if($result) {
            if ($result) {
                $result['type'] = ($result['type'] == 'taobao') ? 1 : 0;
            }
            echo json_encode($result);exit;
        }else{
            echo 'false';exit;
        }
    }
    /**
     * Get link domain
     *
     * @param string $link
     */
    private function getDomain($link){
        $url = parse_url($link);
        if(!isset($url['host'])) return false;
        $domain = explode('.', $url['host'], 2);
        return $domain[1];
    }

    /**
     * 上传图片
     */
    public function choose_imageWt(){
        $model = Model();
        $where = array();
        if(intval($_GET['class_id']) > 0){
            $where['ac_id'] = intval($_GET['class_id']);
        }
        $where['member_id'] = $_SESSION['member_id'];
        $pic_list = $model->table('sns_albumpic')->where($where)->page(6)->select();
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('pic_list', $pic_list);

        $class_list = $model->table('sns_albumclass')->where(array('member_id'=>$_SESSION['member_id']))->select();
        Tpl::output('class_list', $class_list);

        Tpl::showpage('theme.choose_image', 'null_layout');
    }
    /**
     * 附件批量上传 话题
     */
    public function image_uploadWt(){
        $data['msg']                = 'error';
        $data['origin_file_name']   = $_FILES['test_file']['name'];
        // 验证已上传附件数量  最大10个
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        if($_GET['type'] == 'reply'){
            $where['affix_type']= 2;
            $where['reply_id']  = 0;
        }else{
            $where['affix_type']= 1;
            $where['theme_id']  = 0;
        }
        $count = Model()->table('bbs_affix')->where($where)->count();
        if($count < 10){
            $partpath = themePartPath($_SESSION['member_id']);
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_BBS.'/theme/'.$partpath);
            $upload->set('thumb_width', 1024);
            $upload->set('thumb_height', 160);
            $upload->set('thumb_ext', '_160x160');
            $result = $upload->upfile('test_file');     // 暂时的名字
            if ($result){
                $insert = array();
                $insert['affix_filename']   = $partpath.'/'.$upload->file_name;
                $insert['affix_filethumb']  = $partpath.'/'.$upload->thumb_image;
                $insert['affix_filesize']   = intval($_FILES['test_file']['size']);
                $insert['affix_addtime']    = time();
                $insert['affix_type']       = ($_GET['type'] == 'reply')?2:1;
                $insert['member_id']        = $_SESSION['member_id'];
                $insert['theme_id']         = $this->t_id;
                $insert['reply_id']         = 0;
                $insert['bbs_id']        = $this->c_id;
                $id = Model()->table('bbs_affix')->insert($insert);

                if($id){
                    $data['msg']        = 'success';
                    $data['file_id']    = $id;
                    $data['file_name']  = $upload->file_name;
                    $data['file_url']   = themeImageUrl($partpath.'/'.$upload->thumb_image);
                    $data['file_insert']= themeImageUrl($partpath.'/'.$upload->file_name);
                }
            }
        }
        echo json_encode($data);exit;
    }
    /**
     * 附件删除
     */
    public function delimgWt(){
        $id = intval($_GET['id']);
        if($id > 0){
            $affix_info = Model()->table('bbs_affix')->where(array('member_id'=>$_SESSION['member_id'], 'affix_id'=>$id))->find();
            if(!empty($affix_info)){
                @unlink(themeImagePath($affix_info['affix_filename']));
                @unlink(themeImagePath($affix_info['affix_filethumb']));
                Model()->table('bbs_affix')->where(array('affix_id'=>$id))->delete();
                echo true;
            }
        }
    }
    /**
     * 获得未使用附件
     */
    public function unused_imgWt(){
        $affix_list = Model()->table('bbs_affix')->field('affix_id,affix_filename,affix_filethumb')->where(array('member_id'=>$_SESSION['member_id'], 'affix_type'=>1, 'theme_id'=>0))->select();
        if(!empty($affix_list)){
            $affix_array = array();
            foreach($affix_list as $key=>$val){
                $affix_array[$key]['file_id']       = $val['affix_id'];
                $affix_array[$key]['file_url']      = themeImageUrl($val['affix_filethumb']);
                $affix_array[$key]['file_insert']   = themeImageUrl($val['affix_filename']);
            }
        }
        echo json_encode($affix_array);
    }
    /**
     * 话题详细页
     */
    public function theme_detailWt(){

        // 社区信息
        $this->bbsInfo();

        // 圈主和管理信息
        $this->manageList();

        // 会员信息
        $this->memberInfo();

        // sidebar相关
        $this->sidebar();

        // 话题信息
        $this->themeInfo();

        // Verify the read permissions
        $this->readPermissions($this->cm_info);
        if($this->m_readperm < $this->theme_info['theme_readperm']){
            showMessage(L('bbs_Insufficient_permissions'), BBS_SITE_URL, '', 'error');
        }

        $model = Model();
        // 话题被浏览数增加
        $model->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update(array('theme_browsecount'=>array('exp', 'theme_browsecount+1')));

        // 回复列表
        $where = array();
        $where['theme_id'] = $this->t_id;
        if($_GET['only_id'] != ''){
            $where['member_id'] = intval($_GET['only_id']);
        }
        $reply_info = $model->table('bbs_threply')->where($where)->page(15)->order('reply_id asc')->select();
        Tpl::output('reply_info', $reply_info);
        Tpl::output('show_page', $model->showpage(2));

        $replyid_array = array();
        $memberid_array = array();
        if(!empty($reply_info)){
            foreach($reply_info as $val){
                $replyid_array[]    = $val['reply_id'];
                $memberid_array[]   = $val['member_id'];
            }
        }

        $replyid_array[]    = 0;
        ksort($replyid_array);
        $memberid_array[]   = $this->theme_info['member_id'];
        $memberid_array     = array_unique($memberid_array);
        ksort($memberid_array);

        $where = array();
        $where['theme_id']  = $this->t_id;
        $where['reply_id']  = array('in', $replyid_array);

        // goods
        $goods_array = $model->table('bbs_thg')->where($where)->select();
        $goods_array = tidyThemeGoods($goods_array, 'reply_id', 2);

        Tpl::output('goods_list', $goods_array[0]); unset($goods_array[0]);
        Tpl::output('reply_goods', $goods_array);

        // affix
        $affix_array = $model->table('bbs_affix')->where($where)->select();
        if(!empty($affix_array)){
            $affix_list = array();
            $reply_affix = array();
            foreach($affix_array as $val){
                if($val['affix_type'] == 1){
                    $affix_list[] = $val;
                }else{
                    $reply_affix[$val['reply_id']][] = $val;
                }
            }
            Tpl::output('affix_list', $affix_list);
            Tpl::output('reply_affix', $reply_affix);
        }

        // member
        $member_list = $model->table('bbs_member')->field('member_id,bbs_id,cm_level,cm_levelname,is_identity')->where(array('bbs_id'=>$this->c_id, 'member_id'=>array('in', $memberid_array)))->select();
        $member_list = array_under_reset($member_list, 'member_id');
        Tpl::output('member_list', $member_list);

        // 是否赞过话题
        $theme_nolike = 1;
        if (isset($_SESSION['member_id'])) {
            // 是否赞过话题
            $like_info = $model->table('bbs_like')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
            if(empty($like_info)){
                $theme_nolike = 1;
            }else{
                $theme_nolike = 0;
            }
        }
        Tpl::output('theme_onlike', $theme_nolike);

        $this->bbsSEO($this->theme_info['theme_name']);

        // Special theme
        if($this->theme_info['theme_special'] == 1){
            $poll_info = $model->table('bbs_thpoll')->where(array('theme_id'=>$this->t_id))->find();
            Tpl::output('poll_info', $poll_info);
            $option_list = $model->table('bbs_thpolloption')->where(array('theme_id'=>$this->t_id))->order('pollop_sort asc')->select();
            Tpl::output('option_list', $option_list);

            // Verify the vote ended or not
            if($poll_info['poll_endtime'] == 0 || $poll_info['poll_endtime'] > time()){
                Tpl::output('vote_end', false);
                // Whether members have had voted
                $voter_info = $model->table('bbs_thpollvoter')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
                if(!empty($voter_info)){
                    Tpl::output('partake', true);
                }else{
                    Tpl::output('partake', false);
                }
            }else {
                Tpl::output('vote_end', true);
            }
        }

        // breadcrumb navigation
        $this->breadcrumd();
        Tpl::showpage('theme.detail');
    }
    /**
     * 发布话题
     */
    public function new_themeWt(){
        // 社区信息
        $this->bbsInfo();

        // 圈主和管理信息
        $this->manageList();

        // 会员信息
        $this->memberInfo();

        // 不是社区成员不能发帖
        if(!in_array($this->identity, array(1,2,3))){
            showDialog(L('bbs_no_join_ban_release'));
        }

        // 话题分类
        $where = array();
        $where['bbs_id']     = $this->c_id;
        $where['thclass_status']= 1;
        $thclass_list = Model()->table('bbs_thclass')->where($where)->select();
        $thclass_list = array_under_reset($thclass_list, 'thclass_id');
        Tpl::output('thclass_list', $thclass_list);

        // Read Permission
        $readperm = $this->readPermissions($this->cm_info);
        Tpl::output('readperm', $readperm);

        $this->bbsSEO(L('bbs_release_theme'));

        if($_GET['sp'] == 1){
            // breadcrumb navigation
            $this->breadcrumd(L('bbs_new_poll'));
            Tpl::showpage('theme.new_poll');
        }else{
            // breadcrumb navigation
            $this->breadcrumd(L('bbs_new_theme'));
            Tpl::showpage('theme.new_theme');
        }
    }
    /**
     * 编辑话题
     */
    public function edit_themeWt(){
        $model = Model();
        // 验证话题
        $this->checkThemeSelf();

        if(chksubmit()){

            /**
             * 验证
             */
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["name"], "require"=>"true","message"=>Language::get('wt_name_not_null'));
            $validate_arr[] = array("input"=>$_POST["name"], "validator"=>'Length',"min"=>4,"max"=>30,"message"=>Language::get('wt_name_min_max_length'));
            $validate_arr[] = array("input"=>$_POST["themecontent"], "require"=>"true","message"=>Language::get('wt_content_not_null'));
            if(intval(C('bbs_contentleast')) > 0) $validate_arr[] = array("input"=>$_POST["themecontent"],"validator"=>'Length',"min"=>intval(C('bbs_contentleast')),"message"=>Language::get('bbs_contentleast'));
            $obj_validate -> validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog($error,'','error');
            }

            // 主题分类
            $thclass_id = intval($_POST['thtype']);
            $thclass_name = '';
            if($thclass_id > 0){
                $thclass_info = $model->table('bbs_thclass')->where(array('thclass_id'=>$thclass_id))->find();
                $thclass_name = $thclass_info['thclass_name'];
            }

            $model = Model();
            $update = array();
            $update['theme_name']       = bbsCenterCensor($_POST['name']);
            $update['theme_content']    = bbsCenterCensor($_POST['themecontent']);
            $update['thclass_id']       = $thclass_id;
            $update['thclass_name']     = $thclass_name;
            $update['theme_editname']   = $_SESSION['member_name'];
            $update['theme_edittime']   = time();
            $update['theme_readperm']   = intval($_POST['readperm']);
            $rs = $model->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update($update);
            if($rs){
                $has_goods = 0; // 存在商品标记
                $has_affix = 0;// 存在附件标记
                // 删除原有商品
                $goods_list = Model()->table('bbs_thg')->where(array('theme_id'=>$this->t_id, 'reply_id'=>0))->delete();
                // 插入话题商品
                if(!empty($_POST['goods'])){
                    $goods_insert = array();
                    foreach ($_POST['goods'] as $key=>$val){
                        $p = array();
                        $p['theme_id']      = $this->t_id;
                        $p['reply_id']      = 0;
                        $p['bbs_id']     = $this->c_id;
                        $p['goods_id']      = $val['id'];
                        $p['goods_name']    = $val['name'];
                        $p['goods_price']   = $val['price'];
                        $p['goods_image']   = $val['image'];
                        $p['store_id']      = $val['storeid'];
                        $p['thg_type']      = $val['type'];
                        $p['thg_url']       = ($val['type'] == 1)?$val['uri']:'';
                        $goods_insert[]     = $p;
                    }
                    $rs = $model->table('bbs_thg')->insertAll($goods_insert);
                    $has_goods = 1;
                }
                // 更新话题附件
                $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$_SESSION['member_id'], 'theme_id'=>0))->update(array('theme_id'=>$this->t_id, 'bbs_id'=>$this->c_id));

                // 更新话题信息
                $affixe_count = $model->table('bbs_affix')->where(array('affix_type'=>1, 'member_id'=>$_SESSION['member_id'], 'theme_id'=>$this->t_id))->count();
                if($affixe_count > 0){
                    $has_affix = 1;
                }
                if($has_goods || $has_affix){
                    $update = array();
                    $update['has_goods']    = $has_goods;
                    $update['has_affix']    = $has_affix;
                    $model->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update($update);
                }
                // Special theme
                if($_GET['sp'] == 1){
                    // Update the vote
                    $update = array();
                    $update['poll_multiple']    = intval($_POST['multiple']);
                    $update['poll_startime']    = time();
                    $update['poll_endtime']     = intval($_POST['days'])!=0?time()+intval($_POST['days'])*60*60*12:0;
                    $update['poll_days']        = intval($_POST['days']);
                    $model->table('bbs_thpoll')->where(array('theme_id'=>$this->t_id))->update($update);

                    // Update the voting options
                    if(!empty($_POST['polloption'])){
                        $insert_array = array();
                        foreach ($_POST['polloption'] as $key=>$val){
                            $option_info = $model->table('bbs_thpolloption')->where(array('pollop_id'=>$key, 'theme_id'=>$this->t_id))->find();
                            if(!empty($option_info)){
                                $update = array();
                                $update['pollop_option']= $val;
                                $update['pollop_sort']  = $_POST['pollsort'][$key];
                                $model->table('bbs_thpolloption')->where(array('pollop_id'=>$key))->update($update);
                            }else{
                                if ($val == '') continue;
                                $i = array();
                                $i['theme_id']      = $this->t_id;
                                $i['pollop_option'] = $val;
                                $i['pollop_sort']   = $_POST['pollsort'][$key];
                                $insert_array[] = $i;
                            }
                        }
                        if(!empty($insert_array)) $model->table('bbs_thpolloption')->insertAll($insert_array);
                    }
                }
                showDialog(L('wt_deit_op_succ'), BBS_SITE_URL.'/index.php?w=theme&t=theme_detail&c_id='.$this->c_id.'&t_id='.$this->t_id, 'succ');
            }else{
                showDialog(L('wt_deit_op_fail'));
            }
        }

        // 社区信息
        $this->bbsInfo();

        // 圈主和管理信息
        $this->manageList();

        // 会员信息
        $this->memberInfo();

        // 话题商品
        $goods_list = $model->table('bbs_thg')->where(array('theme_id'=>$this->t_id, 'reply_id'=>0))->select();
        $goods_list = tidyThemeGoods($goods_list, 'themegoods_id');
        Tpl::output('goods_list', $goods_list);

        // 话题附件
        $affix_list = $model->table('bbs_affix')->where(array('affix_type'=>1, 'theme_id'=>$this->t_id))->select();
        Tpl::output('affix_list', $affix_list);
        // 话题分类
        $where = array();
        $where['bbs_id']     = $this->c_id;
        $where['thclass_status']= 1;
        $thclass_list = $model->table('bbs_thclass')->where($where)->select();
        $thclass_list = array_under_reset($thclass_list, 'thclass_id');
        Tpl::output('thclass_list', $thclass_list);

        // Members of the information
        $this->memberInfo();
        // Read Permission
        $readperm = $this->readPermissions($this->cm_info);
        Tpl::output('readperm', $readperm);

        $this->bbsSEO(L('wt_edit_theme'));

        // breadcrumb navigation
        $this->breadcrumd(L('wt_edit_theme'));

        if($this->theme_info['theme_special'] == 1){
            $poll_info = $model->table('bbs_thpoll')->where(array('theme_id'=>$this->t_id))->find();
            Tpl::output('poll_info', $poll_info);
            $option_list = $model->table('bbs_thpolloption')->where(array('theme_id'=>$this->t_id))->order('pollop_sort asc')->select();
            Tpl::output('option_list', $option_list);

            Tpl::showpage('theme.edit_themepoll');
        }else{
            Tpl::showpage('theme.edit_theme');
        }
    }
    /**
     * 赞
     */
    public function ajax_likeyesWt(){
        // 话题信息
        $this->themeInfo();

        $like_info = Model()->table('bbs_like')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
        if(empty($like_info)){
            // 插入话题赞表
            Model()->table('bbs_like')->insert(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id'], 'bbs_id'=>$this->c_id));
            // 更新赞数量
            Model()->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update(array('theme_likecount'=>array('exp', 'theme_likecount+1')));
            echo 'true';
        }else{
            echo 'false';
        }
        exit;
    }
    /**
     * 取消赞
     */
    public function ajax_likenoWt(){
        // 话题信息
        $this->themeInfo();

        $like_info = Model()->table('bbs_like')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->find();
        if(empty($like_info)){
            echo 'false';
        }else{
            // 删除话题赞表信息
            Model()->table('bbs_like')->where(array('theme_id'=>$this->t_id, 'member_id'=>$_SESSION['member_id']))->delete();
            // 更新赞数量
            Model()->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update(array('theme_likecount'=>array('exp', 'theme_likecount-1')));
            echo 'true';
        }
        exit;
    }
    /**
     * 高级回复
     */
    public function replyWt(){

        // 社区信息
        $this->bbsInfo();

        // 圈主和管理信息
        $this->manageList();

        // 会员信息
        $this->memberInfo();
        // 不是社区成员不能发帖
        if(!in_array($this->identity, array(1,2,3))){
            showDialog(L('bbs_no_join_ban_reply'));
        }
        // 话题信息
        $this->themeInfo();

        if($_GET['answer_id'] != ''){
            $reply_info = Model()->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'reply_id'=>intval($_GET['answer_id'])))->find();
            if(!empty($reply_info)) Tpl::output('answer', $reply_info);
        }

        // 附件信息
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        $where['affix_type']= 2;
        $where['reply_id']  = 0;
        $affix_list = Model()->table('bbs_affix')->where($where)->select();
        Tpl::output('affix_list', $affix_list);

        $this->bbsSEO(L('wt_reply_theme'));

        // breadcrumb navigation
        $this->breadcrumd(L('wt_showanced_reply'));
        Tpl::showpage('theme.reply');
    }
    /**
     * 编辑回复
     */
    public function edit_replyWt(){
        // 验证回复信息
        $this->checkReplySelf();

        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["replycontent"], "require"=>"true", "message"=>L('bbs_reply_not_null')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showDialog($error);
            }else{
                $model = Model();
                $update = array();
                $update['theme_id']     = $this->t_id;
                $update['reply_id']     = $this->r_id;
                $update['member_id']    = $_SESSION['member_id'];
                $update['member_name']  = $_SESSION['member_name'];
                $update['reply_content']= bbsCenterCensor($_POST['replycontent']);
                $update['reply_addtime']= time();
                $update['is_closed']    = 0;
                $rs = $model->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id))->update($update);
                if($rs){
                    // 删除原有商品
                    $goods_list = Model()->table('bbs_thg')->where(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id))->delete();
                    // 插入话题商品
                    if(!empty($_POST['goods'])){
                        $goods_insert = array();
                        foreach ($_POST['goods'] as $key=>$val){
                            $p = array();
                            $p['theme_id']      = $this->t_id;
                            $p['reply_id']      = $this->r_id;
                            $p['bbs_id']     = $this->c_id;
                            $p['goods_id']      = $val['id'];
                            $p['goods_name']    = $val['name'];
                            $p['goods_price']   = $val['price'];
                            $p['goods_image']   = $val['image'];
                            $p['store_id']      = $val['storeid'];
                            $p['thg_type']      = $val['type'];
                            $p['thg_url']       = ($val['type'] == 1)?$val['uri']:'';
                            $goods_insert[]     = $p;
                        }
                        $rs = $model->table('bbs_thg')->insertAll($goods_insert);
                    }
                    // 更新话题附件
                    $model->table('bbs_affix')->where(array('affix_type'=>2, 'member_id'=>$_SESSION['member_id'], 'reply_id'=>0))->update(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id, 'bbs_id'=>$this->c_id));

                    showDialog(L('wt_common_op_succ'), 'index.php?w=theme&t=theme_detail&c_id='.$this->c_id.'&t_id='.$this->t_id, 'succ');
                }
            }
        }

        // 社区信息
        $this->bbsInfo();

        // 圈主和管理信息
        $this->manageList();

        // 会员信息
        $this->memberInfo();

        // 话题信息
        $this->themeInfo();

        // 附件信息
        $where = array();
        $where['member_id'] = $_SESSION['member_id'];
        $where['affix_type']= 2;
        $where['reply_id']  = array('in', array(0,$this->r_id));
        $where['theme_id']  = array('in', array(0,$this->t_id));
        $affix_list = Model()->table('bbs_affix')->where($where)->select();
        Tpl::output('affix_list', $affix_list);

        // 商品信息
        $where = array();
        $where['theme_id']  = $this->t_id;
        $where['reply_id']  = $this->r_id;
        $goods_list = Model()->table('bbs_thg')->where($where)->select();
        $goods_list = tidyThemeGoods($goods_list, 'themegoods_id');
        Tpl::output('goods_list', $goods_list);

        $this->bbsSEO(L('wt_edit_theme'));

        // breadcrumb navigation
        $this->breadcrumd(L('wt_edit_reply'));

        Tpl::showpage('theme.edit_reply');
    }
    /**
     * 话题回复保存
     */
    public function save_replyWt(){
        // Reply function does close,throw error.
        if(!intval(C('bbs_istalk'))){
            showDialog(L('bbs_has_been_closed_reply'));
        }
        // checked cookie of SEC
        if(cookie(bbs_intervaltime)){
            showDialog(L('bbs_operation_too_frequent'));
        }
        // 会员信息
        $this->memberInfo();
        // 不是社区成员不能发帖
        if(!in_array($this->identity, array(1,2,3))){
            showDialog(L('bbs_no_join_ban_reply'));
        }
        // 话题信息
        $this->themeInfo();

        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["replycontent"], "require"=>"true", "message"=>L('bbs_reply_not_null')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showDialog($error);
            }else{
                $model = Model();
                $insert = array();
                $insert['theme_id']     = $this->t_id;
                $insert['bbs_id']    = $this->c_id;
                $insert['member_id']    = $_SESSION['member_id'];
                $insert['member_name']  = $_SESSION['member_name'];
                $insert['reply_content']= bbsCenterCensor($_POST['replycontent']);
                $insert['reply_addtime']= time();
                $insert['is_closed']    = 0;

                // 回复楼层验证
                if($_POST['answer_id'] != ''){
                    $reply_info = Model()->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'reply_id'=>intval($_POST['answer_id'])))->find();
                    if(!empty($reply_info)) {
                        $insert['reply_replyid']    = $reply_info['reply_id'];
                        $insert['reply_replyname']  = $reply_info['member_name'];
                    }
                }

                $reply_id = $model->table('bbs_threply')->insert($insert);
                if($reply_id){
                    if($_GET['type'] == 'show'){
                        // 插入话题商品
                        if(!empty($_POST['goods'])){
                            $goods_insert = array();
                            foreach ($_POST['goods'] as $key=>$val){
                                $p = array();
                                $p['theme_id']      = $this->t_id;
                                $p['reply_id']      = $reply_id;
                                $p['bbs_id']     = $this->c_id;
                                $p['goods_id']      = $val['id'];
                                $p['goods_name']    = $val['name'];
                                $p['goods_price']   = $val['price'];
                                $p['goods_image']   = $val['image'];
                                $p['store_id']      = $val['storeid'];
                                $p['thg_type']      = $val['type'];
                                $p['thg_url']       = ($val['type'] == 1)?$val['uri']:'';
                                $goods_insert[]     = $p;
                            }
                            $rs = $model->table('bbs_thg')->insertAll($goods_insert);
                        }
                        // 更新话题附件
                        $model->table('bbs_affix')->where(array('affix_type'=>2, 'member_id'=>$_SESSION['member_id'], 'reply_id'=>0))->update(array('theme_id'=>$this->t_id, 'reply_id'=>$reply_id, 'bbs_id'=>$this->c_id));
                    }

                    // 话题被回复数增加 最后发言人发言时间
                    $update = array();
                    $update['theme_commentcount']   = array('exp', 'theme_commentcount+1');
                    $update['lastspeak_id']         = $_SESSION['member_id'];
                    $update['lastspeak_name']       = $_SESSION['member_name'];
                    $update['lastspeak_time']       = time();
                    $model->table('bbs_theme')->where(array('theme_id'=>$this->t_id))->update($update);

                    // 成员回复数增加 最后回复时间
                    $model->table('bbs_member')->where(array('member_id'=>$_SESSION['member_id'], 'bbs_id'=>$this->c_id))->update(array('cm_comcount'=>array('exp', 'cm_comcount+1'), 'cm_lastspeaktime'=>time()));
                    // set cookie of SEC
                    if(intval(C('bbs_intervaltime')) > 0){
                        setWtCookie('bbs_intervaltime', true, intval(C('bbs_intervaltime')));
                    }

                    if($this->theme_info['member_id'] != $_SESSION['member_id']){
                        // Experience for replyer
                        $param = array();
                        $param['member_id']     = $_SESSION['member_id'];
                        $param['member_name']   = $_SESSION['member_name'];
                        $param['bbs_id']     = $this->c_id;
                        $param['theme_id']      = $this->t_id;
                        $param['type']          = 'reply';
                        $param['itemid']        = $this->t_id.','.$reply_id;
                        Model('bbs_exp')->saveExp($param);

                        // Experience for releaser
                        $param = array();
                        $param['member_id']     = $this->theme_info['member_id'];
                        $param['member_name']   = $this->theme_info['member_name'];
                        $param['theme_id']      = $this->t_id;
                        $param['bbs_id']     = $this->c_id;
                        $param['type']          = 'replied';
                        $param['itemid']        = $this->t_id;
                        Model('bbs_exp')->saveExp($param);
                    }

                    if($_GET['type'] == 'quick'){
                        showDialog(L('wt_common_op_succ'), '', 'succ', '$(\'li[wttype="li'.$this->t_id.'"]\').find(\'.quick-reply-2\').removeClass(\'t\').html(\'\').end().find(\'.quick-reply-list-2\').remove().end().end().find(\'a[wttype="reply"]\').click().click();');
                    }else{
                        showDialog(L('wt_common_op_succ'), 'index.php?w=theme&t=theme_detail&c_id='.$this->c_id.'&t_id='.$this->t_id, 'succ');
                    }
                }
            }
        }
    }
    /**
     * 删除回复
     */
    public function del_replyWt(){
        // 验证回复
        $this->checkReplySelf();
        $model = Model();
        // 删除商品
        $goods_list = $model->table('bbs_thg')->where(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id))->delete();
        // 删除附件
        $where = array();
        $where['affix_type']= 2;
        $where['member_id'] = $_SESSION['member_id'];
        $where['theme_id']  = $this->t_id;
        $where['reply_id']  = $this->r_id;
        $affix_list = $model->table('bbs_affix')->where($where)->select();
        if($affix_list){
            foreach ($affix_list as $val){
                @unlink(themeImagePath($val['affix_filename']));
                @unlink(themeImagePath($val['affix_filethumb']));
            }
            $model->table('bbs_affix')->where($where)->delete();
        }
        // The recycle bin add delete records
        $param = array();
        $param['theme_id']  = $this->t_id;
        $param['reply_id']  = $this->r_id;
        $param['op_id']     = $_SESSION['member_id'];
        $param['op_name']   = $_SESSION['member_name'];
        $param['type']      = 'reply';
        Model('bbs_recycle')->saveRecycle($param);

        // 删除回复
        $model->table('bbs_threply')->where(array('theme_id'=>$this->t_id, 'reply_id'=>$this->r_id, 'member_id'=>$_SESSION['member_id']))->delete();

        // Experience
        if(intval($this->reply_info['reply_exp']) > 0){
            $param = array();
            $param['member_id']     = $_SESSION['member_id'];
            $param['member_name']   = $_SESSION['member_name'];
            $param['bbs_id']     = $this->c_id;
            $param['itemid']        = $this->t_id.','.$this->r_id;
            $param['type']          = 'delReplied';
            $param['exp']           = $this->reply_info['reply_exp'];
            Model('bbs_exp')->saveExp($param);
        }
        showDialog(L('wt_common_op_succ'), 'reload', 'succ');
    }
}
