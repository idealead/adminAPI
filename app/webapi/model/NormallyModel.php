<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/22
 * Time: 21:25
 */

namespace app\webapi\model;


use think\Model;
use think\Session;

class NormallyModel extends Model
{

    //获取最大抠图数量
    public function getNumber()
    {


        $model = new NormallyModel();
        $res = $model ->get(1)->toArray();
        $number = $res['number'];
        return $number;
    }


}