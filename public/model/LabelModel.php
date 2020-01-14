<?php


namespace app\api\model;

use think\Model;

class LabelModel extends Model
{

    //根据传来的标签字符串，便利出标签标签键值对，返回
    public function queryLabelName($label)
    {
        $label =explode(",",$label);
        $new_arr = [];
        $num = count($label);
        $model = new LabelModel();
        for($i=0;$i<$num;$i++){
            $id = $label[$i];
            $model_where['id'] = ["eq",$id];
            $model_field=" label_name ";
            $res = $model->where($model_where)
                         ->field($model_field)
                         ->find();
            $res = json_decode($res);

            foreach ($res as $k => $v){
                array_push($new_arr,[$id,$v]);
            }
        }
        return $new_arr;

    }

}