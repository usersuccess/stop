<?php
/**
 * Created by PhpStorm.
 * User: 吕明翰
 * Date: 2017/9/12
 * Time: 8:57
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\Port ;
use think\Db;
use app\index\model\In as Cin;
use app\index\model\Out as Cout;
use app\index\model\Buy as Cbuy;
class Park extends Controller{
    public function index(){
        return view();
    }
    public function port_ajax(){//停车操作关于type选择车位
        $type = $_GET['type'];
        $num = $_GET['num'];
        $arr = Cbuy::where('car_num',$num)->where('end_time','>',time())->find();//固定车位查询
        $port = port::where(['type'=>$type,'status'=>'0'])->column('num');//随机给一个流水车位
        if($arr){
            return $arr->port;
        }else{
            if(empty($port)){
                return '车位已满，无空车位';
            }else{
                return  $port[array_rand($port,1)];//随机返回一个未停车位
            }
        }
    }
    public function port_save(){//停车操作
        $preg = preg_match('/^[\x{4e00}-\x{9fa5}][A-Z][A-Z0-9]{5}$/u',input('post.car_num'));
        $preg1 = preg_match('/^1[0-9]{10}$/',input('post.phone'));//验证车牌号和手机号
        if(!$preg){
            echo "<script>alert('车牌号格式错误');location.href='index'</script>";
        }else{
            if(!$preg1){
                echo "<script>alert('手机格式错误');location.href='index'</script>";
            }
            else
            {
                $Cin = new Cin();//添加数据要初始化模型
                $port = port::get(input('post.port_num'));
                $num = Cin::where('car_num',input('post.car_num'))->select();
                if(empty($port)){
                    $this->error('车位已满','park/index');
                }elseif($num) {
                    $this->error('此车辆未结束停车', 'park/index');
                }else{
                    $port->status = '1';//改变车位的状态
                    $data['car_num'] = input('post.car_num');
                    $data['username'] = input('post.name');
                    $data['phone'] = input('post.phone');
                    $data['type'] = input('post.type');
                    $data['carport'] = input('post.port_num');
                    $data['c_time'] = time();
                    if($Cin->save($data) && $port->save()){//两个事物都完成，可以用事务处理
                        echo "<script>alert('操作成功!');location.href='index'</script>";
                    }else{
                        $this->error($Cin->getError());
                    }
                }
            }
        }
    }
    public function out_save(){//开车离开
        $Cout = new Cout();
        $car_num = $_POST['car_num'];
        $check=Cbuy::where('car_num',$car_num)->find();//查看该车是否是固定车位
        $data = Cin::where('car_num',$car_num)->find();//检测车库内是否有车牌
        if(empty($data)){
            $this->error('此车辆未入库','park/index');
        }else{
            $price = port::where('num',$data->carport)->value('price');//通过车位取得价格
            $c_time = strtotime($data->c_time);

            $time = time()-$c_time;
            $time_h = ceil($time/3600);//四舍五入
            if($check){
                $price=0;//固定车位价格为零
            }

            $price = $price * $time_h;
            $Cout->car_num = $car_num;
            $Cout->out_time = time();
            $Cout->price = $price;
            $Cout->c_time = $c_time;
             if($Cout->save()){
                Cin::where('car_num',$car_num)->delete();//删除car_in内的数据
                $port = port::get($data->carport);//取得改变的对象
                if(Cbuy::where('port',$data->carport)->find()){
                    $port->status = '2';
                }else{
                    $port->status = '0';
                }
                $port->isUpdate(true)->save();

                $this->assign('arr',$Cout);
                return view();
            }else{
                $this->error('未知错误','Park/index');
            }
        }

    }
    public function show(){
        $data = port::select();
        $arr = port::where('status','0')->select();
        $nullCount = count($arr);
        $fullCount = count(port::where('status','1')->select());
        $this->assign('fullCount',$fullCount);
        $this->assign('nullCount',$nullCount);
        $this->assign('data',$data);
        return view();
    }
    public function buy(){
        return view();
    }
    public function buy_ajax(){
        $year = $_GET['year'];
        $month = $_GET['month'];
        $port = $_GET['port'];
        $bprice = port::where('num',$port)->value('buy_price');
        $price = (12*$year+$month)*$bprice;
        return $price;
    }
    public function buy_save()//车位购买
    {
        $preg = preg_match('/^[\x{4e00}-\x{9fa5}][A-Z][A-Z0-9]{5}$/u', input('post.car_num'));
        $preg1 = preg_match('/^1[0-9]{10}$/', input('post.phone'));//验证车牌号和手机号

        if (!$preg) {
            echo "<script>alert('车牌号格式错误');location.href='buy'</script>";
        } else {
            if (!$preg1) {
                echo "<script>alert('手机格式错误');location.href='buy'</script>";
            } else {
//读取器冲突，所以用原生代码

                $cin = Cbuy::where(['car_num'=> input('post.car_num'),'status'=>'1'])->find();
                if ($cin) {
                    $this->error('一车牌只能购买一车位', 'park/buy');
                } else {
                    // $Cbuy = new Cbuy();
                    $port = new port();
                    $Cbuy['car_num'] = $_POST['car_num'];
                    $Cbuy['name'] = $_POST['name'];
                    $Cbuy['phone'] = $_POST['phone'];
                    $Cbuy['type'] = $_POST['type'];
                    $Cbuy['port'] = $_POST['port_num'];
                    $year = $_POST['year'];
                    $month = $_POST['month'];
                    if(!$year && !$month){
                        $this->error('未选择购买时间');
                    }
                    $Cbuy['price'] = $_POST['price'];
                    $Cbuy['start_time'] = time();

                    $Cbuy['end_time'] = strtotime("+ $year year $month month");
                    Db::table('car_buy')->insert($Cbuy);
                    $port = port::get($_POST['port_num']);
                    $port->status = '2';
                    $port->isUpdate()->save();
                    $this->success('购买成功', 'park/buy');
                }
            }
        }
    }
    public function portBuy_ajax(){
        $type = $_GET['type'];
        $port = port::where(['type'=>$type,'status'=>'0'])->column('num');
            if(empty($port)){
                return '车位已满，无空车位';
            }else{
                return  $port[array_rand($port,1)];
            }
    }
    public function checkNum_ajax(){//停车时验证车牌和手机
        $num = $_GET['num'];
        $check = preg_match('/^[\x{4e00}-\x{9fa5}][A-Z][A-Z0-9]{4}[A-Z0-9\x{4e00}-\x{9fa5}]{1}$/u',$num);
        $data=Cin::where('car_num',$num)->find();//判断是否存在该车牌
        if($data){
            return'该车牌已经存在';
        }else
        if($check){
            return '√';
        }else{
            return '车牌号格式错误';
        }
    }
    public function checkNum2_ajax(){
        $num=$_GET['num'];
        $buy=Cbuy::where('car_num',$num)->where('end_time','>',time())->find();
        if($buy) {
            $buy = json_encode($buy);
        }
        return $buy;

    }
    public function checkPhone_ajax(){
        $num = $_GET['num'];
        $check = preg_match('/^1[0-9]{10}$/',$num);
        if($check){
            return '√';
        }else{
            return '手机格式错误';
        }
    }
    public function select($num){//查询车座号
        $data=Cin::where('carport',$num)->find();
        $this->assign('data',$data);
        return view();
    }
    public function guding($num){//查询车座号
        $data=Cbuy::where('port',$num)->find();
        $this->assign('data',$data);
        return view();
    }
    public function edit($num){//修改固定车位
        //$Cbuy=new Cbuy();
        $preg = preg_match('/^[\x{4e00}-\x{9fa5}][A-Z][A-Z0-9]{5}$/u',input('post.car_num'));
        $preg1 = preg_match('/^1[0-9]{10}$/',input('post.phone'));//验证车牌号和手机号
        if(!$preg){
            echo "<script>alert('车牌号格式错误');location.href='../../show'</script>";
        }else{
            if(!$preg1){
                echo "<script>alert('手机格式错误');location.href='../../show'</script>";
            }else {
                $Cbuy = Cbuy::where('port', $num)->find();
                $Cbuy->car_num = input('post.car_num');
                $Cbuy->name = input('post.name');
                $Cbuy->type = input('post.type');
                $Cbuy->phone = input('post.phone');
                $Cbuy->start_time = input('post.start_time');
                $Cbuy->end_time = input('post.end_time');
                if ($Cbuy->save()) {
                    //echo"<script>alert('修改成功');location.href='show'</script>";
                    //$this->success('修改成功','show');
                    echo "<script>alert('操作成功!');location.href='../../show'</script>";
                } else {
                    echo "<script>alert('请修改数据!');location.href='../../show'</script>";
                }

            }   }
    }
}