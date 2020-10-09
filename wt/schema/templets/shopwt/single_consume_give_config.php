<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<style>
    .none_tab{
        display: none;
    }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>消费赠送设置</h3>
      </div>

      <ul class="tab-base nc-row">
        <li id="tab1"><a href="javascript:void 0;" class="current" >基本设置</a></li>
        <li id="tab2"><a href="javascript:void 0;">消息通知</a></li>
<!--        <li id="tab3"><a href="javascript:void 0;">消息</a></li>-->
      </ul>
    </div>
  </div>
  <div id="tab_dividend" class="tab-pan " >
      <form id="add_form1" method="post" enctype="multipart/form-data" action="index.php?act=single_consume_give_config&op=config">
        <div class="ncap-form-default">
          <!-- 区域分红设置 -->

                <dl class="row">
                    <dt class="tit"> <label for="is_area_dividend">消费返现状态</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="is_consume_return" value="0" <?php echo   $output['setting']['is_consume_return']==0?'checked="checked"':'';?> > 关闭</label>
                        <label class="radio-inline"><input type="radio" name="is_consume_return" value="1" <?php echo   $output['setting']['is_consume_return']==1?'checked="checked"':'';?>> 开启</label>
                    </dd>
                </dl>


            <dl class="row">
                <dt class="tit"> <label for="is_area_dividend">返现方式</label></dt>
                <dd class="opt">
                    <label class="radio-inline"><input type="radio" name="consume_return_method" value="0" <?php echo   $output['setting']['consume_return_method']==0?'checked="checked"':'';?> > 递减返现</label>
                    <label class="radio-inline"><input type="radio" name="consume_return_method" value="1" <?php echo   $output['setting']['consume_return_method']==1?'checked="checked"':'';?>> 等比返现</label>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit"> <label for="consume_return_basenum">默认消费赠送比例</label></dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['consume_return_default_rate'];?>" name="consume_return_default_rate" class="input-txt">
                    <span class="err">%</span>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit"> <label for="consume_return_basenum">每期返现比例</label></dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['consume_return_issue_rate'];?>" name="consume_return_issue_rate" class="input-txt">
                    <span class="err">%</span>
                </dd>
            </dl>



            <dl class="row">
                <dt class="tit"> <label for="is_area_dividend">返现时间</label></dt>
                <dd class="opt">
                    <label class="radio-inline" style="margin-left: 23px;"><input type="radio" name="consume_return_time" value="0" <?php echo   $output['setting']['consume_return_time']==0?'checked="checked"':'';?>>每天</label>

                    <select name="consume_return_everyday">
                        <option <?php if($output['setting']['consume_return_everyday']==0){echo 'selected';};?> value="0">0:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==1){echo 'selected';};?> value="1">1:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==2){echo 'selected';};?> value="2">2:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==3){echo 'selected';};?> value="3">3:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==4){echo 'selected';};?> value="4">4:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==5){echo 'selected';};?> value="5">5:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==6){echo 'selected';};?> value="6">6:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==7){echo 'selected';};?> value="7">7:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==8){echo 'selected';};?> value="8">8:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==9){echo 'selected';};?> value="9">9:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==10){echo 'selected';};?> value="10">10:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==11){echo 'selected';};?> value="11">11:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==12){echo 'selected';};?> value="12">12:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==13){echo 'selected';};?> value="13">13:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==14){echo 'selected';};?> value="14">14:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==15){echo 'selected';};?> value="15">15:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==16){echo 'selected';};?> value="16">16:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==17){echo 'selected';};?> value="17">17:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==18){echo 'selected';};?> value="18">18:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==19){echo 'selected';};?> value="19">19:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==20){echo 'selected';};?> value="20">20:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==21){echo 'selected';};?> value="21">21:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==22){echo 'selected';};?> value="22">22:00</option>
                        <option <?php if($output['setting']['consume_return_everyday']==23){echo 'selected';};?> value="23">23:00</option>
                    </select>
                    <label class="radio-inline" style="margin-left: 23px;"><input type="radio" name="consume_return_time" value="1" <?php echo   $output['setting']['consume_return_time']==1?'checked="checked"':'';?>>每周 00:00</label>
                    <select name="consume_return_week" class="form-control">
                        <option value="1" <?php if($output['setting']['consume_return_week']==1){echo 'selected';};?>>周一</option>
                        <option value="2" <?php if($output['setting']['consume_return_week']==2){echo 'selected';};?>>周二</option>
                        <option value="3" <?php if($output['setting']['consume_return_week']==3){echo 'selected';};?>>周三</option>
                        <option value="4" <?php if($output['setting']['consume_return_week']==4){echo 'selected';};?>>周四</option>
                        <option value="5" <?php if($output['setting']['consume_return_week']==5){echo 'selected';};?>>周五</option>
                        <option value="6" <?php if($output['setting']['consume_return_week']==6){echo 'selected';};?>>周六</option>
                        <option value="7" <?php if($output['setting']['consume_return_week']==0){echo 'selected';};?>>周日</option>
                    </select>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit"> <label for="consume_return_basenum">延时返现</label></dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['consume_return_interval_time'];?>" name="consume_return_interval_time" class="input-txt">
                    <span class="err">天</span>
                </dd>
            </dl>

                <div class="bot"><a id="submit1" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>

        </div>
      </form>
  </div>





    <div id="tab_notice" class="tab-pan none_tab" >
        <form id="add_form2" method="post" enctype="multipart/form-data" action="index.php?act=consumption_give_config&op=message">
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit"><label for="apply_again">消费返现通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;"></select>
                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="1" onclick="message_default(this)">开启</label>
                        <label class="radio-inline"><input type="radio" name="is_distinction" value="0" checked="checked" onclick="message_default(this)">关闭</label>
                    </dd>
                </dl>



                <div class="bot"><a id="submit2" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>



            </div>
        </form>
    </div>



<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
    $(function(){
        $('.tab-base li a').click(function(){

           $(this).addClass('current');
           $(this).parent('li').siblings('li').find('a').removeClass('current');
           var tab = $(this).parent('li').attr('id');

           if(tab=='tab1'){
               $('#tab_dividend').removeClass('none_tab');$('#tab_settle').addClass('none_tab');$('#tab_notice').addClass('none_tab');
           }else if(tab=='tab2'){
                $('#tab_notice').removeClass('none_tab');$('#tab_settle').addClass('none_tab');$('#tab_dividend').addClass('none_tab');
           }
//            else if(tab=='tab3'){
//                $('#tab_settle').removeClass('none_tab');$('#tab_dividend').addClass('none_tab');$('#tab_notice').addClass('none_tab');
//           }

        });

        $('#submit1').click(function(){  //消返现送设置

           $('#add_form1').submit();
        });

        $('#submit2').click(function(){  //消费返现 消息通知提交

            $('#add_form2').submit();
        });

//        $('#submit3').click(function(){
//            alert(3);return false;
//
//            $('#add_form3').submit();
//        });
    });

</script>
