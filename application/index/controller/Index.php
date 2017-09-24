<?php
namespace app\index\controller;
use think\Session;

class Index
{
    public function __construct()
    {
        if(!Session::get('root'))
        {
            echo "<script>alert('请先登录!');location.href='./index/login/index';</script>";
        }
    }

    public function index()
    {
        return view();
    }
}
