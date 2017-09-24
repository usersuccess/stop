<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/12
 * Time: 15:00
 */
namespace app\index\model;
use think\Model;
class Out extends Model{
    protected $type       = [
        'c_time' => 'timestamp',
        'out_time' => 'timestamp'
    ];
}