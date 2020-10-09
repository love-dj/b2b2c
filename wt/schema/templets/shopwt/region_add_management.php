<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>添加区域代理</h3>
      </div>

    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=region_management&op=addmanagement">
      <div class="ncap-form-default">
          <dl class="row">
              <dt class="tit"><label for=" ">账号</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"> <input style="height: 24px;" type="text" name="account" placeholder="请输入账号" value="" > </label>

              </dd>
          </dl>


          <dl class="row">
              <dt class="tit"><label for=" ">登录密码</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="password" 　 name="pwd" class="form-control" value="" placeholder="请输入密码"></label>

              </dd>
          </dl>



          <dl class="row">
              <dt class="tit"><label for=" ">验证密码</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="password"   name="pwd2" class="form-control" value="" placeholder="请输入密码"></label>

              </dd>
          </dl>



          <dl class="row">
              <dt class="tit"><label for=" ">真实姓名</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="text"   name="realname" class="form-control" value="" placeholder="请输入真实姓名"></label>

              </dd>
          </dl>



          <dl class="row">
              <dt class="tit"><label for=" ">联系方式</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;"><input style="height: 24px;" type="text"   name="connect_way" class="form-control" value="" placeholder="请输入您的联系方式"></label>

              </dd>
          </dl>

          <dl class="row">
              <dt class="tit"><label for=" ">代理级别</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;height: 30px;">
                      <select style=" height: 30px;"   name="agent_grade"  id="agent_grade"  class="select" onchange="search_region()">
<!--                          <option value="0">请选择级别</option>-->
                          <option value="1">省</option>
                          <option value="2">市</option>
                          <option value="3">县/区</option>
                      </select>
              </dd>
          </dl>


          <dl class="row">
              <dt class="tit"><label for=" ">代理区域</label></dt>
              <dd class="opt">
                  <label class="radio-inline" style="margin-left: 15px;height: 30px;">
                      <select style=" height: 30px;" id="provence"   name="province" onchange="selectArea(this.value,'city');" class="select">
                          <option value="0">请选择省份</option>
                          <?php
                            foreach($output['province'] as $values){
                                echo '<option value="'.$values['area_id'].'">'.$values['area_name'].'</option>';
                            }
                          ?>
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
                     $.each(e, function (i) {

                         html += '<option value="' + e[i]['area_id'] + '">' + e[i]['area_name'] + '</option>';
                     });
                     $('#city').append(html);
                 }
             }else if(local=='area'){

                 if(level==3){
                     var other = '<option value="0">请选择区/县</option>';
                     $('#area').html(other);

                     var htm = '';
                     $.each(e,function(i){
                         htm +='<option value="'+e[i]['area_id']+'">'+e[i]['area_name']+'</option>';
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
