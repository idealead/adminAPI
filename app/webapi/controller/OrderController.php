<?php


namespace app\webapi\controller;


use app\webapi\model\DepartmentModel;
use app\webapi\model\TemplateModel;
use app\webapi\model\UserClientModel;
use app\webapi\model\UserOrderModer;
use cmf\controller\HomeBaseController;

class OrderController extends HomeBaseController
{

    //添加订单
    public function add_order()
    {
       if (request()->isPost()){
           $sel_time = date('Ym');
           $time = date("Y-m-d H:i:s");
           $template_id = input('template_id');
           if(!$template_id) $this->backto( 10001 );
           $user_id = input('user_id');
           if(!$user_id) $this->backto( 10002 );

            $model_template = new TemplateModel();
            $model_user = new UserClientModel();
            $user_tmp = $model_user->orderInfo($user_id);
            $temp = $model_template->orderInfo($template_id);
            if (!$temp){
                $res = [
                    'code'    => '201',
                    'message' => '模板id错误',
                ];
                $res = json_encode($res);
                return $res;
            }
           $department_id = $user_tmp['dep_id'];
           $department_name = $user_tmp['dep_name'];
           $user_name = $user_tmp['user_name'];
           $price = $temp['price'];
           $add_data = [
               'template_id'		=> $template_id,
               'user_id'			=> $user_id,
               'department_id'		=> $department_id,
               'department_name'	=> $department_name,
               'price'	            => $price,
               'user_name'       	=> $user_name,
               'create_time'        => $time,
               'sel_time'           =>$sel_time,
           ];
               $model = new UserOrderModer();
               $model_department = new DepartmentModel();
                $tmp = $model_department ->checkPrice($department_id,$price);
                if($tmp == 201){
                    $res = [
                        'code'    => '203',
                        'message' => '积分不足',
                    ];
                    $res = json_encode($res);
                    return $res;
                }
               $check = $model_department->payIntegral($department_id,$price);
               if ($check !== 1000001){
                   $order_id = $model ->addOrder($add_data);

                   $res = [
                       'code'     => '200',
                       'message'  => '订单添加成功',
                       'order_id' => $order_id,
                       'integral' =>$check,
                   ];
                   $res = json_encode($res);
                   return $res;
               }else{
                   $res = [
                       'code'    => '201',
                       'message' => '扣款失败,积分不足',
                   ];
                   $res = json_encode($res);
                   return $res;
               }

       }else{
           $res = [
               'code'    => '204',
               'message' => '请求方式有误',
           ];
           $res = json_encode($res);
           return $res;
       }

    }

    //查询订单信息（单个）
    public function find_order()
    {
        if(request()->isGet()){
            $model = new UserOrderModer();
            $order_id = input('order_id');
            if(!$order_id) $this->backto( 10001 );
            $res = $model ->findOrder($order_id);

            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式有误',
            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //查询部门全部订单信息
    public function find_order_department()
    {
        if(request()->isGet()){
            $sel_time = input('sel_time');
            $department_id = input('department_id');
            if(!$department_id) $this->backto( 10001 );
            $model = new UserOrderModer();
            $res = $model ->findOrderDepartment($department_id ,$sel_time);

            return $res;
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式有误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

    //查看个人全部订单信息
    public function find_order_self()
    {
        if(request()->isGet()){
            $sel_time = input('sel_time');
            $user_id = input('user_id');
            $model = new UserOrderModer();
            $data = $model ->findOrderSelf($user_id,$sel_time);
            if ($data){
                $res = [
                    'code'    => '200',
                    'message' => '信息查看成功',
                    'data'    => $data,
                ];
                $res = json_encode($res);
                return $res;
            }else{
                $res = [
                    'code'    => '201',
                    'message' => '该用户目前没有订单信息',
                    'data'    => '',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式有误',
            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //下载失败回调
    public function order_failure_callback()
    {
        $order_id = input('order_id');
        $model_order = new UserOrderModer();
        $model_dep   = new DepartmentModel();

        $department = $model_order ->getInformation($order_id);
        $check_dep = $model_dep ->addPrice($department['department_id'],$department['price']);
        if($check_dep == 200){
            $check_order = $model_order ->delOrder($order_id);
            if ($check_order == 200){
                $res = [
                    "code"=>200,
                    "message" =>'回调成功',
                ];
                $res = json_encode($res);
                return $res;
            }else{
                $res = [
                    "code"=>202,
                    "message" =>'回调失败',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                "code"=>201,
                "message" =>'部门获取失败',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

}