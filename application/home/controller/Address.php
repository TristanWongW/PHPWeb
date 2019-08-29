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
        //获取当前用户所有的地址信息
        $address = Db::table("address")->where("user_id","{$id}")->select();
        return $this->fetch("address/index",["shop"=>$data,"addr"=>$address]);
    }

    //添加收货地址
    public function postinsertaddress(){
        // \var_dump($_POST);
        $request = \request();
        $data = $request->only(['name','phone','address']);
        $data['user_id'] = Session::get("userid");
        //插入表
        Db::table("address")->insert($data);
        // echo '成功';
        //调回结算页
        $this->redirect("/productaddress/index");
    }
    
}
