<?php


namespace app\statistical\controller;


use app\statistical\model\DepartmentModel;
use app\statistical\model\UserOrderModel;
use cmf\controller\AdminBaseController;
use app\statistical\controller\EquipmentListController;

class DepartmentStatisticalController extends AdminBaseController
{

    //部门列表展示
    public function index()
    {
        if(request()->isPost()){

            //根据时间选择
            $this->assign('start_time', input('start_time'));
            $this->assign('end_time', input('end_time'));
            if(input('start_time') && input('end_time')){
                $where['create_time'] = ["between", [ input('start_time'),input('end_time') ] ];
            }else if(input('start_time')){
                $where['create_time'] = ["egt",input('start_time')];
            }else if(input('end_time')){
                $where['create_time'] = ["elt",input('end_time')];
            }
            $model_department = new DepartmentModel();
            $res = $model_department ->getDepartmentList($where);
            $data = $res['data'];
            $page = $res['page'];
            $this->assign('data', $data);
            $this->assign('page', $page);
            return $this->fetch( );
        }else{
            $model_department = new DepartmentModel();
            $res = $model_department ->getDepartmentList();
            $data = $res['data'];
            $page = $res['page'];
            $this->assign('data', $data);
            $this->assign('page', $page);
            return $this->fetch( );
        }
    }


    //部门订单详细
    public function detailed()
    {
        // 时间
        if(request()->port()){

            $this->assign('start_time', input('start_time'));
            $this->assign('end_time', input('end_time'));
            if(input('start_time') && input('end_time')){
                $where['f.create_time'] = ["between", [ input('start_time'),input('end_time') ] ];
            }else if(input('start_time')){
                $where['f.create_time'] = ["egt",input('start_time')];
            }else if(input('end_time')){
                $where['f.create_time'] = ["elt",input('end_time')];
            }
        }

        $department_id = input('id');
        $this->assign('department_id', $department_id);
        $where['department_id'] = ['eq',$department_id];
        $model = new UserOrderModel();
        $data = $model->getDepartmentOrder($where);
        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this->fetch( );



    }


    //departmentOut部门打印
    public function departmentOut()
    {
        $where=[];
        if(input('start_time') && input('end_time')){
            $where['create_time'] = ["between", [ input('start_time'),input('end_time') ] ];
        }else if(input('start_time')){
            $where['create_time'] = ["egt",input('start_time')];
        }else if(input('end_time')){
            $where['create_time'] = ["elt",input('end_time')];
        }

        $con = new EquipmentListController();
        $model = new DepartmentModel();
        if ($where){
            $data = $model -> departmentOut($where);
        }else{
            $data = $model -> departmentOut();
        }
        $con ->departmentOut($data);


    }


    //单个部门详细打印
    public function departmentSelfOut()
    {
        $where=[];
        $department_id = input('department_id');
        if(input('start_time') && input('end_time')){
            $where['create_time'] = ["between", [ input('start_time'),input('end_time') ] ];
        }else if(input('start_time')){
            $where['create_time'] = ["egt",input('start_time')];
        }else if(input('end_time')){
            $where['create_time'] = ["elt",input('end_time')];
        }

        $con = new EquipmentListController();
        $model = new DepartmentModel();
        if ($where){
            $data = $model -> departmentSelfOut($department_id,$where);
        }else{
            $data = $model -> departmentSelfOut($department_id);
        }
        if ($data){
            $con ->getDepartmentSelfOut($data);
        }else{
            $this->error('没有数据不能打印', url('DepartmentStatistical/index'));
        }
    }

}