<?php


namespace app\task\model;

use think\Db;
use think\Model;
use app\admin\model\UserModel;

class TaskCategoryModel extends Model
{

    //便利发布任务列表
    public function releaseList()
    {
        $model = new TaskCategoryModel();

        $model_where['is_delete'] = ["eq",1];
        $model_field = "
            c.id, c.task_name, c.label, c.note, c.style, c.industry, c.tone, c.sort, cn.category_name, c.number ,c.status
        ";
        $data = $model ->alias("c")
                       ->where($model_where)
                        ->field($model_field)
                       ->join(['in_category_name'=>'cn'] , 'task_category = cn.id' , 'LEFT')
                       ->paginate(10);
        return $data;
    }

    //重载
    public function KeyReleaseList($keyword)
    {
        $model = new TaskCategoryModel();
        $model_where_name['task_name'] = ['like',"%".$keyword."%"];
        $model_where['is_delete'] = ["eq",1];
        $data = $model ->where($model_where)
                       ->where($model_where_name)
                       ->paginate(10);
        return $data;
    }

    //增加任务
    public function add($data)
    {   $time= date("Y-m-d H:i:s");
        $model = new TaskCategoryModel();
        $model -> data([
            'task_name'           =>   $data['name'],
            'number'         =>   $data['number'],
            'task_category'  =>   $data['task_category'],
            'note'           =>   $data['note'],
            'style'          =>   $data['style'],
            'industry'       =>   $data['industry'],
            'tone'           =>   $data['tone'],
            'label'          =>   $data['label'],
            'creattime'      =>   $time,
            'sort'           =>   $data['sort'],
        ]);
       $model->save();
       $id = Db::name('task_category')->getLastInsID();
       return $id;
    }


    //修改任务
    public function  edit($id)
    {
        $model =new TaskCategoryModel();
        $model_where['id'] =['eq',$id];
        $model_field = "
            id, task_name, label, note, style, industry, tone, sort 
        ";
        $data = $model ->where($model_where)
                       ->field( $model_field)
                       ->find();
        return $data;
    }

    //修改提交
    public function  editSubmit($data)
    {
        $model = new TaskCategoryModel();
        $res = $model->get($data['id']);

        $res-> task_name     = $data['name'];
        $res-> number   = $data['number'];
        $res-> note     = $data['note'];
        $res-> style    = $data['style'];
        $res-> industry = $data['industry'];
        $res-> tone     = $data['tone'];
        $res-> label   = $data['label'];
        $res-> sort     = $data['sort'];
        $res->save();
    }


    //-----------------------------------任务分配--------------------------------------------------------

    public function distributionList()
    {
        $model = new TaskCategoryModel();
        $model_where['status'] =['eq',2];
        $model_where_delete['is_delete'] =['eq',1];
        $data = $model ->where($model_where)
                       ->where($model_where_delete)
                       ->select();
        return $data;
    }


    // 查找无请假用户
    public function findUser()
    {
        $model = new UserModel();
        $model_where_delete['is_delete'] = ['eq',1];
        $model_field = "
            id, user_name
        ";
        $data = $model ->where($model_where_delete)
                       ->field($model_field)
                       ->select();
        return $data;
    }


    //分配模板任务-》可用的、任务最少的人的id
    public function templateReviewer($res,$num)
    {
        $result = Db::query("select u.id ,ru.role_id, num
                                from in_user as u
                                left join in_role_user as ru
                                on u.id = ru.user_id
                                left join (select a.user_id, count(*) as num
                                            from in_task_draw as a
                                            where task_category_id = $res
                                            group by user_id)	as a			 
                                on u.id = a.user_id
                                where u.user_status =1 and u.is_delete = 1 and ru.role_id = $num
                                order by num
                            ");
        if (empty($result[0]["id"]) ){
        $result = 0;
        return $result;
        }else{
            $result = $result[0]["id"];
            return $result;

        }

    }



}