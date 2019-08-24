<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
class Product extends Controller
{
   
    //加载商品详情模板
    public function getindex()
    {
        $request = \request();
        $id = $request->param('id');
        // echo $id;
        $data = Db::table("product")->where("id","{$id}")->find();
        return $this->fetch("Product/index",["product"=>$data]);
    }

   
}
