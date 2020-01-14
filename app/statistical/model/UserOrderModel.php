<?php


namespace app\statistical\model;


use think\Model;

class UserOrderModel extends Model
{

    //查询所有订单，拼接路径
    public function findAllOrder($where = '')
    {
        $model = new UserOrderModel();
        $model_field = "
                    f.id,f.user_id, f.user_name,f.template_id,f.department_id,f.department_name, f.price, f.is_complete, path,file.type,
                    f.create_time
        ";
        if($where){
            $data = $model
                ->alias("f")
                ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
                ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
                ->field($model_field)
                ->where($where)
                ->paginate(15);
                 return $data;
        }else{
            $data = $model
                ->alias("f")
                ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
                ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
                ->field($model_field)
                ->paginate(15);
                  return $data;
        }


    }


    //根据时间，部门id查询订单数量
    public function getDepartmentOrder($where)
    {
        $model = new UserOrderModel();
        $model_field = "
                    f.id,f.user_id, f.user_name,f.template_id,f.department_id,f.department_name, f.price, f.is_complete, path,file.type,
                    f.create_time
        ";
            $data = $model
                ->alias("f")
                ->join(['in_template'=>'t'] , 'f.template_id = t.id' , 'LEFT')
                ->join(['in_file'=>'file'] , 't.preview = file.id' , 'LEFT')
                ->field($model_field)
                ->where($where)
                ->paginate(15);


            return $data;



    }

}