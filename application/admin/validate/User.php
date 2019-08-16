<?php
namespace app\admin\validate;
//导入系统的验证类 Validate
use think\Validate;
class User extends Validate{
    //设置表单验证规则
    protected $rule = [
            'username' => 'require|regex:\w{4,11}|unique:user',
            'pwd'      => 'require|regex:\w{6,10}',
            'repwd'    => 'require|confirm:pwd',
            'state'    => 'require',
    ];
    //规则的提示信息
    protected $message = [
            'username.require' => '用户名不可为空',
            'username.regex'   => '用户名不合法',
            'username.unique'  => '用户名重复',
            'pwd.require'      => '密码不能为空',
            'pwd.regex'        => '密码必须设为6-8位',
            'repwd.require'    => '确认密码不能为空',
            'repwd.confirm'    => '密码不一致',
            'state'            => '等级不可为空', 
    ];
    //验证场景
    protected $scene = [
        'add'   =>  ['username','pwd','repwd','state'],
        
    ];
}

?>