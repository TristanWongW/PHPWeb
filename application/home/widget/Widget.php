<?php
namespace app\home\widget;
//导入Controller类
use think\Controller;
//导入Db类
use think\Db;
class Widget extends Controller {
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

    //加载公共头部模板
    public function header(){
        $category = $this->getcategorybypid(0);
        return $this->fetch("widget:header",['category'=>$category]);
    }

    //加载公共尾部
    public function footer(){
        return $this->fetch("widget:footer");
    }
}
?>