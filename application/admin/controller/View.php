<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
class View extends Controller
{   
    //后台页面主框
    public function getindex()
    {
       //加载模板 （解析模板）
       return $this->fetch("view/index",['o'=>'oh yeah babe','arr'=>array(array("name"=>'Jerry',"sex"=>'man'),array("name"=>'Lily',"sex"=>'woman'))]);
    }

   

}
