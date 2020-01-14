<?php


namespace app\audit\model;

use think\Db;
use think\Model;
use app\substance\model\TemplateModel;

class FailureTemplateModel extends Model
{
    //存储适合失败模板的原信息
    public function BackupFailedData($template_id,$failure_template_id)
    {   $failureTemplateModel = new FailureTemplateModel();
        $temolateModel = new TemplateModel();
        $model_where['id'] = ["eq",$template_id];
        $model_field = "id , template_name, template_width, template_height, label_id, classify_id, material_content, preview, author, createtime";

        $res = $temolateModel
            ->where($model_where)
            ->field($model_field)
            ->find();

        $res = $res ->toArray($res);
        $tmp = array("failure_audit_id" => $failure_template_id);
        $res= array_merge($res,$tmp);

        $failureTemplateModel->save($res);
        return ;
    }


    //历史信息详情展示
    public function historyDetails($id)
    {
        $model = new FailureTemplateModel();
        $model_where['t.failure_audit_id'] = ["eq",$id];
        $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $data = $model
            ->alias("t")
            ->join(['in_file' => 'f'], 'f.id = t.preview', 'LEFT')
            ->join(['in_classify' => 'c'], 'c.id = t.classify_id', 'LEFT')
            ->where($model_where)
            ->field($model_field)
            ->find();

        return $data;

    }

    //便利个人原失败模板信息
    public function getFailTemplate($author)
    {
        $model = new FailureTemplateModel();
        $model_where['author'] =["eq",$author];
        $model_field=" id ,template_name , template_width, template_height, label_id, author, author, createtime,material_content,preview";
        $data = $model ->where($model_where)
            ->field($model_field)
            ->select();

        return $data;

    }

}