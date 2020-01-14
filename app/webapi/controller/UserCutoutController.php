<?php


namespace app\webapi\controller;


use app\webapi\model\CutoutNumberModel;
use app\webapi\model\FileModel;
use app\webapi\model\UserClientModel;
use app\webapi\model\UserCutoutModel;
use app\webapi\services\BaduServicesController;
use cmf\controller\HomeBaseController;
use app\webapi\services\PyServicesController;
//该类用于存储用户抠图后的图片

class UserCutoutController extends HomeBaseController
{

    //客户端接口：用户上传图片
    public function upload_file_once()
    {

        $author = input('user_id');
        if(!$author) $this->backto( 100011 );
        $p_path = input('path');
        if(!$p_path) $this->backto( 100012 );
        $type = input('type');
        $py_path = "/home/wwwroot/ht.idealead.hbindex.com/public/uploadFiles/images/".$p_path;

        $service = new PyServicesController();
        $model_cutout = new CutoutNumberModel();
        $model = new UserClientModel();
        $model_num = new UserCutoutModel();

        $check_num = $model_num->checkCut0utSession();
//        dump($check_num);
//        die;
        if ($check_num == 201){
            $res = [
                'code'    => '207',
                'message' => '抠图已达今日上线',
            ];
            $res = json_encode($res);
            return $res;
        }
        $check = $model ->checkUser($author);
        if (!$check)
        {
            $res = [
                'code'    => '208',
                'message' => '非法用户',
            ];
            $res = json_encode($res);
            return $res;
        }

        $url = $service->GetUrl($py_path);

        if ($url == 201){
            $model_cutout->callStatus($author,0);
            $res = [
                'code'    => '206',
                'message' => '抠图失败',
                'path'    =>'',
            ];
            $res = json_encode($res);
            return $res;
        }
        if (!$url){
            $res = [
                'code'    => '204',
                'message' => '返回路径为空',
                'path'    =>$url,
            ];
            $res = json_encode($res);
            return $res;
        }

        $root = $_SERVER['DOCUMENT_ROOT'];

        $date = date("Ymt");
        $time = time();
        $path = $root . '/uploadFiles/images/'.$date;
//        $no_root_path = '/upload/images/' . $date;
//        $newPath = $no_root_path.'/'.$fileName;

        $fileName = $time . rand(0000, 9999) . '.png';
        $re_path =  $date.'/'. $fileName;

        if (is_dir($path)) {
            $this ->insertImage($url,$path,$fileName);
            $model_cutout->callStatus($author,1);
            if ($type ==1){
                $model = new FileModel();
                $res = $model ->addImagesSelf($author,$re_path);
                return $res;
            }
            $res = [
                'code'    => '200',
                'message' => '抠图成功',
                'path'    =>$re_path,
            ];
            $res = json_encode($res);
            return $res;

        } else {
            $dir = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            if ($dir) {
                $this ->insertImage($url,$path,$fileName);
                $model_cutout->callStatus($author,1);
                if ($type ==1){
                    $model = new FileModel();
                    $res = $model ->addImagesSelf($author,$re_path);
                    return $res;
                }
                $res = [
                    'code'    => '200',
                    'message' => '抠图成功',
                    'path'    =>$re_path,
                ];
                $res = json_encode($res);
                return $res;
            } else {
                $res = [
                    'code'    => '10003',
                    'message' => '文件夹创建失败',
                ];
                $res = json_encode($res);
                return $res;
            }
        }
    }

    //客户端 ，添加用户抠图图片
    public function save_cutout_file()
    {
        if(request()->post())
        {
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 100011 );
            $path = input('path');
            if(!$path) $this->backto( 100012 );
            //  /uploadFiles/images/20191231/15755170072173.png
            $path = substr($path,20);

            $model = new FileModel();
            $res = $model ->addImagesSelf($user_id  , $path);
            return $res;
        }else{
            $res = [
                'code'    => '203',
                'message' => '请求方式错误',

            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //客户端接口：用户查看自己上传过的图片
    public function find_images_self()
    {
        $author = input("author");
        $model = new FileModel();
        $res = $model ->findImagesSelf($author,2);
        return $res;
    }

    //客户端接口：用户删除自己的图片
    public function del_images_self()
    {
        $id  = input('id');
        $model = new FileModel();
        $res = $model ->delImagesSelf($id);
        return $res;
    }

    //get  请求
    private function httpGet($url)
    {
        $curl=curl_init();

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_TIMEOUT,500);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_URL,$url);
        $res=curl_exec($curl);
        curl_close($curl);
        return $res;

    }

    //插入文件
    public function insertImage($url,$path,$fileName)
    {
        $data = $this->httpGet($url);
        $file = fopen($path . '/' . $fileName,'a');
        fwrite($file, $data);
        fclose($file);
       return 200;

    }


    //获取图片主题大小
    public function get_image_body()
    {
        $path = input('path');
        if(!$path) $this->backto( 100011 );
        $services = new BaduServicesController();
        $token = $services->getToken();
        $data = $services ->image($path,$token);
        $res = [
            'code'=>200,
            'message' =>'成功',
            'data' =>$data,
        ];

        $res = json_encode($res);
        echo $res;
        die;
    }


    //抠图直接保存
    public function save_cutout()
    {
        $user_id = input('user_id');
        $p_path = input('path');
        $py_path = "/home/wwwroot/ht.idealead.hbindex.com/public/uploadFiles/images/".$p_path;
        $service = new PyServicesController();
        $url = $service->GetUrl($py_path);
        if ($url == 201){
            $res = [
                'code'    => '206',
                'message' => '抠图失败',
                'path'    =>'',
            ];
            $res = json_encode($res);
            return $res;
        }
        if (!$url){
            $res = [
                'code'    => '204',
                'message' => '返回路径为空',
                'path'    =>$url,
            ];
            $res = json_encode($res);
            return $res;
        }

        $root = $_SERVER['DOCUMENT_ROOT'];
        $date = date("Ymt");
        $time = time();
        $path = $root . '/uploadFiles/images/'.$date;
        $fileName = $time . rand(0000, 9999) . '.png';
        $re_path =  $date.'/'. $fileName;

        if (is_dir($path)) {
            $this ->insertImage($url,$path,$fileName);
            $model = new FileModel();
            $res = $model ->addImagesSelf($user_id,$re_path);
            $res = json_encode($res);
            return $res;
        } else {
            $dir = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            if ($dir) {
                $this ->insertImage($url,$path,$fileName);
                $model = new FileModel();
                $res = $model ->addImagesSelf($user_id,$re_path);
                $res = json_encode($res);
                return $res;
            } else {
                $res = [
                    'code'    => '10003',
                    'message' => '文件夹创建失败',
                ];
                $res = json_encode($res);
                return $res;
            }
        }
    }















}