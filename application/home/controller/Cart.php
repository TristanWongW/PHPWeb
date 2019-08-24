<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
//开启Session
session_start();
class Cart extends Controller
{
   
    //加载购物车
    public function getadd()
    {
        $request = \request();
        $id = $request->param('id');
        // echo $id;
        //获取商品数据
        $product = Db::table("product")->where("id","{$id}")->select();
        // \var_dump($product);
        //检测购物车里面是否有当前购买的商品
        if (isset($_SESSION['s'][$id])) {
            //获取当前商品的库存
            $info = Db::table("product")->where("id","{$id}")->find();
            if ($_SESSION['s'][$id]['num'] < $info['num']) {
                //数量加一
                $_SESSION['s'][$id]['num'] += 1; 
            } else {
                //把库存赋值给当前数量
                $_SESSION['s'][$id]['num'] = $info['num'];
            }
            
        } else {
            //初始化数量为1
            $product[0]['num'] = 1;
            //数量存储在Session中
            // \var_dump($product);die;
            $_SESSION['s'][$id] = $product[0];
        }
        // \var_dump($_SESSION['s']);
        //跳转至购物车
        $this->success("加入购物车成功","/productcart/index");
    }

    //购物车页面
    public function getindex(){
        //获取session信息
        // unset($_SESSION['s']);
        // \var_dump($_SESSION['s']);
        //加载购物车模板
        return $this->fetch("cart/index",["product"=>$_SESSION['s']]);
    }

    //购物车 减法       subtraction
    public function getsubtraction(){
        //请求对象
        $request = \request();
        //获取商品id
        $id = $request->param('id');
        // echo $id;
        // 执行减法  当前商品数量减一
        $_SESSION['s'][$id]['num'] -= 1;
        //数量不能为负 判断
        if ($_SESSION['s'][$id]['num'] < 1) {
            $_SESSION['s'][$id]['num'] = 1;
        } 
        //把减后的参数作相应数据返回给Ajax
        // echo $_SESSION['s'][$id]['num'];
        //数量 数组
        $data['num'] = $_SESSION['s'][$id]['num'];
        //总计
        $data['total'] = $_SESSION['s'][$id]['num'] * $_SESSION['s'][$id]['price'];
        echo json_encode($data);
    }

    //购物车 addition 加法
    public function getaddition(){
        //请求对象
        $request = \request();
        //获取商品id
        $id = $request->param('id');
        // echo $id;
        // 执行加法  当前商品数量加一
        $_SESSION['s'][$id]['num'] += 1;
        //获取当前商品的库存
        $info = Db::table("product")->where("id","{$id}")->find();
        //数量不能超过库存
        if ($_SESSION['s'][$id]['num'] > $info['num']) {
            //把库存赋值给当前购买量
            $_SESSION['s'][$id]['num'] = $info['num'];
        }

        //数量和总计
        $data['num'] = $_SESSION['s'][$id]['num'];
        $data['total'] = $_SESSION['s'][$id]['num'] * $_SESSION['s'][$id]['price'];
        echo json_encode($data);
    }

    //删除购物车 商品
    public function getdel(){
         //请求对象
        $request = \request();
        //获取商品id
        $id = $request->param('id');
        // echo $id;
        unset($_SESSION['s'][$id]);
        echo 1;
    }
}
