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
		
    <section class="top_fixed">
        <div class="table bgf btb tc fs14 tab_nav">
            <a href="javascript:;" class="table-cell list active"  data-value="1"><span> <?php if(($type) == "0"): ?>我<?php else: ?>TA<?php endif; ?>的关注</span></a>
            <a href="javascript:;" class="table-cell list" data-value="2"><span><?php if(($type) == "0"): ?>我<?php else: ?>TA<?php endif; ?>的粉丝</span></a>
        </div>
    </section>

        <!-- 页面主体 -->
        

    <section class="main">
        <div class="comm_con">
            <ul class="attention">
                <?php if(empty($attention)): ?><div class="tc nothing">
                        <i><img src="/yangsheng/html/images/icon_none-order.png" alt="" class="imgm"></i>
                        <span class="mt10 fs14 col9">暂无关注</span>
                    </div>
                    <?php else: ?>
                    <?php if(is_array($attention)): foreach($attention as $key=>$list): ?><li class="flex-box flex-between bb bgf box-b p10">
                            <a href="<?php echo U('Forum/Index/personal_list',array('id'=>$list['attention_userid']));?>" class="flex-box">
                                <p class="item-pic br50 mr10" style="width: 40px;height: 40px;">
                                    <img src="<?php echo getpics(get_user_info($list['attention_userid'],'admin_user','avatar'));?>" alt="" class="imgm">
                                </p>
                                    <span class="col3 fs13">
                                       <?php echo get_user_info($list['attention_userid'],'admin_user','nickname');?>
                                    </span>
                            </a>
                            <?php if(($type) == "0"): ?><a href="javascript:;" data-value="<?php echo ($list["attention_userid"]); ?>" class="block follow-btn fs13 active cancel ">
                                    + 取消关注
                                </a>
                                <?php else: endif; ?>
                        </li><?php endforeach; endif; endif; ?>
            </ul>
            <ul class="fans" style="display: none">
                <?php if(is_array($fans)): foreach($fans as $key=>$list): ?><li class="flex-box flex-between bb bgf box-b p10">
                    <a href="<?php echo U('Forum/Index/personal_list',array('id'=>$list['uid']));?>" class="flex-box">
                        <p class="item-pic br50 mr10" style="width: 40px;height: 40px;">
                            <img src="<?php echo getpics(get_user_info($list['uid'],'admin_user','avatar'));?>" alt="" class="imgm">
                        </p>
                            <span class="col3 fs13">
                                <?php echo get_user_info($list['uid'],'admin_user','nickname');?>
                            </span>
                    </a>
                    <?php if(($type) == "0"): if(($list['attention']) == "0"): ?><a href="javascript:;" data-value="<?php echo ($list["uid"]); ?>" class="block follow-btn attention1  fs13">
                                + 关注
                            </a>
                            <?php else: ?>
                            <a href="javascript:;" data-value="<?php echo ($list["uid"]); ?>" class="block follow-btn fs13 active cancel ">
                                + 取消关注
                            </a><?php endif; ?>
                        <?php else: ?>
                           <!-- <a href="javascript:;" data-value="<?php echo ($list["uid"]); ?>" class="block follow-btn attention1  fs13">
                                + 关注
                            </a>--><?php endif; ?>
                </li><?php endforeach; endif; ?>
            </ul>
        </div>
    </section>



        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script>
        $(function(){
            $('.list').on('click',function(){
                var $this =$(this);
                if($this.attr('data-value')==1){
                    $this.addClass('active').siblings().removeClass('active');
                    $('.attention').show();
                    $('.fans').hide();
                }else{
                    $this.addClass('active').siblings().removeClass('active');
                    $('.fans').show();
                    $('.attention').hide();
                }
            });
            // 关注
            $(document).on('click','.attention1',function(){
                var $this =$(this);
                $.ajax({
                    url:"<?php echo U('Forum/Index/attention');?>",
                    type:'post',
                    dataType:'json',
                    data:{attend_uid:$this.attr('data-value'),type:1},
                    success:function(data){
                        if(data.status){
                            $.alert(data.info,function(){
                                location.reload();
                            });
                        }else{
                            $.alert(data.info);
                        }
                    },
                    error:function(){

                    }
                });
            });
            $('.cancel').on('click',function(){
                var $this =$(this);
                $.confirm("确认取消关注吗？", function(){
                    $.ajax({
                        url:"<?php echo U('Forum/Index/attention');?>",
                        type:'post',
                        dataType:'json',
                        data:{attend_uid:$this.attr('data-value'),type:2},
                        success:function(data){
                            if(data.status){
                                location.reload();
                            }else{
                                $.alert(data.info);
                            }
                        },
                        error:function(){

                        }
                    });
                });
            });
        })
    </script>

</body>
</html>