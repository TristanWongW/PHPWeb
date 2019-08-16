<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Config;
// 应用公共文件
function sendmails(){
    // echo '公共函数库';
    //通过命名空间导入三方类库
    $mail = new \org\util\Mail();
    $mail->sendmail();
}

//接收短信验证码类
function sends($p){
    //导入第三方ucpass类 
    Vendor("lib.Ucpaas");
    //初始化必填
    //填写在开发者控制台首页上的Account Sid
    $options['accountsid']='939187e9e71fe603ffd932dc025f161d';
    //填写在开发者控制台首页上的Auth Token
    $options['token']='7f4f1e31d061b171a689c4dc9765c9e3';
    //初始化 $options必填
    $ucpass = new Ucpaas($options);

    $appid = "c4c7bf05c0d54b0e920dc57f20bf9cbc";	//应用的ID，可在开发者控制台内的短信产品下查看
    $templateid = "493192";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
    //验证码
    $param = rand(1,10000); //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
    //设置cookie 60秒后过期
    setcookie('vcode',$param,time()+60);
    //接收的手机号码
    $mobile = $p;   // $p是当前的手机号
    $uid = "";

    //70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

    echo $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
}