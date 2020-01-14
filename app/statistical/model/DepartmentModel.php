<?php


namespace app\statistical\model;


use app\webapi\model\UserOrderModer;
use think\Model;

class DepartmentModel extends Model
{

    //获取所有部门名称
    public function findAllDepartment()
    {
        $model = new DepartmentModel();
        $model_field = "
                   id,dep_name
        ";
        $data = $model ->select()->toArray();
        return $data;
    }


    //获取部门消费统计信息列表
    public function getDepartmentList($where = '')
    {
        $model = new DepartmentModel();
        $model_order = new UserOrderModer();
        $model_field = "
                   id, dep_name, integral 
        ";
        if ($where){
            $data = $model ->field($model_field)->order('integral')->paginate(20);
            $page = $data->render();
            $data = $data->toArray();
            foreach ($data['data'] as $k =>$v){
                $data['data'][$k]['cost'] = 0;
                $data['data'][$k]['num'] = 0;
                $where['department_id'] = ["eq",$v['id']];
                $tmp = $model_order->where($where)->field('SUM(price)')->select()->toArray();
                $num = $model_order->where($where)->field('id')->count();
                if( $tmp){
                    $data['data'][$k]['num']=$num;
                }
                if ($num){
                    $data['data'][$k]['cost']=$tmp[0]['SUM(price)'];
                }
            }
            $res = [
                'data'=> $data,
                'page'=>$page,
            ];
            return $res;
        }else{
            $data = $model ->field($model_field)->order('integral')->paginate(20);
            $page = $data->render();
            $data = $data->toArray();
            //获取消费的积分
            foreach ($data['data'] as $k =>$v){
                $data['data'][$k]['cost'] = 0;
                $data['data'][$k]['num'] = 0;
                $where['department_id'] = ["eq",$v['id']];
                $tmp = $model_order->where($where)->field('SUM(price)')->select()->toArray();
                $num = $model_order->where($where)->field('id')->count();
                if( $tmp){
                    $data['data'][$k]['cost']=$tmp[0]['SUM(price)'];
                    $data['data'][$k]['num']=$num;
                }
            }
            $res = [
                'data'=> $data,
                'page'=>$page,
            ];
            return $res;
        }

    }


    //获取部门整体的打印信息
    public function departmentOut($where = '')
    {
        $model = new DepartmentModel();
        $model_order = new UserOrderModer();
        $model_field = "
                   id, dep_name, integral 
        ";
            $by_integral = 0;
            $num = 0;
            $data = $model ->field($model_field)->order('integral') ->select()->toArray();
            if ($where){
                $order = $model_order->where($where)->select()->toArray();
            }else{
                $order = $model_order->select()->toArray();
            }
            foreach ($data as $k=>$v)
            {
                $department_id = $v['id'];
                foreach ($order as $ko =>$vo)
                {
                    if ($vo['department_id'] == $department_id){
                        $num+=1 ;
                        $by_integral=$by_integral+$vo['price'];
                    }
                }
                $data[$k]['by_integral'] = $by_integral ;
                $data[$k]['num'] = $num;
                $by_integral = 0;
                $num = 0;
            }
            return $data;

        }


    //获取单个部门打印信息
    public function departmentSelfOut($department_id,$where = '')
    {
        $model_order = new UserOrderModer();
        $model_where['department_id'] =["eq",$department_id];

        if ($where){
            $data = $model_order->where($model_where)->where($where)->select()->toArray();
        }else{
            $data = $model_order->where($model_where)->select()->toArray();
        }

        return $data;


    }

}