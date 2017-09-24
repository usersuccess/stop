<?php
/**
 * Created by PhpStorm.
 * User: 无名
 * Date: 2017/9/12
 * Time: 9:29
 */
namespace app\index\validate;
use think\Validate;

class Admin extends Validate{
    protected $rule=[
        'user'=>'require|/^[0-9]{8}$/',
        'password'=>'require|min:3|max:8',
    ];
    protected $message=[
        'user.require'=>'用户账号必须填写!',
        'user./^[0-9]{8}$/'=>'用户账号必须是八位的数字!',
        'password.require'=>'密码必须填写!',
        'password.min'=>'密码不应小于3位!',
        'password.max'=>'密码不应大于8位!',
    ];
    protected $scene = [
        'index' => ['user'],
    ];
}