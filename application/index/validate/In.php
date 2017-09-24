<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/14
 * Time: 9:08
 */
namespace app\index\validate;
use think\Validate;
class In extends Validate
{
    protected $rule = [
        ['car_num','/^[\x{4e00}-\x{9fa5}][A-Z][A-Z0-9]{5}$/u','车牌格式错误']
    ];
}
