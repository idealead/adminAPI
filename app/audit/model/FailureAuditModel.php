<?php


namespace app\audit\model;

use think\Model;

class FailureAuditModel extends Model
{
    //在failure_audit表中插入模板失败的数据，
    public function addFailureTemplate($id,$user_id,$reason,$time)
    {
        $model = new FailureAuditModel() ;
        $model -> data([
            'template_id'             =>   $id,
            'template_user_id'        =>   $user_id,
            'template_status'         =>   2,
            'template_failure_reason' =>   $reason,
            'createtime'              =>   $time
        ]);
      $model->save();
    }


   // 找到某模板刚创建的failure_audit表中的数据的id
    public function findID($id)
    {
        $model = new FailureAuditModel() ;
        $model_where['template_id'] = ["eq",$id];
        $model_field = "id";

        $res = $model
            ->where($model_where)
            ->field($model_field)
            ->order('id', 'desc')
            ->find();

        $rest = $res->toArray($res);
        $rest = $rest['id'];
        return $rest;

    }


    //在failure_audit表中插入标签失败的数据，
    public function addFailureLabel($id,$user_id,$reason,$time)
    {
        $model = new FailureAuditModel() ;
        $model -> data([
            'template_id'             =>   $id,
            'label_user_id'           =>   $user_id,
            'template_status'         =>   1,
            'label_status'            =>   2,
            'label_failure_reason'    =>   $reason,
            'createtime'              =>   $time
        ]);
        $model->save();
    }


    // 标签审核失败列表便利
    public function failureLableList($id)
    {
        $Model = new FailureAuditModel();
        $model_where_id['f.template_id'] = ["eq", $id];
        $model_where_status['label_status'] = ["eq",2];
        $model_field = "
            f.id, f.template_id, f.template_user_id, f.label_status, f.label_failure_reason ,
            ft.preview, c.path, c.type
        ";
        $data = $Model
            ->alias("f")
            ->join(['in_failure_template' => 'ft'], 'ft.failure_audit_id = f.id', 'LEFT')
            ->join(['in_file' => 'c'], 'c.id = ft.preview', 'LEFT')
            ->where($model_where_id)
            ->where($model_where_status)
            ->field($model_field)
            ->select();

        return $data;
    }
}