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
    <dl class="table tc goods_buy">
      <dt class="table-cell br"><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo C('qq_number');?>&site=qq&menu=yes" class="fs12 col6  gbuy-2 ">客服</a></dt>
      <dt class="table-cell br"><a href="<?php echo U('Shop/Index/index');?>" class="fs12 col6 gbuy-1">首页</a></dt>
      <dt class="table-cell"><a href="<?php echo U('Shop/cart/index');?>" class="fs12 col6 gbuy-3 hint-num">购物车</a></dt>
      <dd class="table-cell"><a href="javascript:;" id="addtocart" class="fs16 colf gbuy-4">加入购物车</a></dd>
      <dd class="table-cell"><a href="javascript:;" id="pay-now" class="fs16 colf gbuy-5">立即购买</a></dd>
    </dl>
  </div>
  <!-- 底部悬浮 end -->

  <section class="main">
    <div class="swiper-container detail_ban">
      <div class="swiper-wrapper">
        <?php if(is_array($banner)): foreach($banner as $key=>$val): ?><div class="swiper-slide">
            <img src="<?php echo getpics($val);?>" alt="3:2,640*427" class="imgm">
          </div><?php endforeach; endif; ?>
      </div>
      <?php if(!empty($coupons_str_info)): ?><span class="youhui-tag">优惠券</span><?php endif; ?>
      <div class="swiper-pagination"></div>
    </div>
    <!-- detail_ban end -->

    <div class="p10 btb bgf detail_info">
      <dl class="table item-hd">
        <dt class="table-cell vt">
          <h2 class="fs14 col3"><?php echo ($goods_list['title']); ?></h2>
          <p class="fs12 colred"><?php echo ($goods_list['subtitle']); ?></p>
        </dt>
        <dd class="table-cell vt">
          <a href="javascript:;" class="fr bl fs12 like <?php echo ($check_on); ?>">收藏</a>
        </dd>
      </dl>
      <div class="fs12 col9 m-price">
        <span class="mt5 item-more">已售：<?php echo ($goods_list['sales_volume']); ?></span>
        <b class="fs16 colred">¥ <em class="fs24"><?php echo ($seckill['seckill_price']?$seckill['seckill_price']:$goods_list['sale_price']); ?></em></b><s class="ml10">¥<?php echo ($goods_list['original_price']); ?></s>
      </div>
      <?php if(!empty($seckill['count_time'])): ?><div class="tr fs13 col6">
          <?php echo ($seckill['subtitle']); ?> <span class="fs12 countdown"><em class="br3">00</em> : <em class="br3">00</em> : <em class="br3">00</em></span>
        </div><?php endif; ?>
      <?php if(!empty($coupons_str_info)): ?><div class="table-cell item-lt vt">促销</div>
        <div class="table-cell">
          <p class="fs12 col6 mt5">　<?php echo ($coupons_str_info); ?></p>
        </div><?php endif; ?>
    </div>
    <ul class="mt10 btb last fs14 col3 detail_extra">
      <li class="table bgf p10">
        <div class="table-cell item-lt vt col9">送至</div>
        <div class="table-cell">
          <p class="fs13 col0" id="address"></p>
          <p class="fs12 col9 mt10"><i class="Icon-time"></i><?php echo ($goods_list['explain']); ?></p>
        </div>
      </li>
      <li class="table bgf p10 bb" style="margin-top: -10px;">
        <div class="table-cell item-lt vt col9">运费</div>
        <div class="table-cell">
          <p class="fs13 col0">满<?php echo ($postage['postage_free']); ?>包邮（10kg内）</p>
        </div>
      </li>
      <li class="mt10 bgf table p10 bb">
        <div class="table-cell item-lt col9">积分</div>
        <div class="table-cell">
          <p class="fs13 col0">购买可得<?php echo ($goods_list['back_integral']); ?>积分</p>
        </div>
      </li>
      <li class="p10 fs13 bg-f0 bb">
        <span class="detail-tag">正品保证</span>
        <span class="detail-tag">极速发货</span>
        <span class="detail-tag">15天退换</span>
     <!--   <span class="detail-tag">中华参科技开发有限公司&售后</span>-->
      </li>
      <?php if(!empty($spec_items)): ?><li class="table bgf p10 bb spec_show">
          <div class="table-cell item-lt ">已选</div>
          <a href="javascript:;" class="table-cell spec-btn arrowR">
            <p class="fs13 col9" id="spec_true_name"></p>
          </a>
        </li><?php endif; ?>
    </ul>
    <?php if(!empty($review_num)): ?><div class="mt10 bgf btb detail_eval">
      <div class="p10 bb item-tit">
        <span class="fr col9">共<?php echo ($review_num); ?>条评论</span>
        <span class="fs15 col3">商品评价</span>
      </div>
      <ul class="list_eval">
        <?php if(is_array($review_info)): foreach($review_info as $key=>$val): ?><li class="p10 bb">
          <div class="clearfix item-hd">
            <div class="fl ba brarc item-pic"><img src="<?php echo ($val['headimgurl']); ?>" alt="" class="imgm"></div>
            <span class="fl plr10"><?php echo ($val['nickname']); ?></span>
            <div class="fl star-show" data-score="<?php echo ($val['rating']); ?>"></div>
            <p class="fr col9"><?php echo date('Y-m-d',strtotime($val['create_time']));?></p>
          </div>
          <div class="mt5 fs14 col3 item-bd"><?php echo ($val['content']); ?></div>
          <?php if(!empty($val['reply'])): ?><div class="mt10 fs12 col6 box item-foot" style="margin-left: 0px">
              <span>中华参科技开发有限公司：</span>
              <p class="flex-1"><?php echo ($val['reply']); ?></p>
            </div><?php endif; ?>
        </li><?php endforeach; endif; ?>
      </ul>
      <?php if(($review_num) > "2"): ?><a href="<?php echo U('Shop/Goods/review_list',['gid'=>$goods_list['id']]);?>" class="block p5 tc fs13 col3">查看全部评论</a><?php endif; ?>
    </div><?php endif; ?>
    <!-- 商品评价 end -->

    <div class="p10 fs15 col3">猜你喜欢</div>
    <ul class="weui-row list_goods">
    <?php if(is_array($youlike)): foreach($youlike as $key=>$val): ?><li class="weui-col-50">
        <a href="<?php echo U('Shop/Goods/detail',['id'=>$val['id']]);?>" class="block ba bgf">
          <div class="item-pic"><img src="<?php echo getpics($val['cover']);?>" alt="" class="imgm"></div>
          <div class="p5 bt item-con">
            <p class="fs14 col3 multi-line"><?php echo ($val['title']); ?></p>
            <div class="fs12">
              <span class="fs13 colred">¥<em class="fs16"><?php echo ($val['sale_price']); ?></em></span>
              <del class="ml10 col9">¥<?php echo ($val['original_price']); ?></del>
            </div>
          </div>
        </a>
      </li><?php endforeach; endif; ?>
    </ul>

    <div class="table mt10 bgf btb tc fs14 detail_nav">
      <a href="javascript:;" class="table-cell active"><span>商品介绍</span></a>
    <!--  <a href="javascript:;" class="table-cell"><span>规格参数</span></a>-->
      <a href="javascript:;" class="table-cell"><span>售后保障</span></a>
    </div>
    <div class="p10 bb bgf detail_wrap">
      <div class="detail_main">
        <article class="fs12 col3 m-editor">
         <?php echo ($goods_list['description']); ?>
        </article>
      </div>
     <!-- <div class="detail_main">
        <article class="fs12 col3 m-editor">
         <?php echo ($goods_list['spec']); ?>
        </article>
      </div>-->
      <div class="detail_main">
        <article class="fs12 col3 m-editor">
         <?php echo ($goods_list['aftersale']); ?>
        </article>
      </div>
    </div>

  </section>
  <!-- main主体 end -->
  <section class="spec-wrap popup-wrap" style="display: none;">
    <div class="popup-overlay"></div>
    <div class="popup-content">
      <ul class="list_goods mt10">
        <li class="p10 btb bgf">
          <a href="javascript:;" class="table">
            <div class="table-cell item-photo">
              <div class="ba br3 item-pic">
                <img src="<?php echo getpics($goods_list['cover']);?>" alt="" class="imgm">
              </div>
            </div>
            <div class="table-cell pl10 item-con">
              <h2 class="fs13 col3 multi-line"><?php echo ($goods_list['title']); ?> </h2>
              <div class="mt5 fs12 m-price">
                <span class="colred">¥ <b class="fs15" id="spec_price"><?php echo ($seckill['seckill_price']?$seckill['seckill_price']:$goods_list['sale_price']); ?></b><!--<em class="ml10 fs12 col9">好评：99%</em>--></span>
                <!-- <em class="item-more"><img src="images/to-cart.png" style="width:30px" alt="" class="block"></em> -->
              </div>
            </div>
          </a>
        </li>
      </ul>
      <?php if(!empty($spec_items)): ?><div class="bb p10 bgf col3 fs13">
            <?php if(is_array($spec_items)): foreach($spec_items as $key=>$spec): ?><p><?php echo ($spec["name"]); ?></p>
              <?php if(!empty($spec['items'])): ?><div class="radio-spec spec_item">
                   <p style="display: none" class="spec_name"><?php echo ($spec["name"]); ?></p>
                    <?php if(is_array($spec['items'])): $i = 0; $__LIST__ = $spec['items'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label for="spec<?php echo ($vo["item_id"]); ?>" class="opt-label">
                          <input type="radio" class="opt-check specs_check" name="goods_spec[<?php echo ($spec['id']); ?>]" data-spec_id="<?php echo ($spec['id']); ?>" id="spec<?php echo ($vo["item_id"]); ?>" value="<?php echo ($vo['item_id']); ?>" <?php if(($i) == "1"): ?>checked<?php endif; ?> >
                          <span class="opt-checked"><?php echo ($vo["item"]); ?></span>
                        </label><?php endforeach; endif; else: echo "" ;endif; ?>
                  </div><?php endif; endforeach; endif; ?>
          </div><?php endif; ?>
        <form action="<?php echo U('Cart/confirm_pay');?>" id="goods_form" method="post">
      <div class="bb p10 fs13 bgf col0 box flex-between" style="padding-bottom:60px;">
          <span>购买数量</span>
          <div class="bgf tc fs14 buy_num mb10">
            <i class="fr plus">+</i>
            <input name="goodsnum" type="tel" class="fr tc value" value="1">
            <i class="fr minus">-</i>
            <input type="hidden" name="s" value="/Shop/Order/confirm">
            <input type="hidden" name="action" value="buynow">
            <input type="hidden" name="key" id="goods_key">
            <input type="hidden" name="ids" id="goods_id" value="<?php echo ($goods_list['id']); ?>">
          </div>
      </div>
        </form>
        <a href="javascript:;" class="bg_own btn-block">确定</a>
    </div>
  </section>

        <!-- 底部导航区域 -->
		
	</section>

  
  

	
    
<script src="/yangsheng/html/weui/js/swiper.js"></script>
<script src="/yangsheng/html/js/jquery.raty.min.js"></script>
<script src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
<script>
  choose_type =0; //1 :购物车  2： 立即购买
  var filter_spec = '<?php echo json_encode($spec_items, JSON_UNESCAPED_UNICODE);?>';
  var spec_goods_price = '<?php echo json_encode($spec_goods_price, JSON_UNESCAPED_UNICODE);?>';
  var spec_goods_price1 ='<?php echo json_encode($spec_goods_price, JSON_UNESCAPED_UNICODE);?>';
  var seckill_price ="<?php echo ($seckill['seckill_price']?$seckill['seckill_price']:0); ?>";
  var spec_item_ids = [];
  var usable_items = {};
  var cart_url = "<?php echo U('Shop/Cart/cart_num');?>";
  var def_price ="<?php echo ($goods_list['sale_price']); ?>";
  var key = '';
  var geolocation = new qq.maps.Geolocation("OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77", "myapp");
  var positionNum = 0;
  var options = {timeout: 8000};



  $(function() {
    //获取地址
    getCurLocation();
    get_price();
    //购物车数量 初始化
    var uid = <?php echo ($uid); ?>;
    if(uid){
      getCartNum(cart_url,1);
    }

    // 购物车页中 物品数量 +-
    $(document).on("click",".buy_num .plus",function() {
      $this = $(this);
      var v = parseInt($this.next().val());
      if(!v){
        v = 0;
      }
      v = v+1;

      var stock =$("input[name='stock']").val();
      if(stock > 0 && v > stock){
        $.toast('库存不足！','text');
        return false;
      }
      $this.next().val(v);
    });
    $(document).on("click",".buy_num .minus",function() {
      $this = $(this);
      var v = parseInt($this.prev().val());
      if(!v){
        v = 2;
      }
      v = v-1;
      if(v>0){
        $this.prev().val(v);
      }
    });
    $(document).on('blur','[name=goodsnum]',function(){
      var goodsnum = $(this).val();
      if(goodsnum<=0){
        goodsnum ='1';
      }
      goodsnum =  goodsnum.replace(/[^\d]/g, '');
      goodsnum =  goodsnum.replace(/^0+/, '');

      $(this).val(goodsnum);
    })
    if($(".specs_check").length>0){
      var spec_true_name1='';
      var spec_true_name =get_select_spec();
      $.each(spec_true_name,function(m,t){
        var spec_t ="spec"+t;
        spec_true_name1 +=$('#'+spec_t+'').siblings('span').html();
      });
      $('#spec_true_name').text(spec_true_name1);
    }
    if($(".specs_check").length > 0){
      filter_spec = JSON.parse(filter_spec);
      spec_goods_price = JSON.parse(spec_goods_price);
      $.each($(".spec_item"), function(k, v){
        $.each($(v).find(".specs_check"), function(index, el) {
          spec_item_ids.push($(el).val());
          if(k==0){
            usable_items[$(el).val()] = {'usable':1};
          }else{
            usable_items[$(el).val()] = {'usable':0};
          }
        });
      });
    }

    $(".specs_check").on('change', function(){
      $this = $(this);
      spec = JSON.parse(JSON.stringify(filter_spec));

      $.each($(".specs_check:checked"), function(k, v){
        spec_item_id = $(v).val();
        spec[$(v).data('spec_id')]['items'] = [{'item_id':spec_item_id}];
      });
      var sarr = [[]];
      $.each(spec, function(k, v){
        var tarr = [];
        for(var j = 0; j < sarr.length; j++){
          $.each(spec[k]['items'], function(k2, v2){
            tarr.push(sarr[j].concat(spec[k]['items'][k2]['item_id']));
          });
        }
        sarr = tarr;
      });
      var usable_items_new = JSON.parse(JSON.stringify(usable_items));
      len = sarr.length;
      for (var i = 0; i < len; i++) {
        $.each(spec_goods_price, function(k, v){
          if(sarr[i].sort().toString()== k.split('_').sort().toString()){
            len2 = sarr[i].length;
            for (var j = 0; j < len2; j++) {
              usable_items_new[sarr[i][j]] = {'usable':1};
            }
            return false;
          }
        });
      }
      select_spec = get_select_spec();
      var select_spec_str = '';
      $.each(select_spec, function(key, value) {
        select_spec_str += select_spec_str ? '_'+value:value;
      });
      price =def_price;
      if(select_spec_str!=''){
        var select_spec_str_extra =int_string_sort(select_spec_str,'_');
        $.each(spec_goods_price,function(k,v){
          if(select_spec_str_extra == v.key){
            if(seckill_price != 0){
              price = seckill_price;
            }else{
              price = v.shop_price;
            }
            $('#spec_price').html(price);
          }
        });

      }
      no_select_spec = get_no_select_spec();
      // 获取页面全部规格
      all_spec = {};
      $(".spec_item").each(function(k, v) {
        $(v).find('.specs_check').each(function(index, el) {
          spec_el = $(el);
          if(k==0){
            all_spec[spec_el.val()] = {'choosable':true};
          }else{
            all_spec[spec_el.val()] = {'choosable':false};
          }
        });
      });
      exist_spec = [];
      $.each(spec_goods_price, function(key, value) {
        key = key.split('_').sort();
        exist_spec.push(key);
      });
    });

    //收藏
    $('.like').click(function(){
        var check =0;
        if($(this).hasClass('on')){
          check =1;
        }
        changeCollect(check);
    })

    //倒计时
    var endTime = "<?php echo ($seckill['count_time']?$seckill['count_time']:''); ?>";
    if(endTime){
      setInterval(function(){
        countDown(endTime);
      },1000);
    }
    $('.popup-overlay').on('click',function(){
      $('.spec-wrap').hide();
      $('html,body').css({'height':'','overflow':''});
    });
    //加入购物车
    $("#addtocart").click(function(){
      choose_type = 1;
      if(key){
        var spec_true_name1='';
        var spec_true_name =get_select_spec();
        $.each(spec_true_name,function(m,t){
          var spec_t ="spec"+t;
          spec_true_name1 +=$('#'+spec_t+'').siblings('span').html();
        });
        $('#spec_true_name').text(spec_true_name1);
        add_cart();
      }else {
        $('.spec-wrap').show();
        $('html,body').css({'height': '100%', 'overflow': 'hidden'});
      }
    });

    //立即购买
    $('#pay-now').click(function(){
      choose_type =2;
      if(key){
        var spec_true_name1='';
        var spec_true_name =get_select_spec();
        $.each(spec_true_name,function(m,t){
          var spec_t ="spec"+t;
          spec_true_name1 +=$('#'+spec_t+'').siblings('span').html();
        });
        $('#spec_true_name').text(spec_true_name1);
        $("#goods_key").val(key);
        $("#goods_form").submit();
      }else{
        $('.spec-wrap').show();
        $('html,body').css({'height':'100%','overflow':'hidden'});
      }
   /*   var num = $("input[name='goodsnum']").val();
      var ids = "<?php echo ($goods_list['id']); ?>";
      var url = "<?php echo U('Shop/Cart/confirm_pay/ids/"+ids+"/num/"+num+"');?>";
      location.href=url;*/
    })
    $('.btn-block').on('click',function(){
        if(!before_submit()){
          return false;
        }
        if(key){
          var spec_true_name1='';
          var spec_true_name =get_select_spec();
          $.each(spec_true_name,function(m,t){
              var spec_t ="spec"+t;
              spec_true_name1 +=$('#'+spec_t+'').siblings('span').html();
          });
          $('#spec_true_name').text(spec_true_name1);
        }
        switch(choose_type){
          case 1:  // 加入购物车
                add_cart();
                break;
          case 2: // 立即购买
              $("#goods_key").val(key);
              $("#goods_form").submit();
              break;
          default :
            $('.spec-wrap').hide();
        }
    });

    $('.spec_show').on('click',function(){
        choose_type =0;
        $('.spec-wrap').show();
    });

    var swiperWidth = $(".detail_ban").width();
    swiperHeight = swiperWidth * (1/1);
    $(".detail_ban").css("height",swiperHeight);
    $(".detail_ban").swiper({
      pagination: '.swiper-pagination',
      paginationType: 'fraction',
      loop: true,
      autoplay: 3000,
      autoplayDisableOnInteraction : false,
    });

    $(".list_goods .item-pic").each(function(){
      var w = $(this).width();
      $(this).css({"height": w});
    });
  });




  function getCurLocation() {

    geolocation.getLocation(showPosition, showErr, options);
  }
  function showPosition(position) {
    var address =position.province + position.city + position.district;
    $('#address').text(address);
  };
  function showErr() {
    positionNum ++;
    getCurLocation();
  };
  function int_string_sort(str, separator)
  {
    var strArr = str.split(separator);//分割成字符串数组
    var intArr = [];//保存转换后的整型字符串
    for (var i = strArr.length - 1; i >= 0; i--) {
      intArr.push(parseInt(strArr[i]));
    }
    return intArr.sort(intSort).join(separator);
  }
  function  get_price(){
    select_spec = get_select_spec();
    var select_spec_str = '';
    $.each(select_spec, function(key, value) {
      select_spec_str += select_spec_str ? '_'+value:value;
    });
    price =def_price;
    if(select_spec_str!=''){
      var select_spec_str_extra =int_string_sort(select_spec_str,'_');
      $.each(JSON.parse(spec_goods_price),function(k,v){
        if(select_spec_str_extra == v.key){
          if(seckill_price != 0){
            price = seckill_price;
          }else{
            price = v.shop_price;
          }
          $('#spec_price').html(price);
        }
      });
    }
  }
  function intSort(a, b) {
    return a - b;
  }

  function  add_cart(){
    var goodsnum = $("input[name='goodsnum']").val();
    var gid = "<?php echo ($goods_list['id']); ?>";
    var url = "<?php echo U('addToCart');?>";
    var stock = <?php echo ($seckill['stock']-$seckill['seckill_sales']); ?>;
    $.ajax({
      url:url,
      type:'POST',
      dataType:'json',
      data:{goodsnum:goodsnum,gid:gid,stock:stock,key:key},
      success:function(data){
        if(data.status==1){
          setTimeout(function(){
            $.toast(data.info, 'text');
            $('.spec-wrap').hide();
            $('html,body').css({'height':'','overflow':''});
            getCartNum(cart_url,1);
          }, 0);
        }else{
          if(data.url){
            // $.confirm("立即登录", function() {
            location.href=data.url;
            // }, function() {});
          }else{
            $.alert(data.info);
          }
        }
      }
    });
  }
  // 获取已选择的规格
  function get_select_spec()
  {
    select_spec = {};
    $(".spec_item .specs_check:checked").each(function(index, el) {
      spec_el = $(el);
      select_spec[spec_el.data('spec_id')] = spec_el.val();
    });
    return select_spec;
  }
  // 没有被选择的规格
  function get_no_select_spec()
  {
    no_select_spec = {};
    $(".spec_item .specs_check:not(:checked)").each(function(index, el) {
      spec_el = $(el);
      if(spec_el.parents('.spec_item').find('.specs_check:checked').length == 0){
        spec_id = spec_el.data('spec_id');
        if(typeof(no_select_spec[spec_id])!='undefined'){
          no_select_spec[spec_id].push(spec_el.val());
        }else{
          no_select_spec[spec_id] = [spec_el.val()];
        }
      }
    });
    return no_select_spec;
  }

  function check_login(){
    if(<?php echo ($uid); ?> == 0){
      Cookies.set('<?php echo (ENV_PRE); ?>Home_before_reg', '<?php echo get_url();?>');
      location.href = '<?php echo U('Home/Member/login');?>';
      return false;
    }else{
      return true;
    }
  }

  function before_submit(){

    if (!check_login()) {
      return false;
    }

    var goodsnum = $('[name=goodsnum]').val();
    var maxnum =<?php echo ($maxnum); ?>;

    if(goodsnum<=0){
      goodsnum ='1';
    }
    goodsnum =  goodsnum.replace(/[^\d]/g, '');
    goodsnum =  goodsnum.replace(/^0+/, '');

    $('[name=goodsnum]').val(goodsnum);


    if(!goodsnum.match(/^[0-9]*[1-9][0-9]*$/)){
      $.alert('请输入正确的商品数量');
      return false;
    }

    if(maxnum > 0 && goodsnum>maxnum){
      $.toast('库存不足','text')
      return false;
    }

    key = '';
    $.each($(".specs_check:checked"), function(k, v){
      key = key=='' ? $(v).val() : key+'_'+$(v).val();
    });
    if($(".specs_check").length>0 && key==''){
      $.alert('请选择'+$(".spec_item:first").find(".spec_name").html());
      return false;
    }
    var can_buy = true;
    $.each($(".radio-spec"), function(k, v){
      $this = $(v);
      if($this.find(".specs_check").length>0 && $this.find(".specs_check:checked").length <1){
        $.alert('请选择'+$this.find(".spec_name").html());
        can_buy = false;
        return false;
      }
    });
    if(can_buy==false){
      return false;
    }
    return true;
  }

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
    $(".countdown em").eq(0).html(hours);
    $(".countdown em").eq(1).html(minutes);
    $(".countdown em").eq(2).html(seconds);
  }

  //收藏 ajax
  function changeCollect(check)
  {
    var collect_url = "<?php echo U('Shop/Goods/collect');?>";
    var gid = "<?php echo ($goods_list['id']); ?>";
    $.ajax({
      url:collect_url,
      dataType:'json',
      type:'POST',
      data:{check:check,gid:gid},
      async:false,
      beforeSend:function(){

      },
      success:function(data){
        if(data.status){
          setTimeout(function(){
            $.toast(data.info,"text");
          }, 0);
          if(check){
            $('.like').removeClass('on');
          }else{
            $('.like').addClass('on');
          }
        }else{
          if(data.url){
            // $.confirm("立即登录", function() {
              location.href=data.url;
            // }, function() {
              //点击取消后的回调函数
            // });
          }else{
            $.alert(d.info);
          }
        }
      },
      complete:function(){

      },
      error:function(){

      }

    })
  }
