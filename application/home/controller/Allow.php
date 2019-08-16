<?php
namespace app\home\controller;
//导入controller
use think\Controller;
//导入Session类
use think\Session;
class Allow extends Controller
{   
   
    //Controller类初始化方法
    public function _initialize(){
        // echo '这是初始化方法_initialize();';die;
        //检测用户的Session 信息
        if (!Session::get('islogin')) {
            //用户信息不存在 跳转到后台登录页
            $this->error("请先登录","/homelogin/login");
        } 
    }

}
?>