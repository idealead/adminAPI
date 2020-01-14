<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/26
 * Time: 10:34
 */

namespace app\feedback\model;


use think\Model;

class FeedbackModel extends Model
{
    //获取列表数据
    public function getList($where)
    {
        $model = new FeedbackModel();

        $model_field = "
                f.id , fc.class_name , f.content,f.create_time, u.user_name,dep_name
            ";
        $data = $model ->field($model_field)
                    ->alias('f')
            ->join(['in_feedback_class'=>'fc'] , 'f.feedback_class = fc.id ' , 'LEFT')
            ->join(['in_user_client'=>'u'] , 'f.user_id = u.id' , 'LEFT')
            ->where($where)
            ->order('create_time', 'desc')
            ->select()->toArray();

             return $data;

    }


    //获取总数量
    public function getNum()
    {
        $model = new FeedbackModel();
        $model_field = "
                count(id) as num
            ";
        $num = $model ->field($model_field)
                    ->select()->toArray();
        if ($num){
            $data = $num[0]['num'];
            return $data;
        }

    }
}