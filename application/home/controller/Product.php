<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
class Product extends Controller
{
   
    //加载列表模板
    public function getindex()
    {
        return $this->fetch("Product/index");
    }

   
}
