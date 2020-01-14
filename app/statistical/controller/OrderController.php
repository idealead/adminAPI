<?php


namespace app\statistical\controller;

use app\statistical\model\DepartmentModel;
use app\statistical\model\UserOrderModel;
use cmf\controller\AdminBaseController;

class OrderController extends AdminBaseController
{


    //订单列表展示
    public function index()
    {
        if(request()->isPost()){
            // 时间
            $this->assign('start_time', input('start_time'));
            $this->assign('end_time', input('end_time'));
            if(input('start_time') && input('end_time')){
                $where['f.create_time'] = ["between", [ input('start_time'),input('end_time') ] ];
            }else if(input('start_time')){
                $where['f.create_time'] = ["egt",input('start_time')];
            }else if(input('end_time')){
                $where['f.create_time'] = ["elt",input('end_time')];
            }

            //员工名称
            $keyword = input("keyword");
            $this->assign('keyword',$keyword);
            if($keyword){
                $where['f.user_name'] = ['like',"%".$keyword."%"];
            }

            //部门编号
            $department_id = input("department");
            if($department_id){
                $where['f.department_id'] = ['eq',$department_id];
                $this->assign('department_id', $department_id);
            }

            $model_department = new DepartmentModel();
            $data_dep = $model_department->findAllDepartment();
            $this->assign('data_dep', $data_dep);
            $model_order = new UserOrderModel();
            $data = $model_order ->findAllOrder($where);
            $this->assign('data', $data);
            $this->assign('page', $data->render());
            return $this->fetch();

        }else{
            $model_department = new DepartmentModel();
            $data_dep = $model_department->findAllDepartment();
            $this->assign('data_dep', $data_dep);
            $model_order = new UserOrderModel();
            $data = $model_order ->findAllOrder();
            $this->assign('data', $data);
            $this->assign('page', $data->render());
            return $this->fetch();
        }
    }
}