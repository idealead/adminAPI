<?php


namespace app\task\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TempUserModel;

class TaskLabelController extends AdminBaseController
{

    public function index()
    {
        $model = new TempUserModel();
        $res =$model ->labelReviewer();
        dump($res);
        die;
    }
}