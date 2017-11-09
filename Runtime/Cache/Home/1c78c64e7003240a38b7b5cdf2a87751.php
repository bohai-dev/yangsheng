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
		
    <div class="top_fixed">
        <p class="ptb10 tc bgf fs14 col0 bb">意见反馈</p>
    </div>

        <!-- 页面主体 -->
        
    <section class="main">
        <form>
            <div class="mt10 p10 bgf fs13 col3 form-group">
                <textarea cols="" rows="5" id="textArea" name="content" class="form-unify ba" placeholder="如果您对我们产品的质量，售后，物流以及商城购物体验有任何问题请告知我们，我们会及时作出调整，感谢您对中华参科技开发有限公司网的支持"></textarea>
                <div class="box flex-between flex-col-center">
                    <a href="javascript:;" class="btn-mini btn-mini_solid br50 upload">＋点击添加图片</a>
                    <p class="col6">还可以输入<span class="col0 textNum">500</span>字</p>
                </div>
            </div>
            <ul class="clearfix multi-upload bgf">
                <li class="mt10 ba br3 upload-file" style="cursor: pointer;display: none">
                    <input type="file" style="visibility: hidden">
                </li>
            </ul>
            <div class="mt10 bgf p10 fs13 col3 form-group">
                <input type="text" class="form-unify" style="margin: 0;" name="contacts" placeholder="请留下您的手机号、邮箱或其它联系方式（选填）">
            </div>
        </form>

        <div class="p10 fs13 col3 pb20">
            <a href="javascript:;" class="mt10 btn-block btn-block__gray br5 sub">提交</a>
            <p class="mt10">客服电话</p>
            <a href="tel:<?php echo ($tel); ?>" class="mt5 btn-block bg_own br5"><img src="/yangsheng/html/images/phone.png" style="width:20px;margin-right: 5px;position: relative;top:4px;" alt=""><?php echo ($tel); ?></a>
            <p class="mt10 tc fs12 col3">您也可以通过邮箱<a class="ora" href="mailto:<?php echo C('email');?>"><?php echo C('email');?></a>与我们联系</p>
        </div>

    </section>

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
    <script>
        function words_deal(){
            var curLength=$("#textArea").val().length;
            if(curLength>500){
                var str = $("#textArea").val().substr(0, 500);
                $("#textArea").val(str);
                $.alert("超过字数限制，多出的字将被截断！" );
            }
            else{
                $(".textNum").text(500-$("#textArea").val().length);
            }
        }

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
                    $('.upload-file').before('<li class="mt10 mr10 ba br3 upload-img" style="background-image:url(' + data.src + ');"><input type="hidden" name="pics[]" value="' + data.id + '" /><a href="javascript:;" class="br50 upload-close"></a></li>');
                    if (localIds.length > 0) {
                        uploadcard(localIds);
                    }
                } else {
                    $.alert('上传失败，请重新上传资料');
                }
            }, 'json').error(function () {
                $.alert('错误的数据')
            });
        }
        $(function(){
            wx.config(<?php echo ($jsapi); ?>);
            $(document).on('click','.upload-file,.upload',function(){
                wx.chooseImage({
                    count: 9, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        uploadcard(localIds,0);
                    }
                });
            });
            $(document).on('click','.upload-close',function(){
                $(this).parent('li').remove();
            });
            $('.sub').on('click',function(){
                if($('[name="content"]').val()==''){
                    $.toptip('请填写意见');
                    return false;
                }
                $.ajax({
                    url:"<?php echo U('complain');?>",
                    type:'post',
                    dataType:'json',
                    data:$('form').serialize(),
                    success:function(data){
                        if(data.status){
                            $.alert(data.info,function(){
                                location.href ="<?php echo U('Member/index');?>";
                            });
                        }else{
                            $.alert(data.info);
                        }
                    },
                    error:function(){

                    }
                });
            });
            $("#textArea").bind('input propertychange', function(){
                words_deal();
            });
        })
    </script>

</body>
</html>