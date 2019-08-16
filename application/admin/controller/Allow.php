<?php
namespace app\admin\controller;
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
            $this->error("请先登录","/adminlogin/login");
        }
        $request = \request();
        //获取当前访问模块的控制器和方法
        $controller = strtolower($request->controller());
        $action = $request->action();
        // echo $action.'----'.$controller; 
        //获取当前登录用户的权限信息
        $nodelist = Session::get('nodelist');
        // 4. 检测访问模块是否在权限列表中
        if (empty($nodelist[$controller]) || !in_array($action,$nodelist[$controller])) {
            $this->error('抱歉,你没有权限访问该模块，请联系超管');
        } 
        
    }

}
?>