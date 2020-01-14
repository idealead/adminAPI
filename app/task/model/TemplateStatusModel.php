<?php


namespace app\task\model;

use think\Model;

class TemplateStatusModel extends  Model
{
    //状态码：1待审核模板，2模板审核通过，3模板审核失败，4待填写标签，5待审核标签，6标签审核失败，7标签审核成功

    //模板任务分配，插入模板状态
    public function createStatus($template_id)
    {
        $model = new TemplateStatusModel();
        $model -> data([
            'template_id'             =>   $template_id,
        ]);
        $model->save();
    }



    //判断template_status表中是否有template_id为$id的数据
    public function judge($id)
    {
        $model = new TemplateStatusModel();
        $model_where['template_id'] = ["eq", $id];
        $res = $model->where($model_where)->find();
        return $res;
    }


    //非首次提交需要还原状态
    public function reduction($id)
    {
        $model = new TemplateStatusModel();
        $res = $model->get($id);
        $res -> status = 1;
        $res->save();
    }



    //根据template_id 找到id
    public function getID($id)
    {
        $model = new TemplateStatusModel() ;
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



}