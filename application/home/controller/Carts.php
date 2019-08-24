<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
use think\Session;
class Carts extends Allow //继承allow 必须登录才能进入购物车

{
    //加载购物车
    public function getadd()
    {   
        $uid = Session::get("userid");
        $request = \request();
        //商品id
        $id = $request->param('id');
        //获取商品信息
        $info = Db::table("product")->where("id","{$id}")->find();
        //检测当前购物车 是否已经添加商品
        $infocarts = Db::table("carts")->where("user_id = {$uid} AND product_id={$id}")->select();
        // \var_dump($infocarts);die;
        if (!empty($infocarts)) {
            //数量加一
            if ($infocarts[0]['num'] < $info['num']) {
                $infocarts[0]['num'] += 1;
            } else {
                $infocarts[0]['num'] = $info['num'];
            }
            //执行修改
            Db::table("carts")->where("id","{$infocarts[0]['id']}")->update($infocarts[0]);
        } else {
            //封装需要添加数据 用户id 商品id 对应购物车cart表的字段
            $data['user_id'] = $uid;
            $data['product_id'] = $id;
            //初始化数量为1
            $data['num'] = 1;
            $data['name'] = $info['name'];
            $data['pic'] = $info['pic'];
            $data['price'] = $info['price'];
            $data['descr'] = $info['descr'];
            //数据添加到购物车 carts表
            Db::table("carts")->insert($data);
        }
        
        // $this->success("已加入购物车","/productcarts/index");
        $this->redirect("/productcarts/index");
    }

    //购物车页面
    public function getindex(){
        //获取当前用户的购物车数据
        $shop = Db::table("carts")->where("user_id",Session::get("userid"))->select();
        //加载模板
        return $this->fetch("carts/index",["shop"=>$shop]);
    }

    //减
    public function getsubtraction(){
        $request = \request();
        //商品id
        $id = $request->param('id');
        //获取当前购物车数据表数据
        $infocarts = Db::table("carts")->where("id","{$id}")->find();
        //执行减 当前商品数量-1
        $infocarts['num'] -= 1;
        //商品不能为负数
        if ($infocarts['num'] < 1) {
            $infocarts['num'] = 1;
        } 
        
        //carts表更新
        Db::table("carts")->where("id","{$id}")->update($infocarts);
        // echo $infocarts['num'];
        //封装数量和总计
        $data['num'] = $infocarts['num'];
        $data['tot'] = $infocarts['num'] * $infocarts['price'];
        echo \json_encode($data);
    }

    //加
    public function getaddition(){
        $request = \request();
        //商品id
        $id = $request->param('id');
        //获取当前购物车数据表信息
        $infocarts = Db::table("carts")->where("id","{$id}")->find();
        //获取商品数据
        $info = Db::table("product")->where("id","{$infocarts['product_id']}")->find();
        //当前数量加一
        $infocarts['num'] += 1;
        if ($infocarts['num'] > $info['num']) {
            $infocarts['num'] = $info['num'];
        }
        //数据更新进carts表
        Db::table("carts")->where("id","{$id}")->update($infocarts);
        //封装 数量和总计
        $data['num'] = $infocarts['num'];
        $data['tot'] = $infocarts['num'] * $infocarts['price'];
        echo \json_encode($data);
    }

    //删除
    public function getdel(){
        $request = \request();
        //商品id
        $id = $request->param('id');
        Db::table("carts")->where("id","{$id}")->delete();
        echo Db("carts")->getlastsql();
        $this->redirect("/productcarts/index");
    }
}
