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
        <div class="table-cell col6 item-lt">收 货 人 :</div>
        <div class="table-cell">
          <input name="realname" type="text" class="form-unify" placeholder="请输入收货人姓名">
        </div>
      </li>
      <li class="table plr10 bb">
        <div class="table-cell col6 item-lt">联系方式：</div>
        <div class="table-cell">
          <input name="phone" type="text" class="form-unify" placeholder="请输入联系方式">
        </div>
      </li>
      <li class="table plr10 bb">
        <div class="table-cell col6 item-lt">所在地区：</div>
        <div class="table-cell arrow-icon">
        <input name="area" type="text" class="form-unify" placeholder="请选择" id="select-site">
        </div>
      </li>
      <li class="table plr10 bb">
        <div class="table-cell vt pt10 col6 item-lt">详细地址：</div>
        <div class="table-cell vt">
          <textarea name="detail" cols="" rows="5" class="form-unify" placeholder="输入详细地址"></textarea>
        </div>
      </li>
    </ul>
    <!-- 收货地址资料填写 end -->

    <div class="p10 weui_cells_checkbox set_default">
      <label class="weui_check_label table-cell" for="buy1">
        <input type="checkbox" class="weui_check" value="1" name="default" id="buy1">
        <i class="weui_icon_checked">设为默认地址</i>
      </label>
    </div>
    <!-- 设为默认地址 end -->

    <div class="p10 btn_submit">
      <button type="button" class="bg_own br5 btn-block">保存并使用</button>
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

  
  

	
    
<script type="text/javascript" src="/yangsheng/html/weui/js/city-picker.js" charset="utf-8"></script>
<script>
    $(function(){
        // 选择城市
        $("#select-site").cityPicker({
            title: "选择地区"
        });

        $("#city-picker").on("click",function () {
            $("input").blur();
        });

        $('.btn-block').on('click', function(){
            if($('[name="realname"]').val()==''){
                $.alert('请填写收货人');
                return false;
            }
            if($('[name="phone"]').val()==''){
                $.alert('请填写联系方式');
                return false;
            }
            var mobile =$('[name="phone"]').val();
            if (!mobile.match(/^0?(13|15|18|14|17)[0-9]{9}$/)) {
                $.alert('请输入正确的手机号！');
                return false;
            }
            if($('[name="detail"]').val()==''){
                $.alert('请填写详细地址');
                return false;
            }
            if($.trim($('[name="area"]').val()) == ''){
                $.alert('请选择收货地区');
            }else{
                $.showLoading();
                var url  ="<?php echo U('Member/address_add');?>";
                var $form = $('form').serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: $form,
                })
                .done(function(data) {
                    if(data.status){
                        setTimeout(function(){
                            $.toast("新增成功", function(){
                                location.href = data.url;
                            });
                        }, 0);
                    }else{
                       $.alert('新增失败');
                    }
                })
                .fail(function() {
                    $.alert('网络超时，请再次尝试失败后联系管理员');
                })
                .always(function() {
                    $.hideLoading();
                });
            }
            return false;
        });
    })
</script>

</body>
</html>