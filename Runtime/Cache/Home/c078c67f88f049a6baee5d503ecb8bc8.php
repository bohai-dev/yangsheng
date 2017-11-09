<?php if (!defined('THINK_PATH')) exit(); switch($checkinfo): case "1": if(is_array($info)): foreach($info as $key=>$list): ?><li class="p10 bb">
            <a href="<?php echo ($list['group'] == 4? U('Shop/Integral/detail',['id'=>$list['gid']]) : U('Shop/Goods/detail',['id'=>$list['gid']])); ?>" class="table">
                <div class="table-cell item-photo">
                    <div class="ba br3 item-pic">
                        <img src="<?php echo getpics($list['cover']);?>" alt="" class="imgm">
                    </div>
                </div>
                <div class="table-cell pl10 item-con">
                    <h2 class="fs14 col3 multi-line"><?php echo ($list["title"]); ?> </h2>
                    <div class="mt10 fs12 m-price">
                        <span class="colred">¥ <b class="fs15"><?php echo ($list["sale_price"]); ?></b><s class="ml10 col9">¥ <?php echo ($list["original_price"]); ?></s></span>
                    </div>
                </div>
            </a>
            <a href="javascript:;" data-value="<?php echo ($list["gid"]); ?>" class="m-collect br3 fs12 btn-mini btn-mini_solid">取消收藏</a>
        </li><?php endforeach; endif; break;?>
    <?php case "2": if(is_array($info)): foreach($info as $key=>$value): if(($value['check']) == "1"): ?><li class="bb">
              <a href="<?php echo U('Shop/Bulletin/detail',['id'=>$value['id']]);?>" class="block p10">
                <div class="fs14 col3 multi-line item-head"><?php echo ($value['title']); ?></div>
                  <div class="ptb10 weui-row item-cont">
                    <?php if(is_array($value['cover'])): foreach($value['cover'] as $key=>$val): ?><div class="weui-col-33 ba item-pic">
                        <img src="<?php echo getpics($val);?>" alt="" class="imgm">
                      </div><?php endforeach; endif; ?>

                  </div>
                <div class="fs12 col9 item-foot"><?php echo ($value['posttime']); ?>前</div>
              </a>
            </li>
          <?php else: ?>
            <li class="bb">
                <a href="<?php echo U('Shop/Bulletin/detail',['id'=>$value['id']]);?>" class="table p10">
                  <div class="table-cell">
                    <div class="fs14 col3 multi-line item-head">
                    <?php echo ($value['title']); ?>
                    </div>
                    <div class="mt10 fs12 col9 item-foot"><?php echo ($value['posttime']); ?>前</div>
                  </div>
                  <div class="table-cell item-rt">

                    <div class="ba item-pic">
                      <img src="<?php echo getpics($value['cover'][0]);?>" alt="" class="imgm">
                    </div>
                  </div>
                </a>
              </li><?php endif; endforeach; endif; break;?>
    <?php default: endswitch;?>