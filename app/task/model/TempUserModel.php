<?php


namespace app\task\model;

use app\Substance\model\TemplateModel;
use think\Model;
use app\task\model\AuditModel;
use app\admin\model\UserModel;
use think\db;
class TempUserModel extends Model
{


    //获取用户状态
    public function getStatus($id)
    {
        $model = new UserModel();
        $model_where['id'] = ["eq",$id];
        $model_field = "
            id, user_status
        ";
        // 查询模版
        $data = $model
            ->where($model_where)
            ->field($model_field)
            ->find();
        return $data['user_status'];
    }



    //更改用户状态（配合任务重新分配）
    //用户状态;0:禁用, 1:正常 , 2:请假 ，3离职 ， 4任务分配中
    public function changeStatus($id,$status)
    {
        $model = new UserModel();
        $res = $model->get($id);
        $res->user_status = $status;
        $res->save();

    }



    //分配模板任务-》可用的、任务最少的人的id
    public function templateReviewer()
  {
      $result = Db::query('select u.id ,ru.role_id, num
                                from in_user as u
                                left join in_role_user as ru
                                on u.id = ru.user_id
                                left join (select template_user_id, count(*) as num
                                            from in_audit 
                                            where template_status=0
                                            group by template_user_id)as a				 
                                on u.id = template_user_id
                                where u.user_status =1 and u.is_delete = 1 and ru.role_id = 3
                                order by num
                            ');
      if (empty($result[0]["id"]) ){
          $result = 0;
          return $result;
      }else{
          $result = $result[0]["id"];
          return $result;
      }

  }



  //分配绘制模板任务，取出任务最少的人进行分配
    public function drawTemplate($num)
    {
        $result = Db::query("select u.id ,ru.role_id, num
                                from in_user as u
                                left join in_role_user as ru
                                on u.id = ru.user_id
                                left join (select a.user_id, count(*) as num
                                            from in_task_draw as a
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


  //分配标签填写任务-》可用的、任务最少的人的id
    public function labelWriter()
    {
        //权限确定，要修改
        $result = Db::query('select u.id ,ru.role_id, num
                                from in_user as u
                                left join in_role_user as ru
                                on u.id = ru.user_id
                                left join (select template_user_id, count(*) as num
                                            from in_audit 
                                            where template_status=0
                                            group by template_user_id)as a				 
                                on u.id = template_user_id
                                where u.user_status =1 and u.is_delete = 1 and ru.role_id = 5
                                order by num
                            ');
        $result = $result[0]["id"];
        return $result;

    }


    //分配标签审核-》可用的、任务最少的人的id
    public function labelReviewer()
    {
        //权限确定，要修改
        $result = Db::query('select u.id ,ru.role_id, num
                                from in_user as u
                                left join in_role_user as ru
                                on u.id = ru.user_id
                                left join (select template_user_id, count(*) as num
                                            from in_audit 
                                            where template_status=0
                                            group by template_user_id)as a				 
                                on u.id = template_user_id
                                where u.user_status =1 and u.is_delete = 1 and ru.role_id = 4
                                order by num
                            ');
        $result = $result[0]["id"];
        return $result;

    }

    //遍历出全部绘制模板的可用人员

    public function getUserList($num)
    {
        $model = new UserModel();
        $model_where['ru.role_id'] = ["eq",$num];
        $model_where_status['u.user_status'] = ["eq",1];
        $model_field = "
            u.id, user_status,u.user_name
        ";
        // 查询模版
        $data = $model  ->alias("u")
            ->join(['in_role_user'=>'ru'] , 'u.id = user_id' , 'LEFT')
            ->where($model_where)
            ->where($model_where_status)
            ->field($model_field)
            ->select();

        return $data;
    }
}