//微信分享
  wx.config(<?php echo ($jsapi); ?>);
  wx.ready(function() {
    //alert('<?php echo ($wechatShare["link"]); ?>');
    //分享到朋友圈
    wx.onMenuShareTimeline({
        title: '<?php echo ($wechatShare["title"]); ?>',
        link: '<?php echo ($wechatShare["link"]); ?>',
        imgUrl: '<?php echo ($wechatShare["imgUrl"]); ?>',
        success: function () {},
        cancel: function () {}
    });
    //分享给朋友
    wx.onMenuShareAppMessage({
        title: '<?php echo ($wechatShare["title"]); ?>',
        desc: '<?php echo ($wechatShare["desc"]); ?>',
        link: '<?php echo ($wechatShare["link"]); ?>',
        imgUrl: '<?php echo ($wechatShare["imgUrl"]); ?>',
        type: '<?php echo ($wechatShare["type"]); ?>',
        dataUrl: "",
        success: function () {},
        cancel: function () {}
    });
//            alert('<?php echo ($wechatShare["title"]); ?>');
    //分享到QQ
    wx.onMenuShareQQ({
        title: '<?php echo ($wechatShare["title"]); ?>',
        desc: '<?php echo ($wechatShare["desc"]); ?>',
        link: '<?php echo ($wechatShare["link"]); ?>',
        imgUrl: '<?php echo ($wechatShare["imgUrl"]); ?>',
        success: function () {},
        cancel: function () {}
    });
    //分享到腾讯微博
    wx.onMenuShareWeibo({
        title: '<?php echo ($wechatShare["title"]); ?>',
        desc: '<?php echo ($wechatShare["desc"]); ?>',
        link: '<?php echo ($wechatShare["link"]); ?>',
        imgUrl: '<?php echo ($wechatShare["imgUrl"]); ?>',
        success: function () {},
        cancel: function () {}
    });
  });

</script>

</body>
</html>