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
        //调用无限分类
        $category = $this->getcategorybypid(0);
        // echo "<pre>";
        // \print_r($category);
        return $this->fetch("index/index",['category'=>$category]);
    }

   
}
