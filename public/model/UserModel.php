<?php


namespace app\api\model;

use think\Model;

class UserModel extends Model
{
    //根据user_id查询 用户数据
    public function findUserInfo($user_id)
    {
        if($user_id){
            $model = new UserModel();
            $model_where['u.id'] = ["eq",$user_id];

            $model_field = "
            u.id , u.user_nickname , u.user_email, u.avatar, u.signature, u.mobile, u.user_name, ru.role_id, r.name,us.sex_name
        ";
            $data = $model
                ->alias("u")
                ->join(['in_role_user' => 'ru'], 'u.id = ru.user_id ', 'LEFT')
                ->join(['in_role' => 'r'], 'ru.role_id = r.id', 'LEFT')
                ->join(['in_user_sex' => 'us'], 'us.id = u.sex', 'LEFT')
                ->where($model_where)
                ->field($model_field)
                ->select();
            return $data;
        }else{
            $res = [
                'code'    => '201',
                'message' => "用户id不存在"
            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //用户修改密码
    public function editUserPassword($user_id,$user_pass)
    {
       $pass = cmf_password($user_pass);
       $model = new UserModel();

        $res = $model->get($user_id);
        $res-> user_pass  = $pass;
        $res->save();
        $ret = [
            'code'    => '200',
            'message' => '密码修改成功',
        ];
        $ret = json_encode($ret);
        return $ret;
    }


    //更改用户信息
    public function editUserInfo($data)
    {
        $user_id = $data['id'];
        $model = new UserModel();
        $res = $model->get($user_id);
        $res->  user_nickname  = $data['user_nickname'];
        $res->  user_email     = $data['user_email'];
        $res-> signature       = $data['signature'];
        $res-> avatar          = $data['avatar'];
        $res-> mobile          = $data['mobile'];
        $res-> user_name       = $data['user_name'];
        $res-> sex             = $data['sex'];
        $res->save();
        $ret = [
            'code'    => '200',
            'message' => '信息修改成功',
        ];
        $ret = json_encode($ret);
        return $ret;




    }


    //用户登录
    public function userLogin($user_login,$user_pass)
    {
        $user_pass = cmf_password($user_pass);
        $model = new UserModel();
        $model_where['user_login'] =["eq",$user_login];
        $data = $model->where($model_where)
                      ->find();
        if($data){
            if($data['user_pass'] == $user_pass ){
                 $id = $data['id'];
                 $res = [
                     'code'    => '200',
                     'message' => '登录成功',
                     'id'      =>$id,
                 ];
                 $res = json_encode($res);
                 return $res;
              }else{
                 $res = [
                     'code'    => '401',
                     'message' => '密码错误'
                 ];
                 $res = json_encode($res);
                 return $res;
             }
        }else{
           $res = [
               'code'    => '201',
               'message' => '用户名不存在'
           ];
           $res = json_encode($res);
           return $res;
        }

    }
}