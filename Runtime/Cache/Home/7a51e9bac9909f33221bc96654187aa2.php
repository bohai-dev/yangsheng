<?php if (!defined('THINK_PATH')) exit(); if(is_array($score_list)): foreach($score_list as $key=>$list): ?><li class="table bgf bb">
        <div class="table-cell vm p10">
            <p class="fs15 col3"><?php echo ($list["title"]); ?></p>
            <p class="mt5 col9"><?php echo substr($list['create_time'],0,10);?></p>
        </div>
        <div class="table-cell vm p10 item-right">
            <span class="fs16 plus"><?php if(($list['type']) == "1"): ?>+ <?php else: ?>-<?php endif; echo ($list["score"]); ?></span>
        </div>
    </li><?php endforeach; endif; ?>