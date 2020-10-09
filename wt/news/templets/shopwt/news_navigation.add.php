<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?w=news_navigation&t=news_navigation_list" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wt_news_navigation_manage'];?> - <?php echo $lang['wt_new'];?></h3>
        <h5><?php echo $lang['wt_news_navigation_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?w=news_navigation&t=news_navigation_save">
    <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="navigation_title"><em>*</em><?php echo $lang['news_navigation_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="navigation_title" id="navigation_title" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['navigation_title_error'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="navigation_link"><em>*</em><?php echo $lang['news_navigation_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="navigation_link" id="navigation_link" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['navigation_link_error'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="navigation_sort"><em>*</em><?php echo $lang['wt_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="navigation_sort" name="navigation_sort" type="text" class="input-txt" value="255" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="navigation_open_type"><em>*</em><?php echo $lang['news_navigation_open_type'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="isuse_1" class="cb-enable selected" title="<?php echo $lang['wt_yes'];?>"><?php echo $lang['wt_yes'];?></label>
            <label for="isuse_0" class="cb-disable" title="<?php echo $lang['wt_no'];?>"><?php echo $lang['wt_no'];?></label>
            <input type="radio" id="isuse_1" name="navigation_open_type" value="2" >
            <input type="radio" id="isuse_0" name="navigation_open_type" value="1" >
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green"><?php echo $lang['wt_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });

    $('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            navigation_title: {
                required : true,
                maxlength : 20
            },
            navigation_link: {
                required : true,
                url : true,
                maxlength : 255
            },
            navigation_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
            navigation_title: {
                required : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['navigation_title_error'];?>" ,
                maxlength : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['navigation_title_error'];?>"
            },
            navigation_link: {
                required : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['navigation_link_error'];?>",
                url : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['navigation_link_error'];?>",
                maxlength : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['navigation_link_error'];?>"
            },
            navigation_sort: {
                required : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_error'];?>",
                digits: "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_error'];?>",
                max : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_error'];?>",
                min : "<i class='fa fa-exclamation-bbs'></i><?php echo $lang['class_sort_error'];?>"
            }
        }
    });
});
</script>