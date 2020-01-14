<?php


namespace app\audit\model;

use think\Model;

class TemplateStatusModel extends Model
{

    //判断template_status表中是否有template_id为$id的数据
    public function judge($id)
    {
        $model = new TemplateStatusModel();
        $model_where['template_id'] = ["eq", $id];
        $res = $model->where($model_where)->find();
        return $res;
    }



    //修改template_status表中template_status的状态为修改为$num
    //状态码：1待审核模板，2模板审核通过，3模板审核失败，4待填写标签，5待审核标签，6标签审核失败，7标签审核成功
    public function updateStatus($id, $num)
    {
        $model = new TemplateStatusModel();
        $id = $model->getID($id);
        $res = $model ->get($id);
        $res -> status = $num;
        $res->save();
    }


    //根据template_id 找到template_status表的id
    public function getID($template_id)
    {
        $model = new TemplateStatusModel() ;
        $model_where['template_id'] = ["eq",$template_id];
        $model_field = "id";
        $res = $model
            ->where($model_where)
            ->field($model_field)
            ->order('id', 'desc')
            ->select();

        $rest = $res->toArray($res);
        $rest = $rest[0]['id'];
        return $rest;
    }

}