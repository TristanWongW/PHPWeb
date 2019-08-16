<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;

//商品管理
class Product extends Controller
{   
    //调整分类类别顺序 添加分隔符
    public function getcategory(){
        $cate = Db::query("select *,concat(path,',',id) as paths from category order by paths");
        //遍历
        foreach ($cate as $key => $value) {
            // echo $value['path'].'<br>';
            //获取path
            $path = $value['path'];
            //转换为数组
            $arr = explode(",",$path);
            // \var_dump($arr);
            //获取逗号的个数
            $len = count($arr)-1;
            // echo $len.'<br>';
            //添加分隔符
            //重复字符串函数
            $cate[$key]['name'] = \str_repeat("|--❤-->",$len).$value['name'];
        }
        return $cate;
    }
   
    public function getindex(){
        $request = \request();
        //获取数据 
        //获取数据总条数
        $count = Db::table("product")->count();
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
        $sql = "select  p.*, c.name as cname from product p left join category  c on p.c_id=c.id  limit {$offset},{$rev}";
        
        //判断当前请求是否为 Ajax请求
        if ($request->isAjax()) {
            // echo "就是Ajax请求";
            // exit;
            //获取当前页信息
            $data = Db::query($sql);
            //独立加载模板 独立模板中重新遍历数据
            return $this->fetch("product/content",['product'=>$data]);
        }
        $product = Db::query($sql);
        // \var_dump($product);
        //加载模板分配数据
        return $this->fetch("product/index",['product'=>$product,"pp"=>$page]);
    }

    public function getadd(){
        $cate = $this->getcategory();
        // echo '公告添加';
        //加载添加模板
        return $this->fetch("product/add",["cate"=>$cate]);
    }

    //执行添加
    public function postinsert(){
        //创建前期对象
        $request = \request();
        // \var_dump($request);
        
        //图片上传
        //获取上传图片资源
        $file = $request->file("pic");
        
        //上传验证规则添加
        $result = $this->validate(['file1'=>$file],['file1'=>"require|image"],['file1.require'=>'上传内容不可为空','file1.image'=>'请上传图片格式的内容']);
        if (true !== $result) {
            $this->error($result,"/adminproduct/add");
        }  
        //原图片移动到指定目录下
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        //  输出上传内容的名字以及上传内容的后缀格式
        // echo $info->getSaveName().":".$info->getExtension();
        //获取上传图片的信息 图片名
        $savename = $info->getSaveName();
        
            //把需要的数据 封装进数组            
            $data = $request->only(['name','c_id','descr','num','price']);
            //原图 封装近数组
            $data['pic'] = "/uploads/".$savename;
            // \var_dump($data);exit;
            //执行添加
            if (Db::table("product")->insert($data)) {
                $this->success("商品添加成功","/adminproduct/index");
            } else {
                $this->error("商品添加失败");
            }

    }
    
    //商品删除
    public function getdelete(){
        $request = \request();
        //获取要删除内容的id
        $id = $request->param('id');
        // echo $id;
        //获取需要删除的数据
        $info = Db::table("product")->where('id',"{$id}")->find();
        // \var_dump($info);die;
        //图信息 加 点 "." 变为相对路径
        $pic = ".".$info['pic'];
        // echo $pic;
        
        if (Db::table("product")->where("id","{$id}")->delete()) {
            //删除图
            unlink($pic);
            $this->success("数据删除成功","/adminproduct/index");
        } else {
            $this->error("删除失败");
        }
    }


    //商品修改
    public function getedit(){
        $cate = $this->getcategory();
        $request = \request();
        //获取商品id
        $id = $request->param('id');
        // echo $id;
        //查询商品信息
        $info = Db::table('product')->where('id',"{$id}")->find();
        // \var_dump($info);
        //加载商品修改模板
        return $this->fetch("product/edit",["product"=>$info,"cate"=>$cate]);
    }


    //执行修改
    public function postupdate(){
        $request = \request();
        //获取要更改内容的id
        $id = $request->param('id');
        //获取需要修改的数据
        $info = Db::table("product")->where('id',"{$id}")->find();
        //获取原图 拼接原图路径
        //图信息 加 点 "." 变为相对路径
        $pic = ".".$info['pic'];
        //图片上传
        //获取上传图片资源
        $file = $request->file("pic");
        // \var_dump($file);die;
        //上传验证规则添加
        $result = $this->validate(['file1'=>$file],['file1'=>"image"],['file1.image'=>'请上传图片格式的内容']);
        // \var_dump($result);die;
        if (true == $result) {
            //删除原图
            unlink($pic);
        }
        //原图片移动到指定目录下
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        //获取上传新图片的信息 图片名
        $savename = $info->getSaveName();
        //把需要的数据 封装进数组            
        $data = $request->only(['name','c_id','descr','num','price']);
        //把新图路径 封装进数组
        $data['pic'] = "/uploads/".$savename;

        // \var_dump($data);

        //执行修改
        if (Db::table("product")->where('id',"{$id}")->update($data)) {
            $this->success("修改成功","/adminproduct/index");
        } else {
            $this->error("修改失败","/adminproduct/edit");
        }
        

    }
}
?>