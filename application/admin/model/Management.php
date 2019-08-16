<?php
namespace app\admin\model;
//导入系统模型类
use think\Model;
class Management extends Model{
    //设置模型类对应的数据表
    protected $table = "admin_users";
    //获取器  对获取到的数据做自动的转换 字段state
    public function getStateAttr($value){
        $state = [0=>'开启',1=>'禁用',2=>'超级管理员'];
        return $state[$value];
    }
}
?>