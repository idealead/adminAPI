<?php


namespace app\webapi\controller;

use app\webapi\model\FileModel;
use app\webapi\model\UserCutoutModel;
use app\webapi\model\UserImagesModel;
use cmf\controller\HomeBaseController;
use think\Db;
//该类用于存储用户用户保存的图片

class UserImagesController extends HomeBaseController
{

    //客户端接口：用户上传图片
    public function  upload_file_once()
    {
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET,POST");

        $file = request()->file('upload_file_once');
//        dump($file);
//        die;
        // $file_size	= 1048576;
        $file_size	= 5242880;
        $file_ext	= 'jpg,png,gif,jpeg';
        $file_root	= ROOT_PATH . 'public';
        $file_path	= 'uploadFiles' . DS . 'images';
        // 文件上传 - 核心代码
        $model = new FileModel();
        $info = $file->rule('date')->validate(['size'=>$file_size,'ext'=>$file_ext])->move( $file_root . DS . $file_path );
        if($info){
            $save_path = str_replace("\\", "/", $info->getSaveName() );
            // 储存文件信息
            $path = $save_path;
            $save_data = [
                'type'		=> $info->getExtension(),
                'path'		=> $path,
                'author'	=> input("user_id"),
                'createtime'=> date("Y-m-d H:i:s")
            ];
            $new_file_id = $model->insertGetId($save_data);	//	文件信息入库
            if(!$new_file_id){
                $this->backto( 20002 );
            }
            // 返回数据
            $ret_data = [
                'ext'		=> $info->getExtension(),
                'savepath'	=> $path ,
                'savename'	=> $info->getFilename(),
                'file_id'	=> $new_file_id
            ];
            $this->backto( 200 , "上传成功!" , $ret_data );
        }else{
            $this->backto( 30001 , $file->getError() );
        }


    }


    //客户端接口：用户查看自己上传过的图片
    public function find_images_self()
    {
        if (request()->isGet()){
            $author = input("user_id");
            if(!$author) $this->backto( 10001 );
            $model = new FileModel();

            $dataImage = $model ->findImagesSelf($author,1);
            $dataCutout = $model->findImagesSelf($author,2);
            $res = [
                'code'    => '200',
                'message' => '访问成功',
                'dataImage'=>$dataImage,
                'dataCutout'=>$dataCutout,
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }


    //客户端接口：用户删除自己的图片
    public function del_images_self()
    {
        if (request()->isGet()){
            $id  = input('id');
            $user_id = input('user_id');
            $model = new FileModel();
            $check = $model ->findUserId($id);
            if ($check ==$user_id){
                $res = $model ->delImagesSelf($id);
                return $res;
            }else{
                $res = [
                    'code'    => '203',
                    'message' => '素材不属于你，无法删除',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }


    //客户端接口：保存放大主题图片
    public function save_main_body($image_base64 ,$type)
    {


        $user_id = input('user_id');
        $root = $_SERVER['DOCUMENT_ROOT'];
        $date = date("Ymt");
        $time = time();
        $path = $root . '/uploadFiles/images/'.$date;
        $fileName = "a".$time . rand(0000, 9999) . '.'.$type;
        $re_path =  $date.'/'. $fileName;
        $model = new FileModel();
        $a = $path.'/'.$fileName;
        $save_data = [
            'type'		=> $type,
            'path'		=> $re_path,
            'author'	=> $user_id,
            'createtime'=> date("Y-m-d H:i:s"),
            'is_cutout' => 3,
        ];

        $check = $this ->make_dir($path);
        if ($check){
            list($type, $image_base64) = explode(';', $image_base64);
            list(, $image_base64) = explode(',', $image_base64);
            $image_base64 = base64_decode($image_base64);
            file_put_contents($a, $image_base64);
            $new_file_id = $model->insertGetId($save_data);
            $res_data = [
                'id' =>$new_file_id,
                'path' =>$re_path,
            ];
            $res = [
                'code' =>200,
                'mag' => '成功',
                'data' =>$res_data,
            ];
            $res = json_encode($res);
            echo $res;
            die;
        }




    }


    //创建文件夹
    public function make_dir($path)
    {
        if (is_dir($path)) {
           return $path;

        } else {
            $dir = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            if ($dir) {

                return 200;
            } else {

                return 0;
            }
        }

    }



}