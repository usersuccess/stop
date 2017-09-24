<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 15:07
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Port as port;
use app\index\model\In as Cin;
use app\index\model\Out as Cout;
use app\index\model\Buy as Cbuy;
 class Search extends Controller{
    public function index(){
        return view();
    }
     public function num(){
         $num=input('post.car_num');
         $data=Cin::where('car_num',$num)->find();
         $data2=Cbuy::where('car_num',$num)->where('end_time','>',time())->find();
         if(($data))
         {
             $this->assign('data',$data);
             return view('num');

         }else
             if($data2){
                 $this->assign('data',$data2);
                 return view('port');

             }
             else{
                 echo"<script>alert('该车没有停');location.href='index'</script>";
             }

     }
     public function port(){
         $num=input('post.car_port');
         $data=Cin::where('carport',$num)->find();
         $data2=Cbuy::where('port',$num)->where('end_time','>',time())->find();
         if(($data))
         {
             $this->assign('data',$data);
             return view('num');

         }else
         if($data2){
             $this->assign('data',$data2);
             return view('port');

         }
         else{
             echo"<script>alert('该车位为空');location.href='index'</script>";
         }

     }
}