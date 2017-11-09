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
		
			<div class="foot_fixed">
				<footer class="table bt bgf tc fs12 footer">
					<a href="<?php echo U('Shop/Index/index');?>" class="table-cell <?php if(($gl) == "1"): ?>active<?php endif; ?>"><i class="fi-1"></i>首页</a>
					<a href="<?php echo U('Shop/Goods/index');?>" class="table-cell  <?php if(($gl) == "2"): ?>active<?php endif; ?>"><i class="fi-2"></i>分类</a>
					<a href="<?php echo U('Forum/Index/index');?>" class="table-cell  <?php if(($gl) == "3"): ?>active<?php endif; ?>"><i class="fi-3"></i>论坛</a>
					<a href="<?php echo U('Shop/Cart/index');?>" class="table-cell  <?php if(($gl) == "4"): ?>active<?php endif; ?>"><i class="fi-4 hint-num"></i>购物车</a>
					<a href="<?php echo U('Home/Member/index');?>" class="table-cell  <?php if(($gl) == "5"): ?>active<?php endif; ?>"><i class="fi-5"></i>我的</a>
				</footer>
        <?php if(!empty($uid)): ?><script>
            $(function(){
              var cart_url = "<?php echo U('Shop/Cart/cart_num');?>";
              getCartNum(cart_url,2);
            });
          </script><?php endif; ?>
			</div>
			<!-- 底部悬浮 end -->
		
        <!-- 页面主体 -->
        
    <!-- 底部悬浮 end -->

    <section class="main">

        <div class="p20 member_head">
            <a href="javascript:;" class="mhead_main memberDown">
                <div class="br50 item-pic avatar">
                    <img src="<?php echo getpics($user['avatar']);?>" alt="" class="imgm">
                </div>
                <p class="mt10 tc fs16 colf single-line">欢迎您，用户<?php echo ($user["nickname"]); ?></p>
                <div class="tc fs12 colf">
                    <span class="mr10">关注：<?php echo ($fans["attention"]); ?>人</span>
                    <span class="ml10">粉丝：<?php echo ($fans["fans"]); ?>人</span>
                </div>
            </a>
            <a href="<?php echo U('Member/setting');?>" class="tc colf set"><i class="Ico-sets"></i>设置</a>
        </div>

        <div class="mb10 plr10 btb bgf last fs14 member_ico member_order">
            <a href="<?php echo U('Shop/Order/index');?>" class="ptb10 arrowR"><i class="mr10 mc-1"></i>我的订单</a>
            <div class="table bt tc fs12" id="orders">
                <a href="<?php echo U('Shop/Order/index');?>" class="table-cell vm ptb10">
                    <i class="cc-1"></i>
                    <p class="mt5 col9">待付款</p>
                </a>
                <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>2));?>" class="table-cell vm ptb10">
                    <i class="cc-2"></i>
                    <p class="mt5 col9">待发货</p>
                </a>
                <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>3));?>" class="table-cell vm ptb10">
                    <i class="cc-3"></i>
                    <p class="mt5 col9">待收货</p>
                </a>
                <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>4));?>" class="table-cell vm ptb10">
                    <i class="cc-4"></i>
                    <p class="mt5 col9">待评论</p>
                </a>
                <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>5));?>" class="table-cell vm ptb10">
                    <i class="cc-4"></i>
                    <p class="mt5 col9">退款售后</p>
                </a>
            </div>
        </div>

        <ul class="clearfix mb10 bt bgf tc fs12 member_ico member_menu">
            <li class="bb br">
                <a href="<?php echo U('Member/score');?>" class="">
                    <i class="mc-2"></i>
                    <p class="mt10 col9">我的积分</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/history_buy');?>" class="">
                    <i class="mc-3"></i>
                    <p class="mt10 col9">曾经购买</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/coupons');?>" class="">
                    <i class="mc-4"></i>
                    <p class="mt10 col9">我的优惠券</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/team');?>" class="">
                    <i class="mc-5"></i>
                    <p class="mt10 col9">我的团队</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/qrcode');?>" class="">
                    <i class="mc-6"></i>
                    <p class="mt10 col9">我的二维码</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/posts');?>" class="">
                    <i class="mc-7"></i>
                    <p class="mt10 col9">我的帖子</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/rent');?>" class="">
                    <i class="mc-8"></i>
                    <p class="mt10 col9">我的出租/求租</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/job');?>" class="">
                    <i class="mc-9"></i>
                    <p class="mt10 col9">我的招聘</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/comment');?>" class="">
                    <i class="mc-10"></i>
                    <p class="mt10 col9">我的评论</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/commission');?>" class="">
                    <i class="mc-11"></i>
                    <p class="mt10 col9">我的佣金</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/collect');?>" class="">
                    <i class="mc-12"></i>
                    <p class="mt10 col9">收藏内容</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/browse');?>" class="">
                    <i class="mc-13"></i>
                    <p class="mt10 col9">浏览的足迹</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/attention');?>" class="">
                    <i class="mc-14"></i>
                    <p class="mt10 col9">我的关注</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/complain');?>" class="">
                    <i class="mc-15"></i>
                    <p class="mt10 col9">投诉意见</p>
                </a>
            </li>
            <li class="bb br">
                <a href="<?php echo U('Member/notice');?>" class="">
                    <i class="mc-16"></i>
                    <p class="mt10 col9">系统通知</p>
                </a>
            </li>
            <li class="bb br">
                <a href="javascript:;" class="logout">
                    <i class="mc-17"></i>
                    <p class="mt10 col9">退出</p>
                </a>
            </li>
        </ul>




    </section>


        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script>
        $(function(){
            //获取订单未读数
            $.ajax({
                dataType: "json",
                url: '<?php echo U("Shop/Order/getOrderNum");?>',
                data: {
                    uid: <?php echo ($uid); ?>
                },
                cache:false,
                success: function(data){
                    if(data.status){
                       /* $.each(data.data, function(i,item){
                            if(item >0){
                                $('#orders a:eq('+(i-1)+') i').addClass('hint-num');
                                $('#orders a:eq('+(i-1)+') i').append('<em></em>')
                            }else{
                                $('#orders a:eq('+(i-1)+') i').removeClass('hint-num');
                            }
                        });*/
                        $.each(data.data, function(i,item){
                            if(item >0){
                                $('#orders a:eq('+(i-1)+') i').addClass('hint-num1');
                                $('#orders a:eq('+(i-1)+') i').append('<em>'+item+'</em>');
                            }else{
                                $('#orders a:eq('+(i-1)+') i').removeClass('hint-num1');
                            }
                        });
                    }
                }
            });
            $('.logout').on('click',function(){
                $.confirm('您确定要退出吗',function(){
                    $.ajax({
                        dataType: "json",
                        url: '<?php echo U("Home/Member/logout");?>',
                        type:'post',
                        success:function(data){
                            if(data.status){
                                $.toast('退出成功',function(){
                                    location.href= data.url;
                                })
                            }else{
                                $.toast('服务器异常，请稍后重试');
                            }
                        },
                        error:function(){
                            $.toast('服务器异常，请稍后重试');
                        }
                    });
                });
            });
        })
    </script>

</body>
</html>