<?php


namespace app\statistical\controller;

use cmf\controller\AdminBaseController;
use app\statistical\model\DrawStatisticalModel;




class DrawStatisticalController extends AdminBaseController
{


    public function index(){

       $keyword_user_id = input('keyword_user_id');
       $keyword_user_name = input('keyword_user_name');
       $model = new DrawStatisticalModel();
       $up_data=$model ->allStatistical();
       $data = $model->getSelfInformation($keyword_user_id,$keyword_user_name);


        $this->assign('data', $data);
        $this->assign('up_data', $up_data);
        return $this -> fetch();
    }

}