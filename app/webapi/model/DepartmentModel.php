<?php


namespace app\webapi\model;


use app\webapi\services\ServicesController;
use think\Model;

class DepartmentModel extends Model
{

    //插入部门
    public function addDepartment($data)
    {
        $model = new DepartmentModel();
        $model -> data([
            'id'        =>   $data['dep_id'],
            'dep_name'  =>   $data['dep_name'],
            'dep_cid'  =>   $data['dep_cid'],

        ]);
        $model ->save();
    }

    //根据id返回部门名 ,没有返回10001
    public function findDepartmentName($id)
    {
        $model = new DepartmentModel();
        $where['id'] =["eq",$id];
        $data = $model ->where($where)->select()->toArray();
        if ($data){
            $res = $data['dep_name'];
            return $res;
        }else{
           $res = 10001;
           return $res ;
        }
    }



    //根据部门id,查询到该部门，扣取相应积分
    public function payIntegral($id,$price)
    {
        $model = new DepartmentModel();
        $where['id'] = ["eq",$id];
        $data = $model ->where($where)->find()->toArray();
        $res = $model ->get($id);
        if($data){
            if($data['integral']<$price)
            {
                return 1000001;
            }else{
                $integral = $data['integral'] - $price;
                $res-> integral = $integral;
                $res->save();
                return $integral;
            }

        }else{
            return 1000001;
        }



    }


    //获取部门领导
    public function getDepartmentLeader($department_id)
    {
        $service = new ServicesController();

        $where = ["ep_id" => $department_id];
        $data = $service->get_leader_info_all($where);
        if ($data){
            $leader_id = $data[0]["ep_id"];
            $res = [
                'code'    => '200',
                'message' => '领导获取成功',
                'leader_id'=>$leader_id,
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '没有找到部门领导',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //给部门添加积分
    public function addPrice($id,$price)
    {
        $model = new DepartmentModel();
        $where['id'] = ["eq",$id];
        $data = $model ->where($where)->find()->toArray();
        $res = $model ->get($id);
        if($data){
            if($data['integral']<$price)
            {
                return 201;
            }else{
                $integral = $data['integral'] + $price;
                $res-> integral = $integral;
                $res->save();
                return 200;
            }
        }else{
            return 201;
        }

    }

    //查看部门是否有足够的积分
    public function checkPrice($id,$price)
    {
        $model = new DepartmentModel();
        $where['id'] = ["eq",$id];
        $data = $model ->where($where)->find()->toArray();
        if($data){
            if($data['integral']<$price)
            {
                return 201;
            }else{
                return 200;
            }
        }else{
            return 201;
        }
    }



    public function getDepartmentIntegral($department_id)
    {
//        $model = new DepartmentModel();
//        $where['id'] = ["eq",$department_id];
//        $data = $model ->where($where)->find()->toArray();
//        $integral = $data['integral'];
//        return $integral;
        $model = new DepartmentModel();
        $where['id'] = ["eq",$department_id];
        //$data = $model ->where($where)->find()->toArray();
        $data = $model ->where($where)->find();
        if ($data){
            $data = $model ->where($where)->find()->toArray();
            $integral = $data['integral'];
            return $integral;
        }else{
            $data['integral']=0;
            $integral = $data['integral'];
            return $integral;
        }

    }
}