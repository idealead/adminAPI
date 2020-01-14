<?php


namespace app\configuration\model;


use think\Db;
use think\Model;

class CutoutNumberModel extends Model
{


    //获取全部数据进行显示
    public function getCutoutList()
    {
        $model = new CutoutNumberModel();
        $model_field = "
                    c.user_id ,u.user_name,count(c.user_id) as num
        ";
        $data = $model ->group('c.user_id')
            ->alias("c")
            ->join(['in_user_client'=>'u'] , 'c.user_id = u.id' , 'LEFT')
            ->field($model_field)
            ->select()->toArray();






        dump($data);
        die;
    }
}