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
        
<div class="web_index_main">
  <div class="top_fixed search_fixed">
    <div class="plr10 box search_header search_bg" style="cursor: pointer">
    <!--<a href="javascript:;" class="sH_classify">分类</a>
      <div class="bgf br50 flex-1 sH_main">
        <a href="javascript:;" class="block col9 sH_search"><i class="icon_search"></i>输入您想要的商品</a>
      </div>-->
      <?php if(empty($uid)): ?><a href="<?php echo U('Home/Member/login');?>" class="sH_login">登录</a>
      <?php else: ?>
      <a href="javascript:;" class="sH_login">　</a><?php endif; ?>
    </div>
  </div>
  <!-- 顶部悬浮搜索 end -->

  <div class="search_classify" >
    <div class="item-bg" style="cursor: pointer"></div>
    <div class="item-con" style="cursor: pointer">
      <ul class="clearfix">
        <?php if(is_array($type)): foreach($type as $key=>$val): ?><li class="bb"><a href="<?php echo U('Shop/Goods/index',['pid'=>$val['id']]);?>" ><?php echo ($val['title']); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
  </div>

  <section class="main">

    <div class="swiper-container banner">
      <div class="swiper-wrapper">
        <?php if(is_array($banner)): foreach($banner as $key=>$val): ?><div class="swiper-slide">
              <a href="<?php echo ($val['url']?$val['url']:'javascript:;'); ?>">
                <img src="<?php echo getpics($val['cover']);?>" alt="2:1,640*320" class="imgm">
              </a>
            </div><?php endforeach; endif; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <!-- banner end -->
    <!--<?php if(!empty($column)): ?><div class="clearfix pt10 btb bgf tc fs12 nav_index">
        <?php if(is_array($column)): foreach($column as $key=>$column_list): ?><a href="<?php echo ($column_list['url']? $column_list['url']:'javascript:;'); ?>" class="mb10">
            <img src="<?php echo get_cover($column_list['cover']);?>" alt="" class="imgm">
            <p class="mt5 col6"><?php echo ($column_list["title"]); ?></p>
          </a><?php endforeach; endif; ?>
      </div><?php endif; ?>-->


    <!--<div class="table mt10 p10 btb bgf bt scroll_news">
      <div class="table-cell vm pr10 item-hd"><img src="/yangsheng/html/images/icon_scrollnews_n.png" alt="" class="imgm"></div>
      <div class="table-cell vm plr10 bl item-lt"><img src="/yangsheng/html/images/icon_scrollnews2.png" alt="" class="imgm"></div>
      <div class="table-cell vm">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <?php if(is_array($bulletin)): foreach($bulletin as $key=>$val): ?><a href="<?php echo U('Shop/Bulletin/detail',['id'=>$val['id']]);?>" class="swiper-slide single-line col_own"><?php echo ($val['title']); ?></a><?php endforeach; endif; ?>
          </div>
        </div>
      </div>
    </div>-->

    <!-- 今日头条 end -->
   <!-- <?php if(!empty($seckill) && !empty($seckill_goods)): ?>
      <div class="mt10 btb bgf index_seckill">
        <div class="clearfix p10 item-hd">
          <div class="fl item-lt" style="width: 80px; padding-right: 10px;">
            <img src="/yangsheng/html/images/icon_seckill_n.png" alt="" class="imgm">
          </div>
          <span class="fl plr10 fs13 col9 ml10"><?php echo ($seckill['title']); ?>&lt;!&ndash;：<?php echo ($seckill['remark']); ?>&ndash;&gt;</span>
          <div class="fl fs12 col3 item-down">
            <em>00</em> : <em>00</em> : <em>00</em>
          </div>
          <a href="<?php echo U('Shop/Goods/seckill_list',['id'=>$seckill['id']]);?>" class="fr fs12 col_own">限时限量</a>
        </div>
        <div class="swiper-container bt tc item-bd">
            <div class="swiper-wrapper">
          <?php if(is_array($seckill_goods)): foreach($seckill_goods as $key=>$val): ?><a href="<?php echo U('Shop/Goods/detail',['id'=>$val['id']]);?>" class="swiper-slide p10 br">
                <div class="item-pic"><img src="<?php echo getpics($val['cover']);?>" alt="" class="imgm"></div>
                <p class="fs13 col3 single-line"><?php echo ($val['title']); ?></p>
                <span class="mt5 fs12 item-price">￥<?php echo ($val['seckill_price']); ?></span>
              </a><?php endforeach; endif; ?>
            </div>
        </div>
      </div>
    <?php endif; ?>-->
    <!-- 秒杀 end -->

    <!--<div class="mt10 btb bgf fs13 col3 table index_menu">
      <a href="<?php echo U('Shop/Ad/index');?>" class="table-cell item-lt">
        <img src="<?php echo getpics($ad[0]['cover']);?>" alt="" class="imgm">
      </a>
      <div class="table-cell bl item-rt">
        <a href="<?php echo U('Shop/Ad/ad');?>" class="block">
          <img src="<?php echo getpics($ad[1]['cover']);?>" alt="" class="imgm">
        </a>
        <a href="<?php echo ($ad[2]['url']); ?>" class="block bt">
          <img src="<?php echo getpics($ad[2]['cover']);?>" alt="" class="imgm">
        </a>
      </div>
    </div>-->
    <!-- 广告 end -->

    <?php if(is_array($columnsInfo)): foreach($columnsInfo as $key=>$columns): ?><!--<a href="<?php echo ($columns['url']?:'javascript;'); ?>" class="block mt10 btb">
        <img src="<?php echo getpics($columns['cover']);?>" alt="" class="imgm">
      </a>-->
      <div class="mt10 p10 btb bgf index_title">
        <p class="tc fs15 col_own"><i class="mr5"><img src="<?php echo getpics($columns['icon']);?>" alt="" class="imgm"></i><?php echo ($columns['title']); ?></p>
      </div>
      <ul class="weui-row list_goods">
        <?php if(is_array($columns["goods"])): foreach($columns["goods"] as $key=>$goodsInfo): ?><li class="weui-col-100 mt10 relative">
            <a href="<?php echo U('Shop/Goods/detail',['id'=>$goodsInfo['id']]);?>" class="block  bgf">
              <div class="item-pic"><img src="<?php echo getpics($goodsInfo['cover']);?>" alt="" class="imgm"></div>
              <div class="p5  item-con">
                <p class="fs13 col3 multi-line"><?php echo ($goodsInfo['title']); ?></p>
                <div class="fs12">
                  <span class="fs13 colred">¥<em class="fs16"><?php echo ($goodsInfo['seckill_price']?$goodsInfo['seckill_price']:$goodsInfo['sale_price']); ?></em></span>
                  <del class="ml10 col9">¥<?php echo ($goodsInfo['original_price']); ?></del>
                </div>
              </div>
            </a>
            <a href="javascript:;" class="to-cart" data-value="<?php echo ($goodsInfo['id']); ?>"><img  src="/yangsheng/html/images/to-cart.png" alt="" class="block"></a>
          </li><?php endforeach; endif; ?>
      </ul><?php endforeach; endif; ?>
      <!-- 栏目 end -->

    <div class="mt10 p10 btb bgf tc" >
      <span class="fs14 col3"><?php echo C('WEB_SITE_TITLE');?></span>
      <p class="fs12 col3">全国统一客服热线 <a href="tel:<?php echo ($shop_mobile); ?>"><?php echo ($shop_mobile); ?></a></p>
      <p class="fs12 col3"> <a href="javascript:;"><?php echo C("WEB_SITE_COPYRIGHT");?></a></p>
    </div>
    <div class="p10 col9 fs13 tc nomore" id="full" style="margin-bottom:60px;">
      <span class="col9">—— <i></i> ——</span>
      <p class="fs12 col9">亲，没有更多了哦~</p>
    </div>
  </section>
  <!-- main主体 end -->
