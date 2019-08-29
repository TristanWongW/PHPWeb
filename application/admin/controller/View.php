<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
use think\Session;
class View extends Controller
{   
    //后台页面主框
    public function getindex()
    {
       $name = Session::get('adminuser');
       //加载模板 （解析模板）
       return $this->fetch("view/index",['o'=>'您好，管理员','arr'=>array("name"=>"{$name}")]);
    }

   

}
