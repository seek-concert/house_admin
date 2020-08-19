<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/18
 * Time: 9:07
 */

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Model\NewCateModel;
use App\Model\NewModel;
use Illuminate\Http\Request;

class NewController extends Controller
{
    //新闻列表
    public function lists()
    {
     $data_new = NewModel::all();
        return view('admin.new.lists',compact('data_new'));
    }
    //新闻添加页面
    public function add(){
$data_new_cate = NewCateModel::all();
        return view('admin.new.add',compact('data_new_cate'));
    }
   //新闻添加处理
    public function create(Request $request){
        {
            $this->validate($request, [
                'cate_id' => 'required',
                'title' => 'required',
                'introduction' => 'required',
                'content' => 'required',
                'publisher' => 'required',
                'is_top' => 'required',
                'status' => 'required',
                'created_at' => 'required',
            ], [
                'cate_id.required'=>'请选择分类',
                'title.required'=>'请输入标题',
                'introduction.required'=>'请输入简介',
                'content.required'=>'请输入新闻内容',
                'publisher.required'=>'请输入发布人员',
                'is_top.required'=>'请选择是否置顶',
                'status.required'=>'请选择是否隐藏',
                'created_at.required'=>'请输入创建时间',
            ]);
            $new_article = new NewModel();


            $new_article->cate_id = $request->get('cate_id');
            $new_article->title = $request->get('title');
            $new_article->introduction = $request->get('introduction');
            $new_article->content = $request->get('content');
            $new_article->publisher = $request->get('publisher');
            $new_article->is_top = $request->get('is_top');
            $new_article->status = $request->get('status');
            $new_article->created_at = $request->get('created_at');
            $new_article->save();
////跳转到列表
            return redirect(route('g_new_lists'));
        }

    }

    //图片添加

    public function upload(){
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];
        $extension = end($temp);     // 获取文件后缀名
        if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 2004800)   // 小于 200 kb
            && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                echo "错误：: " . $_FILES["file"]["error"] . "<br>";
            } else {
//		echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
//		echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
//		echo "文件大小: " . ($_FILES["file"]["size"] / 10024) . " kB<br>";
//		echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
//
                // 判断当期目录下的 upload 目录是否存在该文件
                // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " 文件已经存在。 ";
                } else {
                    // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . date()["name"]);
                    echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
                }
            }
        } else {
            echo "非法的文件格式";
        }



    }

}