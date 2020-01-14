<?php


namespace app\audit\controller;

use app\audit\model\FailureAuditModel;
use app\audit\model\FailureTemplateModel;
use cmf\controller\AdminBaseController;


class FailureTemplateController extends AdminBaseController
{

    //便利审核失败的列表
    public function index()
    {


        // 时间
        $this->assign('start_time', input('start_time'));
        $this->assign('end_time', input('end_time'));
        if(input('start_time') && input('end_time')){
            $template_where_time['f.createtime'] = ["between", [ input('start_time'),input('end_time') ] ];
        }else if(input('start_time')){
            $template_where_time['f.createtime'] = ["egt",input('start_time')];
        }else if(input('end_time')){
            $template_where_time['f.createtime'] = ["elt",input('end_time')];
        }

        $template_user_id = cmf_get_current_admin_id();
        $Model = new FailureAuditModel();
        $model_where['template_user_id'] = ["eq",$template_user_id];
        $model_del['is_delete'] = ["eq",1];
        $model_field = "
            f.id, f.template_id, f.template_user_id, f.template_status, f.template_failure_reason ,
            ft.preview, c.path, c.type
        ";
        if (input('start_time') && input('end_time')){
            $data = $Model
                ->alias("f")
                ->join(['in_failure_template'=>'ft'] , 'ft.failure_audit_id = f.id' , 'LEFT')
                ->join(['in_file'=>'c'] , 'c.id = ft.preview' , 'LEFT')
                ->where( $model_where)
                ->where( $model_del)
                ->where($template_where_time)
                ->field($model_field)
                ->paginate(10);
        }else{
            $data = $Model
                ->alias("f")
                ->join(['in_failure_template'=>'ft'] , 'ft.failure_audit_id = f.id' , 'LEFT')
                ->join(['in_file'=>'c'] , 'c.id = ft.preview' , 'LEFT')
                ->where( $model_where)
                ->where( $model_del)
                ->field($model_field)
                ->paginate(10);
        }


        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this -> fetch();

    }


    //便利个人原失败模板信息
    public function getFailTemplate($author)
    {
        $model = new FailureTemplateModel();
        $data = $model ->getFailTemplate($author);
        return $data;

    }
}