<?php

namespace app\audit\controller;

use cmf\controller\AdminBaseController;
use app\audit\model\AuditModel;
use app\substance\model\TemplateModel;
use app\audit\model\FailureAuditModel;
use app\audit\model\FailureTemplateModel;
use app\audit\model\TemplateStatusModel;
use think\Db;

class AuditLabelController extends AdminBaseController
{
    // 审核状态
    protected $material_template_status = [
        0 => "<span class='btn btn-xs btn-danger'>未审核</span>",
        1 => "<span class='btn btn-xs btn-success'>审核通过</span>",
        2 => "<span class='btn btn-xs btn-danger'>审核失败</span>",
    ];


    //标签审核任务列表展示

    public function index()
    {
        $user = cmf_get_current_admin_id();

        $AuditTemplateModel = new AuditModel();
        $data = $AuditTemplateModel->labelList($user);

        $this->assign('data', $data);
        return $this->fetch();

    }


    //模板信息详情展示

    public function details()
    {
        $template_id = input("template_id");
        $model = new TemplateModel();
        $model_where['t.id'] = ["eq", $template_id];
        $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $data = $model
            ->alias("t")
            ->join(['in_file' => 'f'], 'f.id = t.preview', 'LEFT')
            ->join(['in_classify' => 'c'], 'c.id = t.classify_id', 'LEFT')
            ->where($model_where)
            ->field($model_field)
            ->find();

        $this->assign('data', $data);
        return $this->fetch();

    }


    //历史信息列表展示

    public function historyList()
    {
        $template_id = input("template_id");
        $model = new FailureAuditModel();
        $data = $model->failureLableList($template_id);
        $this->assign('data', $data);
        return $this->fetch();
    }


    //历史信息详细展示

    public function historyDetails()
    {
        $failure_audit_id = input("id");
        $Model = new FailureTemplateModel();

        $data = $Model ->historyDetails($failure_audit_id);

        $this->assign('data', $data);
        return $this->fetch();

    }


    //模板审核通过

    public function labelPass()
    {
        $time = date('Y-m-d H:i:s', time());
        $template_id = input('template_id');
        //1.查询Audit表是否有记录
        $modelAudit = new AuditModel();
        $modelTemplateStatus = new TemplateStatusModel();

        $modelAudit->completeStatus($template_id,$time);
        $modelTemplateStatus->updateStatus($template_id, 5);
        //缺少一部更新template表的status字段信息
        $this->success('审核通过', url('AuditLabel/index'));
    }


    //模板审核失败填写原因列表

    public function labelFailure()
    {
        $template_id = input("template_id");
        $model = new TemplateModel();
        $model_where['t.id'] = ["eq", $template_id];
        $model_field = "
            t.id, t.template_name, t.label_id, t.author, t.classify_id, t.modify, t.material_content,t.preview,
            t.template_width ,t.template_height,f.path, f.type ,c.classify_name
        ";
        // 查询模版
        $data = $model
            ->alias("t")
            ->join(['in_file' => 'f'], 'f.id = t.preview', 'LEFT')
            ->join(['in_classify' => 'c'], 'c.id = t.classify_id', 'LEFT')
            ->where($model_where)
            ->field($model_field)
            ->find();

        $this->assign('data', $data);
        return $this->fetch();
    }


//   模板审核失败列表提交4部操作: 1,修改状态表，状态码   template_status.  2,修改审核表状态码并插入审核失败理由   3,存入历史失败记录   4,保存历史失败的模板文件

    public function submitFailure()
    {
        $time = date('Y-m-d H:i:s', time());
        $template_id = input('id');
        $label_failure_reason = input('label_failure_reason');
        $author = cmf_get_current_admin_id();;

        $modelFailureAudit = new FailureAuditModel();
        $modelAudit = new AuditModel();
        $modelTemplateStatus = new TemplateStatusModel();
        $modelFailureTemplate = new FailureTemplateModel();


        $modelTemplateStatus->updateStatus($template_id, 6);

        $modelAudit->auditFailureLabel($template_id, $label_failure_reason);

        $modelFailureAudit->addFailureLabel($template_id, $author, $label_failure_reason, $time);

        $failure_template_id = $modelFailureAudit->findID($template_id);
        $modelFailureTemplate->BackupFailedData($template_id, $failure_template_id);

        $this->success('审核成功', url('AuditTemplate/index'));

    }

}