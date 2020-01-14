<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/17
 * Time: 23:34
 */

namespace app\webapi\model;


use think\Model;

class LabelAnswerModel extends Model
{

    //查询出所有answer
    public function getAllAnswer()
    {
        $model = new LabelAnswerModel();
        $where['is_delete'] = ["eq",1];
        $model_field = "
                    id, answer_t, path, select
        ";
        $data = $model ->field($model_field)->where($where)->select()->toArray();
        return $data;
    }


    //发现所有图片路径
    public function findAllPath(){
        $model = new LabelAnswerModel();
        $model_field = "
                    path
        ";
        $data = $model ->field($model_field)->select()->toArray();

        return $data;
    }
}