<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;

//公告管理类
class Billboards extends Controller
{   
   
    public function getindex(){
        $request = \request();
        //获取数据 
        //获取数据总条数
        $count = Db::table("billboards")->count();
        //每页显示数据条数
        $rev = 2;
        //获取最大页
        $maxpage = ceil($count/2);
        // echo $maxpage;
        //设置页码
        $page = array();
        for ($i=1; $i <= $maxpage; $i++) { 
            $page[$i] = $i;
        }
        //获取当前页\ 附加参数
        $p = $request->get('page');
        // echo $p;
        //如没有ajax传值 页码默认为 1
        if(empty($p)){
    		$p=1;
    	}
        //偏移量
        $offset = ($p-1)*$rev;
        //准备sql 设定每页只显示两条数据
        $sql = "select * from billboards limit {$offset},{$rev}";
        
        //判断当前请求是否为 Ajax请求
        if ($request->isAjax()) {
            // echo "就是Ajax请求";
            // exit;
            //获取当前页信息
            $data = Db::query($sql);
            //独立加载模板 独立模板中重新遍历数据
            return $this->fetch("billboard/content",['billboard'=>$data]);
        }
        $billboard = Db::query($sql);
        // \var_dump($page);
        //加载模板分配数据
        return $this->fetch("billboard/index",['billboard'=>$billboard,"pp"=>$page]);
    }

    public function getadd(){
        // echo '公告添加';
        //加载添加模板
        return $this->fetch("billboard/add");
    }

    //执行添加
    public function postinsert(){
        //创建前期对象
        $request = \request();
        // \var_dump($request->only(['title','content','pic']));
        //图片上传
        //获取上传图片资源
        $file = $request->file("pic");
        
            //上传验证规则添加
            $result = $this->validate(['file1'=>$file],['file1'=>"require|image"],['file1.require'=>'上传内容不可为空','file1.image'=>'请上传图片格式的内容']);
            if (true !== $result) {
                $this->error($result,"/adminbillboards/add");
            }  
            //原图片移动到指定目录下
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
            //echo  输出上传内容的名字以及上传内容的后缀格式
            // echo $info->getSaveName().":".$info->getExtension();
            //获取上传图片的信息 图片名
            $savename = $info->getSaveName();
            // \var_dump($savename);die;
            //图像处理
            //打开需要处理的图像
            $img = \think\Image::open("./uploads/".$savename);
            //随机图片名字
            $name = time()+rand(1,10000);
            //获取文件后缀
            $ext = $info->getExtension();
            //缩放图片 后台展示
            $img->thumb(150,150)->save("./uploads/publicimg/".$name.".".$ext);
            //封装需要添加的数据
            $data = $request->only(['title','content']);
            //缩略图图片封装进数组
            $data['pic'] = "/uploads/publicimg/".$name.".".$ext;
            //原图 封装近数组
            $data['opic'] = "/uploads/".$savename;
            // \var_dump($data);exit;
            //执行添加
            if (Db::table("billboards")->insert($data)) {
                $this->success("数据添加成功","/adminbillboards/index");
            } else {
                $this->error("数据添加失败");
            }
        

    }
    
    //公共删除
    public function getdelete(){
        $request = \request();
        //获取要删除内容的id
        $id = $request->param('id');
        // echo $id;
        //获取需要删除的数据
        $info = Db::table("billboards")->where('id',"{$id}")->find();
        // \var_dump($info);
        //缩放图信息 加 点 "." 变为相对路径
        $pic = ".".$info['pic'];
        //原图信息
        $opic = ".".$info['opic'];
        // echo $opic;
        //获取内容
        $content = $info['content'];
        // echo $content;
        //检测百度编辑器中是否有要删除内容 如果有内容返回给数组$array
        preg_match_all('/<img.*?src="(.*?)".*?>/is',$content,$array);
        // \var_dump($array);
        if (Db::table("billboards")->where("id","{$id}")->delete()) {
            //判断
            if (isset($array[1])) {
                //直接删除 百度编辑器上传的图片
                foreach ($array[1] as $k => $v) {
                    unlink('.'.$v);
                }
            }
            //删除缩放图 
                unlink($pic);
            //删除原图
                unlink($opic);
            //删除百度编辑器上传的图片
            $this->success("数据删除成功","/adminbillboards/index");
        } else {
            $this->error("删除失败");
        }
        
        
    }

}
?>