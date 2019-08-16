<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;
//导入session类
use think\Session;

class Login extends Controller
{   
   
    public function getlogin(){
        //加载后台登录模板
        return $this->fetch("login/login");
    }

    //执行登录 请求方式为post
    public function postdologin(){
        // echo '瞎几把搞';

        //创建请求对象
        $request = \request();
        //获取输入的验证码
        $fcode = $request->param('fcode');
        //获取用户名和密码
        $name = $request->param('username');
        $password = $request->param('password');
        // echo $fcode;
        //校验验证码
        if (captcha_check($fcode)) {
            // echo '核对成功';
            //检测用户名和密码
            $row = Db::table("admin_users")->where("name='{$name}' and pwd='{$password}'")->select();
            // \var_dump($row);
            if ($row) {
                //把用户登录信息存储到session
                Session::set('islogin',1);
                Session::set('adminuserid',$row[0]['id']);
                Session::set('adminuser',$row[0]['name']);
                // 1. 获取当前登录用户所具有的权限信息
                $list = Db::query("select * from user_role as ur,role_node as rn,node as n where ur.rid = rn.rid and rn.nid = n.id and uid = {$row[0]['id']}");
                // echo '<pre>';
                // \var_dump($list);exit;
                // 2. 初始化权限信息 此处的admin是Admin控制器 
                //所有的管理员都有访问后台首页权限
                $nodelist['admin'][] = 'getindex';
                //遍历权限
                foreach ($list as $k => $v) {
                    //赋值 $nodelist
                    $nodelist[$v['mname']][] = $v['aname'];
                    //如果权限列表具有add方法 添加postinsert
                    if ($v['aname'] == "getadd") {
                        $nodelist[$v['mname']][] = "postinsert";
                    }
                    //如果权限列表具有edit方法 添加postupdate
                    if ($v['aname'] == "getedit") {
                        $nodelist[$v['mname']][] = "postupdate";
                    }
                }
                // \var_dump($nodelist);exit;
                // 3. 把当前登录用所具有的权限信息 存储在session中
                Session::set('nodelist',$nodelist);
                // 4. 检测访问模块是否在权限列表中
                //跳转到后台首页
                $this->success("登录成功","/admin/index");
            }else {
                $this->error("用户名或者密码错误","/adminlogin/login");
            }


        }else {
            // echo '校验失败';
            $this->error("验证码错误","/adminlogin/login");
        }
    }

    //退出登录
    public function getloginout(){
        Session::delete('islogin');
        Session::delete('adminuserid');
        Session::delete('adminuser');
        $this->success("退出成功","/adminlogin/login");
    }
}
?>