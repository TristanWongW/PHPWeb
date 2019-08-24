<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
//导入session类
use think\Session;
class  Login extends Controller
{

    //加载登录页面
    public function getlogin(){

        return $this->fetch("login/login");
    }

    //执行登录
    public function postdologin(){
        $request = \request();
        //获取账号 密码
        $name = $request->param("username");
        $pwd = $request->param("pwd");
        $row = Db::table("user")->where("username='{$name}' and pwd='{$pwd}'")->select();
        // \var_dump($row);
        if ($row) {
            //把用户登录信息存储到session
                Session::set('userid',$row[0]['id']);
                Session::set('username',$row[0]['username']);
            //跳转到前台首页
            // $this->success("登录成功","/homeindex/index");
            $this->redirect("/homeindex/index");
        } else{
            $this->error("用户名或者密码错误","/homelogin/login");
        }
        
    }

    //密码找回
    public function getforget(){
        //加载模板
        return $this->fetch("login/forget");
    }

    //加载修改 重置密码 界面
    public function postdoforget(){
        $request = \request();
        // \var_dump($request);
        $phone = $request->param('phone');
        // echo $phone;
        //加载模板 号码传送 
        return $this->fetch("login/rest",['phone'=>$phone]);
    }

    //执行修改密码
    public function postreset(){
        $request = \request();
        // \var_dump($request);
        $pwd['pwd'] = $request->param('pwd');
        $phone = $request->param('phone');
        // echo $pwd;
        //执行修改
        if (Db::table("user")->where('phone',"{$phone}")->update($pwd)) {
            $this->success("密码修改成功","/homelogin/login");
        } else {
            $this->success("密码修改失败","/homelogin/login");
        }
    }
    
    //登出
    public function getlogout(){
        Session::delete('username');
        Session::delete('userid');
        $this->success("退出成功","/homelogin/login");
    }
}
?>