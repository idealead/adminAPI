<?php


namespace app\webapi\model;


use think\Model;

class WordModel extends Model
{

    //添加规则
    public function addWordRule($template_id,$rule)
    {
        $model = new WordModel();
        foreach ($rule as $k =>$v){
            $data = [
                'rule_name' =>$v,
                'template_id' =>$template_id
            ];
          $model ->insertGetId($data);
        }
        $res = [
            'code'=>200,
            'massige' =>'添加规则成功',
        ];

    }



    //根据id查找文案
    public function findWords($template_id,$word_id)
    {
        $model = new WordModel();
        $where['is_delete'] = ["eq",1];
        $where['template_id'] = ["eq",$template_id];
        $data =$model ->where($where)->select()->toArray();
        $res = [];
        $rest=[];
        foreach ($word_id as $k =>$v){

            foreach ($data as $wk =>$wv){
                if ($v == $wv['id']){
                    $res[$v] = $wv['content'];
                }
            }
        }

        foreach ($res as $k=>$v) {
            array_push($rest,$v);
        }

        return $rest;
    }
}