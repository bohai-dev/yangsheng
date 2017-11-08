<?php

namespace Forum\Model;
use Think\Model;
/**
 * 幻灯片模型
 * @author jry <598821125@qq.com>
 */
class RentModel extends Model {
    protected  $tableName ='forum_rent';

    public  function  get_type($type){
        switch($type){
            case 1:
                $type_name ='出租';
                break;
            case 2:
                $type_name ='求租';
                break;
            default:
                $type_name ='转让';
        }
        return $type_name;
    }
}