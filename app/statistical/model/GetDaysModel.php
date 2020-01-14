<?php

namespace app\statistical\model;

use think\Model;

class GetDaysModel extends Model
{
    //通过data方法获取当月的天数
    public function getDays()
    {
        $tmp_time = date('t');

        return $tmp_time;
    }


    //获取上个月天数
    public function getLastMonthDays()
    {
        $tmp_time = date('t',strtotime('-1 month'));
        return $tmp_time;
    }


    //


}