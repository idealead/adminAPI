<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/24
 * Time: 14:49
 */

namespace app\statistical\model;


use think\Model;

class CutoutNumberModel extends Model
{

    //获取全部数据进行显示>>按照每个人分组，统计调用次数
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

       return $data;
    }



    //查看个人抠图详细
    public function  detailedCutoutList($user_id)
    {
        $model = new CutoutNumberModel();
        $model_field = "
                    c.user_id ,u.user_name,c.create_time,c.status
        ";
        $where['user_id'] = ["eq",$user_id];
        $data = $model
            ->alias("c")
            ->join(['in_user_client'=>'u'] , 'c.user_id = u.id' , 'LEFT')
            ->where($where)
            ->field($model_field)
            ->order('create_time', 'desc')
            ->select()->toArray();

        return $data;
    }

}