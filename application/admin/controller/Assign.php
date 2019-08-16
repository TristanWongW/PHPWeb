<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;
class Assign extends Allow
{     
    //角色对应的 角色权限 遍历 
    //权限分配 多余的页面
    public function getindex(){
        $request = \request();
        //获取角色id
        $id = $request->param('id');
        //获取角色名
        $name = Db::query("select name from role where id = {$id}");
        // \var_dump($name);
        // echo $id;
        //三表联查 遍历 role.id=role_node.rid and role_node.nid =  node.id 查看此角色id所具有的权限信息
        $data = Db::query("select * from role,role_node as rd,node as n where role.id= rd.rid and rd.nid= n.id and role.id = {$id}");
        // \var_dump($data);
        //加载模板 分配参数
        return $this->fetch("assign/index",["node"=>$data,"name"=>$name,"id"=>$id]);
    }

    //增加权限
    public function getadd(){
        //加载权限列表
        $request = \request();
        $id = $request->param('id');
        // echo $id;
        //遍历节点表 权限
        $assign = Db::table("node")->select();
        //三表联查 遍历 role.id=role_node.rid and role_node.nid =  node.id 查看此角色id所具有的权限信息
        $data = Db::query("select * from role,role_node as rd,node as n where role.id= rd.rid and rd.nid= n.id and role.id = {$id}");
        // \var_dump($data);
        $rid = array();
        foreach ($data as $key => $value) {
            $rid[] = $value['id'];
        }
        // var_dump($rid);
        //加载模板 分配变量
        return $this->fetch("assign/assign",["node"=>$assign,"id"=>$id,"data"=>$rid]);
        

    }

    //执行添加权限
    public function  postinsert(){
        //打印ajax传过来的值
        // \var_dump($_POST);
        $request = \request();
        //获取角色id
        $id = $_POST['id'];
        //把ajax传过来的字符串 分割成数组
        $data = explode(',',$_POST['data']);
        //循环遍历数组 重新赋值加入角色id
        foreach ($data as $key => $value) {
            
            $nid[$key]['nid'] = $value;
            $nid[$key]['rid'] = $id;
           
        }
        // var_dump($nid);
        //把角色ID role.id和节点ID node.id 插入到 角色中间节点表里 role_node
        //使用try catch 删除 添加数据
        try{
            Db::table('role_node')->where("rid","{$id}")->delete();
            Db::table('role_node')->insertAll($nid);
            $arr=array('code'=>0,'msg'=>'分配成功');
        } catch (\Exception $e) {
            $arr=array('code'=>1,'msg'=>'分配失败');
        }
        echo \json_encode($arr);

    }

}
?>