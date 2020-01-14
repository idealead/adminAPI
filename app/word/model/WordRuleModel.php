<?php


namespace app\word\model;


use think\Model;

class WordRuleModel extends Model
{

    //获取模板规则
    public function getTemplateRule($template_id)
    {
        $model = new WordRuleModel();
        $where['template_id'] =['eq',$template_id];
        $data = $model ->where($where)->select()->toArray();
        return $data;
    }


}