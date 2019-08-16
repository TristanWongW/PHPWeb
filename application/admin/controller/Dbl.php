<?php
namespace app\admin\controller;
//导入Controller
use think\Controller;
//导入系统的Db类
use think\Db;
class Dbl extends Controller
{
    public function getindex(){
        //基本数据库操作
        //查询数据
        // $res = Db::query("select * from user");
        // \var_dump($res);

        //查询构造器或者连贯操作(首选数据操作方法)
        //查询所有数据
        $res1 = Db::table("users")->select();
        // \var_dump($res1);
        //获取单挑数据
        $res2 = Db::table("users")->where("id",5)->find();
        // \var_dump($res2);
        //获取单挑数据中某个字段的值
        $res3 = Db::table("users")->where("id",5)->value("username");
        // \var_dump($res3);
        //获取一列数据
        $res4 = DB::table("users")->column('username');
        // \var_dump($res4);
        
        //插入单条数据
        // $a = Db::table("user")->insert(['username'=>"tugonggou",'pwd'=>"123weq",'addtime'=>time()]);
        // \var_dump($a);
        //插入多天数据
        // $b = Db::table("user")->insertAll([
        //     ['username'=>"tugonggou2",'pwd'=>"123weq31",'addtime'=>time()],
        //     ['username'=>"tugonggou3",'pwd'=>"123weq23",'addtime'=>time()],
        //     ['username'=>"tugonggou4",'pwd'=>"123weq454",'addtime'=>time()]
        // ]);
        // \var_dump($b);

        //获取id 插入数据
        // $id = Db::name("user")->insertGetId(['username'=>"tugonggou5",'pwd'=>"123weq454",'addtime'=>time()]);
        // \var_dump($id);
        
        //分页
        $data = Db::table("admin_users")->paginate(5);
        echo "<pre>";
        \print_r($data);
    }
}
?>