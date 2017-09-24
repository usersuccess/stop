<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/18
 * Time: 15:40
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\Buy;
use app\index\model\Port;
class buyshow extends Controller{
    public function index(){
        $data = Buy::select();
        $this->assign('data',$data);
        return view();
    }
    public function update($id){
        $data=Buy::get($id);//查询在buy内的字段
        $end_time = strtotime($data->end_time);
        $now = time();
        if($end_time >= $now){
            echo "<script>alert('车位未到期,不能进行注销!');history.back();</script>";
        }else{
            $port=Port::get($data->port);
            $port->status='0';//改变状态
            $data->status='0';
            if($port->save()&& $data->save()) {
                echo "<script>alert('操作成功');history.back();</script>";
            }
        }

    }
}