</div>
  <section class="spec-wrap popup-wrap" style="display: none;"></section>
<!-- 搜索定位弹窗 end -->

        <!-- 底部导航区域 -->
		
  <div class="handle_fixed">
      <a href="<?php echo U('Shop/Cart/index');?>" class="ba brarc"><i class="hf-1"></i></a>
     <!-- <a href="<?php echo U('Shop/Index/index');?>" class="ba brarc"><i class="hf-2"></i></a>
      <a href="<?php echo U('Shop/Goods/index');?>" class="ba brarc"><i class="hf-3"></i></a>-->
      <a href="javascript:;" class="ba brarc to_top"><i class="hf-4"></i></a>
  </div>

	</section>

  
  <section class="search_wrap" style="z-index:2;">
    <dl class="table bb header">
      <dd class="table-cell vm">
        <a href="javascript:;" class="back"></a>
      </dd>
      <dt class="table-cell vm">
        <input type="text" value="" name="search" placeholder="请输入搜索的关键词" >
      </dt>
      <dd class="table-cell vm">
        <input type="submit" value="搜索">
      </dd>
    </dl>
    <div class="p10 search_main">
      <div class="fs13 item-tit">
        <i class="mr5 sm-1"></i>最近搜索
        <a href="javascript:;" id="clean-search" class="fr">
          <?php if(!empty($user_search_info)): ?><i class="sm-3"></i><?php endif; ?>
        </a>
      </div>
      <div class="ptb10 item-con user-search">
        <?php if(!empty($user_search_info)): if(is_array($user_search_info)): foreach($user_search_info as $key=>$val): ?><a href="<?php echo U('Shop/Goods/goods_list',['search_kw'=>$val]);?>"  class="btn-mini search-kw"><?php echo ($val); ?></a>　<?php endforeach; endif; ?>
        <p align="center" class="no-user-search" style="font-size:12px;display:none;">暂无搜索历史</p>
        <?php else: ?>
          <p align="center" style="font-size:12px;">暂无搜索历史</p><?php endif; ?>
      </div>

      <div class="fs13 item-tit">
        <i class="mr5 sm-2"></i>热门搜索
      </div>
      <div class="ptb10 item-con">
        <?php if(is_array($shop_search_info)): foreach($shop_search_info as $key=>$val): ?><a href="<?php echo U('Shop/Goods/goods_list',['search_kw'=>$val['word']]);?>" class="btn-mini search-kw"><?php echo ($val['word']); ?></a>　<?php endforeach; endif; ?>
      </div>
    </div>
  </section>


	
    
  <script src="/yangsheng/html/weui/js/swiper.js"></script>
  <script>
    $(function(){
      //清空数据存储
      sessionStorage.removeItem('aGoodsList');
      sessionStorage.removeItem('aGoodsParam');
      //====加入购物车Start
      $(document).on('click','.to-cart',function(){
        var id = $(this).attr('data-value');
        getGoodsItemHtml(id);
      })
      //隐藏
      $(document).on('click','.popup-overlay',function(){
        $('.spec-wrap').empty();
        $('.spec-wrap').hide();
      });

      //====加入购物车END

      //输入搜索
      $(':submit').click(function(){
        search_kw = $("input[name='search']").val();
        if(!search_kw){
          $.toast('请输入关键词','text');
          return false;
        }
        location.href="<?php echo U('Shop/Goods/goods_list/search_kw/"+search_kw+"');?>";
      })
        //清除最近搜索
      $('#clean-search').click(function(){
        Cookies.set('<?php echo (ENV_PRE); ?>home_user_search','');
        $('#clean-search i,sm-3').remove();
        $('.user-search a').remove();
        $('.no-user-search').show();
      })

      //秒杀倒计时
      var endTime = "<?php echo ($seckill['count_time']); ?>";
      if(endTime !== null ){
        setInterval(function(){
          countDown(endTime);
        },1000);
      }

      //轮播
      var swiperWidth = $(".banner").width();
      swiperHeight = swiperWidth * 0.5;
      $(".banner").css("height",swiperHeight);
      $(".banner").swiper({
        pagination: '.swiper-pagination',
        loop: true,
        autoplay: 3000,
        autoplayDisableOnInteraction : false,
      });

      //头条
      $(".scroll_news .swiper-container").swiper({
        direction: 'vertical',
        pagination: '',
        slidesPerView: 1,
        height: 24,
        loop: true,
        autoplay: 3000,
        autoplayDisableOnInteraction : false,
      });

      //秒杀
      var swiper = new Swiper('.index_seckill .swiper-container', {
        pagination: '',
        slidesPerView: 3,
        paginationClickable: true,
        freeMode: true
      });

      //频道 js
      $(document).on('click', '.search_header .sH_classify', function() {
        if ( !$(this).hasClass('on') ) {
          $(this).addClass('on');
          $('.search_classify').show();
          $('.search_classify .item-con').slideDown('fast');
        } else {
          $(this).removeClass('on');
          $('.search_classify .item-con').slideUp();
          $('.search_classify').hide();
        }
      });
      $(document).on('click', '.search_classify .item-bg', function() {
        $('.search_header .sH_classify').removeClass('on');
        $('.search_classify .item-con').slideUp();
        $('.search_classify').hide();
      });
      $(document).on('click','.search_classify .item-con',function(){
        $(this).removeClass('on');
        $('.search_header .sH_classify').removeClass('on');
        $('.search_classify .item-con').slideUp();
        $('.search_classify').hide();
      })

      //搜索js
      $(document).on('click','.search_header .sH_search',function() {
        $(".wrap").hide();
        $(".search_wrap").show();
      });
      $(document).on('click','.header .back',function() {
        $(".wrap").show();
        $(".search_wrap").hide();
      });
      //栏目
      $(".list_goods .item-pic").each(function(){
        var w = $(this).width();
        $(this).css({"height": w});
      });
    });

    //秒杀倒计时
    function countDown(endTime)
    {
      var date = new Date();
      var nowTime = Date.parse(date)/1000;
      countTime = endTime - nowTime;
      if(countTime>0){
        var hours = parseInt(countTime/3600);
        var minutes = parseInt((countTime-hours*3600)/60);
        var seconds = countTime % 60;
      }else{
        var hours = 0;
        var minutes = 0;
        var seconds = 0;
      }


      if(hours<10){
        hours = '0'+hours;
      }
      if(minutes<10){
        minutes = '0'+minutes;
      }
      if(seconds<10){
        seconds = '0'+seconds;
      }
      $(".item-down em").eq(0).html(hours);
      $(".item-down em").eq(1).html(minutes);
      $(".item-down em").eq(2).html(seconds);
    }

    //获取规格html
    function getGoodsItemHtml(id)
    {
      $.ajax({
        url:"<?php echo U('Shop/Goods/item_detail');?>",
        type:'POST',
        data:{id:id},
        async:false,
        dataType:'json',
        beforeSend:function(){},
        complete:function(){},
        error:function(){},
        success:function(data){
          if(data.status){
            $('.spec-wrap').append(data.html);
            //展示
            $('.spec-wrap').show();
            // $('html,body').css({'height': '100%', 'overflow': 'hidden'});
          }else{
            $.alert('数据异常');
          }
        }
      })
    }


  </script>

</body>
</html>