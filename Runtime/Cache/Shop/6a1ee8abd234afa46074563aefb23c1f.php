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
	<link rel="stylesheet" href="/html/weui/lib/weui.min.css" />
	<link rel="stylesheet" href="/html/weui/css/jquery-weui.min.css" />
  <link rel="stylesheet" href="/html/css/reset.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/html/css/flex.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/html/css/style.css" type="text/css" media="all" />
	
	<script src="/html/weui/lib/jquery-2.1.4.js"></script>
  <script src="/html/weui/js/jquery-weui.min.js"></script>
	<script src="/html/js/jquery.toTop.min.js"></script>
	<script src="/html/js/functions.js"></script>
	<script src="/html/js/jquery.lazyload.min.js"></script>
	<script src="/html/js/jquery.raty.min.js"></script>
	<script src="/html/js/js.cookie.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="/Public/Home/js/ajaxlist.js"></script>
    <!--[if lt IE 9 ]>
    <script src="/html/js/html5.js"></script>
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
        
  <input type="hidden" name="wxconfig">
  <div class="top_fixed">
    <div class="table bgf bb tc fs14 tab_nav"  id="orders">
      <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>1));?>" class="table-cell  <?php if(($checkinfo) == "1"): ?>active<?php endif; ?> "><span>待付款</span></a>
      <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>2));?>" class="table-cell  <?php if(($checkinfo) == "2"): ?>active<?php endif; ?> "><span>待发货</span></a>
      <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>3));?>" class="table-cell  <?php if(($checkinfo) == "3"): ?>active<?php endif; ?>"><span>待收货</span></a>
      <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>4));?>" class="table-cell  <?php if(($checkinfo) == "4"): ?>active<?php endif; ?>"><span>待评价</span></a>
      <a href="<?php echo U('Shop/Order/index',array('checkinfo'=>5));?>" class="table-cell  <?php if(($checkinfo) == "5"): ?>active<?php endif; ?>"><span>退款</span></a>
    </div>
  </div>


  <section class="main">
    <ul class="list_order ajax_list"></ul>
    <div id="loading" class="tc" style="display: none"></div>
    <div class="p10 col9 fs13 tc nomore" id="full" style="display: none">
      <span class="col9">—— <i></i> ——</span>
      <p class="fs12 col9">亲，没有更多了哦~</p>
    </div>
    <div class="tc nothing" style="display: none">
      <i><img src="/html/images/icon_none-order.png" alt="" class="imgm"></i>
      <span class="mt10 fs14 col9">您还没有订单<br>赶快去购物吧！</span>
    </div>
    <!-- 已完成 end -->
  </section>

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script src="/html/js/jweixin-1.0.0.js"></script>
    <script src="/html/js/ap.js"></script>
    <script>
        var domain = "<?php echo XILUDomain();?>"+"";
        $(function(){
            // $(document).ajaxStart(function() {
            //     $.showLoading();
            // });
            // $(document).ajaxComplete(function() {
            //     $.hideLoading();
            // });
            $(window).bind('scroll', function() {
                checkload(); //ajax 下拉加载更多内容
            });
            //初始化加载数据
            LoadList();

            //获取订单数量
            getOrderNum();

            wx.config(<?php echo ($jsapi); ?>);
            //取消订单
            $(document).on('click', '.cancel', function(){
                var $that = $(this);
                $.confirm("确认取消吗？", function() {
                    //点击确认后的回调函数
                    $.post($that.attr('href'), {ordernum:$that.data('ordernum'), action: 'cancel'}, function(data, textStatus, xhr) {
                        if(data.status){
                            setTimeout(function(){
                                $.toast("取消成功", function(){
                                    location.reload(true);

                                    // $('#orders a span em').remove();
                                    // $('#orders a span').removeClass('hint-num');
                                    // getOrderNum();

                                    // $that.parents('li').remove();
                                    // var checkLi =$(".ajax_list > li").length;
                                    // if( checkLi == 0){
                                    //     $("#full").hide();
                                    //     $(".nothing").show();
                                    // }
                                });
                            }, 0);
                        }else{
                            setTimeout(function(){
                                $.alert(data.info);
                            }, 1000);
                        }
                    },'json');
                }, function() {
                    //点击取消后的回调函数
                });
                return false;
            });

            //删除订单
            $(document).on('click', '.delete', function(){
                var $that = $(this);
                $.confirm("确认删除吗？", function() {
                    //点击确认后的回调函数
                    $.post($that.attr('href'), {}, function(data, textStatus, xhr) {
                        if(data.status){
                            location.reload(true);
                            // $('#orders a span em').remove();
                            // $('#orders a span').removeClass('hint-num');
                            // getOrderNum();

                            // $that.parents('li').remove();
                            // var checkLi =$(".ajax_list > li").length;
                            // if( checkLi == 0){
                            //     $("#full").hide();
                            //     $(".nothing").show();
                            // }
                        }else{
                            setTimeout(function(){
                                $.alert(data.info);
                            }, 1000);
                        }
                    },'json');
                }, function() {
                    //点击取消后的回调函数
                });
                return false;
            });

            //去付款
            $(document).on('click', '.topay' ,function(){
                var $that = $(this);
                //点击确认后的回调函数
                $.post($that.attr('href'), {}, function(data, textStatus, xhr) {
                    if(data.status){
                        $('[name="wxconfig"]').data('id', data.id).data('url', data.url);
                        if(data.wxconfig){
                            callpay(data.wxconfig);
                        }else{
                            _AP.pay(data.alipayurl);
                        }

                    }else{
                        setTimeout(function(){
                            $.alert(data.info);
                        }, 1000);
                    }
                },'json');
                return false;
            });

            //确认收货
            $(document).on('click', '.finish', function(){
                var $that = $(this);
                $.confirm("确认收货吗？", function() {
                    //点击确认后的回调函数
                    $.post($that.attr('href'), {}, function(data, textStatus, xhr) {
                        if(data.status){
                            location.href = data.url;
                        }else{
                            setTimeout(function(){
                                $.alert(data.info);
                            }, 1000);
                        }
                    },'json');
                }, function() {
                    //点击取消后的回调函数
                });
                return false;
            });

            // 提醒发货
            $(document).on('click','.remind_send',function(){
                var that =$(this);
                var orderid = that.data('values');
                var send_url ="<?php echo U('Order/remind_send');?>";
                $.ajax({
                    url:send_url,
                    type:'post',
                    dataType:'json',
                    data:{'orderid':orderid},
                    success:function(data){
                        if(data.status){
                            $.alert('已经提醒商家发货');
                        }else{
                            $.alert('系统繁忙,请稍后重试');
                        }
                    },
                    error:function(){

                    }
                })
            });
            // 申请退货
            $(document).on('click', '.apply_return', function(){
                var $that = $(this);
                $.confirm("确认退货吗？", function() {
                    //点击确认后的回调函数
                    $.post($that.attr('href'), { action:'apply_return'}, function(data, textStatus, xhr) {
                        if(data.status){
                            $.alert('退款申请已提交，请等待审核',function(){
                                location.href = data.url;
                            });
                        }else{
                            setTimeout(function(){
                                $.alert(data.info);
                            }, 1000);
                        }
                    },'json');
                }, function() {
                    //点击取消后的回调函数
                });
                return false;
            });
        })

        function checkload() {
            if ($(window).scrollTop() + $(window).height()+1 >= $(document).height()) {
                $("#loading").show();
                LoadList();
            }
        }
        var n = 1;
        var ispost = temp = true;
        function LoadList() {
            if (ispost && temp) {
                var type =$('#type').val();
                var url ="<?php echo U('Shop/Order/index');?>";
                temp = false;
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {n: n,checkinfo:"<?php echo ($checkinfo); ?>"},
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
                                $(".ajax_list").append(data.msg);
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

        //获取订单数量
        function getOrderNum(){
            //获取订单未读数
            $.ajax({
                dataType: "json",
                url: '<?php echo U("Shop/Order/getOrderNum");?>',
                data: {
                    uid: <?php echo ($uid); ?>
                },
                async:true,
                cache:false,
                success: function(data){
                    if(data.status){
                        $.each(data.data, function(i,item){
                            if(item >0){
                                $('#orders a:eq('+(i-1)+') span').addClass('hint-num1');
                                $('#orders a:eq('+(i-1)+') span').append('<em>'+item+'</em>');
                            }else{
                                $('#orders a:eq('+(i-1)+') span').removeClass('hint-num1');
                            }
                        });
                    }
                }
            });
        }
        //支付取消 失败 跳转
        function fail_charge() {
            return false;
            // location.href = '<?php echo U('Order/order',['checkinfo'=>1]);?>';
        }

        function wxpay (){
            var wxconfig = $.parseJSON($('[name="wxconfig"]').val());
            wx.chooseWXPay({
                timestamp: wxconfig.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr: wxconfig.nonceStr, // 支付签名随机串，不长于 32 位
                package: wxconfig.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                signType: wxconfig.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign: wxconfig.paySign, // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    if(res.errMsg == "chooseWXPay:ok"){
                        window.setTimeout(function(){
                            location.href = $('[name="wxconfig"]').data('url');
                        }, 2000);
                    }else{
                        console.log('fail1');
                        var message = '支付失败，请重新尝试，多次失败请联系管理员\n'+res.err_msg;
                        // $.alert(message, function() {
                        //  //点击确认后的回调函数
                        //  location.reload(true);
                        // });
                        $.alert(message);
                    }
                },
                cancel:function(res){
                    fail_charge();
                    //支付取消
                },
                fail:function(res){
                    // $.alert(res.errMsg);
                    // alert(res);
                    // $.post('<?php echo U('Order/query');?>', {id: $('[name="wxconfig"]').data('id')}, function(data, textStatus, xhr) {
                    //  if(data.status){
                    //      console.log(data.query);
                    //  }else{
                    //      $.alert('查询失败');
                    //  }
                    // },'json');
                    fail_charge();
                    $.alert('微信支付订单过期,请重新下单')
                    console.log('失败了');
                    setTimeout(function(){
                        // location.reload(true);
                    }, 1000);
                    console.log(res);
                }
            });
        }

        function callpay(wxconfig){
            $('[name="wxconfig"]').val(wxconfig);
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', wxpay, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', wxpay);
                    document.attachEvent('onWeixinJSBridgeReady', wxpay);
                }
            }else{
                wxpay();
            }
        };
    </script>

</body>
</html>