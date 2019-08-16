<?php
namespace app\admin\controller;
//导入controller
use think\Controller;
//导入Db类
use think\Db;
class category extends Allow
{   
    //调整分类类别顺序 添加分隔符
    public function getcategory(){
        //拼接path 和 id 然后别名 排序查询
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
    //后台分类列表
    public function getindex(){
        //echo '分类列表';
        $cate = $this->getcategory();
        // $cate = Db::table("category")->select();
        return $this->fetch("category/index",['cate'=>$cate]);
    }

    //商品分类添加
    public function getadd(){
        // echo '添加分类';
        //获取分类信息
        $cate = Db::table("category")->select();
        
        //加载添加模板
        return $this->fetch("category/add",['cate'=>$cate]);
    }

    //执行添加
    public function postinsert(){
        //创建请求对象
        $request = \request();
        // \var_dump($request->param());
        $data = $request->only(['name','pid']);
        //获取pid 父级ID
        $pid = $request->param('pid');
        // echo $pid;
        //添加顶级分类
        if ($pid == 0) {
            //拼接path
            $data['path'] = "0";
        } else {
            // 添加子类信息
            // \var_dump($data);
            //获取父类 信息
            $info = Db::table("category")->where("id","{$pid}")->find();
            //拼接path
            $data['path'] = $info['path'].','.$info['id'];
        }
        //执行插入
        // \var_dump($data);
        if ( Db::table("category")->insert($data)) {
            // echo 'success';
            $this->success("类别添加成功","/admincategory/index");
        } else {
            // echo 'default';
            $this->success("类别添加失败");
        }
    }

    //删除分类
    public function getdelete(){
        //获取删除数据的id
        $request = \request();
        $id = $request->param('id');
        //获取当前类别下的子类个数
        $count = Db::table("category")->where('pid',"{$id}")->Count();
        // echo $count;
        if ($count > 0) {
            $this->error("请先删除子类","/admincategory/index");
        }

        //没有子类 直接删除
        if (Db::table("category")->where("id","{$id}")->delete()) {
            $this->success("分类删除成功","/admincategory/index");
        } else {
            $this->error("分类删除失败");
        }
        
    }

    //修改分类信息  获取需要修改信息的数据
    public function getedit(){
        $request = \request();
        //获取需要修改数据的id
        $id = $request->param('id');
        $info = Db::table("category")->where("id","{$id}")->find();
        //获取所有的类别
        $data = Db::table("category")->select();
        return $this->fetch("category/edit",["info"=>$info,"data"=>$data]);
    }
    
    //执行修改
    public function postupdate(){
        $request = \request();
        //获取修改数据的id
        $id = $request->param('id');
        //获取修改数据
        $data = $request->only(['name']);
        if (Db::table('category')->where("id",$id)->update($data)) {
            return $this->success("修改成功","/admincategory/index");
        } else {
            return $this->error("修改失败");
        }
        
    }

}
?>