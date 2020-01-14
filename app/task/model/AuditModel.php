<?php


namespace app\task\model;

use think\Model;
use app\substance\model\TemplateModel;
use app\admin\model\UserModel;

class AuditModel extends Model
{
    // 创建模板任务表数据
    public function createTask($template_id,$template_user_id,$time)
    {
        $model = new AuditModel();
        $model = new FailureAuditModel() ;
        $model -> data([
            'template_id'             =>   $template_id,
            'template_user_id'        =>   $template_user_id,
            'time'                    =>   $time,
        ]);
        $model->save();
    }


    //判断audit表中是否有template_id为$id的数据
    public function judge($id)
    {
        $model = new AuditModel();
        $model_where['template_id']=["eq",$id];
        $res = $model -> where($model_where)->find();
        return $res;
    }

    //非首次提交需要还原状态
    public function reduction($id)
    {
        $model = new AuditModel();
        $res = $model->get($id);
        $res -> template_status = 0;
        $res -> template_failure_reason ='';
        $res->save();
    }


    //根据template_id 找到id
    public function getID($template_id)
    {
        $model = new AuditModel() ;
        $model_where['template_id'] = ["eq",$template_id];
        $model_field = "id";

        $res = $model
            ->where($model_where)
            ->field($model_field)
            ->order('id', 'desc')
            ->find();

        $rest = $res->toArray($res);
        $rest = $rest['id'];
        return $rest;
    }


    //填写标签编写人员
    public function updateLabelWriter($id,$label_writer_id)
    {
        $model = new TemplateStatusModel();
        $res = $model->get($id);
        $res->label_writer_id = $label_writer_id;
        $res->save();
    }



    //填写标签审核人员
    public function updateLabelUser($id,$label_user_id)
    {
        $model = new TemplateStatusModel();
        $res = $model->get($id);
        $res -> label_user_id = $label_user_id;
        $res -> label_status = 0;
        $res -> label_failure_reason = '';
        $res -> save();
    }

    //判断模板审核是否通过
    public function templateSuccess($template_id){

        $model = new AuditModel();
        $model_where['template_id']=["eq",$template_id];
        $model_template_status['template_status']=["eq",2];
        $res = $model -> where($model_where)
                      -> where($model_template_status)
                      ->find();
        return $res;
    }


    //获取audit表中的template 审核人、状态等数据
    public function getTemplate($key_name = '',$key_id = '')
    {

        $model = new AuditModel();
        $model_where_status['template_status']=["eq",0];
        $model_where_name['user_name'] = ['like',"%".$key_name."%"];
        $model_where_id['template_user_id'] = ['eq',$key_id];

        if ($key_name){
            $new_arr = [];
            $user_model = new UserModel();
            $model_field = "id ";
            $data = $user_model->where($model_where_name)
                ->field($model_field)
                ->paginate(10);
            foreach ($data as $k =>$v){
                $new_arr[] = $v['id'];
            }
           $data =  $model ->where(['template_user_id'=>['in',$new_arr]])
                           ->where($model_where_status)
                           ->paginate(10);

        }elseif ($key_id){
           $data = $model ->where($model_where_status)
                          ->where($model_where_id)
                          ->paginate(10);
        }else{
            $data = $model -> where($model_where_status)->paginate(10);
        }
        return $data;
    }


    //获取相应template详细信息
    public function templateInformation($template_id)
    {
        $model = new TemplateModel();
        $model_where['t.id'] = ["eq",$template_id];
        $model_field = "
            t.id, t.template_name, t.author, t.classify_id, t.modify, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $data = $model
            ->alias("t")
            ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
            ->join(['in_classify'=>'c'] , 'c.id = t.classify_id' , 'LEFT')
            ->where($model_where)
            ->field($model_field)
            ->find();
        return $data;

    }


    //重新分配模板审核人
    public function reallocateTemplate($id,$template_user_id)
    {
        $model = new AuditModel();
        $res = $model->get($id);
        $res -> template_user_id = $template_user_id;
        $res -> save();
    }


    //查询该审核人的所有任务返回
    public function allTaskTemplate($id)
    {
        $model = new AuditModel();
        $model_where['template_user_id'] = ["eq",$id];
        $model_field = " id ";

        $data = $model
                ->where($model_where)
                ->field($model_field)
                ->select();
        return $data;
    }


    //重新分配标签审核人
    public function reallocateLabel($id,$label_user_id)
    {
        $model = new AuditModel();
        $res = $model->get($id);
        $res -> label_user_id =$label_user_id;
        $res -> save();
    }

}