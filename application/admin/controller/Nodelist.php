<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;
class Nodelist extends Allow
{   
   //权限列表
    public function getindex(){
        // echo '这是权限列表';
        $node = Db::table("node")->select();
        //加载模板 分配变量
        return $this->fetch("nodelist/index",["node"=>$node]);
    }
    
    //权限添加
    public function getadd(){
        // echo '这是权限添加';
       //加载模板
       return $this->fetch("nodelist/add");
    }

    //执行添加
    public function postinsert(){
        $request = \request();
        //获取要添加的参数
        $data = $request->only(['name','mname','aname','status']);
        //执行添加
        if (Db::table("node")->insert($data)) {
            $this->success("权限添加成功","/adminnode/index");
        } else {
            $this->error("权限添加失败");
        }
    }
    
}
?>