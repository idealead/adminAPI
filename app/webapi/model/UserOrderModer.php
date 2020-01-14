<?php


namespace app\webapi\model;

use think\Model;

class UserOrderModer extends Model
{

    // 插入订单表信息（目前默认完成）
    public function addOrder($data)
    {
        $model = new UserOrderModer();
        $order_id = $model->insertGetId($data);
        return $order_id;
    }

    //根据id查看订单信息
    public function findOrder($id)
    {
        $model = new UserOrderModer();
        $where['f.id'] = ["eq",$id];
        $model_field = "
                    f.id, f.user_name, f.template_id, f.department_name, f.price, f.is_complete, path, f.create_time
        ";

        $data = $model ->where($where)
            ->alias("f")
            ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
            ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
            ->field($model_field)
            ->select()->toArray();

        if ($data){
            $res = [
                'code'    => '200',
                'message' => '访问成功',
                'data'    => $data
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有数据',
                'data'    => '',
            ];
            $res = json_encode($res);
            return $res;
        }
    }

    //根据部门id，查看整个部门的订单信息
    public function findOrderDepartment($department_id ,$sel_time)
    {
        $model = new UserOrderModer();
        $where['f.department_id'] =["eq",$department_id];
        if ($sel_time){
            $where['f.sel_time'] = ["eq",$sel_time];
        }
        $model_field = "
                    f.id, f.user_name, f.template_id, f.department_name, f.price, f.is_complete, path
        ";

        $data = $model ->where($where)
            ->alias("f")
            ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
            ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
            ->field($model_field)
            ->select()->toArray();
        if ($data){
            $res = [
                'code'    => '200',
                'message' => '访问成功',
                'data'    => $data
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => '目前没有任何记录',
                'data'    => '',
            ];
            $res = json_encode($res);
            return $res;
        }

    }

    //根据个人id，查看个人所有订单
    public function findOrderSelf($user_id,$sel_time='')
    {
        $model = new UserOrderModer();
        if ($sel_time){
            $where['f.sel_time'] = ["eq",$sel_time];
        }
        $where['f.user_id'] = ["eq",$user_id];
        $model_field = "
                    f.id, f.template_id, f.price, f.is_complete, path,f.create_time
        ";

        $data = $model
            ->alias("f")
            ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
            ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
            ->field($model_field)
            ->where($where)
            ->select()->toArray();

        return $data;
    }

    //根据订单id 查询部门和price
    public function getInformation($order_id)
    {
        $model = new UserOrderModer();
        $where['id'] =["eq",$order_id];
        $model_field = "
                    template_id, price
        ";
        $data = $model ->where($where)->field($model_field)->find()->toArray();

        if ($data){
            return $data;
        }else{
            return 201;
        }

    }

    //删除订单
    public function delOrder($order_id)
    {
        $model = new UserOrderModer();
        $where['id'] =["eq",$order_id];
        $model ->where($where) ->delete();
        return 200;
    }

    //查询部门共计消费的积分
    public function findDepartmentPrice($department_id)
    {
        $model = new UserOrderModer();
        $where['department_id'] = ["eq",$department_id];
        $data = $model->where($where)->select();
        $sum = '';
        foreach ($data as $k=>$v){
            $sum +=$v['price'];
        }
        return $sum;
    }

}