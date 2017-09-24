<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/13
 * Time: 15:08
 */
namespace app\index\model;
use think\Model;
class Buy extends Model{
    public function setStarttimeAttr($value)
    {//设置化时间

        $value = strtotime($value);
        return $value;
    }
    public function getStarttimeAttr($value)
{//读取格式化时间

    $value=date('Y-m-d H:i:s',$value);
    return $value;
}
    public function getEndtimeAttr($value)
    {//读取格式化时间

        $value=date('Y-m-d H:i:s',$value);
        return $value;
    }
    public function setEndtimeAttr($value)
    {//读取格式化时间

        $value = strtotime($value);
        return $value;
    }
    public function getTypeAttr($value){
    if($value=='1'){
        return $value='小';
    }elseif($value=='2'){
        return $value='中';
    }
    else{
        return $value='大';
    }
}
    public function setTypeAttr($value){
        if($value=='小'){
            return $value='1';
        }elseif($value=='中'){
            return $value='2';
        }
        else{
            return $value='3';
        }
    }

}