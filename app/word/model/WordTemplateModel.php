<?php


namespace app\word\model;


use think\Model;

class WordTemplateModel extends Model
{


    //添加成套的模板
    public function addWordTemplate($template_id,$str)
    {

        $model = new WordTemplateModel();
        $add_data = [
            'template_id' => $template_id,
            'word_id'     =>$str,
        ];
        $id = $model ->insertGetId($add_data);
        return $id;
    }



    //查询某模板全部文案列表数据
    public function findWordTemplateAll($template_id)
    {
        $model = new WordTemplateModel();
        $model_word = new WordModel();
        $where['wt.template_id'] = ["eq",$template_id];
        $where['wt.is_delete'] = ["eq",1];
        $model_field = '
            wt.id , wt.word_id,t.preview,t.template_name ,f.path ,f.type,wt.template_id
        ';

        $data = $model
            ->alias("wt")
            ->join(['in_template'=>'t'] , 'wt.template_id = t.id' , 'LEFT')
            ->join(['in_file'=>'f'] , 't.preview = f.id' , 'LEFT')
            ->field($model_field)
            ->where($where)
            ->select()->toArray();

        if ($data){
            foreach ($data as $k =>$v){
                $tmp = $v['word_id'];
                $tmp = explode(',',$tmp);
                $content =$model_word ->findWord($template_id,$tmp);
                $data[$k]['content'] = $content;
            }
        }

        return $data;

    }


    //删除文案
    public function del($id)
    {
        $model = new WordTemplateModel();
        $data = $model ->get($id);
        $data-> is_delete = 0;
        $data->save();
    }

    //根据查询出文案集合信息
    public function findWords($id)
    {
        $model = new WordTemplateModel();
        $where['id'] = ["eq",$id];
        $data = $model ->where($where) ->select()->toArray();
        if ($data){
            return $data[0];
        }else{
            return $data;
        }

    }




}