<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
class Register extends Controller
{
    

    //加载前台注册页面
    public function getregister()
    {   
        return $this->fetch("register/register");
    }

    //邮箱检测
    public function getcheckmail(){
        //获取请求对象
        $request = \request();
        //获取邮箱
        $email = $request->param('email');
        // echo $email;
        //读取user表 获取表里的所有邮箱
        $arr = Db::table("user")->column("email");
        // \var_dump($arr);
        //对比
        if (in_array($email,$arr)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //手机监测
    public function getcheckphone(){
        //获取请求对象
        $request = \request();
        //获取手机号码
        $phone = $request->param("phone");
        // echo $phone;
        //读取user表 获取表里的所有手机号码
        $arr = Db::table("user")->column("phone");
        // \var_dump($arr);
        //比对
        if (in_array($phone,$arr)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //用户名检测
    public function getcheckname(){
        //获取请求对象
        $request = \request();
        //获取用户名
        $name = $request->param("username");
        // echo $name;
        // 读取user表 获取表里的所有用户名
        $arr = Db::table("user")->column("username");
        // \var_dump($arr);
        // 比对
        if (in_array($name,$arr)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //发送短信验证
    public function getcheckp(){
        //获取请求对象
        $request = \request();
        //获取手机号
        $p = $request->get("p");
        // echo $p;
        //调用第三方平台 云之讯 发送短信校验码
        sends($p);
    }

    //检查短信校验码
    public function getcheckcode(){
        $request = \request();
        //获取输入的校验码
        $code = $request->get('code');
        //比对手机接收到的校验码和输入的校验码
        if (isset($_COOKIE['vcode']) && !empty($code)) {
            //获取手机接受的效验码
            $vcode = $_COOKIE['vcode'];
            //比对
            if ($code == $vcode) {
                echo 1;
            } else {
                echo 0;
            }
        } else if(empty($code)){
            echo 2;
        } else {
            echo 3;
        }
    }

    //执行注册  把会员信息插入到数据库
    public function postdoregister(){
        // \var_dump($_POST);
        $request = \request();
        $data = $request->only(['username','phone','pwd']);
        $data['addtime'] = time();
        // \var_dump($data);
        // 执行插入
        if (Db::table("user")->insert($data)) {
            $this->success("注册成功，请登录","/homelogin/login");
        } else {
            $this->error("注册失败");
        } 
    }

    
}
 