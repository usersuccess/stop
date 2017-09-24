<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/14
 * Time: 10:12
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\Out;
use app\index\model\Buy;
class Money extends Controller{
    public function index(){
        if(isset($_POST['date'])){
            $starTime = strtotime(input('post.date'));
            $endTime = strtotime(input('post.date')."+1 day");
        }else{
            $time = date('Y-m-d',time());
            $starTime = strtotime($time);
            $endTime = date('Y-m-d',strtotime('+1 day'));
        }
        $arr = Out::where('out_time','between',[$starTime,$endTime])->select();
        $price = 0;
        foreach ($arr as $v){
            $price += $v->price;
        }
        //固定车位购买
        $data=Buy::Where('start_time','between',[$starTime,$endTime])->select();
        foreach($data as $v){
            $price += $v->price;
        }
        $this->assign('arr',$arr);
        $this->assign('data',$data);
        $this->assign('price',$price);
        return view();
    }

}