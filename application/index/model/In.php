<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/12
 * Time: 11:00
 */
namespace app\index\model;
use think\Model;
class In extends Model{
    protected $type       = [
        'c_time' => 'timestamp',

    ];
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
    /*public function getCtimeAttr($value){//读取格式化时间

        $value=date('Y-m-d H:i:s',$value);
        return $value;
    }
    public function setCtimeAttr($value){//读取格式化时间

    $value=strtotime($value);
    return $value;
}*/
}