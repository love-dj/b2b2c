wx.config({
    debug: false,
    appId: '<?php echo $output['appid'];?>', 
    timestamp: '<?php echo TIMESTAMP;?>',
    nonceStr: '<?php echo $output['nonceStr'];?>', 
    signature: '<?php echo $output['signature'];?>',
    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage']
});

wx.ready(function () {
    wx.onMenuShareTimeline({
        title: "<?php echo $output['title'];?>", //分享标题
        link: "<?php echo $output['link'];?>", //分享链接
        imgUrl: "<?php echo $output['imgUrl'];?>", //分享图标
        success: function () {
 			//alert('分享成功');
			<?php if(isset($output['key']) && $output['key']!=''){ ?>
			talert();
			<?php }?>
        },
        cancel: function () {
			alert('分享失败');
        }
    });
    wx.onMenuShareAppMessage({
        title: "<?php echo $output['title'];?>", //分享标题
        desc: "<?php echo $output['desc'];?>", //分享描述
        link: "<?php echo $output['link'];?><?php echo $output['myurl'];?>", //分享链接
        imgUrl: "<?php echo $output['imgUrl'];?>", //分享图标
        type: '', 
        dataUrl: '',
        success: function () {
 			//alert('分享成功');
			<?php if(isset($output['key']) && $output['key']!=''){ ?>
			talert();
			<?php }?>
        },
        cancel: function () {
			alert('分享失败');
        }
    });
});
wx_share = 0;