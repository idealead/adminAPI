<?php


namespace app\api\model;

use think\Model;
use app\api\model\LabelModel;
use think\Db;

class TemplateModel extends Model
{


    //前端接口：个人查看没有审核的模板接口  $status_id 为模板的状态码，0为未审核，1为审核
    public function templateAuditList($user_id,$status){

        $model = new TemplateModel();
        $label_model = new LabelModel();
        $model_where['t.author']=["eq",$user_id];
        if($status == 0){
            $model_where_status['t.status'] = ["eq",$status];
            $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type 
        ";
            $data = $model
                ->alias("t")
                ->join(['in_file' => 'f'], 'f.id = t.preview', 'LEFT')
                ->where($model_where)
                ->where($model_where_status)
                ->field($model_field)
                ->select();
        }elseif ($status == 1){
            $model_where_status['t.status'] = ["neq",0];
            $model_where_status2['t.status'] = ["neq",2];
            $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type 
        ";
            $data = $model
                ->alias("t")
                ->join(['in_file' => 'f'], 'f.id = t.preview', 'LEFT')
                ->where($model_where)
                ->where($model_where_status)
                ->where($model_where_status2)
                ->field($model_field)
                ->select();
        }else{
            $res = [
                'code'    => '201',
                'message' => '没有找到到模板id',
            ];
            $res = json_encode($res);
            return $res;
        }

        //拼接标签名称到数组里并返回
        $res = json_decode($data);
        $num = count($res);
        $label_id='label_id';
        for($i=0;$i<$num;$i++){
            foreach ($res[$i] as $k=>$v){
                if($k ==$label_id){
                    if($v){
                        $new_arr = $label_model->queryLabelName($v);
                        $res[$i]->label_id = $new_arr;
                    }
                }
            }
        }
     $data = $res;
     $data = json_encode($data);
        return $data;
    }


    //前端接口：根据模板id查询出来，重写标签
    public function addTemplateLabel($id,$label_id)
    {

        $model = new TemplateModel();
        $model_where['id'] =["eq",$id];
        $data = $model->where($model_where)
                      ->find();
        if ($data){
            $data -> label_id = $label_id;
            $this->save();
            $res = [
                'code'    => '200',
                'message' => "模板标签添加成功",
                'id'      => $id
            ];
            $res = json_encode($res);
            return $res;
        }else{
            $res = [
                'code'    => '201',
                'message' => "模板id不存在"
            ];
            $res = json_encode($res);
            return $res;
        }
    }



    //前端：（假审核）更改模板状态，从未审核0状态码。改为3正常状态码
    public function tempChangeStatus($id)
    {
        $model = new TemplateModel();
        $data = $model ->get($id);
        $data-> status = 3;
        $data->save();
        $res = [
            'code'    => '200',
            'message' => '模板审核成功',
        ];
        $res = json_encode($res);
        return $res;
    }


    //前端：（假审核）更改模板状态，从未审核3状态码。改为1正常状态码
    public function completeTemplateAudit($id)
    {
        $model = new TemplateModel();
        $data = $model ->get($id);
        $data-> status = 1;
        $data->save();
        $res = [
            'code'    => '200',
            'message' => '上线成功',
        ];
        $res = json_encode($res);
        return $res;
    }

}