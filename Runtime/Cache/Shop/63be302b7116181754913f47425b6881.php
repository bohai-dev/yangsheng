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
		
    <div class="foot_fixed buycart_submit">
        <a href="javascript:;" class="btn-block bg_own">确定</a>
    </div>

        <!-- 页面主体 -->
        


    <section class="main">
        <p class="p10 bgf col0 fs13 tc">
            设置发票信息
        </p>

        <p class="mt10 p10 bgf col0 fs13">
            发票开立的金额为实际支付金额（订单总额扣除购物卡、抵用券优惠券、积分抵扣、返利等支付后的余额）。
        </p>

        <div class="mt10 pl10 bgf fs13 col0 bb">
            <p class="ptb10 fs14 bb">发票类型</p>
            <div class="ptb10 weui_cells_checkbox">
                <label class="weui_check_label mr10 fs13" for="invoice1">
                    <input type="radio" class="weui_check" name="receipt_type" value="1" id="invoice1" checked>
                    <i class="weui_icon_checked"></i>
                    <span>纸质发票</span>
                </label>
               <!--  <label class="weui_check_label mr10 fs13" for="invoice2">
                    <input type="radio" class="weui_check" name="receipt_type" value="2" id="invoice2">
                    <i class="weui_icon_checked"></i>
                    <span>电子发票</span>
                </label> -->
            </div>
        </div>
        <div class="pl10 bgf fs13 col0 bb">
            <p class="ptb10 fs14 bb">发票抬头</p>
            <div class="ptb10 weui_cells_checkbox">
                <label class="weui_check_label mr10 fs13" for="invoice-tit1">
                    <input type="radio" class="weui_check" name="receipt_tit" value="1" id="invoice-tit1" checked>
                    <i class="weui_icon_checked"></i>
                    <span>个人</span>
                </label>
                <label class="weui_check_label mr10 fs13" for="invoice-tit2">
                    <input type="radio" class="weui_check" name="receipt_tit" value="2" id="invoice-tit2">
                    <i class="weui_icon_checked"></i>
                    <span>公司</span>
                </label>
            </div>
            <div class="form-group pb10 pr10">
                <input type="text" class="form-unify" name="receipt_name" style="padding: 5px 10px;margin: 0;" placeholder="请输入抬头名称">
            </div>
         <!--    <p class="col0 fs13 pb10">中华参科技开发有限公司仅为所购买商品者提供机打纸质发票。公司抬头务必填写正确，中华参科技开发有限公司只提供次页面填写抬头名称为标准，不承担为客户造成的损失！</p> -->
             <p class="col0 fs13 pb10">中华参科技开发有限公司网目前仅为所购买的商品提供纸质发票。请正确填写发票抬头</p>
        </div>
        <div class="mt10 pl10 bgf fs13 col0 bb">
            <p class="ptb10 fs14 bb">发票内容</p>
            <div class="ptb10 weui_cells_checkbox">
                <label class="weui_check_label mr10 fs13" for="invoice-detail1">
                    <input type="checkbox" class="weui_check" checked disabled name="invoice-detail" id="invoice-detail1">
                    <i class="weui_icon_checked"></i>
                    <span>明细</span>
                </label>
            </div>
        </div>

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

            $('.btn-block').on('click',function(){
                var receipt_type =$('input[name="receipt_type"]:checked').val();
                var receipt_tit =$('input[name="receipt_tit"]:checked').val();
                var receipt_name =$('input[name="receipt_name"]').val();
                if(receipt_tit==2 &&  !receipt_name){
                    $.alert('请填写发票抬头');
                    return false;
                }

                Cookies.set('yangsheng_home_default_receipt_type',  receipt_type);
                Cookies.set('yangsheng_home_default_receipt_tit',  receipt_tit);
                Cookies.set('yangsheng_home_default_receipt_title',  receipt_name);
                location.href= "<?php echo ($from); ?>";
            });


            check_receipt();
            $(document).on('click','[name=receipt_tit]',function(){
                check_receipt();
            })

            //为了 IOS
            $(document).on('click','[name=receipt_name]',function(){
                if($(this).attr('readonly')){
                    $(this).blur();
                }
            })

        })

        function check_receipt()
        {
            var choose = $('[name=receipt_tit]:checked').val();
            if(choose ==1){
                $('[name=receipt_name]').blur();
                $('[name=receipt_name]').val('个人');
                $('[name=receipt_name]').attr('readonly','readonly');
            }else{
                $('[name=receipt_name]').val('');
                $('[name=receipt_name]').removeAttr("readonly");
            }
        }
    </script>

</body>
</html>