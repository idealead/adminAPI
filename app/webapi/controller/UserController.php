<?php


namespace app\webapi\controller;


use cmf\controller\HomeBaseController;
use app\webapi\model\UserClientModel;



class UserController extends HomeBaseController
{

    //获取用户信息根据id
    public function get_user_information()
    {
        if (request()->isGet()){

            $model = new UserClientModel();
            $user_id = input('user_id');
            if(!$user_id) $this->backto( 10001 );
            $res = $model ->getUserInfo($user_id);
            return $res;
        }else{
            $res=[
                'code'=>201,
                'message'=>'请求方式错误',
            ];
            $res = json_encode($res);
            return $res;
        }
    }


    //获取用户信息（增加部门剩余积分，部门消费积分）
    public function get_dep_user()
    {
        $user_id = input('user_id');
        $model = new UserClientModel();
        $res = $model ->getUserInfoSelf($user_id);
        return $res;
    }

}