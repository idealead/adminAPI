<?php


namespace app\webapi\model;


use think\Model;

class CutoutNumberModel extends Model
{

    //插入调用情况数据
    public function callStatus($user_id,$status)
    {
        $time = date("Y-m-d H:i:s");
        $tim = date("Ymd");
        $model = new CutoutNumberModel();
        $data = [
          'user_id'=>$user_id,
          'create_time'=>$time,
          'status' =>$status,
          'sel_time'=>$tim,
        ];
        $model ->insertGetId($data);
    }


}