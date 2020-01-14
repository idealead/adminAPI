<?php


namespace app\statistical\controller;

use cmf\controller\AdminBaseController;
use app\statistical\model\AuditStatisticalModel;

class AuditStatisticalController extends AdminBaseController
{

    public function index(){
        $keyword_user_id = input('keyword_user_id');
        $keyword_user_name = input('keyword_user_name');
        $model = new AuditStatisticalModel();
        $data = $model-> getSelfInformation($keyword_user_id,$keyword_user_name);
        $up_data = $model ->allStatistical();

        $this->assign('up_data', $up_data);
        $this->assign('data', $data);

        return $this->fetch();
    }
}