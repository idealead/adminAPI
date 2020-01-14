<?php


namespace app\audit\model;

use think\Model;

class AuditModel extends Model
{

    //判断audit表中是否有template_id为$id的数据

    public function judge($id)
    {
        $model = new AuditModel();
        $model_where['template_id']=["eq",$id];
        $res = $model -> where($model_where)->find();
        return $res;
    }


    //修改audit表中是否有template_status的状态为1（审核通过）

    public function updateStatus($id)
    {   $time = date("Y-m-d H:i:s");
        $model = new AuditModel() ;
        $res = $model ->get($id);
        $res -> template_status = 1;
        $res -> passtime = $time;
        $res -> template_passtime = $time;
        $res -> save();
    }


    //修改 template_status的状态为2,并插入template_failure_reason失败理由（审核失败）

    public function auditFailure($id,$reason){
        $model = new AuditModel() ;
        $res = $model ->get($id);
        $res ->template_status = 2;
        $res ->template_failure_reason =$reason;
        $res->save();
    }




    //以下为标签Model方法

    //根据传入的标签审核人id和状态，便利相应数据列表
    public function labelList($user)
    {
        $model = new AuditModel();
        $model_where_user['a.label_user_id']      = ["eq", $user];
        $model_where_lStatus['a.label_status']    = ["eq", 0];
        $model_where_tStatus['a.template_status'] = ["eq",1];
        $model_file = "
        a.id, a.template_id, a.label_user_id, a.label_status, a.label_failure_reason, a.createtime
        ";
        $model_order = "a.createtime DESC";

        $data = $model
            ->alias("a")
            ->where($model_where_user)
            ->where($model_where_lStatus)
            ->where($model_where_tStatus)
            ->field($model_file)
            ->order($model_order)
            ->paginate(20);

        return $data;

    }


    //标签审核完成修改audit表的标签审核状态为1
    public function completeStatus($id,$time)
    {
        $model =   new AuditModel() ;
        $res   =   $model ->get($id);
        $res   ->  label_status = 1;
        $res   ->  passtime     = $time;
        $res   ->  save();
    }


    //标签审核失败-> 修改audit表的status状态和填写reason理由
    public function auditFailureLabel($id,$reason){
        $model = new AuditModel() ;
        $res = $model ->get($id);
        $res ->label_status = 2;
        $res ->label_failure_reason =$reason;
        $res->save();
    }

}