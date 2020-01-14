<?php


namespace app\statistical\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TaskDrawModel;

class StatisticalController extends AdminBaseController
{

    public function index(){

    }

    //根据传来的用户，查询绘制的任务, $num =1 时查询未编写， =2是查询已提交  =3 时查询已通过；
    public function getTask($user_id,$num){
        $model = new TaskDrawModel();
        $data = $model ->getTask($user_id,$num);
        return $data;
    }



}