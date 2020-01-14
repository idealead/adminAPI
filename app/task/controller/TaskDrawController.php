<?php


namespace app\task\controller;

use cmf\controller\AdminBaseController;
use app\task\model\TaskCategoryModel;
use app\task\model\TaskDrawModel;
use app\task\model\TempUserModel;
use think\Db;


class TaskDrawController extends AdminBaseController
{
    //便利列表（首页）
    public function index()
    {
        $keyword_user_name = input('keyword_user_name');
        $keyword_task_name = input('keyword_task_name');
        $model = new TaskDrawModel();
        $data  = $model -> getList($keyword_user_name,$keyword_task_name);

        $this->assign('data', $data);
        $this->assign('page', $data->render());
        return $this -> fetch();

    }

    //分配从新单个任务
    public function reallocateDraw()
    {
        $userModel  = new TempUserModel();
        $drawModel  = new TaskDrawModel();
        $taskModel  = new TaskCategoryModel();

        $id = input("id");
        $category_id = input('category_id');
        $user_id = input("user_id");
        $user_status = $userModel -> getStatus($user_id);

        $userModel->changeStatus($user_id,4);
        $new_user_id = $taskModel ->templateReviewer( $category_id,7);
        if ( $new_user_id ==0){
            $userModel->changeStatus($user_id,$user_status);
            $this->error('目前只有一个正常工作人员，无法重新分配');
        }
        $drawModel->reDraw( $id,$new_user_id);
        $userModel->changeStatus($user_id,$user_status);

        $this->success('成功');
    }


    //重新分配该员工所有任务
    public function allReallocateDraw()
    {
        $model = new TaskDrawModel();
        $userModel  = new TempUserModel();
        $user_id = input('user_id');
        $data = $model-> allDraw($user_id);

        $user_status = $userModel -> getStatus($user_id);
        $userModel->changeStatus($user_id,4);
        foreach ($data as $key => $value ){
            $id = $value['id'];

            $new_user_id = $userModel->drawTemplate(7);
            if ( $new_user_id ==0){
                $userModel->changeStatus($user_id,$user_status);
                $this->error('目前只有一个正常工作人员，无法重新分配');
            }
            $model->reDraw($id , $new_user_id);
        }

        $userModel->changeStatus($user_id,$user_status);
        return $this->success('重分配成功');
    }


    //重新分配该员工的某类任务的，全部任务
    public function groupReallocateDraw()
    {
        $model = new TaskDrawModel();
        $userModel  = new TempUserModel();
        $user_id = input('user_id');
        $category_id = input('category_id');
        $data = $model->  groupDraw($user_id,$category_id);


        $user_status = $userModel -> getStatus($user_id);
        $userModel->changeStatus($user_id,4);
        foreach ($data as $key => $value ){
            $id = $value['id'];

            $new_user_id = $userModel->drawTemplate(7);
            if ( $new_user_id ==0){
                $userModel->changeStatus($user_id,$user_status);
                $this->error('目前只有一个正常工作人员，无法重新分配');
            }
            $model->reDraw($id , $new_user_id);
        }

        $userModel->changeStatus($user_id,$user_status);
        return $this->success('重分配成功');
    }


    //某一员工分配$num次任务
    public function assignedTasks($user_id,$task_category_id,$num)
    {

        for($i=1;$i<=$num;$i++){
            $model = new TaskDrawModel();

            $model->add($user_id,$task_category_id);


        }
    }

}