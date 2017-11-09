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
        <!-- form start -->
        <ul class="plr10 btb bgf fs14 last Login_form">
            <li class="table bb">
                <div class="table-cell ico-logfo">
                    <i class="Lf-2"></i>
                </div>
                <div class="table-cell">
                    <input name="mobile" type="tel" class="form-unify" placeholder="请输入手机号码">
                </div>
            </li>
            <li class="table bb">
                <div class="table-cell ico-logfo">
                    <i class="Lf-3"></i>
                </div>
                <div class="table-cell">
                    <input name="password" type="password" class="form-unify" placeholder="设置登录密码">
                </div>
            </li>
            <li class="table bb">
                <div class="table-cell ico-logfo">
                    <i class="Lf-4"></i>
                </div>
                <div class="table-cell">
                    <input name="verify_code" type="text" class="form-unify" placeholder="请输入短信验证码">
                </div>
                <div class="table-cell Login_code" style="width: 130px;">
                    <a href="javascript:;" class="bg_own br5 btn-mini  code">获取验证码</a>
                </div>
            </li>
        </ul>

        <div class="plr10 btn_submit">
            <button type="button" class="bg_own br5 btn-block register">立即注册</button>
            <a href="<?php echo U('Member/login');?>" class="block mt20 tc fs14 col_own">已经有账号，立即登陆</a>
        </div>
        <!-- form end -->
    </form>


        <!-- 底部导航区域 -->
		


	</section>

  
  

	
    
    <script>
        //手机号码验证
        function is_mobile(mobile) {
            if (mobile == "") {
                $.alert("请输入手机号");
                // $("#phone").focus();
                return false;
            }
            if (!mobile.match(/^1[3|4|5|7|8][0-9]{9}$/)) {
                $.alert("请输入正确的手机号");
                return false;
            }
            var msg = check_mobile(mobile);
            if(msg){
                $.alert(msg);
                return false;
            }
            return true;
        }
        //倒计时
        function now_time(time) {
            var $this = $(".code");
            if (time == 'undefined') {
                time = 60;
            }
            $this.html(time + "秒后重发");
            if (time > 0) {
                time = time - 1;
                setTimeout("now_time(" + time + ")", 1000)
            } else {
                $this.removeClass("btn-mini_active");
                $this.html('获取验证码');
            }
        }
        function  check_mobile(mobile){
            var msg ='';
            $.ajax({
                        url: "<?php echo U('checkMobileExist');?>",
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        data: {mobile: mobile},
                    })
                    .done(function(data) {
                        if(data.status){
                            if(data.exist == 1){
                                msg ='手机号已被注册';
                            }else{
                                msg ='';
                            }
                        }
                    })
                    .fail(function() {
                        $.alert('网络问题，请重试');
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
            return msg;
        }

        $(function(){
            $(".register_note").click(function(){
                $(".mem-popup").show();
            })
            $(".mem-popup-close").click(function(){
                $(".mem-popup").hide();
            })
            $(".mem_info .agree").click(function(){
                $(".mem-popup").hide();
                $("#register1").prop({"checked":true});
            })
            //获取验证码
            $(".code").on('click', function () {
                if ($(this).hasClass('btn-mini_active')) {
                    return false;
                }
                var mobile = $('[name=mobile]').val();
                if (!is_mobile(mobile)) {
                    return false;
                }
                var url = "<?php echo U('miao_send');?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    async: false, //ajax同步
                    dataType: "json",
                    data: {mobile: mobile},
                    timeout: 10000,
                    error: function () {
                        $.alert('发送失败，请重新获取验证码！');
                    },
                    success: function (data) {
                        if (data.status) {
                            $(".code").addClass('btn-mini_active');
                            now_time(60);
                        } else {
                            $.alert('发送失败，超过同一号码获取验证码的上限！');
                        }
                    }
                });
            });

            // 表单提交
            $('.register').on('click', function(){
                if($('[name="mobile"]').val()==''){
                    $.toptip('请填写号码');
                    return false;
                }
                var mobile = $('[name=mobile]').val();
                if (!is_mobile(mobile)) {
                    return false;
                }
                if($('[name="verify_code"]').val()==''){
                    $.toptip('请填写验证码');
                    return false;
                }
                if($('[name="password"]').val()==''){
                    $.toptip('请填写密码');
                    return false;
                }
                $.showLoading();
                var $form = $('form');
                $.ajax({
                            url: $form.attr('action'),
                            type: 'POST',
                            dataType: 'json',
                            data: $form.serialize(),
                        })
                        .done(function(data) {
                            if(data.status){
                                setTimeout(function(){
                                    $.toast("注册成功", function(){
                                        location.href = '<?php echo U('index');?>';
                                    });
                                }, 0);
                            }else{
                                $.alert(data.info);
                            }
                            console.log("success");
                        })
                        .fail(function() {
                            $.alert('网络超时，请再次尝试失败后联系管理员');
                            console.log("error");
                        })
                        .always(function() {
                            $.hideLoading();
                        });

                return false;
            });
        })
    </script>

</body>
</html>