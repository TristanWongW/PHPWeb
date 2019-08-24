<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
use think\Session;
class Address extends Allow 

{
    //结算页
    public function getindex(){
        //获取当前用户购买的商品信息
        $id = Session::get("userid");
        $data = Db::table("carts")->where("user_id","{$id}")->select();
        return $this->fetch("address/index",["shop"=>$data]);
    }
}
