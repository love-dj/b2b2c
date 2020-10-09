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
        <h3>区域代理设置</h3>
      </div>

      <ul class="tab-base nc-row">
        <li id="tab1"><a href="javascript:void 0;" class="current" >区域分红设置</a></li>
        <li id="tab2"><a href="javascript:void 0;">结算设置</a></li>
        <li id="tab3"><a href="javascript:void 0;">消息</a></li>
      </ul>
    </div>
  </div>
  <div id="tab_dividend" class="tab-pan " >
      <form id="add_form1" method="post" enctype="multipart/form-data" action="index.php?act=region_config&op=region_dividend">
        <div class="ncap-form-default">
          <!-- 区域分红设置 -->

                <dl class="row">
                    <dt class="tit"> <label for="is_area_dividend">区域分红</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="is_area_dividend" value="0" <?php  echo  $output['setting']['is_area_dividend']==0?'checked="checked"':'';?> > 关闭</label>
                        <label class="radio-inline"><input type="radio" name="is_area_dividend" value="1" <?php echo   $output['setting']['is_area_dividend']==1?'checked="checked"':'';?>> 开启</label>
                    </dd>
                </dl>


            <dl class="row">
                <dt class="tit"> <label for="is_area_dividend">是否开启独家代理</label></dt>
                <dd class="opt">
                    <label class="radio-inline"><input type="radio" name="is_only_agent" value="0" <?php  echo  $output['setting']['is_only_agent']==0?'checked="checked"':'';?> > 否</label>
                    <label class="radio-inline"><input type="radio" name="is_only_agent" value="1" <?php echo   $output['setting']['is_only_agent']==1?'checked="checked"':'';?>> 是</label>
                </dd>
            </dl>

<!--
                <dl class="row">
                    <dt class="tit"><label for="become_agent">成为区域代理</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="become_agent" value="0" <?php  echo $output['setting']['become_agent']==0?'checked="checked"':'';?> > 无条件</label>
                        <label class="radio-inline"><input type="radio" name="become_agent" value="1"  <?php  echo $output['setting']['become_agent']==1?'checked="checked"':'';?>> 申请</label>
                    </dd>
                </dl>
-->

                <dl class="row">
                    <dt class="tit"><label for="become_check">是否需要审核</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="become_check" value="0" <?php  echo $output['setting']['become_check']==0?'checked="checked"':'';?> > 不需要</label>
                        <label class="radio-inline"><input type="radio" name="become_check" value="1"  <?php  echo $output['setting']['become_check']==1?'checked="checked"':'';?> > 需要</label>
                    </dd>
                </dl>


                <dl class="row">
                    <dt class="tit"><label for="apply_again">申请驳回后可再次申请</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="apply_again" value="0" <?php  echo $output['setting']['apply_again']==0?'checked="checked"':'';?> >否</label>
                        <label class="radio-inline"><input type="radio" name="apply_again" value="1"  <?php  echo $output['setting']['apply_again']==1?'checked="checked"':'';?> >是</label>
                    </dd>
                </dl>

                <div class="bot"><a id="submit1" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>

        </div>
      </form>
  </div>


    <div id="tab_settle" class="tab-pan none_tab">
        <form id="add_form2" method="post" enctype="multipart/form-data" action="index.php?act=region_config&op=settlement">
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit"><label for="culate_method">结算方式</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="culate_method" value="0" <?php  echo $output['setting']['culate_method']==0?'checked="checked"':'';?> > 订单价格:(不包括运费及抵扣金额)</label>
                        <label class="radio-inline"><input type="radio" name="culate_method" value="1" <?php  echo $output['setting']['culate_method']==1?'checked="checked"':'';?> > 利润:(订单最终价格-成本，负数取0)</label>
                    </dd>
                </dl>


                <dl class="row">
                    <dt class="tit"><label for="is_average">是否开启平均分红</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="is_average" value="0" <?php  echo $output['setting']['is_average']==0?'checked="checked"':'';?> >否</label>
                        <label class="radio-inline"><input type="radio" name="is_average" value="1"  <?php  echo $output['setting']['is_average']==1?'checked="checked"':'';?> >是</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"><label for="is_distinction">是否开启极差分红</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="0" <?php  echo $output['setting']['is_distinction']==0?'checked="checked"':'';?> >否</label>
                        <label class="radio-inline"><input type="radio" name="is_distinction" value="1"  <?php  echo $output['setting']['is_distinction']==1?'checked="checked"':'';?> >是</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"><label for="apply_again">默认分红比例</label></dt>
                    <dd class="opt">

                        <label class="radio-inline">省:&nbsp;&nbsp;<input type="text" name="province_rate" value="<?php echo $output['setting']['province_rate'];?>">%</label>
                        <label class="radio-inline" style="margin-left: 15px;">市:&nbsp;&nbsp;<input type="text" name="city_rate" value="<?php echo $output['setting']['city_rate'];?>" >%</label>
                        <label class="radio-inline" style="margin-left: 15px;">区/县:&nbsp;&nbsp;<input type="text" name="area_rate" value="<?php echo $output['setting']['area_rate'];?>" >%</label>


                    </dd>
                </dl>

