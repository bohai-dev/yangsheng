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
        
  <div class="foot_fixed">
    <dl class="table bgf item-settle">
      <dd class="table-cell pl10">
      <p class="fs14 col3 pay-price">应付金额：￥ <span><?php echo ($total_price+$postage); ?></span></p>
      </dd>
      <dt class="table-cell">
        <a href="javascript:;" class="bg_own btn-block pay-now">立即支付</a>
        <!-- <button type="" class="bg_own btn-block">立即支付</button> -->
      </dt>
    </dl>

  </div>
  <!-- 底部悬浮 end -->

  <section class="main">

  <form action="<?php echo U('Shop/Order/addOrder');?>" method="post" name="pay">
      <input type="hidden" name="wxconfig">
    <?php if(!empty($address_info)): ?><div class="p10 item-info item-default">
      <input name="address" type="hidden" value="<?php echo ($address_info['id']); ?>">
      <a href="<?php echo U('Home/Member/address');?>" class="fs14 col3 item-hd arrowR">
        <span><i class="mr5 ico-user"></i><?php echo ($address_info['realname']); ?></span>
        <span class="ml10"><i class="mr5 ico-tel"></i><?php echo ($address_info['phone']); ?></span>
        <p class="pt10">
          <?php echo ($address_info['prov']); ?>
          <?php echo ($address_info['city']); ?>
          <?php echo ($address_info['country']); ?>
          <?php echo ($address_info['detail']); ?>
        </p>
        <?php if(($address_info['default']) == "1"): ?><em class="br5 fs12 colf status">默认地址</em><?php endif; ?>
      </a>
    </div>
    <?php else: ?>
      <div class="p10 item-info item-default item-address">
      <a href="<?php echo U('Home/Member/address_add');?>" class="tc fs14 col3">
        <em></em>添加收货地址
      </a>
    </div><?php endif; ?>

    <?php if(is_array($info)): foreach($info as $key=>$val): ?><div class="table p10 btb bgf">
      <div class="table-cell order_pic">
        <div class="ba br5 item-pic">
           <img src="<?php echo getpics($val['cover']);?>" alt="" class="imgm">
        </div>
      </div>
      <div class="table-cell pl10 item-con">
        <p class="fs14 col3 multi-line"><?php echo ($val['title']); ?></p>
        <div class="mt10 m-price">
          <em class="fs18 colred">¥<?php echo ($val['seckill_price']?:$val['sale_price']); ?></em>
          <span class="fs12 col9 item-more">数量×<?php echo ($val['goodsnum']); ?></span>
          <del class="ml10 col9">¥<?php echo ($val['original_price']); ?></del>
        </div>
      </div>
    </div>
    <?php if(($action) == "buynow"): ?><input type="hidden" name="gid" value="<?php echo ($val["id"]); ?>">
      <input type="hidden" name="buynum" value="<?php echo ($val["goodsnum"]); ?>">
      <input type="hidden" name="goodskey" value="<?php echo ($val["spec_key"]); ?>"><?php endif; endforeach; endif; ?>
    <ul class="mt10 btb bgf last fs14 col3 order_settle">
          <input name="integral" type="hidden"  value="0">

