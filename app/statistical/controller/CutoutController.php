<?php
/**
 * Created by PhpStorm.
 * User: 15161
 * Date: 2019/12/24
 * Time: 14:47
 */

namespace app\statistical\controller;


use app\statistical\model\CutoutNumberModel;
use cmf\controller\AdminBaseController;

class CutoutController extends AdminBaseController
{
    //获取和人总次数列表
    public function index()
    {
        $model = new CutoutNumberModel();
        $data = $model -> getCutoutList();
        $this->assign('data', $data);
        return $this->fetch();

    }


    //获取个人详细
    public function detailed()
    {
        $user_id = input('user_id');
        $model = new CutoutNumberModel();
        $data = $model ->  detailedCutoutList($user_id);
        $this->assign('data', $data);
        return $this->fetch();
    }


}