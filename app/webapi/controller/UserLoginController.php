<?php


namespace app\webapi\controller;

use app\webapi\model\DepartmentModel;
use app\webapi\model\LogUserModel;
use app\webapi\model\UserClientModel;
use app\webapi\services\ServicesController;
use cmf\controller\HomeBaseController;
use think\Db;

class UserLoginController extends HomeBaseController
{
    public function _initialize() {
     parent::_initialize();
    header('Access-Control-Allow-Methods:POST,GET');
    header("Access-Control-Allow-Origin: *");
    header('Content-Type:application/json; charset=utf-8');
}


    //登录（调取单位员工系统验证是否登录成功）
    public function user_login()
    {


        if (request()->isPost()){
            $service = new ServicesController();
            $model_dep = new DepartmentModel();
            $email = input('email');
            $password =input('password');
            $id = $service ->login($email,$password);
            $where = ["ep_id" => $id];
            if($id){
                $data  = $service ->get_user_by_field($where);
                $model = new UserClientModel();
                $data  = $model ->addUser($data);
                $leader_id = '';
                $new_data = [];
                foreach ($data as $k =>$v){
                    $new_data = $v;
                    $department_id = $v["dep_id"];
                    $integral = $model_dep ->getDepartmentIntegral($department_id);
                    if ($integral == 0){
                        $res = [
                            'code'    => '207',
                            'message' => '该部门不参与测试',
                        ];
                        $res = json_encode($res);
                        return $res;
                    }
                    $leader_id = $model_dep ->getDepartmentLeader($department_id);
                    $new_data['integral'] = $integral;
                }
                if ($leader_id == $new_data['id'])
                {
                    $new_data['is_leader'] = 1;
                }else{
                    $new_data['is_leader'] = 0;
                }
               // $token = $model->getToken();
                $model_log = new LogUserModel();
                $model_log ->addLog($new_data['id']);
                $res = [
                    'code'    => '200',
                    'message' => '登录成功',
                    'data'    => $new_data,
                ];

                $res = json_encode($res);
                return $res;

            }else{
                $res = [
                    'code'    => '201',
                    'message' => '登录失败，用户名或密码有误',
                ];
                $res = json_encode($res);
                return $res;
            }
        }else{
            $res = [
                'code'    => '204',
                'message' => '请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }

    }



}