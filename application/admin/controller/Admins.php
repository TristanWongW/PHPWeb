<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入模型类
use app\admin\model\Management;
//导入Db
use think\Db;
class Admins extends Allow
{   
    //管理员列表
    public function getindex(){
        //echo 'this is index';
        //获取所有数据
        $data = Management::paginate(2);
        // \var_dump($data);
        //加载模板 分配数据 management文件夹下的index文件 => "management/index"
        return $this->fetch("management/index",['management'=>$data]);

    }
    //管理员添加
    public function getadd(){
        // echo 'this is add ';
        //加载模板
        return $this->fetch("management/add");
    }
    //执行数据添加
    public function postinsert(){
        $request = \request();
        $data = $request->only(['name','pwd','state']);
        // \var_dump($data);exit;
        //执行添加
        if (Management::create($data)) {
            $this->success("数据添加成功","/adminsuser/index");
        } else {
            $this->error("数据添加失败");
        }
    }

    //管理员删除
    public function getdelete(){
        $request = \request();
        $id = $request->param('id');
        // \var_dump($id);die;
        // 执行删除
        if (Management::destroy($id)) {
            $this->success("数据删除成功","/adminsuser/index");
        } else {
            $this->error("数据删除失败");
        }
        
    }

    //管理员修改
    public function getedit(){
        //创建请求对象
        $request = \request();
        //获取修改数据的id
        $id = $request->param('id');
        //实例化模型类 获取单挑数据
        $management = new Management();
        //获取要修改的数据
        $user = $management->where("id","{$id}")->find();
        // \var_dump($user);die;
        //加载模板 并且数组传递数据到执行修改页面
        return $this->fetch("management/edit",["user"=>$user]);
    }

    //执行修改
    public function postupdate(){
        //创建请求对象
        $request = \request();
        //获取修改数据的id
        $id = $request->param('id');
        //封装修改的数据
        $data = $request->only(['username','pwd','state']);
        if (Management::where('id',"{$id}")->update($data)) {
            $this->success("信息修改成功","/adminsuser/index");
        } else {
            $this->error("信息修改失败");
        }
        
    }

    //分配角色
    public function getrolelist(){
        // echo '分配角色';
        $request = \request();
        $id = $request->param('id');
        // echo $id;
        //实例化模型类 获取单挑数据
        $management = new Management();
        //获取选择的管理员信息
        $user = $management->where("id","{$id}")->find();
        //获取所有的角色信息
        $role = Db::table("role")->select();
        //获取当前用户所具有的角色信息
        $data = Db::table("user_role")->where('uid',"{$id}")->select();
        //获取角色id 存储在数组里
        //遍历
        foreach($data as $v){
            $rids[] = $v['rid'];
        }
        //加载模板
        return $this->fetch("management/rolelist",['user'=>$user,'role'=>$role,'rids'=>$rids]);
    }

    //保存角色
    public function postsaverole(){
        $request = \request();
        // echo '保存角色操作';
        //获取用户id
        $uid = $request->param('uid');
        // \var_dump($uid);
        //把当前用户已有的角色信息删除掉
        Db::table("user_role")->where("uid","{$uid}")->delete();
        //获取角色id
        $rid = $_POST['rid'];
        // \var_dump($rid);
        //向用户角色表user_role插入数据
        //遍历数组$rid
        foreach ($rid as $key => $value) {
            //封装需要添加的数据
            $data['uid'] = $uid;//用户id
            $data['rid'] = $value;//新的角色id
            //执行插入
            Db::table("user_role")->insert($data);
        }
        $this->success("角色分配成功","/adminsuser/index");
    }

}
?>