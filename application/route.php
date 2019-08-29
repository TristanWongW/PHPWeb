<?php

//导入路由类
use think\Route;
//路由设置 get->请求方式 /admin->路由规则  "admin/Index/index" admin->模块 Index->控制器 index->方法
// Route::get("/admin","admin/Index/index");

//视图  后台首页
Route::controller("/view","admin/View");
//数据库
Route::controller("/db","admin/Dbl");
//后台登录
Route::controller("/adminlogin","admin/Login");
//后台路由
Route::controller("/admin","admin/Admin");
//用户管理
Route::controller("/adminuser","admin/User");

//文件上传和图像处理
Route::controller("/file","admin/File");
//后台管理员管理
Route::controller("/adminsuser","admin/Admins");
//后台无限分类模块
Route::controller("/admincategory","admin/Category");
//后台公告管理
Route::controller("/adminbillboards","admin/Billboards");
//角色管理
Route::controller("/adminroles","admin/Rolelist");    
//权限管理(节点管理)
Route::controller("/adminnode","admin/Nodelist");
//权限分配管理
Route::controller("/adminpower","admin/Assign");
//后台商品管理
Route::controller("/adminproduct","admin/Product");


//前台注册
Route::controller("/homeregister","home/Register");
//前台登录
Route::controller("/homelogin","home/Login");
//前台首页
Route::controller("/homeindex","home/Index");
//前台列表
Route::controller("/homelist","home/Listp");
//商品详情页
Route::controller("/productdetail","home/Product");
//购物车
Route::controller("/productcart","home/Cart");
//购物车 2 登录后才可购物
Route::controller("/productcarts","home/Carts");
//结算
Route::controller("/productaddress","home/Address");
//订单
Route::controller("/productorder","home/Order");

?>