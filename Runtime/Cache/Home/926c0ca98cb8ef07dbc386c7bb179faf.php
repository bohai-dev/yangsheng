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

        <div class="p10 view-head view-comm">
            <div class="colf item-bd">
                <p class="fs14">佣金总额：<b class="fs30"><?php echo ($user["money"]); ?>元</b></p>
                <span class="mt10 fs14">快去发扩充更多团员一起享受收益吧！</span>
                <a href="<?php echo U('Home/Member/apply');?>" class="br3 fs12 colf">申请提现</a>
            </div>
        </div>

        <div class="table bgf btb tc fs15 comm_nav">
            <a href="javascript:;" class="table-cell change vm br col6  active" data-value="1">可提现</a>
            <a href="javascript:;" class="table-cell change vm col6"  data-value="2">已提现</a>
        </div>

        <div class="comm_con">
            <ul class="view-list change1" >
                <li class="p10 bb fs15 col6 comm_tit">
                    累计佣金金额： <em class="colred"><?php echo ($total_money); ?></em> 元
                </li>
                <?php if(is_array($list)): foreach($list as $key=>$list): ?><li class="table bgf bb">
                        <div class="table-cell vm p10">
                            <p class="fs15 col3"><?php echo ($list["title"]); ?></p>
                            <p class="mt5 col9"><?php echo substr($list['create_time'],0,10);?></p>
                        </div>
                        <div class="table-cell vm p10 item-right">
                            <span class="fs16 plus">＋<?php echo ($list["money"]); ?></span>
                        </div>
                    </li><?php endforeach; endif; ?>

            </ul>
            <!-- 可提现 end -->
            <ul class="view-list change2" style="display: none">
                <li class="p10 bb fs15 col6 comm_tit">
                    累计提现金额： <em class="colred"><?php echo ($withdraw_total); ?></em> 元
                </li>
                <?php if(is_array($withdraw_list)): foreach($withdraw_list as $key=>$list): ?><li class="table bgf bb">
                        <div class="table-cell vm p10">
                            <p class="fs15 yellow">￥<?php echo ($list["money"]); ?></p>
                            <p class="mt5 col9"><?php echo substr($list['create_time'],0,10);?></p>
                        </div>
                        <div class="table-cell vm p10 item-right">
                            <span class="fs16 plus">
                                <?php switch($list['status']): case "1": ?>审核中<?php break;?>
                                    <?php case "2": ?>已转账<?php break;?>
                                    <?php case "3": ?>审核未通过<?php break; endswitch;?>
                            </span>
                        </div>
                    </li><?php endforeach; endif; ?>

            </ul>
            <!-- 冻结中 end -->
        </div>

    </section>

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script>
            $(function(){
                $('.change').on('click',function(){
                    var $this =$(this);
                    $(this).addClass('active').siblings().removeClass('active');
                    if($this.attr('data-value')==1){
                        $('.change1').show();
                        $('.change2').hide();
                    }else{
                        $('.change2').show();
                        $('.change1').hide();
                    }
                });
            })
    </script>

</body>
</html>