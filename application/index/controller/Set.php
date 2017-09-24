<?php
/**
 * Created by PhpStorm.
 * User: 无名
 * Date: 2017/9/14
 * Time: 10:34
 */
namespace app\index\controller;
use app\index\model\Admin;
use think\Controller;
use think\Session;

class Set extends Controller{
    public function index(){
        if(Session::get('root')==2){
            return view();
        }else{
            echo "<script>alert('权限不够,无法进行!');history.back();</script>";
        }
    }
    public function user_ajax(){
        $user = $_GET['user'];
        $res = Admin::where('user',$user)->find();
        if($res){
            return "该账号已存在!";
        }else{
            return "该账号可使用!";
        }
    }
    public function save(){//接收index的数据，添加管理员
        $admin = new Admin();
        $admin->user = input('post.user');
        $admin->username = input('post.username');
       // $admin->password = md5(input('post.password'));
        $admin->root = input('post.root');
        $data['user'] = input('post.user');//$data用来表单验证
       // $data['password'] = input('post.password');

        $result = $this->validate($data,'Admin.index');
        if($result !== true){
            $this->error($result);
        }else{//账号必须是八位数字
            if($admin->save()){
                $this->success('添加成功!','set/index');
            }else{
                $this->error('添加失败!','set/index');
            }
        }
    }
    public function show(){//显示管理信息，以及修改权限
        if(Session::get('root')==2){
            $user = Admin::where('root','1')->paginate(3);//显示出管理员信息，超级管理员不能分页
            //var_dump($user);
            $this->assign('user',$user);
            return view();
        }else{
            echo "<script>alert('权限不够,无法进行!');history.back();</script>";
        }
    }
    public function show_root(){//显示超级管理员信息
        if(Session::get('root')>1){
            $user = Admin::where('root','2')->paginate(3);//显示出管理员信息，超级管理员不能分页
            //var_dump($user);
            $this->assign('user',$user);
            return view();
        }else{
            echo "<script>alert('权限不够,无法进行!');history.back();</script>";
        }
    }
    public function delete($user){
        $user = Admin::get($user);//获取信息
        //var_dump($user);
        if($user){
            if($user->delete()){
                $this->success('数据删除成功!','set/show');
            }else{
                $this->error('数据删除失败!','set/show');
            }
        }else{
            $this->error('删除的记录不存在!');
        }
    }
    public function update($user){//修改信息
        $user = Admin::get($user);
        $user = $user->toArray();
       /* var_dump($user);
        die;*/
        $this->assign('user',$user);
        return view();
    }
    public function edit(){
        $admin = new Admin();
        $data['user'] = input('post.user');
        $data['username'] = input('post.username');
        $data['root'] = input('post.root');
        /*$result = $this->validate(
            ['password' => input('post.password')],
            ['password' => 'require|min:3|max:8'],
            ['password.require' => '密码不为空','password.min' => '密码长度不小于3个字符','password.max'=>'密码长度至多8个字符']
        );*/
        //if($result == 1){
            if($admin->isUpdate(true)->save($data)){//使用模型进行修改
                $this->success('权限修改成功!','set/show');
            }else{
                $this->error('权限修改失败!','set/show');
            }
        /*}else{
            $this->error($result);
        }*/

    }
}