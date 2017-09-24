<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/12
 * Time: 10:09
 */
namespace app\index\model;
use think\Model;
class Port extends Model{
    protected $type       = [
        'start_time' => 'timestamp',
        'end_time' => 'timestamp'
    ];
}