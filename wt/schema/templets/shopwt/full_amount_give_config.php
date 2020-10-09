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
        <h3>满额赠送设置</h3>
      </div>

      <ul class="tab-base nc-row">
        <li id="tab1"><a href="javascript:void 0;" class="current" >赠送设置</a></li>
        <li id="tab2"><a href="javascript:void 0;">消息通知</a></li>
<!--        <li id="tab3"><a href="javascript:void 0;">消息</a></li>-->
      </ul>
    </div>
  </div>
  <div id="tab_dividend" class="tab-pan " >
      <form id="add_form1" method="post" enctype="multipart/form-data" action="index.php?act=full_amount_give_config&op=config">
        <div class="ncap-form-default">
          <!-- 满额赠送设置 -->

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_give_switch">满额赠送状态</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="fullamount_give_switch" value="0" <?php  echo  $output['setting']['fullamount_give_switch']==0?'checked="checked"':'';?> > 关闭</label>
                        <label class="radio-inline"><input type="radio" name="fullamount_give_switch" value="1" <?php echo   $output['setting']['fullamount_give_switch']==1?'checked="checked"':'';?>> 开启</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_show_table">前端赠送列表</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="fullamount_show_table" value="0" <?php  echo  $output['setting']['fullamount_show_table']==0?'checked="checked"':'';?> > 不显示</label>
                        <label class="radio-inline"><input type="radio" name="fullamount_show_table" value="1" <?php echo   $output['setting']['fullamount_show_table']==1?'checked="checked"':'';?>> 显示</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_sett_method">计算方式</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="fullamount_sett_method" value="0" <?php  echo  $output['setting']['fullamount_sett_method']==0?'checked="checked"':'';?> >营业额</label>
                        <label class="radio-inline"><input type="radio" name="fullamount_sett_method" value="1" <?php echo   $output['setting']['fullamount_sett_method']==1?'checked="checked"':'';?>> 利润</label>
                    </dd>
                </dl>


                <dl class="row">
                    <dt class="tit"> <label for="fullamount_interval">时间段</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="fullamount_interval" value="0" <?php  echo  $output['setting']['fullamount_interval']==0?'checked="checked"':'';?> >昨天</label>
                        <label class="radio-inline"><input type="radio" name="fullamount_interval" value="1" <?php echo   $output['setting']['fullamount_interval']==1?'checked="checked"':'';?>> 上个月</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_give_basenum">赠送基数</label></dt>
                    <dd class="opt">
                        <input type="text" onkeyup="value=value.replace(/[^\d]/g,'')"  value="<?php echo $output['setting']['fullamount_give_basenum'];?>" name="fullamount_give_basenum" class="input-txt">
                        <span class="err">%</span>
                        <p class="notic"></p>
                    </dd>
                </dl>



                <dl class="row">
                    <dt class="tit"> <label for="fullamount_interest_limit">权益总数限制</label></dt>
                    <dd class="opt">
                        <input type="text" onkeyup="value=value.replace(/[^\d]/g,'')"  value="<?php echo $output['setting']['fullamount_interest_limit'];?>" name="fullamount_interest_limit" class="input-txt">
                        <span class="err">个</span>
                        <p class="notic" style="color: #ff0000;">[每个会员在返队列最多x个,只能输入正整数且不能小于1个]</p>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_give_basenum">1个权益等于(默认1元)</label></dt>
                    <dd class="opt">
                        <input type="text" value="<?php echo $output['setting']['fullamount_each_value'];?>" name="fullamount_each_value" class="input-txt">
                        <span class="err">元</span>
                        <p class="notic"></p>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"> <label for="fullamount_give_rate">权益赠送比例</label></dt>
                    <dd class="opt">
                        <input type="text" onkeyup="value=value.replace(/[^\d]/g,'')" value="<?php echo $output['setting']['fullamount_give_rate'];?>" name="fullamount_give_rate" class="input-txt">
                        <span class="err">%</span>
                        <p class="notic">权益赠送比例,如果填空，默认100%</p>
                        <p class="notic" style="color: #ff0000;">[100元一个权益，如果设置80%，那么一共赠送会员80元]</p>

                    </dd>
                </dl>



                <dl class="row">
                    <dt class="tit"> <label for="fullamount_give_time">赠送时间</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="fullamount_give_time" value="0" <?php  echo  $output['setting']['fullamount_give_time']==0?'checked="checked"':'';?> >每天</label>
                        <select name="fullamount_give_everyday" class="form-control">
                            <option <?php if($output['setting']['fullamount_give_everyday']==0){echo 'selected';};?> value="0">0:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==1){echo 'selected';};?> value="1">1:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==2){echo 'selected';};?> value="2">2:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==3){echo 'selected';};?> value="3">3:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==4){echo 'selected';};?> value="4">4:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==5){echo 'selected';};?> value="5">5:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==6){echo 'selected';};?> value="6">6:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==7){echo 'selected';};?> value="7">7:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==8){echo 'selected';};?> value="8">8:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==9){echo 'selected';};?> value="9">9:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==10){echo 'selected';};?> value="10">10:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==11){echo 'selected';};?> value="11">11:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==12){echo 'selected';};?> value="12">12:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==13){echo 'selected';};?> value="13">13:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==14){echo 'selected';};?> value="14">14:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==15){echo 'selected';};?> value="15">15:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==16){echo 'selected';};?> value="16">16:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==17){echo 'selected';};?> value="17">17:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==18){echo 'selected';};?> value="18">18:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==19){echo 'selected';};?> value="19">19:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==20){echo 'selected';};?> value="20">20:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==21){echo 'selected';};?> value="21">21:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==22){echo 'selected';};?> value="22">22:00</option>
                            <option <?php if($output['setting']['fullamount_give_everyday']==23){echo 'selected';};?> value="23">23:00</option>
                        </select>
                        <label class="radio-inline" style="margin-left: 23px;"><input type="radio" name="fullamount_give_time" value="1" <?php echo   $output['setting']['fullamount_give_time']==1?'checked="checked"':'';?>>每月1号 00:00</label>
                    </dd>
                </dl>

                <div class="bot"><a id="submit1" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>
        </div>
      </form>
  </div>





    <div id="tab_notice" class="tab-pan none_tab" >
        <form id="add_form2" method="post" enctype="multipart/form-data" action="index.php?act=full_amount_give_config&op=config_message">
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit"><label for="apply_again">获得赠送队列通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;"></select>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit"><label for="apply_again">赠送通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;"></select>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit"><label for="apply_again">消费通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;"></select>
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

        $('#submit1').click(function(){  //股东分红设置提交

           $('#add_form1').submit();
        });

        $('#submit2').click(function(){  //股东分红之消息通知提交

            $('#add_form2').submit();
        });

//        $('#submit3').click(function(){
//            alert(3);return false;
//
//            $('#add_form3').submit();
//        });
    });

</script>
