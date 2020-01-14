<?php


namespace app\configuration\model;


use think\Model;

class NormallyModel extends Model
{

    //获取数据
    public function getList()
    {
        $model = new NormallyModel();
        $data = $model ->select();
        return $data;
    }


    //根据id获取数据
    public function getContent($id)
    {
        $model = new NormallyModel();
        $data = $model->find($id);
        return $data;
    }


    //修改抠图number
    public function updateNumber($id,$number)
    {
        $model = new NormallyModel();

        $data = $model ->get($id);
        $data-> number = $number;
        $data->save();

    }


}