<!--                <dl class="row">-->
<!--                    <dt class="tit"><label for="settlement_model">结算类型</label></dt>-->
<!--                    <dd class="opt">-->
<!--                        <label class="radio-inline"> <input type="radio" name="settlement_model" value="0" --><?php // echo $output['setting']['settlement_model']==0?'checked="checked"':'';?><!-- >自动结算</label>-->
<!--                        <label class="radio-inline"><input type="radio" name="settlement_model" value="1" --><?php // echo $output['setting']['settlement_model']==1?'checked="checked"':'';?><!-- >手动结算</label>-->
<!--                        <span  class="help-block" style="display: block;">-->
<!--                                自动结算：订单完成后，根据结算期时间来加入到提现<br>-->
<!--                                手动结算：订单完成后，需要进入推广中心手动领取才可以提现-->
<!--                        </span>-->
<!--                    </dd>-->
<!--                </dl>-->



                <dl class="row">
                    <dt class="tit"><label for="apply_again">结算期</label></dt>
                    <dd class="opt">
                        <label class="radio-inline" style="margin-left: 15px;"> <input type="text" name="region_settle_days" value="<?php echo $output['setting']['region_settle_days'];?>" >天</label>

                    </dd>
                </dl>

                <div class="bot"><a id="submit2" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>

            </div>
        </form>
    </div>


    <div id="tab_notice" class="tab-pan none_tab" >
        <form id="add_form3" method="post" enctype="multipart/form-data" action="index.php?act=region_config&op=message">
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit"><label for="apply_again">成为区域代理通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;">asdasdasd</select>
                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="1" onclick="message_default(this)">开启</label>
                        <label class="radio-inline"><input type="radio" name="is_distinction" value="0" checked="checked" onclick="message_default(this)">关闭</label>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit"><label for="is_distinction">分红结算通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;">asdasdasd</select>
                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="1" onclick="message_default(this)">开启</label>
                        <label class="radio-inline"><input type="radio" name="is_distinction" value="0" checked="checked" onclick="message_default(this)">关闭</label>
                    </dd>

                </dl>





                <div class="bot"><a id="submit3" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>



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
                $('#tab_settle').removeClass('none_tab');$('#tab_dividend').addClass('none_tab');$('#tab_notice').addClass('none_tab');
           }else if(tab=='tab3'){
                $('#tab_notice').removeClass('none_tab');$('#tab_settle').addClass('none_tab');$('#tab_dividend').addClass('none_tab');
           }

        });

        //独家代理-------》影响---->是否开启平均分红
       var is_agent = $('[name="is_only_agent"]:checked').val();
       var hidden = $('#add_form2').find('.ncap-form-default').find('dl:eq(1)');
       if(is_agent==1){
           hidden.find('.opt').find('[name="is_average"]').attr("checked",true);
           hidden.hide();
       }else{
           hidden.show();
       }


        $('#submit1').click(function(){

           $('#add_form1').submit();
        });

        $('#submit2').click(function(){

            $('#add_form2').submit();
        });

        $('#submit3').click(function(){
            alert(3);return false;

            $('#add_form3').submit();
        });
    });

</script>
