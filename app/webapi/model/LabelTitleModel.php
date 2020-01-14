<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/17
 * Time: 23:20
 */

namespace app\webapi\model;


use think\Model;

class LabelTitleModel extends Model
{

    //获取对应问题
    public function getTitle($label_class_id)
    {
        $model = new LabelTitleModel();
        $model_field = "
                    id,label_title_name , label_answer_id , sort
        ";
        $where['is_delete'] = ["eq",1];
        $where['label_class_id'] = ['eq',$label_class_id];
        $data = $model ->where($where)->field($model_field)->select()->toArray();
        return $data;
    }



    //获取第三题人群
    public function getPerson()
    {
        $model = new LabelTitleModel();
        $model_field = "
                    id,label_title_name , label_answer_id , sort
        ";
        $where['is_delete'] = ["eq",1];
        $where['label_class_id'] = ['eq',10000];
        $data = $model ->where($where)->field($model_field)->select()->toArray();
        return $data;
    }
}