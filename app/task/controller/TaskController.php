<?php


namespace app\task\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TempUserModel;
use app\task\model\AuditModel;
use app\task\model\TemplateStatusModel;

class TaskController extends  AdminBaseController
{

    //自动分配模板审核任务
    public function TemplateTaskAssignment($template_id)
    {
        $temModel = new TempUserModel();
        $auditModel = new AuditModel();
        $templateStatusModel = new TemplateStatusModel();

        $time = date('Y-m-d H:i:s', time());
        $template_user_id = $temModel ->templateReviewer();


        //1.查询Audit表与template_status是否有记录,有记录重置，没有创建
        $res_audit = $auditModel -> judge($template_id);
        $res_status = $templateStatusModel -> judge($template_id);
        if ($res_audit == null){
            $auditModel->createTask($template_id,$template_user_id,$time);
        }else {
            $auditID = $auditModel->getID($template_id);
            $auditModel->reduction($auditID);
        }
        if($res_status == null){
            $templateStatusModel->createStatus($template_id);
            return 1;
        }else{
            $templateStatusID = $templateStatusModel->getID($template_id);
            $templateStatusModel->reduction($templateStatusID);
            return 1;
        }

    }


    //将审核通过的模板分配给标签填写人员
    public function LabelWriter($template_id)
    {
        $temModel = new TempUserModel();
        $auditModel = new AuditModel();
        $templateStatusModel = new TemplateStatusModel();

        $label_writer_id = $temModel->labelWriter();
        $res = $auditModel->templateSuccess($template_id);

        //要判断audit里的template_status字段为2
        if ($res == null){
            $this->error('通过模板审核才能进行标签添加');
        }else{
            $auditID = $auditModel->getID($template_id);
            $templateStatusID = $templateStatusModel->getID($template_id);
            $auditModel->updateLabelWriter($auditID,$label_writer_id);
            $templateStatusModel->updateStatus($templateStatusID,4);
        }
    }


    //将填写好标签的模板分配给标签审核人员
    public function LabelTaskAssignment($template_id)
    {
        //1.判断标签是否有内容(放在前端)
        //2取出标签审核任务最少的人
        //更改audit字段状态及template_status状态

        $temModel = new TempUserModel();
        $auditModel = new AuditModel();
        $templateStatusModel = new TemplateStatusModel();


        $label_user_id = $temModel->labelReviewer();

        $auditID = $auditModel->getID($template_id);
        $templateStatusID = $templateStatusModel->getID($template_id);
        $auditModel->updateLabelWriter($auditID,$label_user_id);
        $templateStatusModel->updateStatus($templateStatusID,5);

    }
}

