<?php


namespace app\audit\controller;

use cmf\controller\AdminBaseController;
use app\audit\model\AuditModel;
use app\substance\model\TemplateModel;
use app\audit\model\FailureAuditModel;
use app\audit\model\FailureTemplateModel;
use app\audit\model\TemplateStatusModel;
use app\task\model\TaskDrawModel;
use app\audit\validate\FailValidate;
use think\Db;


class AuditTemplateController extends AdminBaseController
{
    // 审核状态
    protected $material_template_status = [
        0 => "<span class='btn btn-xs btn-danger'>未审核</span>",
        1 => "<span class='btn btn-xs btn-success'>审核通过</span>",
        2 => "<span class='btn btn-xs btn-danger'>审核失败</span>",
    ];


    //审核任务列表展示
    public function index()
    {

        // 时间
        $this->assign('start_time', input('start_time'));
        $this->assign('end_time', input('end_time'));
        if(input('start_time') && input('end_time')){
            $where_time['a.createtime'] = ["between", [ input('start_time'),input('end_time') ] ];
        }else if(input('start_time')){
            $where_time['a.createtime'] = ["egt",input('start_time')];
        }else if(input('end_time')){
            $where_time['a.createtime'] = ["elt",input('end_time')];
        }

        //便利登录用户对应审核任务
        $user = cmf_get_current_admin_id();
        $AuditTemplateModel = new AuditModel();
        $auditTemplateModel_where['a.template_user_id'] = ["eq",$user];
        $auditTemplateModel_where2['a.template_status'] = ["eq",0];
        $auditTemplate_file = "
        a.id, a.template_id, a.template_user_id, a.template_status, a.template_failure_reason, a.createtime
        ";
        $auditTemplate_order = "a.createtime DESC";

        if (input('start_time') && input('end_time')){
            $auditTemplate_data = $AuditTemplateModel
                ->alias("a")
                ->where($auditTemplateModel_where2)
                ->where($auditTemplateModel_where)
                ->where($where_time)
                ->field($auditTemplate_file)
                ->order($auditTemplate_order)
                ->paginate(10);
        }else{
            $auditTemplate_data = $AuditTemplateModel
                ->alias("a")
                ->where($auditTemplateModel_where2)
                ->where($auditTemplateModel_where)
                ->field($auditTemplate_file)
                ->order($auditTemplate_order)
                ->paginate(10);
        }

        $this->assign('auditTemplate_data', $auditTemplate_data);
        $this->assign('page', $auditTemplate_data->render());
        return $this -> fetch();

    }


    //模板信息详情展示
    public function details()
    {
        $template_id = input("template_id");
        $TemplateModel = new TemplateModel();
        $templateModel_where['t.id'] = ["eq",$template_id];
        $template_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $template_data = $TemplateModel
            ->alias("t")
            ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
            ->join(['in_classify'=>'c'] , 'c.id = t.classify_id' , 'LEFT')
            ->where($templateModel_where)
            ->field($template_field)
            ->find();

        $this->assign('template_data', $template_data);
        return $this -> fetch();

    }


    //历史信息列表展示
    public function historyList()
    {
        $template_id = input("template_id");
        $Model = new FailureAuditModel();
        $model_where['f.template_id'] = ["eq",$template_id];
        $model_field = "
            f.id, f.template_id, f.template_user_id, f.template_status, f.template_failure_reason ,
            ft.preview, c.path, c.type
        ";
        $data = $Model
            ->alias("f")
            ->join(['in_failure_template'=>'ft'] , 'ft.failure_audit_id = f.id' , 'LEFT')
            ->join(['in_file'=>'c'] , 'c.id = ft.preview' , 'LEFT')
            ->where( $model_where)
            ->field($model_field)
            ->select();
        $this->assign('data', $data);
        return $this -> fetch();
    }


    //历史信息详细展示
    public function historyDetails()
    {
        $failure_audit_id = input("id");
        $Model = new FailureTemplateModel();
        $model_where['t.failure_audit_id'] = ["eq",$failure_audit_id];
        $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $data = $Model
            ->alias("t")
            ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
            ->join(['in_classify'=>'c'] , 'c.id = t.classify_id' , 'LEFT')
            ->where($model_where)
            ->field($model_field)
            ->find();

        $this->assign('data', $data);
        return $this -> fetch();

    }


    //模板审核通过
    public function templatePass()
    {
        $audit_id = input('id');
        $template_id = input('template_id');
        $modelAudit = new AuditModel();
        $modelTemplateStatus = new TemplateStatusModel();
        $modelDraw  = new TaskDrawModel();
        $modelTemplateStatus->updateStatus($template_id,2);
        $modelAudit->updateStatus($audit_id);

        $model = new TemplateModel();
        $res =$model->get($template_id);
        $res -> data([
            'status'             => 1,
        ]);
        $model->save();
        $this ->success('审核通过',url('AuditTemplate/index'));
    }


    //模板审核失败填写原因列表
    public function templateFailure()
    {
        $template_id = input("template_id");
        $TemplateModel = new TemplateModel();
        $templateModel_where['t.id'] = ["eq",$template_id];
        $template_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $template_data = $TemplateModel
            ->alias("t")
            ->join(['in_file'=>'f'] , 'f.id = t.preview' , 'LEFT')
            ->join(['in_classify'=>'c'] , 'c.id = t.classify_id' , 'LEFT')
            ->where($templateModel_where)
            ->field($template_field)
            ->find();

        $this->assign('template_data', $template_data);
        return $this -> fetch( );
    }


//                 模板审核失败列表提交5部操作
//                 1,验证是否在状态表和审核表有数据
//                 2,修改状态表，状态码   template_status
//                 3,修改审核表状态码并插入审核失败理由
//                 4,存入历史失败记录
//                 5,保存历史失败的模板文件
    public function submitFailure(){
        $data = input();
        $validate = new FailValidate();
        $result = $validate ->failAudit($data);
        if ($result !== true) {
            $this->error($result);
        }
        $time = date('Y-m-d H:i:s',time());
        $template_id = input('id');
        $template_failure_reason = input('template_failure_reason');
        $author = cmf_get_current_admin_id();;

        $modelFailureAudit = new FailureAuditModel();
        $modelAudit = new AuditModel();
        $modelTemplateStatus = new TemplateStatusModel();
        $modelFailureTemplate = new FailureTemplateModel();


        $modelTemplateStatus->updateStatus($template_id,3);

        $modelAudit->auditFailure( $template_id,$template_failure_reason);

        $modelFailureAudit->addFailureTemplate($template_id , $author ,$template_failure_reason ,$time );

        $failure_template_id = $modelFailureAudit->findID($template_id);
        $modelFailureTemplate->BackupFailedData($template_id, $failure_template_id);

        $this->success('操作成功',url('AuditTemplate/index'));

    }



}