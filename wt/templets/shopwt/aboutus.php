
<div class="wtap-about">
  <div class="left-pic"></div>
  <div class="version">
    <h2>longbasz B2B2C 电商平台系统</h2>
    <h4>当前版本：<?php echo $output['v_date'];?></h4>
    <hr>
    <h4>安装日期：<?php echo $output['s_date'];?></h4>
  </div>
  <div class="content">
	  <h2>产品动态</h2>
    <div class="scroll switchbox" >
		<p>
			  <iframe src="<?php echo ADMIN_TEMPLATES_URL;?>/update.html" width="460" height="210" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="yes">
			  </iframe>
			</p>
    </div>
    <!-- 代码结束 -->
    <div class="scrollbar switchbox" style="display: none;">
      <div class="law-notice">
        <p>版权所有： <a href="http://www.longbasz.com" target="_blank">http://www.longbasz.com</a>。</p>
          感谢您选择longbasz B2B2C电商平台系统。希望我们的努力能为您提供一个高效快速和强大的企业级电子商务整体解决方案。</p>
        <p>longbasz官方网站（www.longbasz.com）属于深圳市龙霸网络技术有限公司所有。</p>
        <p>尊爱网络环境！禁止非法传播。</p>
      </div>
    </div>
    <div class="switchbox" style="display:none;" >
      <ul>
        <li>
          <h4><?php echo $lang['dashboard_aboutus_idea'];?></h4>
          <p><?php echo $lang['dashboard_aboutus_idea_content'];?></p>
        </li>
        <li>
          <h4>关注我们</h4>
          <p><?php echo $lang['dashboard_aboutus_website'];?> <a href="http://www.longbasz.com" target="_blank">http://www.longbasz.com</a></p>
          <p><?php echo $lang['dashboard_aboutus_website_tip'];?></p>
        </li>
        <li>
          <h4><?php echo $lang['dashboard_aboutus_notice'];?></h4>
          <p><?php echo $lang['dashboard_aboutus_notice4'];?>&nbsp;:&nbsp;&nbsp;jQuery,kindeditor<?php echo $lang['dashboard_aboutus_notice5'];?>.&nbsp;<?php echo $lang['dashboard_aboutus_notice6'];?> </p>
        </li>
      </ul>
    </div>
  </div>
  <div class="btns"><a href="javascript:void(0);" onClick="about_change(0)" class="wtap-btn wtap-btn-green">官方动态</a><a href="javascript:void(0);" onClick="about_change(1)" class="wtap-btn">法律声明</a><a href="javascript:void(0);" onClick="about_change(2)" class="wtap-btn">致用户</a></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_STATIC_URL;?>/js/jquery.scroll.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo STATIC_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<link href="<?php echo STATIC_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(function(){
	$("div.scroll").myScroll({
		speed:30,
		rowHeight:60
	});
	$("div.scrollbar").perfectScrollbar();
});

function about_change(i) {
    $(".switchbox").hide().eq(i).show();
    $(".btns > a").removeClass("wtap-btn-green").eq(i).addClass("wtap-btn-green");
    if (i == 0) {
        $("div.scroll").myScroll({
            speed:30,
            rowHeight:60
        });
    }
}
</script>