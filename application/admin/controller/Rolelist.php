<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;

//角色管理类
class Rolelist extends Allow
{   
   
    //角色列表
    public function getindex(){
        // echo '角色列表';
        $list = Db::table("role")->select();
        //加载模板 分配数据
        return $this->fetch("rolelist/index",['rolelist'=>$list]);
    }

    //添加角色
    public function getadd(){
        // echo '添加角色';
        //加载模板
        return $this->fetch("rolelist/add");
    }

    //执行添加
    public function postinsert(){
        $request = \request();
        //获取要添加角色的参数
        $data = $request->only(['name','status','remark']);
        // \var_dump($data);
        //执行添加
        if (Db::table("role")->insert($data)) {
            $this->success("角色添加成功","/adminroles/index");
        } else {
            $this->error("角色添加失败");
        }
        
    }

    //删除角色
    public function getdelete(){
        $request = \request();
        //获取删除角色id
        $id = $request->param('id');
        // echo $id;
        //执行删除
        if (Db::table("role")->delete($id)) {
            $this->success("角色删除成功","/adminroles/index");
        } else {
            $this->error("角色删除失败");
        }
        
    }

}
?>