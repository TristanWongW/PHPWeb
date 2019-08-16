<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
class Admin extends Allow
{   
        //后台首页 页面主体
    public function getindex(){
        //加载后台首页模板
        return $this->fetch("admin/index");
    }

}
?>