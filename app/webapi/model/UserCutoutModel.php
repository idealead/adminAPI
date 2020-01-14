<?php


namespace app\webapi\model;

use think\Model;
use think\Session;

class UserCutoutModel extends Model
{

    //查看某用户所有图片
    public function findImagesSelf($author)
    {
        $model = new UserCutoutModel();
        $model_where['author'] = ["eq",$author];
        $model_where_status['is_delete'] = ["eq",1];
        $model_field = "
            id, path,author
        ";
        $data = $model->where($model_where)
            ->where($model_where_status)
            ->field($model_field)
            ->select()->toArray();

        return $data;
    }


    //删除用户图片
    public function delImagesSelf($id)
    {
        $model = new UserCutoutModel();

        $data = $model ->get($id);
        if($data){
            $data-> is_delete = 0;
            $data->save();
            $res = [
                'code'    => '200',
                'message' => '素材删除成功',
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '2001',
                'message' => '素材以删除，请刷新',
            ];

            $res = json_encode($res);
            return $res;
        }


    }


    //插入抠图图片
    public function addImagesSelf($author , $path)
    {
        $model = new UserCutoutModel();
        $add_data = [
            'author'   => $author,
            'path'     => $path,
            'create_time'=> date("Y-m-d H:i:s"),
        ];

        $new_id = $model->insertGetId($add_data);

        $res = [
            'code'    => '200',
            'message' => '保存成功',
            'id'      =>$new_id,
            'path'    =>$path,
        ];
        $res = json_encode($res);
        return $res;
    }


    //记录抠图次数的session
    public function checkCut0utSession()
    {
        $time =  date('Ymd');

        $session = Session::get($time);

        if ($session){
            $session +=1;
            Session::set($time,$session);
            $model_nm = new NormallyModel();
            $max_number = $model_nm ->getNumber();
//            dump($session);
//            dump($max_number);
            if ($session > $max_number){
                $res = 201;
                return $res;
            }else{
               $res = 200;
               return $res;
            }
        }else{
             Session::set($time,1);
             $res = 200;
             return $res;
        }




    }









}