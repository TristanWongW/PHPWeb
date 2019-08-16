<?php
namespace app\admin\controller;
//导入Controller
use think\Controller;
class File extends Controller{
    public function getindex(){
        return $this->fetch("file/index");
    }

    //执行上传
    public function postupfile(){
        $request = \request();
        // 1.普通上传
        // (1)获取表单数据
        // $file = $request->file('pic');
        // // (2)移动数据到指定的目录  ROOT_PATH   系统根目录 / DS 系统目录分隔符
        // if ($file) {
        //     $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        //     \var_dump($info);
        //     //获取上传后的文件信息和文件后缀
        //     echo $info->getSavename().$info->getExtension();
        // }

        // 2.带有验证的上传
        // (1) 获取表单上传数据
        $file = $request->file('pic');
        // (2) 设置上传规则
        $result = $this->validate(['file1'=>$file],['file1'=>'require|image'],['file1.require'=>'上传文件不能为空','file1.image'=>'上传文件必须为图片类型']);
        
        if (true !== $result) {
            $this->error($result,"/file/index");
        }
        // (3) 移动数据到指定的目录保存
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        // \var_dump($info);
        //获取上传文件的信息
        $path = $info->getSavename();
        //文件后缀
        $ext = $info->getExtension();
        //图像处理
        //打开需要处理的图像
        $img = \think\Image::open("./uploads/".$path);
        //随机生成图片的名字
        $name = time()+rand(1,10000);
        //剪裁
        // $img->crop(100,100,50,200)->save("./uploads/publicimg/".$name.".".$ext);
        //缩放
        // $img->thumb(100,100)->save("./uploads/publicimg/".$name.".".$ext);
        $img->water("./logo.jpg",\think\Image::WATER_NORTHWEST,50)->save("./uploads/publicimg/".$name.".".$ext);
    }
}
?>