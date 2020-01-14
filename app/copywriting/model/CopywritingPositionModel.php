<?php


namespace app\copywriting\model;

use think\Model;

class CopywritingPositionModel extends Model
{

    //增加文本位置信息
    public function addPosition($position_name)
    {

        $model = new CopywritingPositionModel();
        $model -> data([
            'position_name'             =>   $position_name,
        ]);
        $model->save();
        $res = [
            "code" => 200,
            'message' => "位置名称保存成功",
        ];

        return $res;

    }


    //删除文本位置信息
    public function delPosition($id)
    {
        $model = new CopywritingPositionModel();
        $res = $model->get($id);
        $res ->  is_delete  = 0;
        $res ->  save();
        $res = [
            "code" => 200,
            'message' => "位置名称删除成功",
        ];

        return $res;
    }


    //根据id查找出修改的信息
    public function findEdit($id)
    {
        $model = new CopywritingPositionModel();
        $data = $model ->get($id);
        return $data;
    }


    //修改文本位置信息
    public function editPosition($id ,$position_name)
    {


        $model = new CopywritingPositionModel();
        $res = $model->get($id);
        $res ->  position_name  = $position_name;
        $res ->  save();
        $res = [
            "code" => 200,
            'message' => "位置名称修改成功",
        ];

        return $res;
    }


    //根据关键词，返回数据
    public function getPotionsName($name = '')
    {

        $model = new CopywritingPositionModel();
        $model_where_status['is_delete'] = ["eq", 1];
        $model_where_name['position_name'] = ['like', "%" . $name . "%"];
        if ($name) {

            $model_field = "id,position_name ";
            $data = $model->where($model_where_name)
                ->field($model_field)
                ->paginate(10);
        }else{
            $data = $model -> where($model_where_status)->paginate(10);
        }
        return $data;
    }

    //获取所有id 和位置名称
    public function getPositionList()
    {
        $model = new CopywritingPositionModel();
        $model_where['is_delete'] = ["eq",1];
        $model_field = "
            id, position_name
        ";
        $data = $model ->where($model_where) ->field($model_field)->select();
        return $data;
    }

}