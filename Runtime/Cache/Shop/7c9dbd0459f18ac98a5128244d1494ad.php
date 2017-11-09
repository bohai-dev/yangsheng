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
        

    <?php if(!empty($cart_num)): ?><div class="foot_fixed buycart_submit" >
    <dl class="table bgf item-settle">
      <dd class="table-cell vm pl10 weui_cells_checkbox" style="width:22%;">
        <label class="weui_check_label block" for="all_buy">
          <input type="checkbox" checked="checked" class="weui_check" name="check_all_buy" id="all_buy">
          <i class="weui_icon_checked block fs14">全选</i>
        </label>
      </dd>
      <dd class="table-cell vm pl10">
       <p class="fs14 col3">合计：<b class="fs16 col_own total-price">¥0</b></p>
       <P class="col9">（不含运费）</P>
      </dd>
      <dt class="table-cell vm tc">
        <a href="javascript:;" id="to-order" class="bg_own fs16 colf btn-block">去结算</a>
      </dt>
    </dl>
  </div>

  <div class="foot_fixed buycart_edit" >
    <dl class="table bgf item-settle">
      <dd class="table-cell vm pl10 weui_cells_checkbox">
        <label class="weui_check_label block" for="all_del">
          <input type="checkbox" class="weui_check" name="check_all_del" id="all_del">
          <i class="weui_icon_checked block fs14">全选</i>
        </label>
      </dd>
      <dt class="table-cell vm tc">
        <a href="javascript:;" id="delete-list" class="bg_own fs16 colf btn-block">删除</a>
      </dt>
    </dl>
  </div><?php endif; ?>
  <section class="main">
    <form action="<?php echo U('Cart/confirm_pay', ['action'=>'fromcart']);?>" method="POST" name="gotopay">
      <ul class="plr10 bb bgf last weui_cells_checkbox buycart ajax_list"></ul>
      <input type="hidden" name="action" value="fromcart">
    </form>
    <div id="loading" class="tc" style="display: none"></div>
    <div class="tc nothing step0 step" style="display: none;">
      <i><img src="/yangsheng/html/images/icon_none-order.png" alt="" class="imgm"></i>
      <span class="mt10 fs14 col9">暂无商品，快去购物吧！</span>
    </div>
    <div class="p10 col9 fs13 tc nomore" id="full" style="display: none">
      <span class="col9">—— <i></i> ——</span>
      <p class="fs12 col9">亲，没有更多了哦~</p>
    </div>
    <!-- 购物车列表 end -->

    <!--<div class="mt10 p10 index_title">
      <p class="tc fs15 col3"><i class="mr5"><img src="/yangsheng/html/images/icon_index_title3.png" alt="" class="imgm"></i>为你推荐</p>
    </div>

    <ul class="weui-row list_goods" style="margin-bottom: 40px;">
      <?php if(is_array($rec_list)): foreach($rec_list as $key=>$val): ?><li class="weui-col-50 mb10">
        <a href="<?php echo U('Shop/Goods/detail',['id'=>$val['id']]);?>" class="block ba bgf">
          <div class="item-pic">
            <img src="<?php echo getpics($val['cover']);?>" alt="" class="imgm">
          </div>
          <div class="p5 bt item-con">
            <p class="fs14 col3 multi-line"><?php echo ($val['title']); ?></p>
            <div class="fs12">
              <span class="fs13 colred">¥<em class="fs16"><?php echo ($val['seckill_price']?$val['seckill_price']:$val['sale_price']); ?></em></span>
              <del class="ml10 col9">¥<?php echo ($val['original_price']); ?></del>
            </div>
          </div>
        </a>
      </li><?php endforeach; endif; ?>
    </ul>-->
    <?php if(!empty($cart_num)): ?><a href="javascript:;" class="bgf ba br5 tc col9 buy_set" >
        <i class="Ico-set"></i><em>编辑</em>
      </a><?php endif; ?>
  </section>
  <!-- main主体 end -->

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
      //axaj 加载
      $(window).bind('scroll', function() {
          checkload(); //ajax 下拉加载更多内容
      });
      //初始化加载数据
      LoadList();
      //去结算
      $(document).on('click','#to-order',function(){
        if ($('.ajax_list .weui_check:checked').length) {
          $('form[name=gotopay]').submit();
        } else {
          $.toptip('请至少选择一件商品，方可结算', 'error');
        }
        return false;
      })

      //删除
      $(document).on('click','#delete-list',function() {
        $.confirm("确认删除", function() {
          var ids = [];
          $("input[name='checkbox[]']:checked").each(function(){
            ids.push($(this).attr('id'));
          })
          if(ids.length <= 0 ){
            $.toast('请选择商品','text');
            return false;
          }
          deleteCartGoods(ids);
        }, function() {
        //点击取消后的回调函数
        });
      });


      // 购物车页中 物品数量 +-
      $(document).on("click",".buy_num .plus",function() {
        $this = $(this);
        var id = $this.parents('li').find("input[name='checkbox[]']").attr('id');
        var sec = $this.parents('li').find("input[name='checkbox[]']").attr('data-value');
        var v = parseFloat($this.next().val())+1;

        if(sec > 0 && v > sec ){
          $.toast('库存不足！','text');
          return false;
        }
        changeGoodsNum(id,v,sec);
        $this.next().val(v);
        getPriceTotal();
      });

      $(document).on("click",".buy_num .minus",function() {
        $this = $(this);
        var id = $this.parents('li').find("input[name='checkbox[]']").attr('id');
        var sec = $this.parents('li').find("input[name='checkbox[]']").attr('data-value');
        var v = parseInt($this.prev().val())-1;
        if(v>0){
          $this.prev().val(v);
          changeGoodsNum(id,v,sec);
          getPriceTotal();
        }
      });

      $(document).on("blur",'.goodsnum',function(){


        $this  = $(this);
        var v =  $this.val();
        if(v<=0){
          v ='1';
        }
        v =  v.replace(/[^\d]/g, '');
        v =  v.replace(/^0+/, '');
        $this.val(v);

        var id = $this.parents('li').find("input[name='checkbox[]']").attr('id');
        var sec = $this.parents('li').find("input[name='checkbox[]']").attr('data-value');
        changeGoodsNum(id,v,sec);
        getPriceTotal();
      })


      //购物车单选
      $(document).on('click', 'li .weui_check', function(){
        getPriceTotal();
      });
      // 购物车-全选
      $(".item-settle .weui_check_label").on('click',function(){
        getPriceTotal();
        var $this = $(this).find(".weui_check");
        if(!$this.is(":checked")){
          $this.prop({checked:true});
          $(".buycart .weui_check").prop({checked:true});
        }else{
          $this.prop({checked:false});
          $(".buycart .weui_check:checked").prop({checked:false});
        }
      });

      //图片限制
      $(".list_goods .item-pic").each(function(){
        var w = $(this).width();
        $(this).css({"height": w});
      });
      // 购物车-底部功能切换
      $(".buy_set").click(function(){
        if (!$(this).hasClass('on')) {
          $(this).addClass("on");
          $(this).find("em").html("完成");
          $(".buycart_submit").slideUp();
          $(".buycart_edit").slideDown();
          $(".weui_check:checked").prop({checked:false});
        } else {
          $(this).removeClass("on");
          $(this).find("em").html("编辑");
          $(".buycart_edit").slideUp();
          $(".buycart_submit").slideDown();
          $(".weui_check:checked").prop({checked:false});
           $('.total-price').html('¥0');
        }
      });
    });

    function checkload() {
      if ($(window).scrollTop() + $(window).height()+1 >= $(document).height()/2) {
        $("#loading").show();
        LoadList();
      }
    }

    var n = 1;
    var ispost = temp = true;
    function LoadList()
    {
      if (ispost && temp) {
        var url ="<?php echo U('Shop/Cart/index');?>";
        temp = false;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {n: n},
            timeout: 9999,
            beforeSend:function(){
              // $.showLoading();
            },
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
                    $(".step0").show();
                }
                $(".lazy img").lazyload({  //图片延迟加载
                    placeholder : "html/images/loading.gif",
                    effect      : "fadeIn",
                    threshold : 200
                });
            },
            complete:function(){
              getPriceTotal();

              // $.hideLoading();
            }
          });
      }else if(!ispost){
          $("#full").show();
      }
    }
    //获取总价
    function getPriceTotal()
    {
      var priceTotal =0;
      $("input[name='checkbox[]']:checked").each(function(){
        var num = $(this).parents('li').find(".goodsnum").val();
        var price = $(this).parents('li').find('span').text();
        priceTotal += parseInt(num)*parseFloat(price);
      })

      $('.total-price').html('¥'+priceTotal.toFixed(2));
    }
    //删除购物车商品
    function deleteCartGoods(ids)
    {
      $.ajax({
        url:"<?php echo U('Shop/Cart/delete');?>",
        type:'POST',
        data:{ids:ids},
        dataType:'json',
        beforeSend:function(){
          $.showLoading();
        },
        success:function(data){
          if(data.status){
            $('.ajax_list').empty();
            $("#full").hide();
            $(".step0").hide();
            n = 1;
            ispost = temp = true;
            LoadList();
          }else{
            $.alert(data.info)
          }
        },
        complete:function(){
          $.hideLoading();
        },
        error:function(){

        }
      })
    }
    //改变购物车中商品数量
    function changeGoodsNum(cid,num,sec)
    {
      var change_num_url = "<?php echo U('Shop/Cart/changeGoodsNum');?>";
      $.ajax({
        url:change_num_url,
        type:'POST',
        data:{cid:cid,num:num,sec:sec},
        dataType:'json',
        async:false,
        beforeSend:function(){
          $.showLoading();
        },
        complete:function(){
          $.hideLoading();
        },
        success:function(data){
        },
        error:function(){
        }
      })
    }

  </script>

</body>
</html>