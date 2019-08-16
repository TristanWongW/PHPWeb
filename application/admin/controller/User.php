<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;

class User extends Allow
{   
    
    //列表
    public function getindex(){
        //创建请求对象
        $request = \request();
        //获取搜索的关键词
        $k = $request->param('keywords');
        echo $k;
        // echo 'this is index';
        //获取用户的所有数据
        $user = Db::table("user")->where('username','like',"%".$k."%")->paginate(8);
        //加载后台用户列表模板
        return $this->fetch("user/index",['user'=>$user,'request'=>$request->param(),'k'=>$k]);
    }
    //用户添加
    public function getadd(){

        // echo 'this is add';
        //加载后台用户添加模板
        return $this->fetch("user/add");
    }

    //执行添加
    public function postinsert(){
        //创建一个请求对象
        $request = \request();
        $data = $request->only(['username','pwd','state']);
        $data['addtime'] = time();
        // \var_dump($data); 
        //数据校验
        $result = $this->validate($request->param(),'User');
        //对比校验开始
        if(true !== $result){
            // 验证失败 输出错误信息 阻止页面提交
            $this->error($result);
            // dump($result);
        }
        //执行添加的方法
        if (Db::table("user")->insert($data)) {
            $this->success("数据添加完成","/adminuser/index");
        } else {
            $this->error("数据添加失败","/adminuser/add");
        }
        
    }

    //执行删除
    public function getdelete(){
        //创建请求对象
        $request = \request();
        //获取删除数据的id
        $id = $request->param('id');
        // echo $id;
        if (Db::table("user")->where("id","{$id}")->delete()) {
            $this->success("数据删除成功","/adminuser/index/");
        } else {
            $this->error("数据删除失败","/adminuser/index/");
        }
        
    }
    
    //会员信息修改
    public function getedit(){
        //创建请求对象
        $request = \request();
        //获取修改数据的id
        $id = $request->param('id');
        //获取要修改的数据
        $user = Db::table("user")->where("id","{$id}")->find();
        //加载修改的模板 并且数组传递数据到执行修改页面
        return $this->fetch("user/edit",['user'=>$user]);
    }
    //执行修改
    public function postupdate(){
        //创建请求对象
        $request = \request();
        //获取修改数据的id
        $id = $request->param('id');
        //封装修改的数据
        $data = $request->only(['username','pwd','state']);
        if (Db::table("user")->where("id","{$id}")->update($data)) {
            $this->success("数据修改成功","/adminuser/index");
        } else {
            $this->error("数据修改失败");
        }
        
    }

}
?>