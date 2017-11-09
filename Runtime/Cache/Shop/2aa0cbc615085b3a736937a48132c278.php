<?php if (!defined('THINK_PATH')) exit(); if(is_array($info)): foreach($info as $key=>$val): ?><li class="table ptb10 bb">
  <label class="weui_check_label table-cell vm" for="<?php echo ($val['id']); ?>">
    <input type="checkbox" class="weui_check" checked="checked" data-value="<?php echo ($val['stock']-$val['seckill_sales']); ?>" value="<?php echo ($val['id']); ?>" name="checkbox[]" id="<?php echo ($val['id']); ?>">
    <i class="weui_icon_checked block"></i>
  </label>
  <a href="<?php echo U('Shop/Goods/detail',['id'=>$val['gid']]);?>" class="table-cell vm item-lt">
    <div class="ba br5 item-pic">
      <img src="<?php echo getpics($val['cover']);?>" alt="" class="imgm">
    </div>
  </a>
  <div class="table-cell vm pl10">
    <a href="<?php echo U('Shop/Goods/detail',['id'=>$val['gid']]);?>" class="fs14 col3 multi-line"><?php echo ($val['title']); ?></a>
    <div class="mt10 relative">
      <em class="fs16 colred">¥<span><?php echo ($val['sale_price']); ?></span></em>
      <div class="bgf tc fs14 buy_num">
        <i class="fr plus">+</i>
        <input type="tel" class="fr tc value goodsnum" value="<?php echo ($val['goodsnum']); ?>" >
        <i class="fr minus">-</i>
      </div>
    </div>
  </div>
</li><?php endforeach; endif; ?>