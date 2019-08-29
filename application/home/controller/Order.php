<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
use think\Session;
class Order extends Allow //继承allow 必须登录才能进入购物车

{
    //生成订单
    public function getadd()
    {   
        $id = Session::get("userid");
        $requst = \request();
        
    }

   
}
