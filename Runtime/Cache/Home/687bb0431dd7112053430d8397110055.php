<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="renderer" content="webkit">
	<title><?php echo ($meta_title); ?></title>
	<meta name="keywords" content="<?php echo ($meta_keywords); ?>"/>
	<meta name="description" content="<?php echo ($meta_description); ?>"/>
	<link rel="stylesheet" href="/yangsheng/html/weui/lib/weui.min.css" />
	<link rel="stylesheet" href="/yangsheng/html/weui/css/jquery-weui.min.css" />
  <link rel="stylesheet" href="/yangsheng/html/css/reset.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/yangsheng/html/css/flex.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/yangsheng/html/css/style.css" type="text/css" media="all" />
	
	<script src="/yangsheng/html/weui/lib/jquery-2.1.4.js"></script>
  <script src="/yangsheng/html/weui/js/jquery-weui.min.js"></script>
	<script src="/yangsheng/html/js/jquery.toTop.min.js"></script>
	<script src="/yangsheng/html/js/functions.js"></script>
	<script src="/yangsheng/html/js/jquery.lazyload.min.js"></script>
	<script src="/yangsheng/html/js/jquery.raty.min.js"></script>
	<script src="/yangsheng/html/js/js.cookie.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="/yangsheng/Public/Home/js/ajaxlist.js"></script>
    <!--[if lt IE 9 ]>
    <script src="/yangsheng/html/js/html5.js"></script>
    <![endif]-->
    
	
<!-- 	<script>
		window. onerror = function ( msg , url , line , col , error ) {
			//没有URL不上报！上报也不知道错误
			if ( msg != "Script error." && ! url ) { return true ; }
			//采用异步的方式 //我遇到过在window.onunload进行ajax的堵塞上报
			//由于客户端强制关闭webview导致这次堵塞上报有Network Error
			//我猜测这里window.onerror的执行流在关闭前是必然执行的
			//而离开文章之后的上报对于业务来说是可丢失的
			//所以我把这里的执行流放到异步事件去执行
			//脚本的异常数降低了10倍
			setTimeout ( function ( ) {
				var data = { } ;
				//不一定所有浏览器都支持col参数
				col = col || ( window.event && window.event.errorCharacter ) || 0 ;
				data. url = url ;
				data. line = line;
				data. col = col ;
				if ( !! error && !! error.stack ) {
					//如果浏览器有堆栈信息
					//直接使用
					data. msg = error.stack.toString ( ) ;
				} else if ( !! arguments.callee ) {
					//尝试通过callee拿堆栈信息
					var ext = [ ] ;
					var f = arguments.callee.caller , c = 3;
					//这里只拿三层堆栈信息
					while ( f && ( -- c > 0 ) ) {
						ext. push ( f.toString ( ) ) ;
						if ( f   === f.caller ) {
							break ;
							//如果有环
						}
						f = f.caller ;
					}
					ext = ext. join ( "," );
					data.msg = ext ;
				}
				console.log(data.msg);
				console.log('出错了');
				//把data上报到后台！
				$.post('<?php echo U("front_log");?>', {text:data.msg});
			} , 0 ) ;
			return true ;
		};

        /*if (wx != undefined){
            wx.config(<?php echo ($jsapi); ?>);



            wx.ready(function (){
                //分享给朋友
                wx.onMenuShareAppMessage({
                    title: share_conf.title,
                    desc: share_conf.desc,
                    link: share_conf.link,
                    imgUrl: share_conf.imgUrl,
                    success: function () {
                        // alert('分享成功');
                    },
                    cancel: function () {
                        console.log('已取消');
                    }
                });
                //分享到朋友圈
                wx.onMenuShareTimeline({
                    title: share_conf.title,
                    link: share_conf.link,
                    imgUrl: share_conf.imgUrl,
                    success: function () {
                        // alert('分享成功');
                    },
                    cancel: function () {
                        console.log('已取消');
                    }
                });
            });
        }*/
		$(function(){
			//到顶部
			$('.to_top').click(function(){
				var speed=200;
				$('body,html').animate({ scrollTop: 0 }, speed);
				return false;
			})
		})
	</script> -->
	<script>
		$(function(){
			//到顶部
			$('.to_top').click(function(){
				var speed=200;
				$('body,html').animate({ scrollTop: 0 }, speed);
				return false;
			})
		})
	</script>
	<style>
		.nomore span i {
			content: "";
			display: inline-block;
			width: 20px;
			height: 20px;
			vertical-align: top;
			background-image: url('<?php echo getpics(C('WEB_SITE_LOGO'));?>');
			background-size: 100% auto;
			background-position: 50% 50%;
			background-repeat: no-repeat;
		}
	</style>
    
