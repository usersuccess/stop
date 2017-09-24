<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/14
 * Time: 10:12
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\Port ;
class addPort extends Controller{
    public function index(){
        return view();
    }
    public function add(){
        $port = new port();
        $num = input('post.num');
        for($i=0;$i<$num;$i++){
            $data[] = ['type'=>input('post.type'),'price'=>input('post.price'),'buy_price'=>input('post.buy_price')];
        }
        $port->saveAll($data);
        $this->success('添加成功','addport/index');
    }
}