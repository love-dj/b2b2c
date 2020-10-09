<?php defined('ShopWT') or exit('Access Denied By ShopWT');?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title><?php echo $output['html_title'];?></title>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/login.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_STATIC_URL?>/font/css/font-awesome.min.css" rel="stylesheet">
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/common.js" type="text/javascript"></script>
<script src="<?php echo STATIC_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo ADMIN_STATIC_URL?>/js/jquery.supersized.min.js" ></script>
<script src="<?php echo ADMIN_STATIC_URL?>/js/jquery.progressBar.js" type="text/javascript"></script>

</head>
<body>
<?php 
require_once($tpl_file);
?>
<script>
$(function(){
		//得到焦点
		$("#password").focus(function(){
			$("#left_hand").animate({
				left: "150",
				top: " -38"
			},{step: function(){
				if(parseInt($("#left_hand").css("left"))>140){
					$("#left_hand").attr("class","left_hand");
				}
			}}, 2000);
			$("#right_hand").animate({
				right: "-64",
				top: "-38px"
			},{step: function(){
				if(parseInt($("#right_hand").css("right"))> -70){
					$("#right_hand").attr("class","right_hand");
				}
			}}, 2000);
		});
		//失去焦点
		$("#password").blur(function(){
			$("#left_hand").attr("class","initial_left_hand");
			$("#left_hand").attr("style","left:100px;top:-12px;");
			$("#right_hand").attr("class","initial_right_hand");
			$("#right_hand").attr("style","right:-112px;top:-12px");
		});
	
        $.supersized({

        // 功能
        slide_interval     : 4000,    
        transition         : 1,    
        transition_speed   : 1000,    
        performance        : 1,    

        // 大小和位置
        min_width          : 0,    
        min_height         : 0,    
        vertical_center    : 1,    
        horizontal_center  : 1,    
        fit_always         : 0,    
        fit_portrait       : 1,    
        fit_landscape      : 0,    

        // 组件
        slide_links        : 'blank',    
        slides             : [    
							 {image : '<?php echo ADMIN_TEMPLATES_URL;?>/images/login/1.jpg'},
							 {image : '<?php echo ADMIN_TEMPLATES_URL;?>/images/login/2.jpg'},
							 {image : '<?php echo ADMIN_TEMPLATES_URL;?>/images/login/3.jpg'},
							 {image : '<?php echo ADMIN_TEMPLATES_URL;?>/images/login/4.jpg'},
							 {image : '<?php echo ADMIN_TEMPLATES_URL;?>/images/login/5.jpg'}
                       ]

    });
	//显示隐藏验证码
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });
    //跳出框架在主窗口登录
   if(top.location!=this.location)	top.location=this.location;
    $('#user_name').focus();
    if ($.browser.msie && ($.browser.version=="6.0" || $.browser.version=="7.0")){
        window.location.href='<?php echo ADMIN_TEMPLATES_URL;?>/ie6update.html';
    }
    $("#captcha").wt_placeholder();
	//动画登录
    $('.btn-submit').click(function(e){
       
            setTimeout(function () {
                 
                      $('.submit2').html('<div class="progress"><div class="progress-bar progress-bar-success" aria-valuetransitiongoal="100"></div></div>');
                      $('.progress .progress-bar').progressbar({
                          done : function() {$('#form_login').submit();}
                      }); 
              },
          300);

          });

    // 回车提交表单
    $('#form_login').keydown(function(event){
        if (event.keyCode == 13) {
            $('.btn-submit').click();
        }
    });
});

</script>
</body>
</html>
