<?php


namespace app\webapi\controller;


use app\webapi\model\DepartmentModel;
use app\webapi\services\ServicesController;
use cmf\controller\HomeBaseController;

class DepartmentController extends HomeBaseController
{

    public function addDepartment()
    {
        $model = new DepartmentModel();
        $services = new ServicesController();
        $data = $services ->get_department();
        foreach ($data as $k =>$v){
            $model ->addDepartment($v);
        }
        dump(1111);
        die;
    }


}