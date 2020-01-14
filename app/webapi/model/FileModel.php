<?php


namespace app\webapi\model;


use think\Model;

class FileModel extends Model
{

    //查看用户商品图,$is_cutout为状态码，1为抠图  2为用户商品图
    public function findImagesSelf($author, $is_cutout)
    {
        $model = new FileModel();
        $model_where['author'] = ["eq", $author];
        $model_where['is_cutout'] = ["eq", $is_cutout];
        $model_where_status['status'] = ["eq", 1];
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

        $model = new FileModel();
        $data = $model->get($id);
        if ($data) {
            $data->status = 2;
            $data->save();
            $res = [
                'code' => '200',
                'message' => '素材删除成功',
            ];
            $res = json_encode($res);
            return $res;
        } else {
            $res = [
                'code' => '2001',
                'message' => '素材以删除，请刷新',
            ];

            $res = json_encode($res);
            return $res;
        }

    }


    //根据id查询出user_id
    public function findUserId($id)
    {
        $model = new FileModel();
        $where['id'] = ["eq", $id];
        $data = $model->where($where)->select()->toArray();
        if ($data) {
            $user_id = $data[0]['author'];
            return $user_id;
        } else {
            return 0;
        }
    }

    //用户保存抠图图片
    public function addImagesSelf($user_id, $path)
    {
        $model = new FileModel();
        $add_data = [
            'author'   => $user_id,
            'path'     => $path,
            'createtime'=> date("Y-m-d H:i:s"),
            "type"      =>'png',
            "is_cutout" =>1,
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
}