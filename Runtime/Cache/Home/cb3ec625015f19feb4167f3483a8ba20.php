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
					<!--<a href="<?php echo U('Shop/Goods/index');?>" class="table-cell  <?php if(($gl) == "2"): ?>active<?php endif; ?>"><i class="fi-2"></i>分类</a>
					<a href="<?php echo U('Forum/Index/index');?>" class="table-cell  <?php if(($gl) == "3"): ?>active<?php endif; ?>"><i class="fi-3"></i>论坛</a>-->
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
        
    <section class="main">

        <div class="p10 view-head view-jifen">
            <div class="colf item-bd">
                <p class="fs14">积分总额：<b class="fs30"><?php echo ($user["score"]); ?></b></p>
                <span class="mt10 fs14">您在购买时可使用<?php echo ($money); ?>元现金</span>
            </div>
        </div>

        <div class="p10 fs13 col3">积分记录</div>

        <div class="table bgf btb tc fs14 tab_nav">
            <a href="javascript:;" class="table-cell ajax_list active" data-value="1"><span>收入</span></a>
            <a href="javascript:;" class="table-cell ajax_list " data-value="2"><span>支出</span></a>
        </div>

        <div class="comm_con">
            <ul class="view-list">
            </ul>
            <div id="loading" class="tc" style="display: none"></div>
            <div class="p10 col9 fs13 tc nomore" id="full" style="display: none">
                <span class="col9">—— <i></i> ——</span>
                <p class="fs12 col9">亲，没有更多了哦~</p>
            </div>
            <div class="tc nothing" style="display: none">
                <i><img src="/yangsheng/html/images/icon_none-order.png" alt="" class="imgm"></i>
                <span class="mt10 fs14 col9">暂无记录</span>
            </div>
        </div>
    </section>

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script>
        jQuery(document).ready( function($){
            $(document).ajaxStart(function() {
                $.showLoading();
            });
            $(document).ajaxComplete(function() {
                $.hideLoading();
            });
            $(window).bind('scroll', function() {
                checkload(); //ajax 下拉加载更多内容
            });
            LoadList();
        });
        $(function(){
            $('.ajax_list').on('click',function(){
                var $this =$(this);
                if($this.hasClass('active')){
                    return false;
                }
                type =$this.attr('data-value');
                $this.addClass('active').siblings().removeClass('active');
                $(".view-list li").remove();
                $("#full").hide();
                $('.nothing').hide();
                n=1;
                ispost = temp = true;
                LoadList();
            })
        });
        function checkload() {
            if ($(window).scrollTop() + $(window).height()+1 >= $(document).height()) {
                $("#loading").show();
                LoadList();
            }
        }
        var n = 1;
        var ispost = temp = true;
        var type =1;
        function LoadList() {
            if (ispost && temp) {
                var url ="<?php echo U('Member/score');?>";
                temp = false;
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    async: false,
                    data: {n: n,type:type},
                    timeout: 9999,
                    success: function(data) {
                        n++;
                        temp = true;
                        if(data.status){
                            if(data.msg == ''){
                                temp = false;
                                $("#full").show();
                            }else{
                                $("#loading").hide();
                                $(".view-list").append(data.msg);
                                imgHeight();
                                temp =true;
                                imglazyload();
                                checkload();
                            }
                        }else{
                            temp = false;
                            $("#loading").hide();
                            $(".nothing").show();
                        }
                        $(".lazy img").lazyload({  //图片延迟加载
                            placeholder : "html/images/loading.gif",
                            effect      : "fadeIn",
                            threshold : 200
                        });
                    }
                });
            }else if(!ispost){
                $("#full").show();
            }
        }
    </script>

</body>
</html>