<?php


namespace app\webapi\model;


use think\Model;

class UserClientModel extends Model
{

    //根据传入的信息注册用户
    public function addUser($data)
    {
        $time= date("Y-m-d H:i:s");
        $id =  $data['ep_id'];
        $model = new UserClientModel();
        $check = $model ->checkUser($id);
        if($check){
            return $check;
        }else{
            $model -> data([
                'id'        =>   $data['ep_id'],
                'dep_id'    =>   $data['dep_id'],
                'dep_name'  =>   $data['dep_name'],
                'sn'        =>   $data['sn'],
                'user_name' =>   $data['ep_name'],
                'create_time' => $time,
                'sex'        =>$data['sex']
            ]);
            $res = [
                'id'        =>   $data['ep_id'],
                'dep_id'    =>   $data['dep_id'],
                'dep_name'  =>   $data['dep_name'],
                'sn'        =>   $data['sn'],
                'user_name' =>   $data['ep_name'],
                'create_time' => $time,
                'sex'        =>$data['sex']
            ];
            $model->save();
            return $res ;
        }



    }

    //查询，查看是否存在用户
    public function checkUser($id)
    {
        $model = new UserClientModel();
        $where['id'] = ['eq',$id];
        $res = $model ->where($where)->select()->toArray();
        return $res;
    }

    
    public function getToken()
    {
        $date = date("YmdHis");
        $code = rand(1000000, 9999999);
        $str = $date.$code;
        $token = md5($str);
        return $token;
    }


    //根据user_id查询全部用户信息
    public function getUserInfo($user_id)
    {
        $model = new UserClientModel();
        $where['id'] = ['eq',$user_id];
        $data = $model ->where($where)->select()->toArray();
        if($data) {
            $res=[
                'code'=>200,
                'message'=>'用户信息请求成功',
                'data' =>$data[0],
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res=[
                'code'=>202,
                'message'=>'用户不存在',
            ];
            $res = json_encode($res);
            return $res;
        }
        return $res;
    }

    //查询订单用户信息
    public function orderInfo($user_id)
    {
         $model = new UserClientModel();
        $model_field = "
                    id, dep_id, dep_name, user_name
        ";
        $where['id'] = ["eq",$user_id];
        $data = $model
            ->where($where)
            ->field($model_field)
            ->find();
        if ($data){
            $data = $data ->toArray();
            return $data;
        }else{
            return $data;
        }
    }

    //查询用户信息（增加部门剩余积分，部门消费积分）
    public function getUserInfoSelf($user_id)
    {
        $model = new UserClientModel();
        $model_order = new UserOrderModer();
        $where['c.id'] = ['eq',$user_id];
        $model_field = "
                    c.id, c.dep_id, c.dep_name, c.user_name,sex,d.integral
        ";
        $data = $model
            ->alias("c")
            ->join(['in_department'=>'d'] , 'c.dep_id = d.id' , 'LEFT')
            ->field($model_field)
            ->where($where)
            ->select()->toArray();
        if ($data){
            $dep_id = $data[0]['dep_id'];
            $sum = $model_order ->findDepartmentPrice($dep_id);
            $data[0]['pay_integral'] = $sum;

            $res=[
                'code'=>200,
                'message'=>'用户信息请求成功',
                'data' =>$data[0],
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res=[
                'code'=>202,
                'message'=>'用户不存在',
            ];
            $res = json_encode($res);
            return $res;
        }
    }

}