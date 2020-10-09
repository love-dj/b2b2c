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
        <h3>股东分红设置</h3>
      </div>

      <ul class="tab-base nc-row">
        <li id="tab1"><a href="javascript:void 0;" class="current" >股东分红</a></li>
        <li id="tab2"><a href="javascript:void 0;">消息通知</a></li>
<!--        <li id="tab3"><a href="javascript:void 0;">消息</a></li>-->
      </ul>
    </div>
  </div>
  <div id="tab_dividend" class="tab-pan " >
      <form id="add_form1" method="post" enctype="multipart/form-data" action="index.php?act=shareholders_base_config&op=base_config">
        <div class="ncap-form-default">
          <!-- 区域分红设置 -->

                <dl class="row">
                    <dt class="tit"> <label for="is_area_dividend">股东分红</label></dt>
                    <dd class="opt">
                        <label class="radio-inline"><input type="radio" name="shareholder_is_dividend" value="0" <?php  echo  $output['setting']['shareholder_is_dividend']==0?'checked="checked"':'';?> > 关闭</label>
                        <label class="radio-inline"><input type="radio" name="shareholder_is_dividend" value="1" <?php echo   $output['setting']['shareholder_is_dividend']==1?'checked="checked"':'';?>> 开启</label>
                    </dd>
                </dl>


            <dl class="row" >
                <dt class="tit"> <label for="is_area_dividend">分红等级</label></dt>
            </dl>

            <dl class="row" style="margin-left: 113px;">
                <dt class="tit">
                    <label for="shareholder_lever_3">钻石经销商</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['shareholder_lever_3'];?>" name="shareholder_lever_3" class="input-txt">
                    <span class="err">%</span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row" style="margin-left: 113px;">
                <dt class="tit">
                    <label for="shareholder_lever_2">金牌经销商</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['shareholder_lever_2'];?>" name="shareholder_lever_2" class="input-txt">
                    <span class="err">%</span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row" style="margin-left: 113px;">
                <dt class="tit">
                    <label for="shareholder_lever_1">银牌经销商</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['shareholder_lever_1'];?>" name="shareholder_lever_1" class="input-txt">
                    <span class="err">%</span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" style="margin-left: 113px;">
                <dt class="tit">
                    <label for="shareholder_lever_0">普通股东</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['setting']['shareholder_lever_0'];?>" name="shareholder_lever_0" class="input-txt">
                    <span class="err">%</span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit"><label for="shareholder_sett_method">结算方式</label></dt>
                <dd class="opt">
                    <label class="radio-inline"> <input type="radio" name="shareholder_sett_method" value="0" <?php  echo $output['setting']['shareholder_sett_method']==0?'checked="checked"':'';?> > 营业额:(默认为上一个周期的营业额) </label>
                    <label class="radio-inline"><input type="radio" name="shareholder_sett_method" value="1" <?php  echo $output['setting']['shareholder_sett_method']==1?'checked="checked"':'';?> >利润:(开启利润为上一个周期的利润进行累计)</label>
                    <p class="notic">默认为上一个周期的营业额，开启利润为上一个周期的利润进行累计</p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit"><label for="shareholder_sett_cycle">结算周期</label></dt>
                <dd class="opt">
                    <label class="radio-inline"> <input type="radio" name="shareholder_sett_cycle" value="0" <?php  echo $output['setting']['shareholder_sett_cycle']==0?'checked="checked"':'';?> >天</label>
                    <label class="radio-inline"><input type="radio" name="shareholder_sett_cycle" value="1" <?php  echo $output['setting']['shareholder_sett_cycle']==1?'checked="checked"':'';?> >周</label>
                    <label class="radio-inline"><input type="radio" name="shareholder_sett_cycle" value="1" <?php  echo $output['setting']['shareholder_sett_cycle']==1?'checked="checked"':'';?> >月</label>
                    <p class="notic">按天是前一天的营业，按周为上周一至周日的营业额，按月为上月1号至30/31日的营业额</p>
                </dd>
            </dl>

                <div class="bot"><a id="submit1" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a></div>

        </div>
      </form>
  </div>





    <div id="tab_notice" class="tab-pan none_tab" >
        <form id="add_form2" method="post" enctype="multipart/form-data" action="index.php?act=region_config&op=message">
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit"><label for="apply_again">股东分红通知</label></dt>
                    <dd class="opt">
                        <select name="" style="width: 240px;"></select>
                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="1" onclick="message_default(this)">开启</label>
                        <label class="radio-inline"><input type="radio" name="is_distinction" value="0" checked="checked" onclick="message_default(this)">关闭</label>
                    </dd>
                </dl>

<!--                <dl class="row">-->
<!--                    <dt class="tit"><label for="is_distinction">消息通知</label></dt>-->
<!--                    <dd class="opt">-->
<!--                        <select name="" style="width: 240px;">asdasdasd</select>-->
<!--                        <label class="radio-inline"> <input type="radio" name="is_distinction" value="1" onclick="message_default(this)">开启</label>-->
<!--                        <label class="radio-inline"><input type="radio" name="is_distinction" value="0" checked="checked" onclick="message_default(this)">关闭</label>-->
<!--                    </dd>-->
<!---->
<!--                </dl>-->

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
