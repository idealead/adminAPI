<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/17
 * Time: 23:06
 */

namespace app\webapi\model;


use think\Model;

class LabelClassModel extends Model
{

    //获取标签类别
    public function getClass()
    {
        $model = new LabelClassModel();
        $model_field = "
                    id, path , answer_t,select  
        ";
        $where['is_delete'] = ["eq",1];
        $data = $model ->where($where)->field($model_field)->select()->toArray();
        return $data;
    }



    //发现所有图片路径
    public function findAllPath(){
        $model = new LabelClassModel();
        $model_field = "
                    path
        ";
        $data = $model ->field($model_field)->select()->toArray();
        return $data;

    }

}