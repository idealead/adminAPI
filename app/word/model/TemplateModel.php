<?php


namespace app\word\model;


use think\Model;

class TemplateModel extends Model
{

    //获取设计师模板数据
    public function findTemplate()
    {
        $model = new TemplateModel();
        $where['level'] = ["eq","permanent"];
       // $where['status'] = ["eq",1];
        $model_field = "
                    t.id, f.path, t.level , t.author, preview, type, template_name,t.createtime
        ";
        $data = $model ->where($where)
            ->alias("t")
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->select()
            ->toArray();

        return $data;


    }

    //获取单个设计师模板数据
    public function findTemplateOnce($id)
    {
        $model = new TemplateModel();
        $where['level'] = ["eq","permanent"];
         $where['t.id'] = ["eq",$id];
        $model_field = "
                    t.id, f.path, t.author, preview,type,template_name
        ";
        $data = $model ->where($where)
            ->alias("t")
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->find()
            ->toArray();

        return $data;


    }
}