<!--       <li class="table bb">
        <div class="table-cell item-lt">积分</div>
        <div class="table-cell ">
          <input name="integral" type="text" class="form-unify"  onkeyup="checkIntegral(this)" onpaste="checkIntegral(this)"   placeholder="最多可使用<?php echo ($user_score); ?>积分" value="" id="popShow">
        </div>
      </li>
 -->
      <?php if(!empty($check_coupons)): ?><li class="table bb">
          <div class="table-cell item-lt">优惠券</div>
          <a href="<?php echo U('Shop/Order/coupons',array('gid'=>$coupon_gid));?>">
            <div class="table-cell arrowR">
              <input name="coupon" type="text" readonly="readonly" class="form-unify" data-values="<?php echo ((isset($coupons_info['id']) && ($coupons_info['id'] !== ""))?($coupons_info['id']):'0'); ?>" value="<?php echo ((isset($coupons_info['title']) && ($coupons_info['title'] !== ""))?($coupons_info['title']):''); ?>" placeholder="请选择" >
              <input name="true_pay_coupon" type="hidden" value="<?php echo ((isset($coupons_info['id']) && ($coupons_info['id'] !== ""))?($coupons_info['id']):'0'); ?>">
            </div>
          </a>
        </li><?php endif; ?>
    </ul>

    <ul class="mt10 btb bgf last fs14 col3 order_settle">
      <li class="table bb">
        <div class="table-cell item-lt">支付方式</div>
        <a href="<?php echo U('Shop/Order/pay_type');?>">
        <div class="table-cell arrowR">
          <p class="tr form-unify" ><?php echo ($pay_name); ?></p>
         <!--  <input name="pay_type" type="text"  readonly="readonly" class="tr form-unify" placeholder="请选择" value="<?php echo ($pay_name); ?>" data-values="<?php echo ($pay_type); ?>"> -->
          <input name="true_pay_type" type="hidden" value="<?php echo ($pay_type); ?>">
        </div>
        </a>
      </li>
      <li class="table bb ">
        <div class="table-cell item-lt">索取发票</div>
          <div class="table-cell">
            <!-- <a href="<?php echo U('Shop/Order/receipt');?>"> -->
            <input name="receipt_title" type="text"  unselectable="true"  onfocus="this.blur()"  readonly class="form-unify" placeholder=""  value="<?php echo ($receipt_title); ?>">
            <!-- <input type="checkbox" class="weui_check" name="cancel_receipt" id="cancel_receipt" value="1"> -->
            <!-- </a> -->
            <!-- <div class="weui_cell_ft"> -->
            <!-- </div> -->
            <!-- <input name="cancel_receipt" type="checkbox" value="1"> -->
            <input name="receipt_type" type="hidden" value="<?php echo ($receipt_type); ?>">
            <input name="receipt_tit" type="hidden" value="<?php echo ($receipt_tit); ?>">
          </div>
        <div class="table-cell" style="width:45px">
          <input class="weui_switch" id="choose_receipt" name="choose_receipt" type="checkbox" style="display: inline-block;vertical-align: middle;" <?php echo empty($receipt_tit)?'':'checked';?>>
        </div>
      </li>
      <li class="table bb">
        <div class="table-cell item-lt vt " style="padding-top:6px;">订单备注</div>
        <div class="table-cell vt">
          <textarea name="remark" class="form-unify " placeholder="订单留言(选填)"></textarea>
        </div>
      </li>
      <!-- <li class="table bb invi_hide">
        <div class="table-cell item-lt">发票抬头</div>
        <div class="table-cell">
          <input name="receipt_title" type="text" class="form-unify" placeholder="请输入发票抬头">
        </div>
      </li>-->
    </ul>

    <div class="table mt10 p10 btb bgf fs14 col3">
      <div class="table-cell">将获得积分</div>
      <div class="table-cell tr"><?php echo ($back_integral); ?>点</div>
    </div>

    <ul class="mt10 p10 btb bgf fs14 col3">
      <li class="clearfix">
        <span>商品总额</span>
        <em class="fr colred total-price">￥<span><?php echo ($total_price); ?></span></em>
      </li>
      <li class="clearfix">
        <span>运费</span>
        <em class="fr colred postage">￥ <span><?php echo ($postage); ?></span></em>
      </li>
      <!--       <li class="clearfix">
        <span>积分抵扣</span>
        <em class="fr colred integral">￥ -<span>0</span></em>
      </li> -->
      <?php if(!empty($check_coupons)): ?><li class="clearfix" >
          <span>优惠券</span>
          <em class="fr colred coupon">￥ -<span><?php echo ((isset($coupons_info['price']) && ($coupons_info['price'] !== ""))?($coupons_info['price']):"0"); ?></span></em>
        </li><?php endif; ?>
    </ul>
    <input type="hidden" name="action" value="<?php echo ($action); ?>">
  </form>


  </section>
  <!-- main主体 end -->

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
  <script src="/yangsheng/html/js/ap.js"></script>
  <script>
  var domain = "<?php echo XILUDomain();?>"+"/yangsheng";
  wx.config(<?php echo ($jsapi); ?>);
  $(function(){

    countPayPrice();

    $('#choose_receipt').click(function(){
      if($(this).is(':checked')){
        location.href="<?php echo U('Shop/Order/receipt');?>";
      }else{
        Cookies.set('<?php echo (ENV_PRE); ?>home_default_receipt_type',  '');
        Cookies.set('<?php echo (ENV_PRE); ?>home_default_receipt_tit',  '');
        Cookies.set('<?php echo (ENV_PRE); ?>home_default_receipt_title',  '');
        $('[name=receipt_title]').val('');
        $('[name=receipt_type]').val('');
        $('[name=receipt_tit]').val('');

      }
    })

    //阻止支付方式輸入框
    // $('[name=pay_type]').click(function(){
    //   $(this).blur();
    // })

    //立即支付
    $('.pay-now').click(function(){
      var url = "<?php echo U('Shop/Order/addOrder');?>";
      var address  = $("input[name='address']").val(); //地址
      var coupon   = $("input[name='coupon']").attr('data-values');//优惠券
      var integral = $("input[name='integral']").val();//积分
      // var pay_type   = $("input[name='pay_type']").attr('data-values');//支付方式
      // var receipt   = $("input[name='receipt']").val();//发票
      var receipt_title   = $("input[name='receipt_title']").val();//抬头
      var receipt_type   = $("input[name='receipt_type']").val();//类型
      var receipt_tit   = $("input[name='receipt_tit']").val();//个人公司
      var remark        = $('[name=remark]').val();
      var ids = "<?php echo ($ids); ?>";
      var num = "<?php echo ($num); ?>";
      var key ="<?php echo ($spec_key); ?>";
      if(!address){
        $.toast('请选择地址','text');
        return false;
      }

      var $form = $('form[name=pay]');
      $.post($form.attr('action'), $form.serialize(), function(data, textStatus, xhr) {
        if(data.status){
          if(data.paytype ==4){
            $.alert('购买成功',function(){
              location.href = data.url;
            });
          }
          else if(data.paytype == 2){	//支付宝支付
            _AP.pay(data.alipayurl);
          }
          else {	//微信支付
            if (data.wxconfig != '') {
              $('[name="wxconfig"]').data('id', data.id).data('url', data.url);
                  callpay(data.wxconfig);
            } else {
              setTimeout(function () {
                $.toast('购买成功', function () {
                  location.href = data.url;
                });
              }, 0);
            }
          }
        }else{
          $('.buycart_submit').show();
          $.alert(data.info,function(){
            if(data.url){
              location.href = data.url;
            }
          });
        }
      }, 'json');
    })

  });

  //输入积分
  function checkIntegral(obj)
  {
    var max_integral = <?php echo ($user_score); ?>;
    obj.value=obj.value.replace(/^0+\d/g,'');
    obj.value=obj.value.replace(/\D/g,'');
    if(obj.value==0){
      obj.value='';
    }
    if(obj.value >max_integral){
      obj.value=max_integral;
    }

    var integral_rate = "<?php echo ($integral_rate); ?>";
    var integral_price = (obj.value * integral_rate).toFixed(2);
    $('.integral span').text(integral_price);
    countPayPrice();
  }

  //算总价
  function countPayPrice()
  {
    var total_price = $('.total-price span').text()*1;
    var postage = $('.postage span').text()*1;
    var integral = $('.integral span').text()*1;
    var coupon = $('.coupon span').text()*1;
    var pay_price = total_price+postage-integral-coupon;
    if(pay_price<=0){
      pay_price=0.01;
    }
    $('.pay-price span').text(pay_price);
  }

  function callpay(wxconfig)
  {
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
  }

  function wxpay ()
  {
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
            location.href = "<?php echo U('Shop/Order/index',['checkinfo'=>2]);?>";
          }, 1000);
        }else{
          var message = '支付失败，请重新尝试，多次失败请联系管理员\n'+res.err_msg;
          $.alert(message, function() {
           //点击确认后的回调函数
           location.reload(true);
          });
          // $.alert(message);
        }
      },
      cancel:function(res){
        //支付取消
        fail_charge();
      },
      fail:function(res){
        $.alert('微信支付订单过期,请重新下单');
        fail_charge();
        // setTimeout(function(){
        //   location.reload(true);
        // }, 1000);
      }
    });
  }

  function fail_charge()
  {
    location.href = "<?php echo U('Shop/Order/index',['checkinfo'=>1]);?>";
  }
  </script>

</body>
</html>