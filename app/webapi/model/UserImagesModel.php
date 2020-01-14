<?php


namespace app\webapi\model;

use think\Model;

class UserImagesModel extends  Model
{

    //查看某用户所有图片
    public function findImagesSelf($author)
    {
        $model = new UserImagesModel();
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

        $model = new UserImagesModel();
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

}