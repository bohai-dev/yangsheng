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
        
    <section class="main">
        <p class="ptb10 tc bgf fs14 col0 bb">选择支付方式</p>
        <ul class="pl10 bgf mt10 last">
            <li class="fs14 ptb10 bb">网上支付</li>
            <li class="bb bgf fs13 weui_cells_radio">
                <label class="weui_cell weui_check_label" for="pay1">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p><i class="Icon-pay1"></i>微信支付</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" class="weui_check" name="pay" id="pay1" value="1"  data-title="法官"> <span class="weui_icon_checked"></span> </div>
                </label>
            </li>
            <li class="bb bgf fs13 weui_cells_radio">
                <label class="weui_cell weui_check_label" for="pay2">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p><i class="Icon-pay2"></i>支付宝支付</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" class="weui_check" name="pay" id="pay2" value="2" data-title="法官"> <span class="weui_icon_checked"></span> </div>
                </label>
            </li>
        </ul>
        <ul class="pl10 bgf mt10 last">
            <li class="fs14 ptb10 bb">货到付款</li>
            <li class="bb bgf fs13 weui_cells_radio">
                <label class="weui_cell weui_check_label" for="pay3">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p><i class="Icon-pay3"></i>货到付款（仅限上海地区）</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" class="weui_check" name="pay" id="pay3" value="4" data-title="法官"> <span class="weui_icon_checked"></span> </div>
                </label>
            </li>
        </ul>
    </section>

        <!-- 底部导航区域 -->
		
			<div class="handle_fixed">
				<!--<a href="<?php echo U('Shop/Cart/index');?>" class="ba brarc"><i class="hf-1"></i></a>-->
				<a href="<?php echo U('Shop/Index/index');?>" class="ba brarc"><i class="hf-2"></i></a>
				<!--	<a href="<?php echo U('Shop/Goods/index');?>" class="ba brarc"><i class="hf-3"></i></a>-->
				<a href="javascript:;" class="ba brarc to_top"><i class="hf-4"></i></a>
			</div>
		
	</section>

  
  

	
    
    <script>
        $(function(){
            $('.weui_cells_radio').on('change',function(){
                var pay_type = $(this).find('input').val();
                Cookies.set('yangsheng_home_default_pay_type',  pay_type);
                location.href= "<?php echo ($from); ?>";
            })
        });
    </script>

</body>
</html>