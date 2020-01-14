<?php


namespace app\word\model;


use think\Model;

class WordModel extends Model
{

    //添加文案
    public function addWord($content,$word_rule_id,$template_id)
    {
        $model = new WordModel();
        $time = date("Y-m-d H:i:s");
        $data = [
            'content' =>$content,
            'word_rule_id'=>$word_rule_id,
            'create_time' =>$time,
            'template_id' =>$template_id
        ];
        $rule_id = $model ->insertGetId($data);
        return $rule_id;
    }


    //根据template_id 选出对应所有文案，并根据要求拼接
    public function findWord($template_id,$tmp)
    {
        $model = new WordModel();
        $where['is_delete'] = ["eq",1];
        $where['template_id'] = ["eq",$template_id];
        $data =$model ->where($where)->select()->toArray();
        $res = [];
        $rest='';
        foreach ($tmp as $k =>$v){

            foreach ($data as $wk =>$wv){
                if ($v == $wv['id']){
                    $res[$v] = $wv['content'];
                }
            }
        }
        foreach ($res as $k=>$v){
          //  $long = count($rest);
            if (!$rest){
                $rest.=$v;
            }else{
                $rest.="/".$v;
            }
        }
        return $rest;
    }


    //根据id删除文案
    public function deleteWord($id)
    {
        $model = new WordModel();
        $data = $model ->get($id);
        $data-> is_delete = 0;
        $data->save();
    }

    //根据id集合返回数据

    public function findWordSelf($word_id)
    {

        $model = new WordModel();
        $data = [];
        foreach ($word_id as $k =>$v){
            $data[$k] = $model ->findWordOnce($v);
        }
        return $data;
    }


    //根据id查询单条数据
    public function findWordOnce($id)
    {
        $model = new WordModel();
        $where['w.id'] = ["eq",$id];

        $model_field = '
            w.id ,w.content,wr.rule_name,w.word_rule_id
        ';
        $data = $model
            ->alias("w")
            ->join(['in_word_rule'=>'wr'] , 'wr.id = w.word_rule_id' , 'LEFT')
            ->field($model_field)
            ->where($where)->select()->toArray();

        if ($data){
            return $data[0];
        }else{
            return $data;
        }
    }

    //根据id修改  新文案
    public function updateContent($id,$content)
    {
        $model = new WordModel();
        $data = $model ->get($id);
        $data-> content = $content;
        $data->save();
    }
}