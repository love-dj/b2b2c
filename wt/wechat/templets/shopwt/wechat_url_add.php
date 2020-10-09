<div class="page">
	 <div class="fixed-bar">
      <div class="item-title"><a class="back" href="index.php?w=url" title="返回列表"><i class="fa fa-arrow-bbs-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['wechat_url_manage'];?> - 新增</h3>
        <h5>设置自定义url</h5>
      </div>
    </div>
  </div>

  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
  <input type="hidden" name="form_submit" value="ok" />
   <div class="wtap-form-default">
      <dl class="row">
        <dt class="tit">
        <label for="name"><?php echo $lang['wechat_url_name']; ?>:</label>
        </dt>
        <dd class="opt">
		   <input id="name" name="name" value="" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['not_info_url_name'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
        <label for="urllink"><?php echo $lang['wechat_url_link']; ?>:</label>
        </dt>
        <dd class="opt">
		    <input id="urllink" name="urllink" value="" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['not_info_url_link'];?></p>
        </dd>
      </dl>
	   <div class="bot"><a id="submitBtn" href="javascript:void(0)" class="wtap-btn-big wtap-btn-green">确认提交</a></div>
	  </div>
	
  </form>
</div>
<script type="text/javascript">
$(function(){	 
	$("#submitBtn").click(function(){
		if($("#add_form").valid()){
			$("#add_form").submit();
		}
    });
	
	$("#add_form").validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	name: {
        		required : true
        	},
        	urllink: {
        		required : true
        	}
        },
        messages : {
        	name: {
        		required : '<?php echo $lang['not_info_url_name'];?>'
        	},
        	urllink: {
        		required : '<?php echo $lang['not_info_url_link'];?>'
        	}
        }
	});
})
</script>