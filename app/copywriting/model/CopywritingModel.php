<?php


namespace app\copywriting\model;

use think\Model;
use app\copywriting\model\LabelCwModel;
use app\copywriting\model\CopywritingPositionModel;


class CopywritingModel extends Model
{
    //根据关键词，返回数据
    public function getLabelName($name = '')
    {
        $model = new CopywritingModel();

        $model_where_status["c.is_delete"] = ["eq", 1];
        $model_where_content['c.content'] = ['like', "%" . $name . "%"];
        if ($name) {
            $model_field = "c.id ,content ,c.create_time ,cl.label_id,label_name";
            $data = $model->alias("c")
                          ->join(['in_copywriting_label'=>'cl'] , 'c.id = cl.copywriting_id' , 'LEFT')
                          ->join(['in_label_cw'=>'la'] , 'cl.label_id = la.id' , 'LEFT')
                          ->where($model_where_status)
                          ->where($model_where_content)
                          ->field($model_field)
                          ->paginate(10);
        }else{
            $model_field = "c.id ,content ,c.create_time ,cl.label_id,label_name";
            $data = $model->alias("c")
                ->join(['in_copywriting_label'=>'cl'] , 'c.id = cl.copywriting_id' , 'LEFT')
                ->join(['in_label_cw'=>'la'] , 'cl.label_id = la.id' , 'LEFT')
                ->where($model_where_status)
                ->field($model_field)
                ->paginate(10);
        }
        return $data;
    }

    //获取标签数据
    public function getLabelList()
    {
        $model = new  LabelCwModel();
        $data = $model -> getLabelList();

        return $data;

    }

    //获取位置信息数据
    public function getPositionList()
    {
        $model = new CopywritingPositionModel();
        $data = $model ->getPositionList();
        return $data;
    }

    //添加文案 返回文案id
    public function add($data)
    {
        $model = new CopywritingModel();
        $add_data = [
            'content'     => $data['content'],
            'position_id' => $data['position_id'],
            'create_time'			=> date("Y-m-d H:i:s"),
        ];
        $id = $model ->insertGetId($add_data);
        return $id;
    }

    //删除文案
    public function del($id)
    {
        $model = new CopywritingModel();
        $res = $model->get($id);
        $res -> is_delete = 0;
        $res->save();
    }

    //修改文案
    public function edit($id,$content)
    {
        $model = new CopywritingModel();
        $res = $model->get($id);
        $res -> content = $content;
        $res->save();

    }

}