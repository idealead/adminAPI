<?php


namespace app\api\controller;

use cmf\controller\HomeBaseController;
use app\api\model\UserModel;

class UserController extends HomeBaseController
{

    //前端：根据user_id查询用户信息并返回
    public function find_user_info( )
    {
        $user_id = input('user_id');
        $model = new UserModel();
        $data  = $model ->findUserInfo($user_id);
        $data  = json_encode($data);
        return $data;
    }

    //前端：用户更改个人信息
    public function edit_user_info()
    {
        $data = input();
        $data = json_decode($data);
        $model = new UserModel();
        $res = $model->editUserInfo($data);
        $res = json_encode($res);
        return $res;
    }

    //前端：用户修改密码
    public function edit_user_password()
    {
        $user_pass = input('password');
        $model = new UserModel();
        $res= $model->editUserPassword($user_pass);
        $res = json_encode($res);
        return $res;
    }

    //前端：用户登录
    public function user_login(){
        $user_login = input('user_login');
        $user_pass  = input('user_pass');
        $model = new UserModel();
        $res = $model->userLogin($user_login,$user_pass);
        return $res;
    }


}