<?php


namespace app\task\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TempUserModel;
use app\task\model\AuditModel;
use app\task\model\TemplateStatusModel;

class TaskTemplateController extends AdminBaseController
{

    //模板任务分配列表
    public function index()
    {
        $key_name = input('keyword_name');
        $key_id   = input('keyword_id');
        $model = new AuditModel();
        $data = $model->getTemplate($key_name,$key_id);
        $this->assign('page', $data->render());
        $this->assign('data', $data);
        return $this -> fetch();
    }


    //分配模板详情
    public function details()
    {
        $template_id = input("template_id");
        $model = new AuditModel();
        $data = $model->templateInformation($template_id);
        $this->assign('data', $data);
        return $this -> fetch();
    }

    //从新分配任务方法
    public function reallocateTemplate()
    {

        $userModel  = new TempUserModel();
        $auditModel = new AuditModel();

        $id = input("id");
        $user_id = input("template_user_id");
        $user_status = $userModel -> getStatus($user_id);

        $userModel->changeStatus($user_id,4);
        $template_user_id = $userModel->templateReviewer();
        if ( $template_user_id ==0){
            $userModel->changeStatus($user_id,$user_status);
            $this->error('目前只有一个正常工作人员，无法重新分配');
        }
        $auditModel->reallocateTemplate( $id, $template_user_id);
        $userModel->changeStatus($user_id,$user_status);
        return $this->success('重分配成功');

    }


    //重新分配该用户全部任务
    public function allReallocateTemplate()
    {
        $auditModel = new AuditModel();
        $userModel  = new TempUserModel();
        $user_id = input('template_user_id');
        $data = $auditModel->allTaskTemplate($user_id);

        $user_status = $userModel -> getStatus($user_id);
        $userModel->changeStatus($user_id,4);
        foreach ($data as $key => $value ){
            $id = $value['id'];
            $template_user_id = $userModel->templateReviewer();
            if ( $template_user_id ==0){
                $userModel->changeStatus($user_id,$user_status);
                $this->error('目前只有一个正常工作人员，无法重新分配');
            }
            $auditModel->reallocateTemplate( $id, $template_user_id);
        }

        $userModel->changeStatus($user_id,$user_status);
        return $this->success('重分配成功');

    }
}