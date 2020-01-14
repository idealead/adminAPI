<?php


namespace app\task\model;

use think\Model;
use think\Db;
use app\admin\model\UserModel;

class TaskDrawModel extends Model
{
    //列表展示
    public function getList($keyword_user_name = '',$keyword_task_name = '')
    {
        $model = new TaskDrawModel();
        $model_where['t.is_delete'] = ["eq",1];
        $model_field = "
            t.id, c.task_name , u.user_name, t.status, t.user_id,t.task_category_id
        ";
        $new_arr = [];
        // 查询模版

        if ($keyword_user_name){
            $user_model = new UserModel();
            $model_user_field = "id ";
            $model_where_user['user_name'] =['like',"%".$keyword_user_name."%"];
            $data = $user_model->where($model_where_user)
                ->field($model_user_field)
                ->select();
            foreach ($data as $k =>$v){
                $new_arr[] = $v['id'];
            }
            $data = $model ->where(['t.user_id'=>['in',$new_arr]])
                ->alias("t")
                ->join(['in_task_category'=>'c'] , 't.task_category_id = c.id' , 'LEFT')
                ->join(['in_user'=>'u'] , 't.user_id = u.id' , 'LEFT')
                ->where($model_where)
                ->field($model_field)
                ->paginate(10);

        }elseif ($keyword_task_name){
            $user_model = new TaskCategoryModel();
            $model_task_field = "id ";
            $model_where_task['task_name'] =['like',"%".$keyword_task_name."%"];
            $data = $user_model->where( $model_where_task)
                ->field($model_task_field)
                ->select();
            foreach ($data as $k =>$v){
                $new_arr[] = $v['id'];
            }
            $data = $model ->where(['t.task_category_id'=>['in',$new_arr]])
                ->alias("t")
                ->join(['in_task_category'=>'c'] , 't.task_category_id = c.id' , 'LEFT')
                ->join(['in_user'=>'u'] , 't.user_id = u.id' , 'LEFT')
                ->where($model_where)
                ->field($model_field)
                ->paginate(10);

        }else{
            $data = $model
                ->alias("t")
                ->join(['in_task_category'=>'c'] , 't.task_category_id = c.id' , 'LEFT')
                ->join(['in_user'=>'u'] , 't.user_id = u.id' , 'LEFT')
                ->where($model_where)
                ->field($model_field)
                ->paginate(10);
        }

        return $data;

    }


    //从新分配一个任务
    public function reDraw($id , $user_id)
    {
        $model = new TaskDrawModel();
        $res = $model->get($id);
        $res -> user_id = $user_id;
        $res->save();
    }


    //取出某用户的全部绘制任务
    public function allDraw($user_id)
    {
        $model =new TaskDrawModel();
        $model_where['user_id'] = ['eq',$user_id];
        $data = $model->where($model_where)
                      ->select();
        return $data;
    }


    //取出某用户的一组绘制任务
    public function groupDraw($user_id,$category_id)
    {
        $model =new TaskDrawModel();
        $model_where['user_id'] = ['eq',$user_id];
        $model_group['task_category_id'] = ['eq',$category_id];
        $data = $model->where($model_where)
                      ->where($model_group)
                      ->select();
        return $data;
    }


    //增加个人任务
    public function add($user_id,$task_category_id)
    {
        $time= date("Y-m-d H:i:s");
        $model = new TaskDrawModel();
        $model -> data([
            'user_id'           =>   $user_id,
            'task_category_id'  =>   $task_category_id,
            'creattime'         =>   $time,
        ]);
        $model->save();

    }


    //根据传来的用户与索要查询的状态获取数据
    public function getTask($user_id,$num){

        $model = new TaskDrawModel();
        $model_where['user_id'] = ["eq",$user_id];
        $model_where_status['status'] = ["eq",$num];
        $model_user_field = "d.id, d.task_category_id, d.user_id, d.creattime, d.status, t.sort";

        $data = $model ->join(['in_task_category'=>'t'] , 'd.task_category_id = d.id' , 'LEFT')
            ->where($model_where)
            ->where($model_where_status)
            ->alias("d")
            ->field($model_user_field)
            ->order('t.sort')
            ->select();
        return $data;
    }


}