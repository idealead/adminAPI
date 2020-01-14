<?php


namespace app\copywriting\model;

use think\Model;


class LabelCwModel extends Model
{

    //增加标签信息
    public function addLabel($label_name)
    {

        $time= date("Y-m-d H:i:s");
        $model = new LabelCwModel();
        $model -> data([
            'label_name'             =>   $label_name,
            'create_time'            =>   $time,
        ]);
        $model->save();
        $res = [
            "code" => 200,
            'message' => "位置名称保存成功",
        ];

        return $res;

    }


    //删除标签信息
    public function delLabel($id)
    {
        $model = new LabelCwModel();
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
        $model = new LabelCwModel();
        $data = $model ->get($id);
        return $data;
    }


    //修改标签信息
    public function editLabel($id ,$label_name)
    {
        $model = new LabelCwModel();
        $res = $model->get($id);
        $res ->  label_name  = $label_name;
        $res ->  save();
        $res = [
            "code" => 200,
            'message' => "位置名称修改成功",
        ];

        return $res;
    }


    //根据关键词，返回数据
    public function getLabelName($name = '')
    {

        $model = new LabelCwModel();
        $model_where_status['is_delete'] = ["eq", 1];
        $model_where_name['label_name'] = ['like', "%" . $name . "%"];
        if ($name) {

            $model_field = "id,label_name ,create_time ";
            $data = $model->where($model_where_name)
                ->field($model_field)
                ->paginate(10);
        }else{
            $data = $model -> where($model_where_status)->paginate(10);
        }
        return $data;
    }


    //便利出所有标签id和名字
    public function getLabelList ()
    {
        $model = new LabelCwModel();
        $model_where['is_delete'] = ["eq",1];
        $model_field = "
            id, label_name
        ";
        $data = $model ->where($model_where)->field($model_field)->select();
        return $data;
    }


}