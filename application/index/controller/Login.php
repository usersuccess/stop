<?php
/**
 * Created by PhpStorm.
 * User: 无名
 * Date: 2017/9/13
 * Time: 9:42
 */
namespace app\index\controller;
use think\Controller;
use app\index\model\Admin;
use think\Request;
use think\Session;

class Login extends Controller{
    public function index(){//登陆页面
        return view();
    }
    public function save(){//接收form表单的数据
        $data['user'] = input('post.user');
        $data['password'] = (input('post.pwd'));
        $result = $this->validate($data,'Admin.index');
        if($result !== true){
            $this->error($result);
        }else{
            $user = Admin::where(['user'=>$data['user'],'password'=>(md5($data['password']))])->find();
            if($user){//用户存在
                $user = $user->toArray();//转换为数组
                Session::set('root',$user['root']);//设置root,用来判断是否是超级管理员
                Session::set('user',$user['user']);//用来修改个人密码
                Session::set('username',$user['username']);
                $this->success('欢迎进入停车管理系统','park/index');
            }else{//不存在,提示错误信息
                $this->error('账号或密码错误');
            }
        }
    }
    public function update(){//显示个人设置页面
        return view();
    }
    public function edit(){//修改密码
        $pwd = input("post.password");
        $result = $this->validate(
            ['pwd' => $pwd],
            ['pwd' => 'require|min:3|max:8'],
            ['pwd.require' => '密码不为空','pwd.min' => '密码长度不小于3个字符','pwd.max'=>'密码长度至多8个字符']
        );
        if ($result == 1) {
            $res = Admin::where('user',Session::get('user'))->update(['password'=>md5($pwd)]);
            if ($res) {
                $this->success('修改成功', 'park/index');
            } else {
                $this->error('修改失败');
            }
        } else {
            $this->error($result);
        }

    }
    public function logout(){
        if(Session::has('user')){
            Session::delete('user');
            Session::clear();
            $this->success('退出成功!','login/index');
        }else{
            $this->error('操作失败','login/index');
        }
    }
}
