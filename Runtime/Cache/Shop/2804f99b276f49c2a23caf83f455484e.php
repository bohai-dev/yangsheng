<?php if (!defined('THINK_PATH')) exit();?><div class="popup-overlay" style="cursor:pointer;overflow:hidden; text-overflow:ellipsis;"></div>
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
                <?php if(is_array($spec['items'])): foreach($spec['items'] as $key=>$vo): ?><label for="spec<?php echo ($vo["item_id"]); ?>" class="opt-label">
                      <input type="radio" class="opt-check specs_check" name="goods_spec[<?php echo ($spec['id']); ?>]" data-spec_id="<?php echo ($spec['id']); ?>" id="spec<?php echo ($vo["item_id"]); ?>" value="<?php echo ($vo['item_id']); ?>">
                      <span class="opt-checked"><?php echo ($vo["item"]); ?></span>
                    </label><?php endforeach; endif; ?>
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
        <input type="hidden" name="stock" id="stock" value="<?php echo ($seckill['stock']-$seckill['seckill_sales']); ?>">
        <input type="hidden" name="filter_spec" id="filter_spec" value="<?php echo json_encode($spec_items, JSON_UNESCAPED_UNICODE);?>">
      </div>
  </div>
    </form>
    <a href="javascript:;" class="bg_own btn-block">确定</a>
</div>
<script type="text/javascript">
  var filter_spec = '<?php echo json_encode($spec_items, JSON_UNESCAPED_UNICODE);?>';
  var spec_goods_price = '<?php echo json_encode($spec_goods_price, JSON_UNESCAPED_UNICODE);?>';
  var spec_goods_price1 ='<?php echo json_encode($spec_goods_price, JSON_UNESCAPED_UNICODE);?>';
  var seckill_price ="<?php echo ($seckill['seckill_price']?$seckill['seckill_price']:0); ?>";
  var spec_item_ids = [];
  var usable_items = {};
  var cart_url = "<?php echo U('Shop/Cart/cart_num');?>";
  var def_price ="<?php echo ($goods_list['sale_price']); ?>";
  var key = '';
  $(function(){
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

    $(document).off('change','.specs_check');
    $(document).off('click','.buy_num .plus');
    $(document).off('click','.buy_num .minus');
    $(document).off('blur','[name=goodsnum]');
    $(document).off('click','.btn-block');

    //修改展示价格
    $(document).on('change', ".specs_check",function(){
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
    //数量
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
      //确认加入
    $(document).on('click','.btn-block',function(){
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

      add_cart();
    });
  })

  function int_string_sort(str, separator)
  {
    var strArr = str.split(separator);//分割成字符串数组
    var intArr = [];//保存转换后的整型字符串
    for (var i = strArr.length - 1; i >= 0; i--) {
      intArr.push(parseInt(strArr[i]));
    }
    return intArr.sort(intSort).join(separator);
  }

  function intSort(a, b) {
    return a - b;
  }

  function  add_cart(){
    var goodsnum = $("input[name='goodsnum']").val();
    var gid = $("input[name='ids']").val();
    var stock =$("input[name='stock']").val();
    $.ajax({
      url:"<?php echo U('Shop/Goods/addToCart');?>",
      type:'POST',
      dataType:'json',
      data:{goodsnum:goodsnum,gid:gid,stock:stock,key:key},
      async:false,
      success:function(data){
        if(data.status==1){
          setTimeout(function(){
            $.toast(data.info,function(){
              $('.spec-wrap').empty();
              $('.spec-wrap').hide();
              $('html,body').css({'height':'','overflow':''});
              getCartNum("<?php echo U('Shop/Cart/cart_num');?>");
            });
          }, 0);
        }else{
          if(data.url){
            location.href=data.url;
          }else{
            $.alert(data.info);
          }
        }
      }
    });
  }

  //提交前验证
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

  //检测登录
  function check_login(){
    if(<?php echo ($uid); ?> == 0){
      Cookies.set('<?php echo (ENV_PRE); ?>Home_before_reg', '<?php echo get_url();?>');
      location.href = '<?php echo U('Home/Member/login');?>';
      return false;
    }else{
      return true;
    }
  }

</script>