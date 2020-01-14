<?php


namespace app\webapi\model;


use think\Model;

class WordTemplateModel extends Model
{

    //获取某模板全部文案
    public function findAllWord($template_id)
    {
        $model = new WordTemplateModel();
        $model_word = new WordModel();
        $where['template_id'] = ["eq",$template_id];
        $where['is_delete'] = ["eq",1];
        $data = $model ->where($where)->select()->toArray();

        if ($data){
            foreach ($data as $k =>$v){
                $tmp  = $v['word_id'];
                $tmp = explode(',',$tmp);
                $content = $model_word->findWords($template_id,$tmp);
                $data[$k]['content'] = $content;
            }
            return $data;
        }else{
            return $data;
        }
    }



}