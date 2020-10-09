<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=article_class&t=article_class" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['article_class_index_class'];?> - <?php echo $lang['wt_new'];?></h3>
        <h5><?php echo $lang['article_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>

  <form id="article_class_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="ac_name"><em>*</em><?php echo $lang['article_class_index_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="ac_name" id="ac_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="parent_id"><?php echo $lang['article_class_add_sup_class'];?></label>
        </dt>
        <dd class="opt">
          <select name="ac_parent_id" id="ac_parent_id">
            <option value="0"><?php echo $lang['wt_please_choose'];?></option>
            <?php if(!empty($output['parent_list']) && is_array($output['parent_list'])){ ?>
            <?php foreach($output['parent_list'] as $k => $v){ ?>
            <option <?php if($output['ac_parent_id'] == $v['ac_id']){ ?>selected='selected'<?php } ?> value="<?php echo $v['ac_id'];?>"><?php echo $v['ac_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['article_class_add_sup_class_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ac_sort"><?php echo $lang['wt_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="255" name="ac_sort" id="ac_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="wtap-btn-big wtap-btn-green" id="submitBtn"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#article_class_form").valid()){
     $("#article_class_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#article_class_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            ac_name : {
                required : true,
                remote   : {                
                url :'index.php?w=article_class&t=ajax&branch=check_class_name',
                type:'get',
                data:{
                    ac_name : function(){
                        return $('#ac_name').val();
                    },
                    ac_parent_id : function() {
                        return $('#ac_parent_id').val();
                    },
                    ac_id : ''
                  }
                }
            },
            ac_sort : {
                number   : true
            }
        },
        messages : {
            ac_name : {
                required : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['article_class_add_name_null'];?>',
                remote   : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['article_class_add_name_exists'];?>'
            },
            ac_sort  : {
                number   : '<i class="fa fa-exclamation-bbs"></i><?php echo $lang['article_class_add_sort_int'];?>'
            }
        }
    });
});
</script>