</head>
<body>
	

	
	<section class="wrap <?php echo ($meta_wrap); ?>">
		
        <!-- 页面主体 -->
        
    <form action="">
        <ul class="bgf fs14 col3 user_data">
            <li class="table plr10 bb">
                <div class="table-cell col6 item-lt">头　　像</div>
                <div class="table-cell">
                    <a href="javascript:;" class="block ptb10 upload-file ">
                        <div class="brarc item-pic">
                            <img src="<?php echo getpics($user['avatar']);?>" alt="" class="imgm">
                            <input name="avatar" type="hidden" value="<?php echo ($user["avatar"]); ?>">
                        </div>
                    </a>
                </div>
            </li>
            <li class="table plr10 bb">
                <div class="table-cell col6 item-lt">用户昵称</div>
                <div class="table-cell">
                    <input name="nickname" type="text"  value="<?php echo ($user["nickname"]); ?>"class="form-unify" placeholder="请输入昵称">
                </div>
            </li>
            <li class="table plr10 bb">
                <div class="table-cell col6 item-lt">联系电话</div>
                <div class="table-cell">
                    <input name="mobile" type="text" class="form-unify" placeholder="" value="<?php echo ($user["mobile"]); ?>">
                </div>
            </li>
            <li class="table plr10 bb">
                <div class="table-cell col6 item-lt">密码修改</div>
                <div class="table-cell">
                    <input name="password" type="password" class="form-unify"  placeholder="请重新输入密码">
                </div>
            </li>
            <li class="table plr10 bb">
                <div class="table-cell">
                    <a href="<?php echo U('Member/address');?>" class="block col6 arrowR" style="padding:13px 0;">地址管理</a>
                </div>
            </li>
        </ul>
        <!-- 个人资料填写 end -->
        <div class="p10 btn_submit">
            <button type="button" class="bg_own br5 sub btn-block">确认修改</button>
        </div>
        <!-- 表单提交 end -->
    </form>


        <!-- 底部导航区域 -->
		
			<div class="handle_fixed">
				<!--<a href="<?php echo U('Shop/Cart/index');?>" class="ba brarc"><i class="hf-1"></i></a>-->
				<a href="<?php echo U('Shop/Index/index');?>" class="ba brarc"><i class="hf-2"></i></a>
				<!--	<a href="<?php echo U('Shop/Goods/index');?>" class="ba brarc"><i class="hf-3"></i></a>-->
				<a href="javascript:;" class="ba brarc to_top"><i class="hf-4"></i></a>
			</div>
		
	</section>

  
  

	
    
    <script>
        // 上传图片
        function uploadcard(localIds,num) {
            var localId = localIds.pop();
            wx.uploadImage({
                localId: localId, // 需要上传的图片的本地ID，由chooseImage接口获得
                isShowProgressTips: 1, // 默认为1，显示进度提示
                success: function (res) {
                    var serverId = res.serverId; // 返回图片的服务器端ID
                    afterUploadcard(serverId,num);
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }

        //上传图片回调
        function afterUploadcard(serverId,num) {
            $.post("<?php echo U('Home/Weixin/download_img');?>", {media_id: serverId}, function (data, textStatus, xhr) {
                if (data.status) {
                    $(".item-pic img").attr('src',data.src);
                    $('input[name="avatar"]').val(data.id);
                    if (localIds.length > 0) {
                        uploadcer(localIds);
                    }
                    $.alert('上传成功！');
                } else {
                    $.alert('上传失败，请重新上传资料');
                }
            }, 'json').error(function () {
                $.alert('错误的数据')
            });
        }
        $(function(){
            wx.config(<?php echo ($jsapi); ?>);
            $(document).on('click','.upload-file',function(){
                wx.chooseImage({
                    count: 1, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        uploadcard(localIds,0);
                    }
                });
            });
            $('.sub').on('click',function(){
                var nickname = $("input[name='nickname']").val();
                var mobile = $("input[name='mobile']").val();
                if(!nickname){
                    $.toast("请完善您的姓名！", "text");
                    return false;
                }
                if(!mobile){
                    $.toast("请输入手机号码！", "text");
                    return false;
                }
                if(!mobile.match(/^1[3|4|5|7|8][0-9]{9}$/)) {
                    $.toast("手机号码格式不正确！", "text");
                    return false;
                }
                $.ajax({
                    url:"<?php echo U('Member/setting');?>",
                    dataType:'json',
                    type:'post',
                    data:$('form').serialize(),
                    success:function(data){
                        if(data.status){
                            setTimeout(function(){
                                $.toast("操作成功", function(){
                                    location.href = "<?php echo U('index');?>";
                                });
                            }, 0);
                        }else{
                            $.alert(data.info);
                        }
                    },
                    error:function(){
                        $.alert('服务器连接失败');
                    }
                });
            });
        });
    </script>

</body>
</html>