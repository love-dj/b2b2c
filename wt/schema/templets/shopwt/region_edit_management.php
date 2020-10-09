<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>添加区域代理</h3>
      </div>

    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=region_management&op=editmanagement">
      <input type="hidden" name="id" value="<?php echo $_GET['id'] ;?>">
      <div class="ncap-form-default">
          <dl class="row">
              <dt class="tit"><label for=" ">账号</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"> <input style="height: 24px;" type="text" name="account" placeholder="请输入账号" value="<?php echo $output['info']['account']?>" > </label>

              </dd>
          </dl>


<!--          <dl class="row">-->
<!--              <dt class="tit"><label for=" ">登录密码</label></dt>-->
<!--              <dd class="opt">-->
<!--                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="password" 　 name="pwd" class="form-control" value="--><?php //echo $output['info']['member_passwd']?><!--" placeholder="请输入密码"></label>-->
<!---->
<!--              </dd>-->
<!--          </dl>-->
<!---->
<!---->
<!---->
<!--          <dl class="row">-->
<!--              <dt class="tit"><label for=" ">验证密码</label></dt>-->
<!--              <dd class="opt">-->
<!--                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="password"   name="pwd2" class="form-control" value="--><?php //echo $output['info']['member_passwd']?><!--" placeholder="请输入密码"></label>-->
<!---->
<!--              </dd>-->
<!--          </dl>-->



          <dl class="row">
              <dt class="tit"><label for=" ">真实姓名</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="text"   name="realname" class="form-control" value="<?php echo $output['info']['truename']?>" placeholder="请输入真实姓名"></label>

              </dd>
          </dl>



          <dl class="row">
              <dt class="tit"><label for=" ">联系方式</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="text"   name="connect_way" class="form-control" value="<?php echo $output['info']['member_mobile']?>" placeholder="请输入您的联系方式"></label>

              </dd>
          </dl>

          <dl class="row">
              <dt class="tit"><label for=" ">代理级别</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;height: 30px;">
                      <select style=" height: 30px;"   name="agent_grade"  id="agent_grade"  class="select" onchange="search_region()" >

                          <option value="1" <?php echo $output['info']['agent_level']==1?'selected':'' ;?>>省</option>
                          <option value="2" <?php echo $output['info']['agent_level']==2?'selected':'' ;?>>市</option>
                          <option value="3" <?php echo $output['info']['agent_level']==3?'selected':'' ;?>>县/区</option>
                      </select>
              </dd>
          </dl>

<?php //var_dump($output['info']['member_provinceid']) ;?>
          <dl class="row">
              <dt class="tit"><label for=" ">代理区域</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;height: 30px;">
                      <select style=" height: 30px;" id="provence"   name="province" onchange="selectArea(this.value,'city');" class="select">
                          <option value="0">请选择省份</option>
                          <?php

                            foreach($output['province'] as $values){ ?>
                                  <option <?php if($values['area_id']==$output['info']['province']){echo 'selected';}; ?> value="<?php echo $values['area_id'];?>"><?php echo $values['area_name'];?></option>;
                          <?php  } ?>

                      </select>
                      <select style="margin-left: 15px;height: 30px;" id="city" name="city" onchange="selectArea(this.value,'area');" class="select">
                          <option value="0">请选择市</option>
                      </select>
                      <select style="margin-left: 15px;height: 30px;" id="area" name="area"  class="select">
                          <option value="0">请选择区/县</option>
                      </select>

              </dd>
          </dl>
          <div class="bot" style="margin: 0 auto; ">
              <a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green">提交</a>
          </div>
      </div>


  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
var province = <?php echo $output['info']['province']?$output['info']['province']:0;?>; //省id
var city = <?php echo $output['info']['city']?$output['info']['city']:0;?>;         //市id
var area = <?php echo $output['info']['area']?$output['info']['area']:0;?>;        // 区id
console.log(province,city);
    $(function(){
        selectArea(province,'city');
        selectArea(city,'area')



    });

    //获取该省的所有市区
    function selectArea(id,local){
        var url = 'index.php?act=region_management&op=get_cityarea';
        var level = $('#agent_grade option:selected').val();  //代理级别


        $.post(url,{province_id:id},function(e){
             if(local=='city'){
                 if(level!=1) {
                     var other = '<option value="0">请选择市</option>';
                     $('#city').html(other);

                     var html = '';
                     var ss ='';
                     $.each(e, function (i) {

                            if(e[i]['area_id']==city){ ss='selected'; }else{ss='';}

                         html += '<option value="' + e[i]['area_id'] + '" '+ss+'>' + e[i]['area_name'] + '</option>';
                     });
                     $('#city').append(html);
                 }
             }else if(local=='area'){

                 if(level==3){
                     var other = '<option value="0">请选择区/县</option>';
                     $('#area').html(other);

                     var htm = '';
                     var ss='';
                     $.each(e,function(i){
                         if(e[i]['area_id']==area){ss = 'selected';}else{ss ='';}
                         htm +='<option value="'+e[i]['area_id']+'"'+ss+'>'+e[i]['area_name']+'</option>';
                     });
                     $('#area').append(htm);
                 }
             }

         },'json');
    }


    function search_region(){
        $('#city').html('');
        $('#area').html('');

    }


    $('#submit').click(function(){
        $('#add_form').submit();
    });
  </script> 
