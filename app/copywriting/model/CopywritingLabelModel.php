<?php


namespace app\copywriting\model;

use think\Model;

class CopywritingLabelModel extends Model
{

    //添加文案对应标签
    public function add($data,$copywriting_id)
    {
        $model = new CopywritingLabelModel();
        foreach ($data['label_id'] as $k =>$v)
        {
            $model ->add_label($copywriting_id,$v);
        }
        return 1;
    }


    //文案标签添加存储
    public function add_label($copywriting_id,$label_id)
    {
        $model = new CopywritingLabelModel();
        $model -> data([
            'copywriting_id'     =>   $copywriting_id,
            'label_id'   =>   $label_id,
        ]);
        $model->save();
    }


    //文本对应标签删除

    public function del ($copywriting_id)
    {
        $model = new CopywritingLabelModel();
        $model_where['copywriting_id'] = ["eq",$copywriting_id];
        $res = $model ->where($model_where) ->setField('is_delete',0);

    }



}