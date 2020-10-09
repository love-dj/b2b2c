<?php
/**
 * 计划任务
 * ShopWTv1.0 
 **/
defined('ShopWT') or die('Access Denied By ShopWT');
class taskControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('task');
    }
	
	public function indexWt() {
        $this->listWt();
    }
    public function listWt()
    {
        $a = Model()->table('task')->select();
        Tpl::output('task_list', $a);
		Tpl::setDirquna('system');
        Tpl::showpage('task.index');
    }
    public function delWt()
    {
        Model()->table('task')->where(array('id' => $_GET['id']))->delete();
        showMessage(Language::get('wt_common_del_succ'));
    }
    public function addWt()
    {
        if (chksubmit()) {
            $b = new Validate();
            $b->validateparam = array(array('input' => $_POST['taskname'], 'require' => 'true', 'message' => '请填写任务名称！'), array('input' => $_POST['dourl'], 'require' => 'true', 'message' => '请填写运行程序！'));
            $c = $b->validate();
            if ($c != '') {
                showMessage($c);
            } else {
                $d = $_POST['h'] . ':' . $_POST['i'] . ':' . $_POST['s'];
                $e = empty($_POST['starttime']) ? 0 : strtotime($_POST['starttime']);
                $f = empty($_POST['endtime']) ? 0 : strtotime($_POST['endtime']);
                $g = array();
                $g['taskname'] = $_POST['taskname'];
                $g['dourl'] = $_POST['dourl'];
                $g['islock'] = trim($_POST['islock']);
                $g['description'] = $_POST['description'];
                $g['runtype'] = trim($_POST['runtype']);
                $g['runtime'] = $d;
                $g['starttime'] = $e;
                $g['endtime'] = $f;
                $g['freq'] = $_POST['freq'];
                $g['parameter'] = $_POST['parameter'];
                $g['lastrun'] = '0';
                $g['settime'] = time();
                $h = Model('task')->insert($g);
                if ($h) {
                    showMessage(Language::get('wt_common_save_succ'));
                } else {
                    showMessage('增加任务失败!');
                }
            }
        }
		Tpl::setDirquna('system');
        Tpl::showpage('task_add');
    }
    public function editWt()
    {
        if (chksubmit()) {
            $b = new Validate();
            $b->validateparam = array(array('input' => $_POST['taskname'], 'require' => 'true', 'message' => '请填写任务名称！'), array('input' => $_POST['dourl'], 'require' => 'true', 'message' => '请填写运行程序！'));
            $c = $b->validate();
            if ($c != '') {
                showMessage($c);
            } else {
                $d = $_POST['h'] . ':' . $_POST['i'] . ':' . $_POST['s'];
                $e = empty($_POST['starttime']) ? 0 : strtotime($_POST['starttime']);
                $f = empty($_POST['endtime']) ? 0 : strtotime($_POST['endtime']);
                $g = array();
                $g['taskname'] = $_POST['taskname'];
                $g['dourl'] = $_POST['dourl'];
                $g['islock'] = trim($_POST['islock']);
                $g['description'] = $_POST['description'];
                $g['runtype'] = trim($_POST['runtype']);
                $g['runtime'] = $d;
                $g['starttime'] = $e;
                $g['endtime'] = $f;
                $g['freq'] = $_POST['freq'];
                $g['parameter'] = $_POST['parameter'];
                $h = Model('task')->where(array('id' => $_GET['id']))->update($g);
                if ($h) {
                    showMessage(Language::get('wt_common_save_succ'));
                } else {
                    showMessage('修改任务失败!');
                }
            }
        }
        $i = Model()->table('task')->where(array('id' => $_GET['id']))->find();
        Tpl::output('task', $i);
		Tpl::setDirquna('system');
        Tpl::showpage('task_edit');
    }
}
