<?php
namespace app\home\controller;
//导入Controller
use think\Controller;
//导入Db类
use think\Db;
class Index extends Controller
{
    //无限分类递归遍历数据
    public function getcategorybypid($pid){
        $data = Db::table("category")->where("pid","{$pid}")->select();
        $data1 = array();
        //遍历$data
        foreach ($data as $key => $value) {
            //获取子类信息 也就是把父类id存储到shop中
            $value['shop'] = $this->getcategorybypid($value['id']);
            $data1[] = $value;
        }
        return $data1;
    }
   
    public function getindex()
    {    
        //创建请求对象
        $request = \request();
        //调用无限分类
        $category = $this->getcategorybypid(0);
        // echo "<pre>";
        // \print_r($category);
        //获取当前一级分类的id
        $cid = $request->get("cid");
        //检测当前请求是否为Ajax
        if (!$request->isAjax()) {
            //如果当前不是Ajax请求 获取当前以及id上的顶级id下5条商品数据
            // $sql = "select pd.name, pd.pic, pd.descr, pd.price, c.name from product pd left join category c on pd.c_id = c.id limit 5";
            $data = Db::table("product")->limit(5)->select();
            // $data = Db::query($sql);
            return $this->fetch("index/index",['category'=>$category,'data'=>$data]);
        }
        
        //是Ajax请求
        // echo $cid;
        //获取当前一级分类下的所有商品数据
        $res = Db::table("product")->alias('p')->field("p.id as pid,p.name as pname,p.pic,p.descr,p.price,c.id as cid,c.name as cname")->where("p.c_id","{$cid}")->join("category c","p.c_id=c.id")->select();
        // \var_dump($res);die;
        echo json_encode($res);
    }

   
}
